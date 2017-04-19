<?php

namespace App\Console\Commands;

use App\Services\Server\SwooleConfigrator;
use App\Services\Server\SwooleServer;
use Illuminate\Console\Command;

class ServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server
    {option : start|stop}
    {--daemon : running in daemon mode}
    {--debug : enable debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'server management';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SwooleConfigrator $swooleConfig, SwooleServer $swooleServer)
    {
        parent::__construct();
        $this->swooleConfig = $swooleConfig;
        $this->swooleServer = $swooleServer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->swooleServer->fire();
    }
}
