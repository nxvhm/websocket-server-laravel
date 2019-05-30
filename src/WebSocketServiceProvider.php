<?php

namespace Nxvhm\WebSocket;

use Illuminate\Support\ServiceProvider;

class WebSocketServiceProvider extends ServiceProvider
{
  protected $commands = [
    \Nxvhm\WebSocket\Commands\Server::class
  ];

  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
  }
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->commands($this->commands);
    }

    # php artisan vendor:publish --tag=config
    $this->publishes([
      __DIR__ . '/config/websocket.php' => \config_path('websocket.php')
    ], 'config');

    $this->publishes([
      __DIR__ . '/assets/js/SocketClient.js' => \public_path('assets/js/SocketClient.js')
    ], 'assets');
  }
}
