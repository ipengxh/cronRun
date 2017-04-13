import socket
from Crypto.Cipher import AES
import base64
import ConfigParser
import json

class Connector():
    """docstring for Connector"""
    def __init__(self, config):
        self.config = ConfigParser.ConfigParser()
        self.config.read(config)

    def test(self):
        self.connect()
        self.send(message = 'connect test')
        self.close()

    def connect(self):
        print "Try to connect to server %s:%s" %(self.config.get('server', 'ip'), self.config.get('server', 'port'))
        self.connection = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.connection.connect(self.address())
        if 'connected' != self.connection.recv(512):
            raise ConnectorException('Server is alive but refuse this connection.')
        else:
            return True

    def register(self):
        data = {
            'action': 'register',
            'server_token': self.config.get('server', 'token'),
            'message': 'register'
        }
        self.send(json.dumps(data))
        response = self.connection.recv(512)
        print response

    def address(self):
        host = self.config.get('server', 'ip')
        port = self.config.getint('server', 'port')
        return (host, port)

    def listen(self):
        while True:
            data = self.connection.recv(512)
            if data:
                print "data recived: ", data

    def close(self):
        self.connection.close()

    def send(self, message, encrypt = True):
        if encrypt:
            ciphertext = self.config.get('server', 'token')
            aes_object = AES.new(ciphertext, AES.MODE_CBC, ciphertext[0:16])
            message = message + ('\0' * (16 - len(message) % 16))
            message = aes_object.encrypt(message)
        self.connection.send(message)

class ConnectorException(Exception):
    """docstring for ConnectorException"""
    def __init__(self, message):
        self.message = message

    def __str__(self):
        return self.message
