<?php namespace Itmaker\DtpApp\Api;

use Input;
use Validator;
use Illuminate\Routing\Controller;

use Itmaker\DtpApp\Models\Tarif;
use Itmaker\DtpApp\Models\Service;
use Itmaker\DtpApp\Models\Insurance;

use Itmaker\DtpApp\Resources\TariffResource;
use Itmaker\DtpApp\Resources\ServiceResource;


class Helper extends Controller
{


    public function insurances() {
        $data = Insurance::get();

        return [
            'data' => $data,
        ];
    }

    public function tariffs() {
        $collection = Tarif::with('image')->get();

        return TariffResource::collection($collection);
    }


    public function services() {
    	$types = Service::TYPES;
    	$type = Input::get('type');

    	$query = Service::with('icon');

    	if ($type && isset($types[$type])) {
    		$query->where('type', $type);
    	}

        $collection = $query->get();

        return ServiceResource::collection($collection);
    }

}