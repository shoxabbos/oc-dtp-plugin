<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;


class TariffResource extends Resource
{
	public function toArray($request)
	{
		$data = parent::toArray($request);

		if ($this->image) {
			$data['image'] = $this->image->getThumb(250, 250, ['mode' => 'crop']);
		}

		return $data;
	}
}