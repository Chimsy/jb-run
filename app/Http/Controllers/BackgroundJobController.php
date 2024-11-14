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

    public function cancel($id)
    {
        $job = BackgroundJob::find($id);
        if ($job) {

            $job->status = 'CANCELLED';
            $job->save();

            return redirect()->route('background-jobs.index')->with('SUCCESS', 'Job Cancelled Successfully.');
        }

        return redirect()->route('background-jobs.index')->with('ERROR', 'Job Not Found.');
    }
}

