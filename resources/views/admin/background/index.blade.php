@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Background Jobs</h1>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
