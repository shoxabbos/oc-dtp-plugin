<?php namespace Itmaker\DtpApp\Api;

use Lang;
use Input;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RainLab\User\Models\User as UserModel;
use Itmaker\DtpApp\Models\Rate;

class Rates extends Controller
{
	private function auth() 
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function createRate()
    {
        $user = $this->auth();
        
        $data = Input::only('employe_id', 'raiting', 'comment');
        $data['user_id'] = $user->id;
        $rate = new Rate();
        $rate->user_id = $data['user_id'];
        $rate->employe_id = $data['employe_id'];
        $rate->raiting = $data['raiting'];
        $rate->comment = $data['comment'];
        $rate->save();
        
        return response()->json(['status' => 'ok']);
    }
}