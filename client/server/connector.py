import socket
from Crypto.Cipher import AES
import base64
import ConfigParser
import json, sys, os, setproctitle, time

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
            'os': os.uname(),
            'message': 'register'
        }
        self.send(json.dumps(data))
        message_length = int(self.connection.recv(10), 16)
        chunks = []
        while message_length > 0:
            chunk = self.connection.recv(512)
            chunks.append(chunk)
            message_length = message_length - 512
        message = self.decrypt(b''.join(chunks))
        print message

    def heart_beat(self):
        server_token = self.config.get('server', 'token')
        while True:
            data = {
                'action': 'beat',
                'server_token': server_token,
                'message': 'heart beat'
            }
            self.send(json.dumps(data))
            time.sleep(30)

    def address(self):
        host = self.config.get('server', 'ip')
        port = self.config.getint('server', 'port')
        return (host, port)

    def listen(self):
        while True:
            data = self.connection.recv(512)
            print "data recived: ", data

    def close(self):
        self.connection.close()

    def send(self, message, encrypt = True):
        if encrypt:
            ciphertext = self.config.get('server', 'secret')
            aes_object = AES.new(ciphertext, AES.MODE_CBC, ciphertext[0:16])
            message = message + ('\0' * (16 - len(message) % 16))
            message = aes_object.encrypt(message)
        self.connection.send(self.config.get('server', 'token') + message)

    def decrypt(self, message):
        ciphertext = self.config.get('server', 'secret')
        aes_object = AES.new(ciphertext, AES.MODE_CBC, ciphertext[0:16])
        return aes_object.decrypt(message).strip()

class ConnectorException(Exception):
    """docstring for ConnectorException"""
    def __init__(self, message):
        self.message = message

    def __str__(self):
        return self.message
