<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //\Mail::send('');
        $server = app(\App\Services\Server::class);
        $task = \App\Models\Task::with('project', 'project.node')->first();
        $server->addTask($task);
        while (1) {
            $message = $this->ask('message:');
            $server->send('message', $message);
        }
    }
}
