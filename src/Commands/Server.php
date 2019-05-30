<?php

namespace Nxvhm\WebSocket\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Nxvhm\WebSocket\Messanger;
use Config;

class Server extends Command
{
    /**
     * @var string
     */
    protected $signature = 'wss:start {port?}';

    /**
     * @var string
     */
    protected $description = 'Start Ratchet Based Web Socket Server';

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

      $port = $this->argument('port') ?? Config::get('websocket.port', false);

      if (!$port) {
        throw new \Exception('No port specified');
      }

      $server = IoServer::factory(new HttpServer(
          new WsServer(new Messanger())
        ), $port
      );

      $this->info('WebSocketServer Started at port '.$port);
      $server->run();
    }
}
