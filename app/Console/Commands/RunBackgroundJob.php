<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\BackgroundJobRunner;

class RunBackgroundJob extends Command
{
    protected $signature = 'background-job:run {className} {method} {params} {--retries=3} {--delay=0}';
    protected $description = 'Run a background job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $className = $this->argument('className');
        $method = $this->argument('method');
        $params = json_decode($this->argument('params'), true);
        $retries = (int) $this->option('retries');
        $delay = (int) $this->option('delay');

        $jobRunner = new BackgroundJobRunner();
        $jobRunner->run($className, $method, $params, $delay);
    }
}
