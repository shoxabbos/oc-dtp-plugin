<?php namespace Itmaker\DtpApp\Providers;

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
            return new \Pusher\Pusher(
                '9090d7f4b6974f9e0c63',
                'c33072f80b8d8dd9ad47',
                '895882',
                $this->options
            );
        });
    }

}