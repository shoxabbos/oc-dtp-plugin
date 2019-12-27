<?php namespace Itmaker\DtpApp\Api;

use Lang;
use Input;
use JWTAuth;
use Validator;
use ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\UserGroup as UserGroupModel;
use Itmaker\DtpApp\Resources\UserResource;

class User extends Controller
{
    
    private $user;

    public function __construct() {
        $this->user = $this->auth();
    }

    public function get() {
        return new UserResource($this->user);
    }

    public function update() {
        $user = $this->auth();
        $data = Input::only('username', 'name', 'surname', 'password', 'password_confirmation', 'email', 'avatar');

        $rules = [
            'name' => 'string|required',
            'surname' => 'string|required',
            'username' => 'string|required',
            'email' => 'email|required',
            'avatar' => 'nullable|image',

            'password' => 'sometimes|required|between:6,255|confirmed',
            'password_confirmation' => 'required_with:password|between:6,255',
        ];
        

        $validation = Validator::make($data, $rules);
        if ($validation->fails()){
            return response()->json(['error' => $validation->messages()->first()], 422);
        }

        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }

        try {
            $user->fill($data);

            if (isset($data['avatar']) && $data['avatar']) {
                $user->avatar = $data['avatar'];
            }

            $user->save();
            $user = new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return [
            'data' => $user,
            'success' => "Данные успешно обновлены"
        ];
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