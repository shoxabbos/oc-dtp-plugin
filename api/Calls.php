<?php namespace Itmaker\DtpApp\Api;

use Lang;
use Input;
use JWTAuth;
use Validator;
use ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RainLab\User\Models\User as UserModel;

use Itmaker\DtpApp\Models\Call;
use Itmaker\DtpApp\Resources\CallResource;

class Calls extends Controller
{
    
    private $user;

    public function __construct() {
        $this->user = $this->auth();
        $this->push = \App::make('fcm');
    }

    public function index() {
        $query = $this->user->calls()->orderByDesc('id');

        return CallResource::collection($query->get());
    }

    public function accept($id) {
        $user = $this->user;
        $model = Call::find($id);

        if (!$model || $model->status != 'new' || $model->employe) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        // add employe
        $model->employe = $user;
        $model->save();

        // send push to client
        if ($model->client && $model->client->device_id) {
            $this->push->sendNotification(
                'Ваша заявка принята', 
                'Наш специалист уже в пути', 
                $model->client->device_id
            );
        }

        return new CallResource($model); 
    }

    public function cancel($id) {
        $call = $this->user->calls()->where('id', $id)->first();

        if ($call) {
            $call->status = 'canceled';
            $call->save();

            return new CallResource($call);
        }

        return response()->json(['error' => 'Call not found!']);
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