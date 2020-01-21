<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;


class CallResource extends Resource
{
	public function toArray($request)
	{
		$data = [
			'id' => (int) $this->id,
			'coor_lat' => (double) $this->coor_lat,
			'coor_long' => (double) $this->coor_long,
			'address' => $this->address,
			'type' => $this->type,
			'status' => $this->status,
			'can_set_arrived_status' => $this->can_set_arrived_status,
			'comment' => $this->comment,
			'employee_lat' => (double) $this->employee_lat, 
			'employee_long' => (double) $this->employee_long,
			'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i') : null,
			'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i') : null,
			'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,
			'review_star' => $this->review_star,
			'review_text' => $this->review_text,

			'user' => $this->client ? new UserResource($this->client) : null,
			'employe' => $this->employe ? new UserResource($this->employe) : null,
			'services' => $this->services ? ServiceResource::collection($this->services) : null,
		];


		if ($this->images) foreach ($this->images as $key => $value) {
			$data['images'][] = $value->getThumb(600, 400);
		}

		return $data;
	}
}