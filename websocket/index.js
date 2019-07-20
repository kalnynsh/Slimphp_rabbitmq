let WebSocket = require('ws');

let fs = require('fs');
let jwt = require('jsonwebtoken');
let dotenv = require('dotenv');

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
