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
            'is_active' => $this->is_active,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'groups' => $this->groups
        ];

		$group = $this->groups()->whereNotIn('code', ['clients', 'specialists'])->first();
		if ($group) {
            $data['balance'] = $this->balance;
        }

		return $data;
	}
}