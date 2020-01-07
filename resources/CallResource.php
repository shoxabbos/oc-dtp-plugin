<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;


class CallResource extends Resource
{
	public function toArray($request)
	{
		$data = parent::toArray($request);

		$data['coor_lat'] = (double) $data['coor_lat'];
		$data['coor_long'] = (double) $data['coor_long'];

		return $data;
	}
}