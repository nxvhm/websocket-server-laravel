<?php

require __DIR__ . '/../vendor/autoload.php';

\Ratchet\Client\connect('ws://localhost:4255?uid=16')->then(function($conn) {
  $conn->on('message', function($msg) use ($conn) {
      echo "Received: {$msg}\n";
      $conn->close();
  });

  $conn->send('Hello World!');
}, function ($e) {
  echo "Could not connect: {$e->getMessage()}\n";
});
