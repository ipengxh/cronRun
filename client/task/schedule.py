from config import *
from os import listdir, walk
from os.path import isfile
import time, threading, sys, Queue, subprocess
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

    '''watch config file'''
    def watch(self):
        conf = config.schedule(self.configFile)
        conf.watch()

    '''get config'''
    def config(self, section, option, type = None):
        if 'int' == type:
            return self.parser.getint(section, option)
        if 'float' == type:
            return self.parser.getfloat(section, option)
        if 'boolean' == type:
            return self.parser.getboolean(section, option)
        return self.parser.get(section, option)

    '''schedule manager'''
    def run(self):
        self.boot_time = datetime.datetime.now()
        self.run_times = 0
        try:
            interval = self.config('schedule', 'interval', 'float')
        except ValueError:
            interval = False
        while not self.thread_stop:
            if interval is not False:
                self.run_interval_task(interval)
            else:
                self.run_timer_task()

    def run_interval_task(self, interval):
        thread = threading.Thread(target = self.execute)
        thread.start()
        thread.join(self.config('error', 'timeout', 'float'))
        self.run_times += 1
        # can not sleep exactly "interval" seconds, a few time passed while
        # these codes running, it could make the schedule run a little later
        # than expect
        next_run_time = self.boot_time + datetime.timedelta(seconds = interval * self.run_times)
        needs_sleep = (next_run_time - datetime.datetime.now()).total_seconds()
        time.sleep(needs_sleep)

    def run_timer_task(self):
        timers = self.config('schedule', 'timer').split(',')
        time_format = '%Y-%m-%d %H:%M:%S'
        now = datetime.datetime.now()
        now.strftime(time_format)
        wait = False
        for timer in timers:
            timer = timer.strip()
            now_time_str = now.strftime(time_format)
            timer_str = time.strftime(timer)
            if time.strptime(now_time_str, time_format) >= time.strptime(timer_str, time_format):
                next_run_time = self.next_time(timer)
                this_wait = (datetime.datetime.strptime(next_run_time, time_format) - now).total_seconds()
            else:
                next_run_time = time.strftime(timer)
                wait = (datetime.datetime.strptime(next_run_time, time_format) - now).total_seconds()
            if wait is False:
                wait = this_wait
            if this_wait < wait:
                wait = this_wait
        time.sleep(wait)
        print 'wait:', wait
        # task may stopped, check it before execute it
        if not self.thread_stop:
            thread = threading.Thread(target = self.execute)
            thread.start()
            thread.join(self.config('error', 'timeout', 'float'))

    # modify the stop flag
    def stop(self):
        self.thread_stop = True

    # execute the task
    def execute(self):
        subProgress = subprocess.Popen(self.config('app', 'command'), shell = True, stdout = subprocess.PIPE, stderr = subprocess.STDOUT)

    # get next running time
    def next_time(self, timer):
        if timer.find('%M') >= 0:
            next_time = datetime.datetime.now() + datetime.timedelta(minutes = 1)
        elif timer.find('%H') >= 0:
            next_time = datetime.datetime.now() + datetime.timedelta(hours = 1)
        elif timer.find('%d') >= 0:
            next_time = datetime.datetime.now() + datetime.timedelta(days = 1)
        else:
            return None
        return next_time.strftime(timer)
