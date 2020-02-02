<?php namespace Itmaker\DtpApp\Providers;

use \Pusher\Pusher;
use October\Rain\Support\ServiceProvider;

class PusherServiceProvider extends ServiceProvider
{
    public $options;

    public function __construct($app)
    {
        $this->options = array(
            'cluster' => 'ap3',
            'useTLS' => true
        );

        parent::__construct($app);

    }

    public function register()
    {
        $this->app->singleton('pusher', function() {
            return new Pusher(
                'b5417a2494115d7be8e3',
                'ec5291ddbfe11f6b85d9',
                '941261',
                $this->options
            );
        });
    }

}