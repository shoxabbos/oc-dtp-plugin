<?php namespace Itmaker\DtpApp\Providers;

use October\Rain\Support\ServiceProvider;

class PushNotificationProvider extends ServiceProvider
{

    public function __construct($app)
    {
        parent::__construct($app);

    }

    public function register()
    {
        $this->app->singleton('fcm', function() {
            return new \Itmaker\DtpApp\Classes\FcmSender();
        });
    }

}