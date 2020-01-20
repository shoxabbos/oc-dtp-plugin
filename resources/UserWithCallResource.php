<?php namespace Itmaker\DtpApp\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * 
 */
class UserWithCallResource extends Resource
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
                  "device_id" => $this->device_id,
                  "device_type" => $this->device_type,
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
                  'active_call' => null,
                  'insurance' => $this->insurance,
                  'groups' => '',
		];

            if ($this->type == 'client') {
                  $activeCall = $this->calls()->orderByDesc('id')->first();
            } else {
                  $activeCall = $this->employe_calls()->orderByDesc('id')->first();
            }

            if ($activeCall && !in_array($activeCall->status, ['canceled', 'completed'])) {
                  $activeCall->client;
                  $activeCall->employe;
                  $activeCall->services;
                  $data['active_call'] = $activeCall;
            }

            $groups = $this->groups->lists('name');

            if (!empty($groups) && is_array($groups)) {
                  $data['groups'] = implode(", ", $groups);
            }

		return $data;
	}
}