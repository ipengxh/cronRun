<?php

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Server
{
    protected $server;

    public function __construct($config, $swoole)
    {
        $this->config = $config;
        $this->swoole = $swoole;
        $this->touchLog();
    }

    private function config()
    {
        $this->server->set($this->swoole);
        $this->server->on('connect', function ($server, $fd) {
            echo "Client {$fd} Connect.\n";
            $server->send($fd, 'You\'ve connected');
        });
        $this->server->on('receive', function ($server, $fd, $from_id, $data) {
            echo "Client {$fd} send a message, from id: {$from_id}\n";
            print_r(json_decode($data));
            $server->send($fd, 'Swoole has received your message: ' . $data);
        });
        $this->server->on('close', function ($server, $fd) {
            echo "Client {$fd}: Close.\n";
        });
    }

    private function init()
    {
        $this->server = new swoole_server($this->config['listen'], $this->config['listen_port']);
    }

    public function run()
    {
        $this->init();
        $this->config();
        $this->server->start();
    }

    private function touchLog()
    {
        if (!file_exists(dirname($this->swoole['log_file']))) {
            mkdir(dirname($this->swoole['log_file']));
        }
        if (!file_exists($this->swoole['log_file'])) {
            touch($this->swoole['log_file']);
        }
    }
}
