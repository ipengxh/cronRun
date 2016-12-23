#!/bin/bash
DD=`ps aux|grep run.py|grep -v grep|wc -l`
if [ $DD -ge 1 ];
then
    echo "$DD Running"
else
    nohup /usr/bin/python ./cronrun.py >> /tmp/cronRun.log&
fi
