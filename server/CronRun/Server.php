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
        'bye', // client down
        'info', // get client information
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
            $key = "a8cbb1c06ec1f8813eff915ca4a3c91d";
            $token = substr($data, 0, 32);
            $client = $this->getClient($token);
            $data = substr($data, 32);
            $message = json_decode(trim($data, $key));
            if (in_array($message->action, $this->access)) {
                $this->{$message->action}($server, $fd, $message);
            }
            echo "Client({$fd}) is doing {$message->action} with a message: {$message->message}, from id: {$from_id}\n";
        });
        $this->server->on('close', function ($server, $fd) {
            echo "Client {$fd}: Close.\n";
        });
    }

    private function getClient($token)
    {

    }

    private function register($server, $fd, $data)
    {
        $key = "a8cbb1c06ec1f8813eff915ca4a3c91d";
        $content = file_get_contents('/mnt/workspace/cronRun/server/docs/API.md');
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
