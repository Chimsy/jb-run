<!-- resources/views/background_jobs/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Background Jobs</h1>
        @if(session('success'))
            <div class="alert alert-success"> {{ session('success') }} </div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Timestamp</th>
                <th>Class</th>
                <th>Method</th>
                <th>Parameters</th>
                <th>Status</th>
                <th>Retry Count</th>
                <th>Priority</th>
                <th>Error Message</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->created_at }}</td>
                    <td>{{ $job->class_name }}</td>
                    <td>{{ $job->method_name }}</td>
                    <td>{{ $job->params }}</td>
                    <td>{{ $job->status }}</td>
                    <td>{{ $job->retry_count }}</td>
                    <td>{{ $job->priority }}</td>
                    <td>{{ $job->error_message }}</td>
                    <td>
                        @if ($job->status == 'RUNNING')
                            <form action="{{ route('background-jobs.cancel', $job->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Cancel</button>
                            </form>
                        @elseif ($job->status == 'COMPLETED')
                            <span class="badge badge-success">Completed</span>
                        @elseif ($job->status == 'FAILED')
                            <span class="badge badge-danger">Failed</span>
                        @elseif ($job->status == 'CANCELLED')
                            <span class="badge badge-warning">Cancelled</span>
                        @else
                            <span class="badge badge-secondary">Unknown</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
