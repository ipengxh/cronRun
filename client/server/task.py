class task():
    """docstring for task"""
    def __init__(self, connection):
        self.connection = connection


class DownloadConfigException(Exception):
    """docstring for DownloadConfigException"""
    def __init__(self, message):
        self.message = message

    def __str__():
        return repr(self.message)

