#!/usr/bin/python
# -*-coding:utf-8 -*-

from task import *
from server import *
import sys, os, textwrap, subprocess, setproctitle, time
from socket import error as socket_error

base_path = os.path.dirname(os.path.abspath(__file__))
config_file = os.path.dirname(os.path.abspath(__file__)) + '/cronrun.ini'

def connect():
    try:
        connection = connector.Connector(config_file)
        connection.connect()
        connection.register()
        connection.heart_beat()
        connection.listen()
    except connector.ConnectorException, e:
        print "Error: " + e.message
    except socket_error, e:
        print e
        print "Server is down"

def scan():
    print 'scan for schedules'
    schedules = schedule.scan(base_path + '/schedules')
    for task in schedules.all():
        print task

def daemon():
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

    return pid

def start():
    #daemon()
    setproctitle.setproctitle('cronRun master')
    connect()
    schedules = schedule.scan('%s/schedules' % (base_path))
    for task in schedules.all():
        if os.fork() <= 0:
            setproctitle.setproctitle("cronRun worker: %s" % task)
            print task
            while True:
                time.sleep(1)
            #subProgress = subprocess.Popen(command, shell = True, stdout = subprocess.PIPE, stderr = subprocess.STDOUT)
    while True:
        time.sleep(1)
    sys.exit()
    cron = matrix.matrix(config_file)
    print cron
    sys.exit()
    cron.start()

def main():
    try:
        sys.argv[1]
        if 'connect' == sys.argv[1]:
            connect()
            return
        elif 'scan' == sys.argv[1]:
            scan()
            return
        elif 'start' == sys.argv[1]:
            #daemon()
            start()
            return
        elif 'restart' == sys.argv[1]:
            print 'scan for schedules'
            schedules = schedule.scan('schedules')
            for task in schedules.all():
                print task
            return
        elif 'stop' == sys.argv[1]:
            if is_running() is None:
                print "cronRun is not running"
                return

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
