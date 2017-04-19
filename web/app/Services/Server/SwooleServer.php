<?php
namespace App\Services\Server;

use App\Models\Node;
use swoole_server;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class SwooleServer
{
    protected $config;

    protected $nodes = [];

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

    public function __construct(SwooleConfigrator $config)
    {
        $this->config = $config;
    }

    public function fire()
    {
        $this->setUp();
        $this->boot();
    }

    protected function setUp()
    {
        cli_set_process_title('CronRun server');
        $this->server = new swoole_server($this->config->server_ip, $this->config->server_port);
        $this->server->set($this->config->toArray());
    }

    protected function boot()
    {
        $this->server->on('connect', function ($server, $fd) {
            echo "Client {$fd} Connect.\n";
            $server->send($fd, 'connected');
        });
        $this->server->on('receive', function ($server, $fd, $from_id, $data) {
            $node = $this->getNode($data);
            $message = $this->getMessage($data);
            if (in_array($message->action, $this->access)) {
                $this->{$message->action}($server, $fd, $node, $message);
            }
            echo "Client({$fd}) is doing {$message->action} with a message: {$message->message}, from id: {$from_id}\n";
        });
        $this->server->on('close', function ($server, $fd) {
            echo "Client {$fd}: Close.\n";
        });
        $this->server->start();
    }

    private function getMessage($data)
    {
        $trimedData = substr($data, 32);
        return json_decode(trim($trimedData, $this->getNode($data)->key));
    }

    private function getNode($data)
    {
        $token = $this->getToken($data);
        return $this->nodes[$token] ?? $this->nodes[$token] = Node::where('token', $token)->first();
    }

    private function getToken($data)
    {
        return substr($data, 0, 32);
    }

    private function register($server, $fd, $node)
    {
        // max message length is 4GBytes, should be enough...
        $message = '0x' . str_pad(dechex(strlen($node->toJson())), 8, '0', STR_PAD_LEFT) . $node->toJson();
        $server->send($fd, $message);
    }

    private function beat($server, $fd, $data)
    {
        return;
    }
}
