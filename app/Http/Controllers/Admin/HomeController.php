<?php

namespace App\Http\Controllers\Admin;

use App\Console\BackgroundJobRunner;

class HomeController
{
    public function index()
    {

        $jobRunner = new BackgroundJobRunner();
        $jobRunner->run('SomeClass', 'someMethod', ['param1', 'param2']);


        return view('home');
    }
}
