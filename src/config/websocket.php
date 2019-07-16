<?php

return [
  'port' => 8080,
  'host' => '127.0.0.1',
  'require_user_id' => true,
  'user_id_key' => 'uid',

  # USED ONLY FOR JS CLIENT CONNECTIONS
  # Use connection_url in case ws server is deployed behind reverse proxy.
  # The js client library will try to connect to this url instead
  # of ip/port combination.
  'connection_url' => false,
];
