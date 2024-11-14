<?php

namespace App\Http\Controllers;

use App\Models\BackgroundJob;

class BackgroundJobController extends Controller
{
    public function index()
    {
        $jobs = BackgroundJob::all();
        return view('admin.background.index', compact('jobs'));
    }
}
