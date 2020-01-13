<?php namespace Itmaker\DtpApp\Providers;

use Kreait\Firebase\Factory;
use October\Rain\Support\ServiceProvider;

class PushNotificationProvider extends ServiceProvider
{

    public function __construct($app)
    {
        parent::__construct($app);

    }

    public function register()
    {
        $messaging = (new Factory())
            ->withServiceAccount(app_path() . '/dtp-firebase.json')
            ->createMessaging();
    
        $this->app->singleton('fcm', function() {
            return $messaging;
        });
    }

}