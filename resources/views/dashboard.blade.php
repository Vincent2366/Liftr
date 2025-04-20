<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .border-left-primary { border-left: 4px solid #4e73df; }
        .border-left-success { border-left: 4px solid #1cc88a; }
        .border-left-info { border-left: 4px solid #36b9cc; }
        .border-left-warning { border-left: 4px solid #f6c23e; }
        .border-left-danger { border-left: 4px solid #e74a3b; }
        .text-primary { color: #4e73df !important; }
        .text-success { color: #1cc88a !important; }
        .text-info { color: #36b9cc !important; }
        .text-warning { color: #f6c23e !important; }
        .text-danger { color: #e74a3b !important; }
        .badge-success { background-color: #1cc88a; color: white; }
        .badge-warning { background-color: #f6c23e; color: white; }
        .badge-danger { background-color: #e74a3b; color: white; }
        .badge { padding: 0.25em 0.5em; border-radius: 0.25rem; }
        .btn-primary { background-color: #4e73df; border-color: #4e73df; }
        .btn-success { background-color: #1cc88a; border-color: #1cc88a; }
        .btn-danger { background-color: #e74a3b; border-color: #e74a3b; }
        .btn-secondary { background-color: #858796; border-color: #858796; }
        .card { margin-bottom: 24px; border: none; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); }
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        .font-weight-bold { font-weight: 700 !important; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Content Row -->
            <div class="row">
                <!-- Pending Requests Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Requests</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\SubdomainRequest::where('status', 'pending')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Tenants Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                         Tenants</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Tenant::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-globe fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Domain Requests -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Domain Requests</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Subdomain</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\SubdomainRequest::where('status', 'pending')->get() as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->subdomain }}.localhost</td>
                                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <form action="{{ route('subdomain.approve', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('subdomain.reject', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"> Tenants</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tenantsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Domain</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Tenant::all() as $tenant)
                                <tr>
                                    <td>{{ $tenant->id }}</td>
                                    <td>{{ $tenant->id }}.localhost</td>
                                    <td>
                                        <a href="http://{{ $tenant->id }}.localhost:8000/login" target="_blank" class="btn btn-primary btn-sm me-1">
                                            <i class="fas fa-external-link-alt"></i> Visit
                                        </a>
                                        
                                        @if($tenant->status == \App\Models\Tenant::STATUS_ACTIVE)
                                            <form action="{{ route('tenant.freeze', $tenant->id) }}" method="POST" class="d-inline me-1">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-snowflake"></i> Freeze
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('tenant.unfreeze', $tenant->id) }}" method="POST" class="d-inline me-1">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">
                                                    <i class="fas fa-sun"></i> Unfreeze
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($tenant->plan == \App\Models\Tenant::PLAN_FREE)
                                            <a href="#" onclick="openSubscriptionModal('premium', '{{ $tenant->id }}'); return false;" class="btn btn-success btn-sm">
                                                <i class="fas fa-arrow-up"></i> Upgrade
                                            </a>
                                        @else
                                            <a href="#" onclick="openPlanModal('free', '{{ $tenant->id }}'); return false;" class="btn btn-warning btn-sm">
                                                <i class="fas fa-arrow-down"></i> Downgrade
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Page level plugins -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            $('#tenantsTable').DataTable();
        });
    </script>
</x-app-layout>





@include('components.plan-modals')

<!-- Add this somewhere in your dashboard view -->
<input type="hidden" id="current-tenant-id" value="{{ $tenant->id ?? '' }}">



