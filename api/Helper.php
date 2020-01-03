<?php namespace Itmaker\DtpApp\Api;

use Input;
use Validator;
use Illuminate\Routing\Controller;

use Itmaker\DtpApp\Models\Tarif;
use Itmaker\DtpApp\Models\Service;

use Itmaker\DtpApp\Resources\TariffResource;
use Itmaker\DtpApp\Resources\ServiceResource;


class Helper extends Controller
{

    public function tariffs() {
        $collection = Tarif::with('image')->get();

        return TariffResource::collection($collection);
    }


    public function services() {
        $collection = Service::with('icon')->get();

        return TariffResource::collection($collection);
    }

}