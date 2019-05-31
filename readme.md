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

