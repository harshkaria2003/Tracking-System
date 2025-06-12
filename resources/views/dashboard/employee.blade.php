@extends('layouts.app')

@section('title', 'Employee Timer')

@section('content')
<div class="container">
    <h2>Start Your Task Timer</h2>

    <div class="mb-3">
        <label for="task_description" class="form-label">Task Description:</label>
        <input type="text" id="task_description" name="task_description" class="form-control" autocomplete="off" />
    </div>

    <div class="mb-3">
        <label for="project_id" class="form-label">Select Project:</label>
        <select id="project_id" name="project_id" class="form-select">
            <option value="">-- Select Project --</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

   

    <div id="timerStatus" class="alert alert-info mt-3 d-none"></div>
</div>

<script>
    let currentLogId = null;
    const statusDiv = document.getElementById('timerStatus');
    const toggleBtn = document.getElementById('timerToggleBtn');

    function showStatus(message) {
        statusDiv.innerText = message;
        statusDiv.classList.remove('d-none');
    }

    function resetForm() {
        document.getElementById('task_description').value = '';
        document.getElementById('project_id').value = '';
        document.getElementById('task_description').disabled = false;
        document.getElementById('project_id').disabled = false;
        currentLogId = null;
        toggleBtn.innerText = 'Start Timer';
        toggleBtn.classList.remove('btn-danger');
        toggleBtn.classList.add('btn-primary');
    }

    function startTaskTimer() {
        const taskDesc = document.getElementById('task_description').value.trim();
        const projectId = document.getElementById('project_id').value;

        if (!taskDesc || !projectId) {
            alert('Please enter task description and select a project.');
            return;
        }

        fetch("{{ route('employee.timer.start') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task_description: taskDesc, project_id: projectId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'started') {
                currentLogId = data.log_id;
                showStatus(`Task started at: ${data.start_time}`);
                document.getElementById('task_description').disabled = true;
                document.getElementById('project_id').disabled = true;
                toggleBtn.innerText = 'Stop Timer';
                toggleBtn.classList.remove('btn-primary');
                toggleBtn.classList.add('btn-danger');
            } else {
                alert(data.message || 'Failed to start task.');
            }
        })
        .catch(() => alert('Error starting task.'));
    }

    function stopTaskTimer() {
        if (!currentLogId) {
            showStatus('No running task to stop.');
            return;
        }

        fetch("{{ route('employee.timer.stop') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ log_id: currentLogId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'stopped') {
                showStatus(`Task stopped at: ${data.end_time}. Total time: ${data.formatted_time}`);
                resetForm();
            } else {
                alert(data.message || 'Failed to stop task.');
            }
        })
        .catch(() => alert('Error stopping task.'));
    }

    toggleBtn.addEventListener('click', () => {
        const isRunning = toggleBtn.classList.contains('btn-danger');
        if (!isRunning) {
            startTaskTimer();
        } else {
            stopTaskTimer();
        }
    });
</script>

@endsection