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
            $server->send($fd, 'connected');
        });
        $this->server->on('receive', function ($server, $fd, $from_id, $data) {
            $key = "7941ed0ba1e74b920beaee3d40de909a";
            $message = json_decode(trim($this->decode($data, $key)));
            if (in_array($message->action, ['register', 'test', 'projectAdd', 'projectUpdate', 'projectDelete', 'taskAdd', 'taskUpdate', 'taskDelete'])) {
                $this->{$message->action}($server, $fd, $message);
            }
            echo "Client({$fd}) is doing {$message->action} with a message: {$message->message}, from id: {$from_id}\n";
            $server->send($fd, 'Swoole has received your message: ' . $message->message);
        });
        $this->server->on('close', function ($server, $fd) {
            echo "Client {$fd}: Close.\n";
        });
    }

    private function register($server, $fd, $data)
    {
        $server->send($fd, file_get_contents('docs/API.md'));
    }

    private function init()
    {
        $this->server = new swoole_server($this->config['listen'], $this->config['listen_port']);
    }

    private function decode($message, $password)
    {
        $iv = substr($password, 0, 16);
        return openssl_decrypt($message, 'aes-256-cbc', $password, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
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
