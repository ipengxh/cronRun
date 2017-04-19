<?php

return [
    'reactor_num' => 1,
    'worker_num' => 1,
    #'dispatch_mode' => 2,
    'daemonize' => false,
    'log_file' => '/tmp/cronRun/cronRun.log',
    'log_level' => 0,
    'open_tcp_keepalive' => 1,
    'heartbeat_check_interval' => 60,
    'user' => 'www-data',
    'group' => 'www-data',
];
