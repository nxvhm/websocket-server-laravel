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
    // $this->app->make('Nxvhm\Users\Http\Controllers\UsersController');
    // $this->loadViewsFrom(__DIR__.'/resources/views', 'users');
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
  }
}
