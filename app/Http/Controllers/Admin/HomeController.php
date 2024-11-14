<?php

namespace App\Http\Controllers\Admin;

use App\Console\BackgroundJobRunner;
use App\Helpers\BackgroundJobHelper;


class HomeController
{
    public function index()
    {

        $jobRunner = new BackgroundJobRunner();
        $jobRunner->run(
            'App\Services\SomeClass',
            'someMethod', ['paramA', 'paramB']
        );

        // Run the background job
        BackgroundJobHelper::runBackgroundJob(
            'App\Services\SomeClass',
            'someMethod', ['paramX', 'paramY']
        );

        return view('home');
    }
}
