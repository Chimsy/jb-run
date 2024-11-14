<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\BackgroundJobRunner;

class RunBackgroundJob extends Command
{
    protected $signature = 'background-job:run {className} {method} {params}';
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

        $jobRunner = new BackgroundJobRunner();
        $jobRunner->run($className, $method, $params);
    }
}
