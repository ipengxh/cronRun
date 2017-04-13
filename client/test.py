#!/usr/bin/python
import subprocess, os, time, sys, setproctitle

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

setproctitle.setproctitle('cronRun master')

tasks = ['hello', 'world']

for task in tasks:
    pid = os.fork()
    if pid <= 0:
        setproctitle.setproctitle('cronRun worker: ' + task)
        time.sleep(10)

time.sleep(10)
