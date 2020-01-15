<?php namespace Itmaker\DtpApp\Jobs;

use App;

class SendSinglePush
{
    public function fire($job, $data)
    {
     	$firebase = App::make('fcm');

     	$firebase->sendNotification(
     		$data['title'],
     		$data['body'],
     		$data['token'],
            $data['data']
     	);

    	$job->delete();
    }
}