<?php
namespace CronRun;

use swoole_http_server;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 */
class Api
{
    protected $api;
    protected $swoole;

    public function __construct($api, $swoole)
    {
        $this->api = $api;
        $this->swoole = $swoole;
    }

    public function run()
    {
        $this->config();
    }

    private function config()
    {
        $http = new swoole_http_server($this->api['bind'], $this->api['port']);
        $http->set($this->swoole);
        $http->on('request', function ($request, $response) {
            $response->end(json_encode(['status' => 'success']));
        });
        $http->start();
    }
}
