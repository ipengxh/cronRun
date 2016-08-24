#!/usr/bin/python
# -*-coding:utf-8 -*-

from ConfigParser import *
import pyinotify
import os, sys, threading
import datetime
import logging
import schedule

class local(threading.Thread):
    """docstring for Local"""
    def __init__(self, configFile):
        self.configFile = configFile

    def watch(self):
        WatchManager = pyinotify.WatchManager()
        WatchManager.add_watch(self.configFile, pyinotify.ALL_EVENTS, rec = True)
        handler = EventHandler(self.configFile)
        notifier = pyinotify.Notifier(WatchManager, handler)
        notifier.loop()

class scheduleMonitor(threading.Thread):
    """docstring for schedule"""
    def __init__(self, configFile, taskThread):
        threading.Thread.__init__(self)
        self.configFile = configFile
        self.taskThread = taskThread
        self.thread_stop = False

    def run(self):
        self.watch()

    def watch(self):
        WatchManager = pyinotify.WatchManager()
        WatchManager.add_watch(self.configFile, pyinotify.ALL_EVENTS, rec = True)
        handler = EventHandler(self.configFile, self.taskThread)
        notifier = pyinotify.Notifier(WatchManager, handler)
        notifier.loop()

class new():
    """docstring for new"""
    def __init__(self, configPath):
        self.configPath = configPath
        self.watch()

    def watch(self):
        WatchManager = pyinotify.WatchManager()
        WatchManager.add_watch(self.configPath, pyinotify.ALL_EVENTS, rec = True)
        handler = EventHandler(self.configPath)
        notifier = pyinotify.Notifier(WatchManager, handler)
        notifier.loop()

class Scaner():
    """docstring for Scaner"""
    def __init__(self, configPath):
        self.configPath = configPath

class EventHandler(pyinotify.ProcessEvent):
    logging.basicConfig(level = logging.INFO,filename='/tmp/monitor.log')
    logging.info("Starting monitor...")

    def __init__(self, config, taskThread = False):
        self.config = config
        self.taskThread = taskThread

    def process_IN_CREATE(self, event):
        print "CREATE event:", event.pathname, self.config
        # boot a new task if schedule created

        logging.info("CREATE event : %s  %s" % (os.path.join(event.path,event.name),datetime.datetime.now()))

    def process_IN_DELETE(self, event):
        print "DELETE event:", event.pathname, self.config
        if self.taskThread is not False:
            print 'Task schedule config file deleted, task quit.'
            self.taskThread.stop()
            sys.exit()
        logging.info("DELETE event : %s  %s" % (os.path.join(event.path,event.name),datetime.datetime.now()))

    def process_IN_MODIFY(self, event):
        print "MODIFY event:", event.pathname, self.config
        if self.taskThread is not False:
            print 'Task schedule config file deleted, task quit.'
            self.taskThread.stop()
            # reboot schedule
            taskThread = schedule.schedule(self.config)
            taskThread.start()
            print self.config, 'booted again'
            monitor = scheduleMonitor(self.config, taskThread)
            monitor.start()
            print 'start to monitoring', self.config
            sys.exit()
        logging.info("MODIFY event : %s  %s" % (os.path.join(event.path,event.name),datetime.datetime.now()))

def get(a, b):
    parser = ConfigParser()
    parser.read('config.ini')
    return parser.get(a, b)

def watcher():
    WatchManager = pyinotify.WatchManager()
    WatchManager.add_watch('./', pyinotify.ALL_EVENTS, rec = True)
    EventHandler = EventHandler()
    notifier = pyinotify.Notifier(WatchManager, EventHandler)
    notifier.loop()

if __name__ == '__main__':
    watcher()
