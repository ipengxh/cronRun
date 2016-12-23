<?php
namespace App\Services;

use App\Models\Task as TaskModel;
use swoole_client;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Server
{
    private $connection;

    private function connect()
    {
        if ($this->connection and false !== $this->connection->isConnected()) {
            return $this->connection;
        }
        $this->connection = new swoole_client(SWOOLE_SOCK_TCP);
        if (!$this->connection->connect(env('SERVER_IP'), env('SERVER_PORT'))) {
            throw new \Exception("Could not connect to server");
        } else {
            return $this->connection;
        }
    }

    public function addTask(TaskModel $task)
    {
        $this->send('server:task:add', $task->toArray());
    }

    public function delTask(TaskModel $task)
    {
        $this->send('server:task:del', $task->toArray());
    }

    public function updateTask(TaskModel $task)
    {
        $this->send('server:task:update', $task->toArray());
    }

    public function action()
    {

    }

    public function send($action, $message)
    {
        if (is_object($message)) {
            $message = (array) $message;
        }
        $data = [
            'action' => $action,
            'message' => $message,
        ];
        $this->connect()->send(json_encode($data));
        $this->connect()->close();
    }
}
