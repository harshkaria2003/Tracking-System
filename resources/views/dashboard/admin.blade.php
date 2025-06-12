@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Admin Dashboard</h1>
        <p class="lead text-muted">Welcome to the admin panel. Manage your records below.</p>
    </div>

    @if (session('success'))
        <div 
            id="success-alert" 
            class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow-sm" 
            role="alert" 
            style="min-width: 300px; max-width: 500px; z-index: 1050; border-radius: 8px;"
        >
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 3000);
        </script>
    @endif

    <!-- Admin Card -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="card-title">Admins</h3>
                        <p class="card-text">Manage Admin Records</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <i class="fas fa-user-shield fa-3x opacity-75"></i>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            Add Employee <i class="fas fa-arrow-circle-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View All Records with Dropdown -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">View All Employee Records</h5>
                   <form method="GET" action="{{ route('admin.timelog.report') }}">

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Select Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }} ({{ $employee->email }})
                                    </option>   
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">View reocrds </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add Employee -->
@include('models.add_employee_modal', ['projects' => $projects, 'countries' => $countries])
@endsection
