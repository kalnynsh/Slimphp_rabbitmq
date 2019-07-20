const WebSocket = require('ws');
const fs = require('fs');
const jwt = require('jsonwebtoken');
const dotenv = require('dotenv');
const kafka = require('kafka-node');

dotenv.load();

let server = new WebSocket.Server({ port: 8000 });
let jwtKey = fs.readFileSync(process.env.WS_JWT_PUBLIC_KEY);

server.on('connection', function (ws, request) {
    // eslint-disable-next-line no-console
    console.log('Connected: %s', request.connection.remoteAddress);

    ws.on('message', function (message) {
        let data = JSON.parse(message);

        if (data.type && data.type === 'auth') {
            try {
                let token = jwt.verify(data.token, jwtKey, {algorithms: ['RS256']});

                // eslint-disable-next-line no-console
                console.log('user_id: %s', token.sub);
                ws.user_id = token.sub;
            } catch (error) {
                // eslint-disable-next-line no-console
                console.error(error);
            }
        }
    });
});

const client = new kafka.KafkaClient({
    kafkaHost: process.env.WS_KAFKA_BROKER_LIST
});

const consumer = new kafka.Consumer(
    client,
    [
        {
            topic: 'notifications',
            partition: 0,
        }
    ],
    {
        groupId: 'websocket'
    }
);

consumer.on('message', function (message) {
    // eslint-disable-next-line no-console
    console.log('consumed: %s', message.value);

    let value = JSON.parse(message.value);

    server.clients.forEach(ws => {
        if (ws.user_id === value.user_id) {
            ws.send(message.value);
        }
    });
});
