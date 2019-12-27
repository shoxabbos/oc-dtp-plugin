<?php namespace Itmaker\DtpApp\Api;

use Input;
use Validator;
use Illuminate\Routing\Controller;

use Itmaker\DtpApp\Models\Tarif;
use Itmaker\DtpApp\Resources\TariffResource;


class Helper extends Controller
{

    public function tariffs() {
        $collection = Tarif::with('image')->get();

        return TariffResource::collection($collection);
    }

}