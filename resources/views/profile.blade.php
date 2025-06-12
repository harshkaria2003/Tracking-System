@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <i class="fas fa-user-circle fa-2x me-3"></i>
            <h4 class="mb-0">Profile Information</h4>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-3 text-muted text-end fw-semibold">First Name:</div>
                <div class="col-md-9 fs-5">{{ Auth::user()->first_name }}</div>
            </div>
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-3 text-muted text-end fw-semibold">Last Name:</div>
                <div class="col-md-9 fs-5">{{ Auth::user()->last_name }}</div>
            </div>
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-3 text-muted text-end fw-semibold">Email:</div>
                <div class="col-md-9 fs-5">{{ Auth::user()->email }}</div>
            </div>
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-3 text-muted text-end fw-semibold">Role:</div>
                <div class="col-md-9 fs-5">
                    @switch(Auth::user()->role_id)
                        @case(1)
                            Super Admin
                            @break
                        @case(2)
                            Admin
                            @break
                        @default
                            User
                    @endswitch
                </div>
            </div>

            @if(Auth::user()->profile_picture)
            <div class="row mb-4">
                <div class="col-md-3 text-muted text-end fw-semibold">Profile Picture:</div>
                <div class="col-md-9">
                    <img src="{{ asset('upload/' . Auth::user()->profile_picture) }}" alt="Profile Picture" 
                         class="img-thumbnail rounded-circle shadow" style="max-width: 150px;">
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-end">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

