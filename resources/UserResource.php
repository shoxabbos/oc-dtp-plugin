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

		if ($this->avatar) {
			$data['image'] = $this->avatar->getThumb(250, 250, ['mode' => 'crop']);
		}

		return $data;
	}
}