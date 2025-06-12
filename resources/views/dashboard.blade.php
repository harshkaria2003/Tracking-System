@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard</h1>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Total Duration: {{ gmdate('H:i:s', $timeLog->total_duration ?? 0) }}</h5>

            @if($runningSession)
                <p>Timer is running since: {{ $runningSession->start_time->format('Y-m-d H:i:s') }}</p>
            @else
                <p>No active timer session.</p>
            @endif
        </div>
    </div>

    <div>
        <button id="startBtn" class="btn btn-success">Start Timer</button>
        <button id="pauseBtn" class="btn btn-warning">Pause Timer</button>
        <button id="stopBtn" class="btn btn-danger">Stop Timer</button>
    </div>
</div>

<script>
document.getElementById('startBtn').addEventListener('click', () => {
    fetch('{{ route("timelog.start") }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
        .then(res => res.json()).then(data => alert(data.message));
});

document.getElementById('pauseBtn').addEventListener('click', () => {
    fetch('{{ route("timelog.pause") }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
        .then(res => res.json()).then(data => alert(data.message));
});

document.getElementById('stopBtn').addEventListener('click', () => {
    fetch('{{ route("timelog.stop") }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
        .then(res => res.json()).then(data => alert(data.message));
});
</script>
@endsection
{{-- --}}