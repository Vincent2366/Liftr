@extends('tenant.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Welcome</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Hello, {{ Auth::user()->name }}!</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">My Activity</h6>
                </div>
                <div class="card-body">
                    <p>Welcome to your personal dashboard. Here you can track your sessions and manage your account.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection