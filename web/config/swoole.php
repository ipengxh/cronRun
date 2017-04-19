<?php
return [
    'server_ip' => env('SWOOLE_SERVER_IP', '127.0.0.1'),
    'server_port' => env('SWOOLE_SERVER_PORT', 4096),
    'reactor_num' => env('SWOOLE_REACTOR_NUM', 1),
    'worker_num' => env('SWOOLE_WORKER_NUM', 1),
    'daemonize' => env('SWOOLE_DAEMONIZE', true),
    'log_file' => env('SWOOLE_LOG_FILE', storage_path('logs/server.log')),
    'log_level' => env('SWOOLE_LOG_LEVEL', '0'),
    'open_tcp_keepalive' => env('SWOOLE_OPEN_TCP_KEEPALIVE', 1),
    'heartbeat_check_interval' => env('SWOOLE_HEARTBEAT_CHECK_INTERVAL', 60),
    'user' => env('SWOOLE_USER', 'www-data'),
    'group' => env('SWOOLE_GROUP', 'www-data'),
];
