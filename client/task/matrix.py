from server import *
import config
import schedule
import threading, time, sys, os

class matrix():
    """docstring for CronRun"""
    def __init__(self, ConfigFile):
        # init server connector
        self.connector = connector.connector(ConfigFile)
        self.ConfigFile = ConfigFile

    """boot the cronRun"""
    def start(self):
        try:
            # connect to server
            connection = self.connector.connect()
            heart.beat(connection)
        except connector.ConnectorException as exception:
            # connect to server failed, boot as self host
            print exception.message
        except task.DownloadConfigException as exception:
            # download task setting files failed
            # use old tasks' schedule for booting
            print exception.message
        except Exception, e:
            print 'Could not start CronRun:', e
        except KeyboardInterrupt, e:
            print 'user stopped cronRun'
            sys.exit()
        else:
            # start beat my heart
            print 'Connected to server.'
        finally:
            # start task bootloader
            print 'Tasks are enabled, cronRun is running...'
            self.bootstrap()

    def bootstrap(self):
        # boot self hosting config watcher
        self.bootHost()
        # boot tasks
        scheduleConfigPath = os.path.dirname(self.ConfigFile) + '/schedules'
        scheduleConfigFiles = schedule.scan(scheduleConfigPath)
        self.newSchedule(scheduleConfigPath)
        for scheduleConfigFile in scheduleConfigFiles.all():
            print 'trying load task', scheduleConfigFile
            taskThread = self.bootSchedule(scheduleConfigFile)
            self.bootMonitor(scheduleConfigFile, taskThread)
        print 'all tasks are booted.'

    def bootHost(self):
        host = config.local(self.ConfigFile)
        threading.Thread(target = host.watch).start()

    def newSchedule(self, dirName):
        threading.Thread(target = config.new, args = (dirName,)).start()

    def bootSchedule(self, scheduleConfigFile):
        taskThread = schedule.schedule(scheduleConfigFile)
        taskThread.start()
        print scheduleConfigFile, 'booted'
        return taskThread

    def bootMonitor(self, scheduleConfigFile, taskThread):
        monitor = config.scheduleMonitor(scheduleConfigFile, taskThread)
        monitor.start()
        print 'start to monitoring', scheduleConfigFile
