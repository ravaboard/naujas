<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Laravel PHP Framework</title>
        <style>
        </style>

        <script src="https://cdn.socket.io/socket.io-1.2.1.js"></script>

        <script type="text/javascript">// <![CDATA[

                var socket = io.connect('192.168.10.10:3000', {
                        query: 'user_id={{ Session::getId() }}'
                });

                socket.on('connect', function(data) {

                        socket.emit('subscribe', { channel: 'notifications' });
                });

                socket.on('notifications', function (data) {

                        console.log(data);
                });
        // ]]></script>
</head>

<body></body>
</html>