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

class Users extends Controller
{
    private $messages = [];

    private function  getMessages()
    {
        $this->messages = [
            'name.required' => Lang::get('itmaker.dtpapp::lang.messages.name.required'),
            'name.min' => Lang::get('itmaker.dtpapp::lang.messages.name.min3'),
            'surname.required' => Lang::get('itmaker.dtpapp::lang.messages.surname.required'),
            'surname.min' => Lang::get('itmaker.dtpapp::lang.messages.surname.min3'),
            'phone.required' => Lang::get('itmaker.dtpapp::lang.messages.phone.required'),
            'phone.unique' => Lang::get('itmaker.dtpapp::lang.messages.phone.unique'),
            'password.required' => Lang::get('itmaker.dtpapp::lang.messages.password.required'),
            'password.min6' => Lang::get('itmaker.dtpapp::lang.messages.password.min6'),
            'password.confirmed' => Lang::get('itmaker.dtpapp::lang.messages.password.confirmed'),
            'email.required' => Lang::get('itmaker.dtpapp::lang.messages.email.required'),
            'email.min3' => Lang::get('itmaker.dtpapp::lang.messages.email.min3'),
            'email.email' => Lang::get('itmaker.dtpapp::lang.messages.email.email'),
            'login.required' => Lang::get('itmaker.dtpapp::lang.messages.login.required'),
            'login.exists' => Lang::get('itmaker.dtpapp::lang.messages.login.exists'),
            'groups.required' => Lang::get('itmaker.dtpapp::lang.messages.groups.required'),
        ];
    }

    public function registerClient()
    {
        $credentials = Input::only('name', 'surname', 'email', 'phone', 'password', 'password_confirmation');
        $rules = [
            'name'      => 'required|min:3',
            'surname'   => 'required|min:3',
            'phone'     => 'required|unique:users,username',
            'password'  => 'required|min:6|confirmed',
            'email'     => 'required|min:3|email'
        ];

        $this->getMessages();

        $validation = Validator::make($credentials, $rules, $this->messages);

        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 400);
        }
        $credentials['username'] = $credentials['phone'];
        unset($credentials['phone']);
        
        try {
            $userModel = UserModel::create($credentials);
            $userModel->is_activated = true;
            $userModel->activated_at = time();

            if ($group = UserGroupModel::where('code', 'clients')->first()){
                $userModel->groups = $group;
            }

            $userModel->update();
            $user = UserModel::find($userModel->id);
            $token = JWTAuth::fromUser($userModel);
            $user = new UserResource($user);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


        return response()->json(compact('token', 'user'));
    }

    public function registerMasters()
    {
        $credentials = Input::only('name', 'surname', 'email', 'phone', 'password', 'password_confirmation', 'groups');
        $rules = [
            'name'      => 'required|min:3',
            'surname'   => 'required|min:3',
            'phone'     => 'required|unique:users,username',
            'password'  => 'required|min:6|confirmed',
            'email'     => 'required|min:3|email|unique:users,email',
            'groups'    => 'required|array'
        ];

        $this->getMessages();

        $validation = Validator::make($credentials, $rules, $this->messages);

        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 401);
        }
        $credentials['username'] = $credentials['phone'];
        unset($credentials['phone']);

        try {
            $userModel = UserModel::create($credentials);
            $user = UserModel::find($userModel->id);
            $token = JWTAuth::fromUser($userModel);
            $user = new UserResource($user);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if ($credentials['groups']){
            $userModel->groups = $credentials['groups'];
            $userModel->update();
        }

        return response()->json(compact('token', 'user'));
    }

    public function loginClient()
    {
        $data = Input::only('login', 'password');
        $rules = [
            'login' => 'required|exists:users,username',
            'password' => 'required'
        ];
        $validation = Validator::make($data, $rules, $this->messages);
        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 401);
        }
        $credentials = [
            'username' => $data['login'],
            'password' => $data['password']
        ];
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => [
                        'password' => Lang::get('itmaker.dtpapp::lang.messages.password.wrong')
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $userModel = JWTAuth::authenticate($token);
        if (!$userModel->groups()->where('code', 'clients')){
            return response()->json([
                'errors' => [ 'error' => Lang::get('itmaker.dtpapp::lang.messages.clients.only_clients_login')]
            ]);
        }

        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = new UserResource($userModel);
        }

        return response()->json(compact('token', 'user'));
    }

    public function loginSpecialist()
    {
        $data = Input::only('login', 'password');
        $rules = [
            'login' => 'required|exists:users,username',
            'password' => 'required'
        ];

        $this->getMessages();

        $validation = Validator::make($data, $rules, $this->messages);

        if ($validation->fails()){
            throw new ValidationException($validation);
        }

        $credentials = [
            'username' => $data['login'],
            'password' => $data['password']
        ];

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new ValidationException(['error' => [
                    'password' => Lang::get('itmaker.dtpapp::lang.messages.password.wrong')
                ]]);
            }
        } catch (JWTException $e) {
            throw new ValidationException(['error' => 'could_not_create_token']);
        }

        $userModel = JWTAuth::authenticate($token);
        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = new UserResource($userModel);
        }
        $userGroups = $userModel->groups()->where('code', 'specialists')->get();
        if ($userGroups->isEmpty()){
            try {
                // invalidate the token
                JWTAuth::invalidate($token);
                return response()->json(Lang::get('itmaker.dtpapp::lang.messages.no_access'), 404);
            } catch (Exception $e) {
                // something went wrong
                return response()->json(['error' => 'could_not_invalidate_token'], 500);
            }
        }
        return response()->json(compact('token', 'user'));
    }

    public function loginMaster()
    {
        $data = Input::only('login', 'password');
        $rules = [
            'login' => 'required|exists:users,username',
            'password' => 'required'
        ];

        $this->getMessages();

        $validation = Validator::make($data, $rules, $this->messages);

        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 401);
        }

        $credentials = [
            'username' => $data['login'],
            'password' => $data['password']
        ];

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new ValidationException(['error' => [
                    'password' => Lang::get('itmaker.dtpapp::lang.messages.password.wrong')
                ]]);
            }
        } catch (JWTException $e) {
            throw new ValidationException(['error' => 'could_not_create_token']);
        }

        $userModel = JWTAuth::authenticate($token);
        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = new UserResource($userModel);
        }
        $userGroups = $userModel->groups()->where('code', '!=','specialists')->where('code', '!=','clients')->get();
        if ($userGroups->isEmpty()){
            try {
                // invalidate the token
                JWTAuth::invalidate($token);
                return response()->json(Lang::get('itmaker.dtpapp::lang.messages.no_access'), 404);
            } catch (Exception $e) {
                // something went wrong
                return response()->json(['error' => 'could_not_invalidate_token'], 500);
            }
        }
        return response()->json(compact('token', 'user'));
    }

    public function getUser()
    {
        $user = $this->auth();

        return new UserResource($user);
    }

    public function updateUser()
    {
        $user = $this->auth();

        $data = Input::only('username', 'name', 'surname', 'password', 'password_confirmation', 'email');


        if (Input::hasFile('avatar')) {
            $user->avatar = Input::file('avatar');
        }

        try {
            $user->fill($data);
            $user->save();
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getGroups()
    {
        $groups = UserGroupModel::
            where('code', '!=','clients')
            ->where('code', '!=','specialists')->get();

        return $groups;
    }


    private function auth()
    {
        return JWTAuth::parseToken()->authenticate();
    }

}