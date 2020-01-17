<?php namespace Itmaker\DtpApp\Api;

use Lang;
use Input;
use Queue;
use JWTAuth;
use Validator;
use ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RainLab\User\Models\User as UserModel;

use Itmaker\DtpApp\Models\Call;
use Itmaker\DtpApp\Resources\CallResource;

use Itmaker\DtpApp\Jobs\SendSinglePush;
use Itmaker\DtpApp\Jobs\NewCallCreated;

class Calls extends Controller
{
    
    private $user;

    public function __construct() {
        $this->user = $this->auth();
        $this->push = \App::make('fcm');
    }

    public function new() {
        $page = input('page', 1);
        $perpage = input('page', 10);

        $result = Call::where('status', 'new')->paginate($perpage, $page);

        return CallResource::collection($result);
    }

    public function setLocation($id) {
        $user = $this->user;
        $model = $this->user->employe_calls()->where('id', $id)->first();

        $data = Input::only('coor_lat', 'coor_long');
        $rules = [
            'coor_lat' => 'required|numeric',
            'coor_long' => 'required|numeric',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->messages()->first()]);
        }

        if (!$model) {
            return response()->json(['error' => 'Record not found'], 404);
        }


        $model->employee_long = $data['coor_long'];
        $model->employee_lat = $data['coor_lat'];


        $diff = $this->distance(
            $model->coor_lat, $model->coor_long,
            $model->employee_lat, $model->employee_long
        );


        if ($diff < 1000 && !$model->can_set_arrived_status) {
            $model->can_set_arrived_status = 1;

            if ($model->client && $model->client->device_id) {
                Queue::push(SendSinglePush::class, [
                    'title' => 'Специалист почти рядом',
                    'body' => 'Наш специалист уже в пути',
                    'token' => $model->client->device_id,
                    'data' => [
                        'action_type' => 'call_almost_arrived',
                        'call' => $model->id,
                    ]
                ]); 
            }

            if ($model->employe && $model->employe->device_id) {
                Queue::push(SendSinglePush::class, [
                    'title' => 'Осталось еще примерно 1 километр',
                    'body' => '',
                    'token' => $model->employe->device_id,
                    'data' => [
                        'action_type' => 'call_almost_arrived',
                        'call' => $model->id,
                    ],
                ]); 
            }
        }

        $model->save();

        return new CallResource($model);
    }

    public function index() {
        if ($this->user->type == 'client') {
            $query = $this->user->calls();
        } else {
            $query = $this->user->employe_calls();
        }

        $result = $query->orderByDesc('id')->get();

        if (empty($result)) {
            return response()->json(['error' => 'History not found'], 404);
        }

        return CallResource::collection($result);
    }


    public function complete($id) {
        $model = $this->user->calls()->where('id', $id)->first();

        if (!$model) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        $data = Input::only('rating', 'text');

        $rules = [
            'rating' => 'integer|min:1|max:5',
            'text' => 'string|max:255',
        ];

        $data['rating'] = (isset($data['rating']) && !empty($data['rating'])) ? $data['rating'] : 3;
        $data['text'] = (isset($data['text']) && !empty($data['text'])) ? $data['text'] : "";


        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->messages()->first()], 422);
        }

        $model->status = 'completed';
        $model->review_star = $data['rating'];
        $model->review_text = $data['text'];
        $model->save();

        // send push to client
        if ($model->client && $model->client->device_id) {
            Queue::push(SendSinglePush::class, [
                'title' => 'Заявка завершена',
                'body' => '',
                'token' => $model->client->device_id,
                'data' => [
                    'action_type' => 'call_completed',
                    'call' => $model->id,
                ],
            ]); 
        }

        if ($model->employe && $model->employe->device_id) {
            Queue::push(SendSinglePush::class, [
                'title' => 'Заявка завершена',
                'body' => '',
                'token' => $model->employe->device_id,
                'data' => [
                    'action_type' => 'call_completed',
                    'call' => $model->id,
                ],
            ]);
        }

        return new CallResource($model);
    }


    public function accept($id) {
        $user = $this->user;
        $model = Call::find($id);

        if (!$model || $model->status != 'new' || $model->employe) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        if ($user->type == 'client') {
            return response()->json(['error' => 'You cant accept this call'], 422);
        }

        // add employe
        $model->employe = $user;
        $model->save();

        // send push to client
        if ($model->client && $model->client->device_id) {
            Queue::push(SendSinglePush::class, [
                'title' => 'Ваша заявка принята',
                'body' => 'Наш специалист уже в пути',
                'token' => $model->client->device_id,
                'data' => [
                    'action_type' => 'call_accepted',
                    'call' => $model->id,
                ],
            ]); 
        }

        return new CallResource($model); 
    }

    public function arrived($id) {
        if ($this->user->type == 'client') {
            $call = $this->user->calls()->where('id', $id)->first();
        } else {
            $call = $this->user->employe_calls()->where('id', $id)->first();
        }

        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        } 

        if ($call->status == 'arrived') {
            return response()->json(['error' => 'Call status already arrived'], 422);
        }

        // change status
        $call->status = 'arrived';
        $call->save();

        // send notify to employe if that exists
        if ($call->client && $call->client->device_id) {
            Queue::push(SendSinglePush::class, [
                'title' => 'Специалист прибыл',
                'body' => '',
                'token' => $call->client->device_id,
                'data' => [
                    'call' => $call->id,
                    'action_type' => 'call_arrived'
                ],
            ]);
        }

        return new CallResource($call);
    }

    public function cancel($id) {
        if ($this->user->type == 'client') {
            $call = $this->user->calls()->where('id', $id)->first();    
        } else {
            $call = $this->user->employe_calls()->where('id', $id)->first();    
        }

        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        } 

        if ($call->status == 'canceled') {
            return response()->json(['error' => 'Call already cancelled'], 422);
        }

        // change status
        $call->status = 'canceled';
        $call->save();
 


        // send notify to employe if that exists
        if ($this->user->type == 'client') {

            if ($call->employe && $call->employe->device_id) {
                Queue::push(SendSinglePush::class, [
                    'title' => 'Клиент отменил заявку',
                    'body' => '',
                    'token' => $call->employe->device_id,
                    'data' => [
                        'call' => $call->id,
                        'action_type' => 'call_canceled'
                    ],
                ]);
            }    

        } else {

            if ($call->client && $call->client->device_id) {
                Queue::push(SendSinglePush::class, [
                    'title' => 'Специалист отменил заявку',
                    'body' => '',
                    'token' => $call->client->device_id,
                    'data' => [
                        'call' => $call->id,
                        'action_type' => 'call_canceled'
                    ],
                ]);
            }

        }

        

        return new CallResource($call);
    }

    public function view($id) {
        $model = Call::find($id);

        if (!$model) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        return new CallResource($model);
    }

    
    public function create()
    {
        $user = $this->user;

        $data = Input::only(['services', 'comment', 'type', 'coor_lat', 'coor_long', 'address', 'images']);

        $rules = [
            'services'            => 'array',
            'comment'             => 'string',
            'type'                => 'required',
            'coor_lat'            => 'required',
            'coor_long'           => 'required',
            'address'             => 'required|min:4',
            'images'              => 'array'
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->messages()->first()], 422);
        }

        $data['status'] = 'new';
        $user->calls()->create($data);

        $call = $user->calls()->orderByDesc('created_at')
            ->with('services', 'employe', 'client', 'images')->first();

        // send push to client
        if ($call) {
            Queue::push(NewCallCreated::class, ['id' => $call->id]);
        }

        return response()->json([
            'data' => $call
        ]);
    }





    private function auth() {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $this->user = UserModel::find($user->id);
        } catch (\Exception $e) {

        }

        return $this->user;
    }


    private function distance($lat1, $lng1, $lat2, $lng2){
        return ceil(12745594 * asin(sqrt(
            pow(sin(deg2rad($lat2-$lat1)/2),2)
            +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            pow(sin(deg2rad($lng2-$lng1)/2),2)
        )));
    }

}