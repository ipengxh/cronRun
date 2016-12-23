# Task configure

Example of task

```ini
; brief information of task
[app]
; the project name of this task
project = project A
; the name of this task
name = schedule A

; the unique id of this task
id = 97e284f4d5ca17f219d0a2f3edd6a012

; command to run
command = date
; param of this command, cronRun will append it to command for open a new process
; to run command
; some place holder will be useful
; {today} the date string of today, example: 2016-12-05
; {yesterday} the date string of yesterday, example: 2016-12-04
; {tomorrow} the date string of tomorrow, example: 2016-12-06
; {now} the timestamp of now, example: 1482397122
; {month} the month string of this month, example: 2016-12
; {last_month} the month string of last month, example: 2016-11
; {next_month} the month string of next month, example: 2017-01
param = >> /tmp/project_a_schedule_a.log

; schedule configure
[schedule]
; for interval tasks, the interval time for running task, seconds
interval =
; for timer task
timer = %Y-%m-%d 05:10:00, %Y-%m-%d %H:%M:00, %Y-%m-%d %H:%M:40, %Y-%m-%d %H:%M:20, %Y-%m-%d %H:57:15

[error]
; it's will be an error if time of command running less than this value(seconds)
min_time = 1

; it's will be an error if time of command running more than this value(seconds)
max_time = 2

timeout = 5

retry = 3
retry_interval = 2

error_code = 2, 3, 4

[notify]
enabled = true
email = cron.run@ipengxh.com
success = false
error = true

```
