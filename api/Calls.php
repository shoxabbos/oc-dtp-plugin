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
                'token' => $model->client->device_id
            ]);
        }

        return new CallResource($model); 
    }

    public function cancel($id) {
        $call = $this->user->calls()->where('id', $id)->first();

        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        } 

        if ($call->status == 'canceled') {
            //return response()->json(['error' => 'Call already cancelled'], 422);
        }

        // change status
        $call->status = 'canceled';
        $call->save();

        // send notify to employe if that exists
        if ($call->employe && $call->employe->device_id) {
            Queue::push(SendSinglePush::class, [
                'title' => 'Клиент отменил заявку',
                'body' => '',
                'token' => $call->employe->device_id
            ]);
        }

        return new CallResource($call);
    }

    public function view($id) {
        $model = Call::find($id);

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
            return response()->json(['error' => $validation->messages()->first()]);
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

}