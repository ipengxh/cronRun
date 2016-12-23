class connector(object):
    """docstring for Connector"""
    def __init__(self, config):
        self.config = config

    def connect(self):
        raise ConnectorException('Could not connect to server.')

class ConnectorException(Exception):
    """docstring for ConnectorException"""
    def __init__(self, message):
        self.message = message

    def __str__():
        return repr(self.message)

