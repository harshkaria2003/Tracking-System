@extends('layouts.app')

@section('title', 'Employee Work Hours Report')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">📊 Employee Work Hours Report</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.admin.employee.report') }}" class="mb-4">
        <div class="row g-3 align-items-end justify-content-center">
            <div class="col-md-3 text-md-end">
                <label for="date_range" class="form-label">Select Date Range:</label>
            </div>
            <div class="col-md-3">
                <input 
                    type="text" 
                    id="date_range" 
                    name="date_range" 
                    class="form-control border-primary shadow-sm" 
                    placeholder="Choose date range" 
                    value="{{ request('date_range') }}" 
                    required 
                    autocomplete="off"
                >
            </div>
            <div class="col-md-3">
                <label for="project_id" class="form-label">Select Project:</label>
                <select name="project_id" id="project_id" class="form-select shadow-sm">
                    <option value="">All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 text-md-start">
                <button type="submit" class="btn btn-primary shadow">🔍 Filter</button>
            </div>
        </div>
    </form>

    {{-- Selected Filters Summary --}}
    @if(request()->has('date_range'))
        @php
            $rawRange = request('date_range');
            $rawRange = str_replace(' to ', ',', $rawRange);
            $dates = explode(',', $rawRange);
            $start = \Carbon\Carbon::parse(trim($dates[0]));
            $end = isset($dates[1]) ? \Carbon\Carbon::parse(trim($dates[1])) : $start;
        @endphp

        <div class="text-center fw-semibold mb-3">
            Showing records from: {{ $start->format('F j, Y') }} to {{ $end->format('F j, Y') }}
            @if(request('project_id'))
                @php
                    $project = $projects->firstWhere('id', request('project_id'));
                @endphp
                <br>For Project: <span class="text-primary">{{ $project?->name }}</span>
            @endif
        </div>
    @endif

    {{-- Main Report Table --}}
    @if(!empty($employees) && count($employees))
        <div class="table-responsive shadow rounded mt-4">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>👤 Employee Name</th>
                        <th>📧 Email</th>
                        <th>⏱️ Total Time Worked</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        @php
                        
                            $totalSeconds = (int) ($employee->total_time_seconds ?? 0);
                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $seconds = $totalSeconds % 60;

                            $parts = [];
                            if ($hours > 0) $parts[] = $hours . ' hr' . ($hours > 1 ? 's' : '');
                            if ($minutes > 0) $parts[] = $minutes . ' min' . ($minutes > 1 ? 's' : '');
                            if ($seconds > 0 || empty($parts)) $parts[] = $seconds . ' sec' . ($seconds > 1 ? 's' : '');

                            $displayTime = implode(' ', $parts);
                        @endphp

                        <tr>
                            <td class="text-start">
                                <button class="btn btn-sm btn-outline-primary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $employee->id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                                {{ $employee->name }}
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $displayTime }}</td>
                        </tr>

                        {{-- Collapsible Row for Tasks --}}
                        <tr class="collapse bg-light" id="collapse{{ $employee->id }}">
                            <td colspan="3" class="p-0">
                                @if($employee->timeLogs->count())
                                    <div class="p-3">
                                        <h6 class="text-muted mb-3">📝 Detailed Tasks</h6>
                                        <table class="table table-sm table-bordered align-middle mb-0">
                                            <thead class="table-secondary text-center">
                                                <tr>
                                                    <th>📁 Project</th>
                                                    <th>🧾 Task name</th>
                                                    <th>🕒 Start</th>
                                                    <th>🕔 End</th>
                                                    <th>⏱️ Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employee->timeLogs as $log)
                                                    @php
                                                        $start = \Carbon\Carbon::parse($log->start_time);
                                                        $end = $log->end_time ? \Carbon\Carbon::parse($log->end_time) : null;
                                                        $diffSeconds = $end ? $start->diffInSeconds($end) : 0;

                                                      
                                                        $hrs = floor($diffSeconds / 3600);
                                                        $mins = floor(($diffSeconds % 3600) / 60);
                                                        $secs = $diffSeconds % 60;

                                                        $durParts = [];
                                                        if ($hrs > 0) $durParts[] = "$hrs hr" . ($hrs > 1 ? 's' : '');
                                                        if ($mins > 0) $durParts[] = "$mins min" . ($mins > 1 ? 's' : '');
                                                        if ($secs > 0 || empty($durParts)) $durParts[] = "$secs sec" . ($secs > 1 ? 's' : '');
                                                        $dur = implode(' ', $durParts);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $log->project->name ?? 'N/A' }}</td>
                                                        <td>{{ $log->task_description ?? '-' }}</td>
                                                        <td>{{ $start->format('d M Y, h:i A') }}</td>
                                                        <td>
                                                            @if($end)
                                                                {{ $end->format('d M Y, h:i A') }}
                                                            @else
                                                                <span class="badge bg-warning text-dark">Ongoing</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $dur }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-3 text-center text-muted">No task data available for this employee.</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(request()->has('date_range'))
        <div class="alert alert-info text-center mt-5">
            No records found for the selected filters. 📭
        </div>
    @endif
</div>

{{-- Required Libraries --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        maxDate: "today",
        allowInput: true,
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                instance.input.value = selectedDates.map(d =>
                    instance.formatDate(d, "Y-m-d")
                ).join(',');
            }
        }
    });
</script>
@endsection
