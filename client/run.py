#!/usr/bin/python
# -*-coding:utf-8 -*-

import socket, threading, time, sys, os, commands

from TaskManager import *

ConfigFile = 'config.ini'

cron = CronRun.CronRun(ConfigFile)
cron.start()
