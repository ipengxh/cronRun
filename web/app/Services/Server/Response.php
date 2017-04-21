<?php
namespace App\Services\Server;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Response
{
    private $swoole;
    private $fd;

    public function __construct($swoole, $fd)
    {
        $this->swoole = $swoole;
        $this->fd = $fd;
    }

    public function send($message)
    {
        $message = '0x' . str_pad(dechex(strlen($message)), 8, '0', STR_PAD_LEFT) . $message;
        return $this->swoole->send($message);
    }

    public function sendJson($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        } elseif (is_object($message)) {
            $message = json_encode((array) $message);
        } elseif (!is_string($message)) {
            $message = json_encode((string) $message);
        } else {
            $message = json_encode($message);
        }
        return $this->swoole->send($message);
    }

    public function success($message)
    {
        return $this->sendJson(['status' => 'success', 'data' => $message]);
    }

    public function error($message)
    {
        return $this->sendJson(['status' => 'error', 'data' => $message]);
    }
}
