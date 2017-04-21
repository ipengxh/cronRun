<?php
namespace App\Services\Server;

use Illuminate\Support\Str;

/**
 * @author Bruce Peng <ipengxh@ipengxh.com>
 * swoole config getter/setter
 */
class SwooleConfigrator
{
    protected $configuration;

    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        return $this->configuration = config('swoole');
    }

    public function __get($name)
    {
        return env('SWOOLE_' . strtoupper(Str::snake($name)));
    }

    public function toArray()
    {
        return $this->configuration;
    }
}
