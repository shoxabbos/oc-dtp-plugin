<?php namespace Itmaker\DtpApp\Jobs;

use App;
use Itmaker\DtpApp\Models\Call;
use RainLab\User\Models\User;

class NewCallCreated
{
    
    public function fire($job, $data)
    {
        $model = Call::find($data['id']);

        if (!$model) {
        	return $job->delete();
        }

        // send push notifications to employes
        $firebase = App::make('fcm');

        if ($model->type == 'crash' || $model->type == 'tracker') {
        	$query = User::where('type', 'master');
        } else {
        	$query = User::where('type', 'specialists');
        }

        $tokens = $query->where('device_id', '')->lists('device_id', 'id');

        if (empty($lists)) {
        	return $job->delete();
        }

     	$firebase->sendNotificationMultiple(
     		$tokens,
     		'Title',
     		'Body',
     		['id' => $model->id]
     	);

        $job->delete();
    }

}