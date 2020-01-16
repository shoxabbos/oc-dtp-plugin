<?php namespace Itmaker\DtpApp\Classes;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmSender {

	public $firebase;

	public function __construct() {
		$this->firebase = (new Factory())
	            ->withServiceAccount(app_path() . '/dtp-firebase.json')
	            ->createMessaging();
	}


	public function sendNotification($title, $body, $token, $data = ['key' => 'value']) {
		$data = $this->stringifyArray($data);

		$notification = Notification::create($title, $body);

		$message = CloudMessage::withTarget('token', $token)
						->withNotification($notification)
		    			->withData($data);

		 try {
			return $this->firebase->send($message);
		 } catch (\Exception $e) {
		 	return false;
		 }
	}


	public function sendNotificationMultiple($tokens, $title, $body, $data = ['key' => 'value']) {
		$data = $this->stringifyArray($data);

		$notification = Notification::create($title, $body);
		$message = CloudMessage::new()
			->withNotification($notification)
			->withData($data);

		try {
			return $this->firebase->sendMulticast($message, $tokens);
		 } catch (\Exception $e) {
		 	return false;
		 }
	}


	private function stringifyArray(array $data) {
		$newData = [];

		foreach ($data as $key => $value) {
			$value = (string) $value;

			if ($value) {
				$newData[(string) $key] = $value;
			}
		}

		return $newData;
	}
}