# Web Socket Server for Laravel Artisan

Simple wrapper of http://socketo.me/docs/hello-world intended to use with artisan in laravel env

## Install
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

## Start
```php
php artisan wss:start {port}
```
Then in your html:
```html
var conn = new WebSocket('ws://localhost:4255?uid=12&param1=val1');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);
};
```
