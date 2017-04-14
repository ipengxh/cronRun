<?php
namespace CronRun;

use swoole_server;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Server
{
    protected $server;

    protected $access = [
        'register', // register
        'test', // connection test
        'add', // client create a new task
        'update', // client update an exists task
        'delete', // client delete a task
        'beat', // heart beat
        'log', // send task log to server
        'bye',
    ];

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
            $key = "6b6e7abf3a2b0b260a5afea196503b72";
            $token = substr($data, 0, 32);
            $data = substr($data, 32);
            $message = json_decode(trim($this->decode($data, $key)));
            if (in_array($message->action, $this->access)) {
                $this->{$message->action}($server, $fd, $message);
            }
            echo "Client({$fd}) is doing {$message->action} with a message: {$message->message}, from id: {$from_id}\n";
        });
        $this->server->on('close', function ($server, $fd) {
            echo "Client {$fd}: Close.\n";
        });
    }

    private function register($server, $fd, $data)
    {
        $key = "6b6e7abf3a2b0b260a5afea196503b72";
        $content = $this->encode(file_get_contents('/mnt/workspace/cronRun/server/docs/API.md'), $key);
        // max message length is 4GBytes, should be enough...
        $message = '0x' . str_pad(dechex(strlen($content)), 8, '0', STR_PAD_LEFT) . $content;
        $server->send($fd, $message);
    }

    private function beat($server, $fd, $data)
    {
        return;
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

    private function encode($message, $password)
    {
        $iv = substr($password, 0, 16);
        return openssl_encrypt($message, 'aes-256-cbc', $password, true, $iv);
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
