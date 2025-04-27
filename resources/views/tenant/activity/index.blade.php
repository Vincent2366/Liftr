<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('tenant.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tenant.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Management
            </div>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tenant.users') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Nav Item - Sessions -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('tenant.sessions') }}">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Activity</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

           

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('tenant.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('tenant.settings') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">User Activities</h1>
                        @if(tenant() && tenant()->plan === \App\Models\Tenant::PLAN_ULTIMATE)
                            <a href="{{ route('tenant.activities.report') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                            </a>
                        @else
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm disabled">
                                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                                @if(tenant() && tenant()->plan === \App\Models\Tenant::PLAN_PREMIUM)
                                    <span class="ms-1 badge bg-info">Upgrade to Ultimate</span>
                                @endif
                            </a>
                        @endif
                    </div>

                    <!-- Activities Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Activities</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="activitiesTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $activity)
                                        <tr>
                                            <td>{{ $activity->user->name }}</td>
                                            <td>{{ $activity->start_time->format('Y-m-d') }}</td>
                                            <td>{{ $activity->start_time->format('h:i A') }}</td>
                                            <td>{{ $activity->end_time ? $activity->end_time->format('h:i A') : 'In Progress' }}</td>
                                            <td>
                                                @if($activity->end_time)
                                                    {{ $activity->start_time->diffForHumans($activity->end_time, true) }}
                                                @else
                                                    {{ $activity->start_time->diffForHumans(now(), true) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($activity->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($activity->status == 'completed')
                                                    <span class="badge badge-primary">Completed</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($activity->status) }}</span>
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
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }} 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('js/sb-admin-2.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ url('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#activitiesTable').DataTable({
                "order": [[ 1, "desc" ]],
                "pageLength": 15,
                "responsive": true,
                "language": {
                    "emptyTable": "No activities found",
                    "zeroRecords": "No matching activities found",
                    "search": "Search activities:"
                },
                "columnDefs": [
                    { 
                        "type": "date", 
                        "targets": 1 // Ensure column 1 (Date) is treated as date for proper sorting
                    }
                ]
            });
        });
    </script>
</body>

</html>



