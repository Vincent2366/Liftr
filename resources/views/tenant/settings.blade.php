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
    
    <!-- Apply tenant theme -->
    <x-tenant-theme />
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
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tenant.sessions') }}">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Activity</span>
                </a>
            </li>

            <!-- Nav Item - Settings -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('tenant.settings') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
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
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Settings</h1>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- Only show theme settings to Admin users -->
                        @if(Auth::user()->role === 'Admin')
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Appearance</h6>
                                </div>
                                <div class="card-body">
                                    <p>Customize the look and feel of your tenant application.</p>
                                    <a href="{{ route('tenant.theme.settings') }}" class="btn btn-primary">
                                        <i class="fas fa-paint-brush"></i> Theme Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Account Settings</h6>
                                </div>
                                <div class="card-body">
                                    <p>Manage your account preferences and security settings.</p>
                                    <a href="{{ route('tenant.profile') }}" class="btn btn-primary">
                                        <i class="fas fa-user"></i> Profile Settings
                                    </a>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    
                    <!-- Only show tenant information to Admin users -->
                    @if(Auth::user()->role === 'Admin')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tenant Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Domain:</strong> {{ tenant()->domains->first()->domain }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Plan:</strong> {{ ucfirst(tenant()->plan) }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong> 
                                        <span class="badge {{ tenant()->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst(tenant()->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->role === 'Admin')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Version Control</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Current Version:</strong> 
                                        <span class="badge badge-info">v1.0</span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Available Versions:</strong>
                                        <div class="mt-2">
                                            <form method="POST" action="{{ route('tenant.version.update') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="version" value="1.1">
                                                <button type="submit" class="btn btn-sm btn-success mr-2">
                                                    <i class="fas fa-arrow-up"></i> Upgrade to v1.1
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('tenant.version.rollback') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="version" value="0.9">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-arrow-down"></i> Rollback to v0.9
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="small text-muted mt-3">
                                        <i class="fas fa-info-circle"></i> Changing versions may affect functionality. Make sure to back up your data before upgrading or rolling back.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }} {{ date('Y') }}</span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('js/sb-admin-2.min.js') }}"></script>

</body>
</html>



