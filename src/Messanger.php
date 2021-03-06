<?php
namespace Nxvhm\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Config;

class Messanger implements MessageComponentInterface {

  protected $connections;

  public function __construct() {
    $this->connections = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {

    if (Config::get('websocket.require_user_id', false)) {
      $this->connectionWithUid($conn);
    } else {
      $this->connections->attach($conn);

      echo "New connection! ({$conn->uid})\n";
    }

  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->connections) - 1;

    echo sprintf('UID %d sending message "%s" to %d other connection%s' . "\n"
      , $from->uid, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    if (is_array(json_decode($msg, true))) {
      $payload = json_decode($msg, true);
    }

    $receiverUid = false;

    if (isset($payload['for_uid'])) {
        $receiverUid = $payload['for_uid'];
    }

    foreach ($this->connections as $client) {
      if ($from !== $client) {
        if ($receiverUid) {
          if ($client->uid == $receiverUid) {
            $client->send($msg);
          }
        } else {
          $client->send($msg);
        }
      }
    }
  }

  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->connections->detach($conn);

    echo "Connection {$conn->uid} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
      echo "An error has occurred: {$e->getMessage()}\n";

      $conn->close();
  }

  protected function connectionWithUid(ConnectionInterface $conn) {

    parse_str($conn->httpRequest->getUri()->getQuery(), $requestParams);

    if (!isset($requestParams[Config::get('websocket.user_id_key')])) {
      $conn->close();
      throw new \Exception('NO USER ID PROVIDED, CONNECTION REFUSED');
    }

    $userId = $requestParams[Config::get('websocket.user_id_key')];

    $conn->uid = $userId;

    $this->connections->attach($conn);

    echo "New connection User Id: ({$conn->uid})\n";
  }
}
