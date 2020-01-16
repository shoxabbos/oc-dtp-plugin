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

        $tokens = $query->lists('device_id');

        foreach ($tokens as $key => $value) {
            if (empty($value)) {
                unset($tokens[$key]);
            }
        }

        if (empty($tokens)) {
        	return $job->delete();
        }

        $body = $model->address.", ".$model->client->username;

     	$firebase->sendNotificationMultiple(
     		$tokens, 
            'Новая заявка', 
            $body,
            [
                'action_type' => 'new_call', 
                'call' => $model->id
            ]
     	);

        $job->delete();
    }

}