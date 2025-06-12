@extends('layouts.app')

@section('title', 'Time Log Report')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Time Log Report</h2>

    @if ($logs->count())
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Task Description</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Total Time (MM:SS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->task_description }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->start_time)->format('d M Y, h:i A') }}</td>
                            <td>
                                @if ($log->end_time)
                                    {{ \Carbon\Carbon::parse($log->end_time)->format('d M Y, h:i A') }}
                                @else
                                    <span class="badge bg-warning text-dark">Ongoing</span>
                                @endif
                            </td>
                            <td>
                                @if (is_numeric($log->total_time_seconds))
                                    @php
                                        $minutes = floor($log->total_time_seconds / 60);
                                        $seconds = $log->total_time_seconds % 60;
                                    @endphp
                                    {{ sprintf('%02d:%02d', $minutes, $seconds) }}
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
            {{ $logs->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>

    @else
        <div class="alert alert-info text-center" role="alert">
            No time logs found.
        </div>
    @endif
</div>
@endsection
