# Web Socket Server for Laravel Artisan

Simple wrapper of http://socketo.me/docs/hello-world intended to use with artisan in laravel env

## Install
0. Install the required dependencies **cboden/ratchet** and **textalk/websocket** in your laravel project

```bash
$ composer require cboden/ratchet
$ composer require textalk/websocket
```

1. Clone or download the repository      

2. Copy the folder in your laravel project. For example in *laravel-project/app/Library*      

3. Register the package in your composer.json
```json
    "autoload": {
      "psr-4": {
          "App\\": "app/",
          "Nxvhm\\WebSocket\\": "app/Library/WebSocket/src/"
      }
    }
```
4. Register the service provider in *config/app.php*
```php

  'providers'=> [
    ...
    Nxvhm\WebSocket\WebSocketServiceProvider::class
  ]

```

5. Run 
```bash
$ composer dump-autoload
```

6. Publish config file and assets
```bash
php artisan vendor:publish --provider="Nxvhm\WebSocket\WebSocketServiceProvier"
```

## Start
```php
php artisan wss:start {port}
```

## Example Usage

Start your ws server with *php artisan wss:start*                  

In your .blade view include SocketClient.js, initialize connection to the socket server and start listening for some event you know will be dispatched ( *my-event-progress* in our case )
```html
<div id="my-progressbar"></div>

<script src="{{ asset('/assets/js/SocketClient.js') }}" type="text/javascript"></script>

<script>
  // Get Server Ip and port
  var uid =  '{{ Auth::user()->id }}';
  var socketHost = "{{ Config::get('websocket.host', false)}}";
  var socketPort = "{{ Config::get('websocket.port', false)}}";
  $(document).ready(function(){
    // Initialize connection
    window.SocketClient.connect(socketHost, socketPort, uid, function(result) {
      console.log('connected', result);
      // Add event listener to update the DOM when event occure
      window.SocketClient.getConnection().addEventListener('my-event-progress', function(event){
        document.getElementById('my-progressbar').innerHtml(event.detail);
      });
    });
  })
</script>
```

And then from your artisan command or your job class use the websocket client provided 
from textalk/websocket to send messages to our ratchet server.

```php

namespace App\Jobs;
use WebSocket\Client;

....
....
class ProcessSomething implements ShouldQueue {
  ....
  //The uid is user id for which the messages are intended
  // We use this user id to build the correct message
  // So the server knows to which connection to send it
  public function __construct(array $items, $uid = null) {
    $this->items = $items;
    $this->uid = $uid;
  }

  /**
   * Execute the job.
   * @return void
    */
  public function handle()
  {
    $count = count($this->items);
    $port = Config('websocket.port', false);
    $ip = Config('websocket.host', false);

    $connectionString ="ws://$ip:$port" . '?uid=0';
    $client = new Client($connectionString);

    foreach($this->items as $key => $item) {
      $keyCount = $key + 1;

      // do some procesing of items

      $percentsComplete = round(($keyCount/$count)*100);
      $msg = json_encode(['for_uid' => $this->uid, 'type' => 'event', 'name' => 'my-event-progress', 'value' => $percentsComplete]);
      try {
        $client->send($msg);
      } catch (\WebSocket\ConnectionException $e) {
        // Unsuccessful connection attempt
      }


    }    
  }
}
