#!/usr/bin/python
# -*-coding:utf-8 -*-

import time

def beat(self, sock):
    while True:
        sock.send('heartbeat')
        time.sleep(1)
