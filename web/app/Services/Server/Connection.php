<?php
namespace App\Services\Server;

use Exception;
use stdClass;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Connection
{
    protected $connections = [];

    protected $action = [
        'client' => [
            'register', // client register
            'test', // client connection test
            'keepalive', // client heart beat
            'output', // client send task output to server
            'start', // client send a task starting message
            'finish', // client send a task finish message
        ],
        'server' => [
            'add', // server create a new task
            'update', // server update an exists task
            'delete', // server delete a task
            'info', // server get client information
            'command', // run a custom command
        ],
    ];

    const ERROR_ILLEGAL_DATA = 1000;
    const ERROR_ILLEGAL_ACTION = 1001;

    public function handle($server, $fd, $from_id, $data)
    {
        $data = json_decode($data);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception("Illegal data, data should be json", static::ERROR_ILLEGAL_DATA);
        }
        if (in_array($data->action, $this->action['client'])) {
            $connection = $this->get($server, $fd, $from_id, $data);
            return $this->handleClientConnection($connection, $data);
        } elseif (in_array($data->action, $this->action['server'])) {
            return $this->handleServerConnection($data);
        } else {
            throw new Exception("Illegal action, action should be register/test/beat/bye/log", static::ERROR_ILLEGAL_ACTION);
        }
    }

    public function close($fd)
    {
        unset($this->connections[$fd]);
    }

    protected function get($server, $fd, $from_id, $data)
    {
        if (!isset($this->connections[$fd])) {
            $this->connections[$fd] = new ClientConnection($server, $fd, $from_id);
        }
        return $this->connections[$fd];
    }

    protected function handleClientConnection(ClientConnection $connection, stdClass $data)
    {
        return $connection->{$data->action}($data);
    }

    protected function handleServerConnection($data)
    {
        return $connection->{$data->action}($data);
    }
}
