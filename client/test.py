#!/usr/bin/python
import time, threading, sys

a = 1

def modify():
    global a
    while True:
        a = a + 100
        print a
        time.sleep(2)
        if a > 500:
            sys.exit()


def main():
    threading.Thread(target = modify).start()
    global a
    while True:
        print a
        time.sleep(1)
        a = a + 1

if __name__ == '__main__':
    main()
