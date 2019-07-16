SocketClient = {

  connection: null,

  /**
   * Get current connection
   * @return  {WebSocket} or NULL
   */
  getConnection: () => {
    return SocketClient.connection;
  },

  /**
   * Check if socket client is currently connected to socket server
   * @return  {Bool}
   */
  isConnected: () => {
    let connection = SocketClient.getConnection();

    return (connection && connection.readyState && connection.readyState === 1);
  },

  /**
   * Connect to socket Server
   * @param {Object}  options Connection options to WebSocket server (ip, port, etc)
   * @param {Int}     port    WebSocket Server Port
   * @param {String}  uid     User ID connecting to the socket
   * @callback cb The function to execute on successfull connection or error/interuption.
   */
  connect: (options, uid, ssl = false, cb = null) => {
    let protocol = ssl ? 'wss://' : 'ws://'

    // If connection url provided use it instead of ip/port
    let url = options.connection_url
      ? `${protocol}${options.connection_url}`
      : `${protocol}${options.ip}:${options.port}?uid=${uid}`;

    SocketClient.connection = new WebSocket(url);

    //If connection throws error execute the provided
    SocketClient.connection.onerror = () => {
      // Some logic...
      if (cb) cb({error: true});
    }
    // On connection open execute the provided
    SocketClient.connection.onopen = () => {
      // Some logic here ??
      if (cb) cb({success: true});
    };

    SocketClient.connection.onmessage = SocketClient.incomingMessage;
  },

  /**
   * On WebSocket message handler
   * @param   {object}  WsMsg
   */
  incomingMessage: (WsMsg) => {
    try {

      // Dispatch Event or Custom event if data has value field
      let data = JSON.parse(WsMsg.data);

      if (data.type === 'event') {
        detail = data.value ? data.value : null;

        return SocketClient.dispatchEvent(data.name, detail);
      }

    } catch (error) {
      console.log('WsMsg', WsMsg.data);
      return false;
    }
  },

  dispatchEvent: (name, detail = null) => {
    if (SocketClient.isConnected()) {

      let socketEvent = (detail)
        ? new CustomEvent(name, {detail})
        : new Event(name);
      SocketClient.getConnection().dispatchEvent(socketEvent);
    }
  }
}

window.SocketClient = SocketClient;
