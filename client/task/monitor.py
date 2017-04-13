from config import *
from os import listdir, walk
from os.path import isfile
import time, threading, sys, Queue, subprocess
from ConfigParser import ConfigParser
import setproctitle

class Monitor():
    """docstring for Monitor"""
    def __init__(self, task_config_file):
        self.task_config_file = task_config_file
        self.set_title()

    def set_title(self):
        setproctitle.setproctitle("cronRun monitor: " + self.task_config_file)

if __name__ == "__main__":
    monitor = Monitor(sys.argv[1])
    while True:
        time.sleep(1)
