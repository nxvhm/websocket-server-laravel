<?php
namespace Nxvhm\WebSocket;
use Config;

class WsClient {

  public static function send(String $msg, $uid = 0) {

    $port = Config('websocket.port', false);

    $ip = Config('websocket.host', false);

    $connectionString ="ws://$ip:$port" . ($uid !== false ? '?uid='.$uid : '');

    if (!$port || !$ip) {
      throw new \InvalidArgumentException('No port/host provided');
    }
    \Ratchet\Client\connect($connectionString)->then(function($conn) use($msg) {

      $conn->send($msg);
      $conn->close();
    }, function ($e) {
      throw($e);
      // echo "Could not connect: {$e->getMessage()}\n";
    });
  }



}
