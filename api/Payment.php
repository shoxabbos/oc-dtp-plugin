<?php


namespace Itmaker\DtpApp\Api;

use Input;
use JWTAuth;
use Validator;
use Illuminate\Routing\Controller;
use Shohabbos\Payme\Models\Settings as PaymeSettings;

class Payment extends Controller
{
    private function auth()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function renderUrl()
    {
        $user = $this->auth();

        $data = Input::only('amount');

        $validation = Validator::make($data, [
            'amount' => 'required|min:1000|integer'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(),400);
        }

        $merchantId =  PaymeSettings::get('merchant_id');

        $url = 'https://checkout.paycom.uz/';

        $key = "m={$merchantId};ac.user_id={$user->id};a={$data['amount']}";
        $key = base64_encode($key);
        $link = $url . $key;
        return response()->json(['link' => $link]);

    }

}