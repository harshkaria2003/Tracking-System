@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-4">
            <h1 class="display-6">
                <i class="fas fa-tachometer-alt me-2"></i> Super Admin Dashboard
            </h1>
            <p class="lead text-muted mb-0">Manage your administrative users and permissions here.</p>
        </div>
        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


        <!-- Main Content -->
        <div class="row justify-content-center">
            <!-- Admins Card -->
            <div class="col-md-6 col-xl-4">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Admins</h5>
                            <p class="card-text mb-0">Manage Admin Users</p>
                        </div>
                        <div class="display-4">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                            <i class="fas fa-plus-circle me-1"></i> Add Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('models.add_admin_modal')
@endsection
