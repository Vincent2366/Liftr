<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- Apply tenant theme -->
    <x-tenant-theme />
    
    <style>
        .bg-register-image {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .register-logo {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }
        
        .btn-user {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-user:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        .bg-gradient-primary {
            background-color: var(--primary-color);
            background-image: linear-gradient(180deg, var(--primary-color) 10%, var(--secondary-color) 100%);
            background-size: cover;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        a {
            color: var(--primary-color);
        }
        
        a:hover {
            color: var(--secondary-color);
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        @if(isset($themeSettings) && $themeSettings->logo_path)
                            <img src="{{ asset('storage/' . $themeSettings->logo_path) }}" alt="Logo" class="register-logo">
                        @elseif(isset($tenant) && $tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }} Logo" class="register-logo">
                        @else
                            <div class="text-center">
                                <h1 class="h1 text-gray-900">{{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }}</h1>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form method="POST" action="{{ url('/register') }}" class="user">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('tenant.login') }}">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
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
