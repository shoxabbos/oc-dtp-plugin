<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * 
 */
class UserResource extends Resource
{
	public function toArray($request)
	{
		$data = [
			'id' => $this->id,
			'name' => $this->name,
			'surname' => $this->surname,
			'username' => $this->username,
			'email' => $this->email,
			'is_activated' => $this->is_activated,
		];

		if ($this->balance) {
            $data['balance'] = $this->balance;
        }

		return $data;
	}
}