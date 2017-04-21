import socket
from Crypto.Cipher import AES
import base64
import ConfigParser
import json, sys, os, setproctitle, time
from socket import error as socket_error

class Connector():
    """docstring for Connector"""
    def __init__(self, config):
        self.config = ConfigParser.ConfigParser()
        self.config.read(config)
        self.token = self.config.get('server', 'token')

    def test(self):
        try:
            self.connect()
            data = {
                'action': 'test',
                'token': self.token,
            }
            self.connection.send(json.dumps(data))
            print self.get_response()['data']
            self.connection.close()
        except socket_error as e:
            print "Oops, unable to connect to the server"
        else:
            pass
        finally:
            self.connection.close()

    def connect(self):
        print "Try to connect to server %s:%s" %(self.config.get('server', 'ip'), self.config.get('server', 'port'))
        self.connection = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.connection.connect(self.address())

    def reconnect(self):
        self.connect()

    def register(self):
        data = {
            'action': 'register',
            'token': self.token,
            'os': os.uname(),
        }
        self.connection.send(json.dumps(data))
        data = self.get_response()
        if data['status'] == 'error':
            print 'Server response: ', data['message']
            print 'Error code: ', data['code']
            sys.exit()
        tasks = data['data']
        for task in tasks:
            with open('schedules/' + task['app']['token'] + '.ini', 'w') as config_file:
                config = ConfigParser.ConfigParser()
                for (section_name, section) in task.items():
                    config.add_section(section_name)
                    for (setting_key, setting_value) in section.items():
                        config.set(section_name, setting_key, setting_value)
                config.write(config_file)

    def keep_alive(self):
        while True:
            data = {
                'action': 'keepalive',
                'token': self.token,
                'message': 'heart beat'
            }
            self.connection.send(json.dumps(data))
            time.sleep(30)

    def address(self):
        host = self.config.get('server', 'ip')
        port = self.config.getint('server', 'port')
        return (host, port)

    def listen(self):
        while True:
            data = self.connection.recv(512)
            print "data recived: ", data

    def get_response(self):
        message_length = int(self.connection.recv(10), 16)
        chunks = []
        while message_length > 0:
            chunk = self.connection.recv(512)
            chunks.append(chunk)
            message_length = message_length - 512
        return json.loads(b''.join(chunks))

class ConnectorException(Exception):
    """docstring for ConnectorException"""
    def __init__(self, message):
        self.message = message

    def __str__(self):
        return self.message
