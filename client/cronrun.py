#!/usr/bin/python
# -*-coding:utf-8 -*-

from task import *
from server import *
import sys, os, textwrap, subprocess, setproctitle, time, signal, commands
from socket import error as socket_error
from ConfigParser import ConfigParser

base_path = os.path.dirname(os.path.abspath(__file__))
config_file = os.path.dirname(os.path.abspath(__file__)) + '/cronrun.ini'

class CronRun():
    """docstring for cronrun"""
    def __init__(self, config_file):
        self.config_file = config_file
        self.config = ConfigParser()
        self.config.read(config_file)
        self.pid_file = self.config.get('cronrun', 'pid')

    def test(self):
        print 'testing server...'
        connector.Connector(self.config_file).test()
        self.scan()

    def connect(self):
        try:
            connection = connector.Connector(self.config_file)
            connection.connect()
            connection.register()
            connection.keep_alive()
            connection.listen()
        except connector.ConnectorException, e:
            print "Error: " + e.message
        except socket_error, e:
            print e
            print "Server is down"

    def scan(self):
        print 'Scan schedules...'
        schedules = schedule.ScheduleConfig('%s/schedules' % (base_path))
        for task_config_file in schedules.all():
            config = ConfigParser()
            config.read(task_config_file)
            print "Schedule:", config.get('app', 'name')
            print "project: ", config.get('app', 'project')
            print "config file path: ", task_config_file, "\n"

    def daemon(self):
        pid = os.fork()
        if pid > 0:
            sys.exit()

        os.chdir("/")
        os.setsid()
        os.umask(0)

        pid = os.fork()
        if pid > 0:
            sys.exit()

        si = file("/dev/null", 'r')
        so = file("/dev/null", 'a+')
        se = file("/dev/null", 'a+', 0)
        os.dup2(si.fileno(), sys.stdin.fileno())
        os.dup2(so.fileno(), sys.stdout.fileno())
        os.dup2(se.fileno(), sys.stderr.fileno())

        with open(self.pid_file, 'w') as pid_file:
            pid_file.write("%s" % os.getpid())

    def start(self):
        self.daemon()
        setproctitle.setproctitle('cronRun master')
        self.connect()
        schedules = schedule.ScheduleConfig('%s/schedules' % (base_path))
        #for task in schedules.all():
        #    if os.fork() <= 0:
        #        setproctitle.setproctitle("cronRun worker: %s" % task)
        #        print task
                #while True:
                #    time.sleep(1)
                #subProgress = subprocess.Popen(command, shell = True, stdout = subprocess.PIPE, stderr = subprocess.STDOUT)
        while True:
            time.sleep(1)
        sys.exit()
        cron = matrix.matrix(self.config_file)
        print cron
        #sys.exit()
        cron.start()

    def stop(self):
        pid = int(open(self.pid_file, 'r').read())
        os.kill(pid, signal.SIGTERM)
        os.remove(self.pid_file)

    def status(self):
        try:
            pid = int(open(self.pid_file, 'r').read())
            running_pid = commands.getoutput('ps -A | grep "^%s"' % pid)
            if running_pid is not None:
                return pid
            return False
        except Exception as e:
            return False

def main():
    try:
        sys.argv[1]
        cron_run = CronRun(config_file)
        if 'test' == sys.argv[1]:
            cron_run.test()
            return
        elif 'scan' == sys.argv[1]:
            cron_run.scan()
            return
        elif 'start' == sys.argv[1]:
            cron_run.daemon()
            cron_run.start()
            return
        elif 'restart' == sys.argv[1]:
            print 'scan for schedules'
            schedules = schedule.scan('schedules')
            for task in schedules.all():
                print task
            return
        elif 'stop' == sys.argv[1]:
            cron_run.stop()
            return
        elif 'status' == sys.argv[1]:
            pid = cron_run.status()
            if pid is False:
                print 'CronRun is not running'
            else:
                print 'CronRun is running at pid %s' % pid
            return

    except IndexError, e:
        pass
        #cron.start()
    except Exception, e:
        print e
    else:
        print 'Invalid option', sys.argv[1]
        sys.exit()

if __name__ == "__main__":
    main()
