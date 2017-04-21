<?php
namespace App\Services\Server;

use Exception;
use swoole_server;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class SwooleServer
{
    protected $config;

    protected $nodes = [];

    public function __construct(SwooleConfigrator $config, Connection $connection)
    {
        $this->config = $config;
        $this->connection = $connection;
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
        $this->server->on('connect', function ($server, $fd, $from_id) {
            echo "Client {$fd} Connect.\n";
        });
        $this->server->on('receive', function ($server, $fd, $from_id, $data) {
            try {
                $this->connection->handle($server, $fd, $from_id, $data);
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage() . " @line" . $e->getLine(),
                    'code' => $e->getCode(),
                ];
                $server->send($fd, ClientConnection::buildMessage($response));
            }
        });
        $this->server->on('close', function ($server, $fd, $from_id) {
            $this->connection->close($fd);
            echo "Client {$fd}: Close.\n";
        });
        $this->server->start();
    }
}
