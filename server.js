var app     = require('express')(),
    http        = require('http'),
    sys         = require('sys'),
    redis       = require('redis')
    io          = require('socket.io');


    var server      = http.createServer(app),
    clients     = {};

server.listen(3000, '192.168.10.10');

io.listen(server).on('connection', function(client) {

    var client_id = client.handshake.query.user_id;

    if (typeof clients[client_id] == 'undefined')
        clients[client_id] = [];

    clients[client_id].push(client);

    console.log('Connected: ' + client_id);
});

var redisClient = redis.createClient()
    redisClient.subscribe('actions');

redisClient.on("message", function(channel, message) {

    var response = JSON.parse(message);

    try {

        clients[response.client_id].forEach(function(client) {

            client.emit('notifications', response.data);
            console.log('Pushed ' + response.data + ' to ' + response.client_id);
        });
    }
    catch ( error ) {}
});