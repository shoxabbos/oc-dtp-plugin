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
			'comment' => $this->comment,
			'employee_lat' => (double) $this->employee_lat, 
			'employee_long' => (double) $this->employee_long,
			'deleted_at' => $this->deleted_at,
			'updated_at' => $this->updated_at,
			'created_at' => $this->created_at,

			'user' => $this->client ? new UserResource($this->client) : null,
			'employe' => $this->employe ? new UserResource($this->employe) : null,
			'services' => $this->services ? ServiceResource::collection($this->services) : null,
		];

		return $data;
	}
}