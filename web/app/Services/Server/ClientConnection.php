<?php
namespace App\Services\Server;

use App\Models\Node;
use App\Models\Project;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class ClientConnection
{
    protected $swoole;
    protected $fd;
    protected $from_id;
    protected $node;

    const ERROR_ILLEGAL_TOKEN = 2000;

    public function __construct($swoole, $fd, $from_id)
    {
        $this->swoole = $swoole;
        $this->fd = $fd;
        $this->from_id = $from_id;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function getFromId()
    {
        return $this->from_id;
    }

    public function register($data)
    {
        $node = Node::where('token', $data->token)->first();
        if (!$node) {
            throw new Exception("Could not find this server, please make sure your token is correct", static::ERROR_ILLEGAL_TOKEN);
        }
        $projects = Project::with('task')
            ->where('node_id', $node->id)
            ->get();
        $response = [];
        foreach ($projects as $project) {
            foreach ($project->task as $task) {
                $response[] = $task->toIni();
            }
        }
        return $this->success($response);
    }

    public function test()
    {
        $this->success("Congratulations, You'are able to connect to server");
    }

    public function keepalive()
    {
        $this->success("got");
    }

    public function bye()
    {

    }

    public function log()
    {

    }

    public function send($message)
    {
        echo self::buildMessage($message) . PHP_EOL;
        $this->swoole->send($this->fd, self::buildMessage($message));
    }

    public function success($data, $message = null)
    {
        $response = [
            'status' => 'success',
            'data' => $data,
        ];
        if ($message) {
            $response['message'] = $message;
        }
        return $this->send($response);
    }

    public static function buildMessage($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        } elseif (is_object($message)) {
            $message = json_encode((array) $message);
        } else {
            $message = json_encode(['status' => $status, 'data' => $message]);
        }
        // max message length is 4GBytes, should be enough...
        return '0x' . str_pad(dechex(strlen($message)), 8, '0', STR_PAD_LEFT) . $message;
    }
}
