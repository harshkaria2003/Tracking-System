@extends('layouts.app')

@section('title', 'View All Employee Records')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">View All Employee Time records </h2>

    <form method="GET" action="{{ route('admin.timelog.report') }}" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <label for="employee_id" class="form-label">Select Employee</label>
                <select name="employee_id" id="employee_id" class="form-select" required>
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">View records</button>
            </div>
        </div>
    </form>

    @if ($selectedEmployee)
        <h4 class="mb-3">Time Logs for: {{ $selectedEmployee->name }}</h4>

        @if ($timeLogs->isEmpty())
            <div class="alert alert-info">No time record found for this employee.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Task Description</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Total Time (MM:SS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeLogs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $log->project->name ?? 'N/A' }}</td>
                                <td>{{ $log->task_description ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->start_time)->format('d M Y, h:i A') }}</td>
                                <td>
                                    @if ($log->end_time)
                                        {{ \Carbon\Carbon::parse($log->end_time)->format('d M Y, h:i A') }}
                                    @else
                                        <span class="badge bg-warning text-dark">Ongoing</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->end_time)
                                        @php
                                            $totalSeconds = \Carbon\Carbon::parse($log->start_time)->diffInSeconds(\Carbon\Carbon::parse($log->end_time));
                                            $minutes = floor($totalSeconds / 60);
                                            $seconds = $totalSeconds % 60;
                                        @endphp
                                        {{ $minutes }}m {{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}s
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $timeLogs->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @endif
</div>
@endsection
