from config import *
from os import listdir, walk
from os.path import isfile
import time, threading, sys, Queue
from ConfigParser import ConfigParser

class scan():
    """docstring for scan"""
    def __init__(self, configPath):
        self.configPath = configPath

    def all(self):
        files = []
        for (dirPath, dirNames, fileNames) in walk(self.configPath):
            #print dirPath
            for fileName in fileNames:
                #files.append(dirPath + '/' + fileName)
                files.extend([dirPath + '/' + fileName])
        return files

class schedule(threading.Thread):
    """docstring for boot"""
    def __init__(self, configFile):
        threading.Thread.__init__(self)
        self.configFile = configFile
        self.parser = ConfigParser()
        self.parser.read(configFile)
        self.schedule = {'status': True}
        print configFile, 'has been loaded'
        self.thread_stop = False

    def watch(self):
        conf = config.schedule(self.configFile)
        conf.watch()

    def config(self, section, option):
        return self.parser.get(section, option)

    def run(self):
        while not self.thread_stop:
            print 'app', self.config('app', 'project'), self.config('app', 'name'), 'executed'
            time.sleep(int(self.config('schedule', 'interval')))

    def stop(self):
        self.thread_stop = True
