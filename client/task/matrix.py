from server import *
import config
import schedule
import threading, time, sys, os

class matrix():
    """docstring for CronRun"""
    def __init__(self, config_file):
        # init server connector
        self.connector = connector.connector(config_file)
        self.config_file = config_file

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
        schedule_config_path = os.path.dirname(self.config_file) + '/schedules'
        schedule_config_files = schedule.scan(schedule_config_path)
        self.newSchedule(schedule_config_path)
        for schedule_config_file in schedule_config_files.all():
            print 'trying load task', schedule_config_file
            taskThread = self.bootSchedule(schedule_config_file)
            self.bootMonitor(schedule_config_file, taskThread)
        print 'all tasks are booted.'

    def bootHost(self):
        host = config.local(self.config_file)
        threading.Thread(target = host.watch).start()

    def newSchedule(self, dirName):
        threading.Thread(target = config.new, args = (dirName,)).start()

    def bootSchedule(self, schedule_config_file):
        taskThread = schedule.schedule(schedule_config_file)
        taskThread.start()
        print schedule_config_file, 'booted'
        return taskThread

    def bootMonitor(self, schedule_config_file, taskThread):
        monitor = config.scheduleMonitor(schedule_config_file, taskThread)
        monitor.start()
        print 'start to monitoring', schedule_config_file
