from Server import *

class CronRun():
    """docstring for CronRun"""
    def __init__(self, ConfigFile):

        self.connector = Connector.Connector(ConfigFile)

    def start(self):
        try:
            # connect to server
            connector = self.connector.connect()

            Heart.beat(connector)
        except Connector.ConnectorException as exception:
            # connect to server failed, boot as self host
            print exception.message
        except TaskDownloader.DownloadConfigException as exception:
            # download task setting files failed
            # use old tasks' schedule for booting
            print exception.message
        except Exception, e:
            print 'Could not start CronRun:', e
        else:
            # start beat my heart
        finally:
            # start task bootloader
