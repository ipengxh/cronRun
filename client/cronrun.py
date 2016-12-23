#!/usr/bin/python
# -*-coding:utf-8 -*-

from task import *
from server import *
import sys, os, textwrap

ConfigFile = os.path.dirname(os.path.abspath(__file__)) + '/cronrun.ini'

def connect():
    print 'Try to connect to server...'
    try:
        connection = connector.connector(ConfigFile)
        connection.connect()
    except connector.ConnectorException, e:
        print e.message

def scan():
    print 'scan for schedules'
    schedules = schedule.scan('schedules')
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
    so = file("/dev/null",'a+')
    se = file("/dev/null",'a+',0)
    os.dup2(si.fileno(), sys.stdin.fileno())
    os.dup2(so.fileno(), sys.stdout.fileno())
    os.dup2(se.fileno(), sys.stderr.fileno())

    return pid

def start():
    cron = matrix.matrix(ConfigFile)
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
            print setproctitle.getproctitle()
            #if is_running() is not None:
            #    print "cronRun is running, pid: ", is_running()
            #    return
            daemon_pid = daemon()
            print 'daemon booted, start to run'
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
