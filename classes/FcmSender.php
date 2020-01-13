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


	public function sendNotification($title, $body, $token, $data = []) {
		$notification = Notification::create($title, $body);

		$message = CloudMessage::withTarget('token', $token)
						->withNotification($notification)
		    			->withData($data);

		return $this->firebase->send($message);
	}

}