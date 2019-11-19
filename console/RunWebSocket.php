<?php namespace Itmaker\DtpApp\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Itmaker\DtpApp\Classes\ServerFactory;

class RunWebSocket extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'itmaker:runwebsocket';

    /**
     * @var string The console command description.
     */
    protected $description = 'running ratchet websocket server';

    protected $port;

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->info("Server listening on 7979");

        // Run the server application through the WebSocket protocol on port 8080
        $app = new \Ratchet\App('itmaker.uz', 8043);
        $app->route('/', new \Itmaker\DtpApp\Classes\Tracking(), array('*'));
        $app->route('/echo', new \Ratchet\Server\EchoServer, array('*'));
        $app->run();

    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}