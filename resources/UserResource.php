<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * 
 */
class UserResource extends Resource
{
	public function toArray($request)
	{
		$data = parent::toArray($request);

		return $data;
	}
}