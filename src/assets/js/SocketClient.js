window.SocketClient = {

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
   * @param {String}  url   WebSocket server domain
   * @param {Int}     port  WebSocket Server Port
   * @param {String}  uid   User ID connecting to the socket
   * @callback cb The function to execute on successfull connection or error/interuption.
   */
  connect: (url, port, uid, cb = null) => {
    SocketClient.connection = new WebSocket(`ws://${url}:${port}?uid=${uid}`);

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

    // SocketClient.connection.addEventListener('new-user-online', function(e) {
    //   console.log(console.log('new-user-online', e));
    // });
  },

  /**
   * On WebSocket message handler
   * @param   {object}  payload
   */
  incomingMessage: (payload) => {
    try {
      let data = JSON.parse(payload.data);

      console.log('WsMsg', data);
      if (data.type === 'event') {
        return SocketClient.dispatchEvent(data.name);
      }

    } catch (error) {
      console.error(error.message);
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

