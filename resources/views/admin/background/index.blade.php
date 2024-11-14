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
                <th>ID</th>
                <th>Class</th>
                <th>Method</th>
                <th>Parameters</th>
                <th>Status</th>
                <th>Error Message</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->class_name }}</td>
                    <td>{{ $job->method_name }}</td>
                    <td>{{ $job->params }}</td>
                    <td>{{ $job->status }}</td>
                    <td>{{ $job->error_message }}</td>
                    <td>{{ $job->created_at }}</td>
                    <td>{{ $job->updated_at }}</td>
                    <td>
                        @if ($job->status == 'RUNNING')
                            <form action="{{ route('background-jobs.cancel', $job->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Cancel</button>
                            </form>
                        @elseif ($job->status == 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif ($job->status == 'failed')
                            <span class="badge badge-danger">Failed</span>
                        @elseif ($job->status == 'cancelled')
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
