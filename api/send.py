#!/usr/bin/env python

import sys
import websocket
import json
import ssl

from websocket import create_connection
ws = create_connection("wss://jinks.ml:8889/verify", sslopt={"cert_reqs": ssl.CERT_NONE})
ws.send(json.dumps(['updated', [sys.argv[1], sys.argv[2], sys.argv[3]]]))
