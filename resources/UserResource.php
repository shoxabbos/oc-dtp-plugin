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
			"id" => $this->id,
            "name" => $this->name,
            "surname" => $this->surname,
            "phone" => $this->username,
            "email" => $this->email,
            "username" => $this->username,
            "balance" => $this->balance,
            "type" => $this->type,
            "permissions" => $this->permissions,
            "last_login" => $this->last_login,
            "last_seen" => $this->last_seen,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            "activated_at" => $this->activated_at,
            "is_activated" => $this->is_activated,
            "is_guest" => $this->is_guest,
            "is_superuser" => $this->is_superuser,
            "image" => $this->avatar ? $this->avatar->getThumb(250, 250, ['mode' => 'crop']) : null,
		];


		return $data;
	}
}