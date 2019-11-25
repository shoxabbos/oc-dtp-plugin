<?php namespace Itmaker\DtpApp\Api;

use Lang;
use Input;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itmaker\DtpApp\Models\EmployeesLocation;
use Itmaker\DtpApp\Resources\LocationResource;


class Locations extends Controller
{
	private function auth() 
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function set()
    {
    	$user = $this->auth();

    	$location = Input::only('coor_lat', 'coor_long');

    	$rules = [
    		'coor_lat' => 'required',
    		'coor_long' => 'required'
    	];

    	$messages = [
    		'coor_lat.required' => Lang::get('itmaker.dtpapp::lang.messages.locations.coor_lat_required'),
    		'coor_long.required' => Lang::get('itmaker.dtpapp::lang.messages.locations.coor_long_required'),
    	];

    	$validation = Validator::make($location, $rules, $messages);

    	if ($validation->fails()){
    		return response()->json(['errors' => $validation->errors()], 400);
    	}

    	$user->locations()->create($location);

    	$location = $user->locations()->with('user')->orderByDesc('id')->first();

        return new LocationResource($location);
    }

    public function get($id) {
    	$location = EmployeesLocation::where('user_id', $id)->orderByDesc('id')->with('user')->first();

    	if (!$location) {
    		return response()->json([
    			'errors' => [
    				'location' => Lang::get('itmaker.dtpapp::lang.messages.locations.not_found')
    			]
    		]);
    	}

    	return new LocationResource($location);
   	}
    

}