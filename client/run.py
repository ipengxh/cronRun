#!/usr/bin/python
# -*-coding:utf-8 -*-

import socket, threading
import time


def heartbeat(sock):
    while True:
        sock.send('heartbeat')
        time.sleep(1)

def sockmessage(sock):
    while True:
        data =sock.recv(1024)
        print data

HOST = '192.168.40.229'
PORT = 4096

s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)

s.connect((HOST,PORT))
# new thread for send heartbeat
threading.Thread(target = heartbeat, args = (s,)).start()
threading.Thread(target = sockmessage, args = (s,)).start()

while True:
    cmd = raw_input("your message: ")
    s.send(cmd)
    print "your message \"%s\" has been sent" %(cmd)
    data = s.recv(1024)
    print data
s.close()
