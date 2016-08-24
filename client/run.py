#!/usr/bin/python
# -*-coding:utf-8 -*-

from task import *
from server import *
import sys, os

ConfigFile = 'config.ini'

schedules = []

try:
    sys.argv[1]
    if 'connect' == sys.argv[1]:
        print 'Try to connect to server...'
        try:
            connection = connector.connector(ConfigFile)
            connection.connect()
        except connector.ConnectorException, e:
            print e.message

    if 'scan' == sys.argv[1]:
        print 'scan for schedules'
        schedules = schedule.scan('schedules')
        for task in schedules.all():
            print task
    sys.exit()

except IndexError, e:
    cron = matrix.matrix(ConfigFile)
    cron.start()
except Exception, e:
    print e
else:
    print 'Invalid option', sys.argv[1]
    sys.exit()
