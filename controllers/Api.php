<?php namespace Itmaker\DtpApp\Controllers;

use Lang;
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
    		'employe_group_code'  => 'required|exists:user_groups,code',
    		'coor_lat'	          => 'required',
    		'coor_long'	          => 'required',
            'address'             => 'required|min:4',
            'images'              => 'array'
    	];

    	$messages = [
    	    'address.required' => Lang::get('itmaker.dtpapp::lang.messages.address.required'),
    	    'address.min'       => Lang::get('itmaker.dtpapp::lang.messages.address.min4'),
            'coor_lat.required'  => Lang::get('itmaker.dtpapp::lang.messages.coordinates.required'),
            'coor_long.required'  => Lang::get('itmaker.dtpapp::lang.messages.coordinates.required'),
        ];

    	$validation = Validator::make($data, $rules, $messages);

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

    public function callsHistory($page = 1)
    {
    	$user = $this->auth();

    	$calls = $user->calls()
                    ->with('services', 'employe', 'client', 'employe_group', 'images', 'status')
                    ->paginate(10, $page);

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

    public function activeCalls($page = 1)
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
            'services')->paginate(10, $page);

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
            //return response()->json('insufficient funds in the account');
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