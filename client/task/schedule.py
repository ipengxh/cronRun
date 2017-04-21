from config import *
from os import listdir, walk
from os.path import isfile
import time, threading, sys, Queue, subprocess
from ConfigParser import ConfigParser
import setproctitle

class ScheduleConfig():
    """docstring for scan"""
    def __init__(self, config_path):
        self.config_path = config_path

    def all(self):
        config_files = []
        for (dir_path, dir_names, file_names) in walk(self.config_path):
            #print dir_path
            for file_name in file_names:
                config_files.extend([dir_path+'/'+file_name])
        return config_files

    def new_thread(self):
        for task in self.all():
            print task

class schedule(threading.Thread):
    """docstring for boot"""
    def __init__(self, configFile):
        threading.Thread.__init__(self)
        self.configFile = configFile
        self.config = ConfigParser()
        self.config.read(configFile)
        self.schedule = {'status': True}
        print configFile, 'has been loaded'
        self.thread_stop = False

    '''watch config file'''
    def watch(self):
        setproctitle.setproctitle("cronrun: config monitor " + self.configFile)
        conf = config.schedule(self.configFile)
        conf.watch()

    '''schedule manager'''
    def run(self):
        self.boot_time = datetime.datetime.now()
        self.run_times = 0
        try:
            interval = self.config.getfloat('schedule', 'interval')
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
        thread.join(self.config.getfloat('error', 'timeout'))
        self.run_times += 1
        # can not sleep exactly "interval" seconds, a few time passed while
        # these codes running, it could make the schedule run a little later
        # than expect
        next_run_time = self.boot_time + datetime.timedelta(seconds = interval * self.run_times)
        needs_sleep = (next_run_time - datetime.datetime.now()).total_seconds()
        time.sleep(needs_sleep)

    def run_timer_task(self):
        timers = self.config.get('schedule', 'timer').split(',')
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
            thread.join(self.config.getfloat('error', 'timeout'))

    # modify the stop flag
    def stop(self):
        self.thread_stop = True

    # execute the task
    def execute(self):
        command = self.config.get('app', 'command') + " " + self.config.get('app', 'param')
        subProgress = subprocess.Popen(command, shell = True, stdout = subprocess.PIPE, stderr = subprocess.STDOUT)

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
