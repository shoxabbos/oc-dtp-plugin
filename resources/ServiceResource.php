<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;


class ServiceResource extends Resource
{
	public function toArray($request)
	{
		$data = [
			'id' => $this->id,
			'name' => $this->name,
			'type' => $this->type,
		];

		if ($this->icon) {
			$data['icon'] = $this->icon->getThumb(250, 250, ['mode' => 'crop']);
		}

		return $data;
	}
}