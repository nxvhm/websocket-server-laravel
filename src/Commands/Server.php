<?php

namespace Nxvhm\WebSocket\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Nxvhm\WebSocket\Messanger;

class Server extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wss:start {port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Ratchet Based Web Socket Server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $port = $this->argument('port');

      $server = IoServer::factory(new HttpServer(
          new WsServer(new Messanger())
        ), $port
      );

      $this->info('WebSocketServer Started at port '.$port);
      $server->run();
    }
}
