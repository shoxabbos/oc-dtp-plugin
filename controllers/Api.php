<?php namespace Itmaker\DtpApp\Controllers;

use Input;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\UserGroup as UserGroupModel;
use Itmaker\DtpApp\Models\Comment as CommentModel;
use Itmaker\DtpApp\Models\Service as ServiceModel;
use Itmaker\DtpApp\Models\Status as StatusModel;
use Itmaker\DtpApp\Models\Call;
use Itmaker\DtpApp\Models\Tarif;
use Itmaker\DtpApp\Models\Slide;

class Api extends Controller
{
	private function auth() 
    {
        return JWTAuth::parseToken()->authenticate();
    }


    public function index()
    {
        $services = ServiceModel::where('parent_id', null)->with('childrens', 'childrens.childrens', 'icon', 'childrens.icon')->get();
        $tarifs = Tarif::get();
        return response()->json(compact('services', 'tarifs'));
    }

    public function getSlides()
    {
        $slides = Slide::where('is_active', true)->orderBy('sort_order', 'asc')
                        ->with('image')->get();

        return response()->json(compact('slides'));
    }

    public function register()
    {
    	$credentials = Input::only('name', 'surname', 'email', 'phone', 'password', 'password_confirmation');
    	$rules = [
            'name'      => 'required|min:3',
            'surname'   => 'required|min:3',
    		'phone'	    => 'required|unique:users,username',
    		'password'	=> 'required|min:6|confirmed',
            'email'     => 'required|min:3|email'
    	];


    	$validation = Validator::make($credentials, $rules);

    	if ($validation->fails()){
    		return response()->json(['errors' => $validation->errors()], 401);
    	}
    	$credentials['username'] = $credentials['phone'];
    	unset($credentials['phone']);
    	

    	try {
    		$userModel = UserModel::create($credentials);
    		$user = UserModel::find($userModel->id);
    		$user->phone = $user->username;
	        $token = JWTAuth::fromUser($userModel);
    	} catch (Exception $e) {
    		return response()->json(['error' => $e->getMessage()], 401);
    	}

        if ($group = UserGroupModel::where('code', 'clients')->first()){
            $userModel->groups = $group;
            $userModel->update();
        }

    	return response()->json(compact('token', 'user'));
    }

    public function login()
    {
    	$data = Input::only('login', 'password');

    	$rules = [
    		'login' => 'required|exists:users,username',
    		'password' => 'required'
    	];

    	$validation = Validator::make($data, $rules);

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
                        'password' => 'wrong password'
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $userModel = JWTAuth::authenticate($token);
        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'surname' => $userModel->surname,
                'username' => $userModel->username,
                'email' => $userModel->email,
                'is_activated' => $userModel->is_activated,
                'user_role' => $userModel->user_role
            ];
        }

        return response()->json(compact('token', 'user'));
    }

    public function employeLogin()
    {
        $data = Input::only('login', 'password');

        $rules = [
            'login' => 'required|exists:users,username',
            'password' => 'required'
        ];

        $validation = Validator::make($data, $rules);

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
                        'password' => 'wrong password'
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $userModel = JWTAuth::authenticate($token);
        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'surname' => $userModel->surname,
                'username' => $userModel->username,
                'email' => $userModel->email,
                'is_activated' => $userModel->is_activated,
                'user_role' => $userModel->user_role
            ];
        }

        $userGroups = $userModel->groups()->where('code', '!=','clients')->get();
        if ($userGroups->isEmpty()){
            return response()->json('error credentials', 404);
        }

        return response()->json(compact('token', 'user'));
    }

    public function getUser()
    {
    	$user = $this->auth();

    	$user->phone = $user->username;

    	return response()->json(compact('user'));
    }

    public function updateUser()
    {
    	$user = $this->auth();

    	$data = Input::only('username', 'name', 'surname', 'password', 'password_confirmation');

    	if (Input::hasFile('avatar')) {
            $user->avatar = Input::file('avatar');
        }

        try {
            $user->fill(post());
            $user->save();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function calling()
    {
    	$user = $this->auth();

    	$data = Input::only(
            'services', 
            'addantial_comment', 
            'employe_group_code', 
            'coor_lat', 
            'coor_long', 
            'address',
            'images'
        );

    	$rules = [
    		'services'            => 'array',
    		'addantial_comment' => 'string',
    		'employe_group_code'  => 'required|in:specialists,masters',
    		'coor_lat'	          => 'required',
    		'coor_long'	          => 'required',
            'address'             => 'required|min:4',
            'images'              => 'array'
    	];
    	$validation = Validator::make($data, $rules);

    	if ($validation->fails()){
    		return response()->json(['errors' => $validation->errors()]);
    	}

        $data['status_id'] = StatusModel::where('is_active', true)->orderBy('sort_order', 'asc')->first()->id;


    	$user->calls()->create($data);

    	$call = $user->calls()->orderByDesc('created_at')
                        ->with('services', 'employe', 'client', 'employe_group', 'images', 'status')->first();

    	return response()->json(compact('call'));
    }

    public function getLastCall()
    {
    	$user = $this->auth();

    	$call = $user->calls()->orderByDesc('created_at')
                     ->with('services', 'employe', 'client', 'employe_group', 'images', 'status')->first();

    	if (!$call){
    		return response()->json('call not found', 404);
    	}

    	return response()->json(compact('call'));
    }

    public function callsHistory()
    {
    	$user = $this->auth();

    	$calls = $user->calls()->with('services', 'employe', 'client', 'employe_group', 'images', 'status')->get();

    	if (!$calls) {
    		return response()->json('calls not found', 404);
    	}

    	return response()->json(compact('calls'));
    }

    public function getCall($id)
    {
        $user = $this->auth();

        $call = $user->calls()->with(
            'images', 
            'status', 
            'client', 
            'employe', 
            'employe_group', 
            'services')->find($id);

        if (!$call){
            return response()->json('call not found', 404);
        }

        return response()->json(compact('call'));
    }

    public function activeCalls()
    {
    	$user = $this->auth();

        $groupCodes = [];
        foreach ($user->groups as $group) {
            $groupCodes[] = $group->code;
        }

        $approvedStatus = StatusModel::where('code', 'approved')->first();

    	$calls = Call::whereIn('employe_group_code', $groupCodes)
                ->whereNull('employe_id')->whereHas('status', function ($q) use ($approvedStatus){
                    $q->where('code', $approvedStatus->code);
                })->with(
            'images', 
            'status', 
            'client', 
            'employe', 
            'employe_group', 
            'services')->get();

    	if (empty($calls)){
    		return response()->json('active calls not found');
    	}

    	return response()->json(compact('calls'));
    }

    public function mySpents()
    {
    	$user = $this->auth();

    	$calls = $user->employe_calls;

    	if (!$calls){
    		return response()->json('calls not found');
    	}

    	return response()->json(compact('calls'));
    }

    public function callEmployed()
    {
        $user = $this->auth();

        $call = Call::with(
            'employe', 
            'client', 
            'status', 
            'images', 
            'services', 
            'employe_group')
            ->whereHas('status', function ($q) {
                $q->where('code', 'approved');
            })->find(post('call_id'));

        if (!$call){
            return response()->json('call not found or was accepted', 404);
        }

        if($call->employe){
            return response()->json('the call was accepted by another employee', 201);
        }

        $activeStatus = StatusModel::where('code', 'active')->first();

        if ($user->balance < 10000){
            return response()->json('insufficient funds in the account');
        }
        $user->balance = $user->balance - 10000;
        $user->update();

        $call->employe = $user;
        $call->status = $activeStatus;
        $call->update();

        $status = 'calling is accepted';

        return response()->json(compact('status', 'call'));
    }

    public function callComplated()
    {
    	$user = $this->auth();

    	$id = post('call_id');

    	$call = $user->employe_calls()->with(
            'images', 
            'status', 
            'client', 
            'employe', 
            'employe_group', 
            'services')->whereHas('status', function ($q) {
                $q->where('code', 'active');
            })->find($id);

    	if (!$call){
    		return response()->json('call not found');
    	}

        $complatedStatus = StatusModel::where('code', 'completed')->first();

    	$call->status = $complatedStatus;
    	$call->save();

    	return response()->json(compact('call'));
    }
}