<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * 
 */
class LocationResource extends Resource
{
	
	public function toArray($request) {
		$data = parent::toArray($request);

		$data['user'] = new UserResource($this->whenLoaded('user'));	

		return $data;
	}

}