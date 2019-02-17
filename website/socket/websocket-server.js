#!/usr/bin/env node

var WebSocketServer = require('websocket').server;
//var http = require('http');
var https = require('https');
var fs = require('fs');
//var os  = require('os-utils');
//var exec = require('child_process').exec;

//var serialize = require('./serialize.js');
//var unserialize = require('./unserialize.js');

global.connections = [];

var server = https.createServer(
{
	key: fs.readFileSync( '/etc/letsencrypt/live/jinks.ml/privkey.pem' ),
	cert: fs.readFileSync( '/etc/letsencrypt/live/jinks.ml/cert.pem' )
},
function(request, response) {
    console.log((new Date()) + ' Received request for ' + request.url);
    response.writeHead(404);
    response.end();
});

server.listen(8889, function() {
	console.log((new Date()) + ' Server is listening on port 8888');
});

server.on('close', function() {
	// close function
});

wsServer = new WebSocketServer({
    httpServer: server,
    autoAcceptConnections: false
});

function originIsAllowed(origin) {
	return true;
	//return origin === 'https://notes.help.helpfulseb.com';
}

//setInterval(function() {
//}, 1000);

/*
setInterval(function() {
	global.connections.forEach(function(connection) {
		if (connection._auth_session.session === 1 && connection._auth_session.expires >= time()) {
			mysqlconn.query('UPDATE auth_sessions SET expires = ? WHERE id = ? LIMIT 1', [time() + SESSION_TIMEOUT, connection._auth_session.id]);
			connection._auth_session.expires = time() + SESSION_TIMEOUT;
		}
	});
	console.log("Authentication sessions updated successfully");
	console.log("Concurrent active connections: " + global.connections.length);
}, 60000);
*/

wsServer.on('request', function(request) {
	if (!originIsAllowed(request.origin)) {
		request.reject();
		console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected.');
		return;
	}

	var acceptableProtocols = ["verify"];
	for (var i = 0, len = request.requestedProtocols.length; i < len; i++) {
			if (acceptableProtocols.indexOf(request.requestedProtocols[i]) === -1) {
					console.log((new Date()) + ' Rejecting requested protocols: ', request.requestedProtocols);
					request.reject();
					return;
			} else {
					request.protocol = request.requestedProtocols[i];
					break;
			}
	}

	var connection = request.accept(request.protocol, request.origin);
	global.connections.push(connection);

	console.log((new Date()) + ' Connection accepted');

	// main start
	connection.on('message', function(message) {
		if (message.type === 'utf8') {
			console.log('Received Message ['+request.protocol+']: ' + message.utf8Data);
			if (JSON.parse(message.utf8Data)) {
				var opts = JSON.parse(message.utf8Data);
				switch(opts[0]) {
					case "updated":
						console.log('broadcasting update', opts);
						wsServer.broadcast(JSON.stringify(opts));
					break;
					default:
						console.log('unknown command', opts);
				}
			}
		} else if (message.type === 'binary') {
			console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');
			connection.sendBytes(message.binaryData);
		}
	});

	connection.on('close', function(reasonCode, description) {
		var index = global.connections.indexOf(connection);
		if (index > -1) {
			global.connections.splice(index, 1);
		} else {
			console.log("ERROR: Unable to locate connection in connectios array");
		}
		console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected.');
	});
	// main end

	function reject_request() {
		console.log(request.remoteAddresses);
		request.reject();
		console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected/bad auth.');
	}
});

time = function() {
	return Math.floor(new Date().getTime() / 1000)
}
