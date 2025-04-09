<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Liftr - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                @if(isset($tenant) && $tenant->logo)
                                    <div class="h-100 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }} Logo" class="img-fluid px-3">
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome to {{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }}!</h1>
                                        @if(isset($tenant) && $tenant->name)
                                            <p class="mb-4">{{ $tenant->name }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ url('/login') }}" id="loginForm">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" id="email" 
                                                aria-describedby="emailHelp" placeholder="Enter Email Address..." value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" 
                                                id="password" placeholder="Password" required>
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                <label class="custom-control-label" for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        @if (Route::has('password.request'))
                                            <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // For AJAX requests, ensure the CSRF token is included
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Refresh CSRF token before form submission
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Prevent the default form submission
                    e.preventDefault();
                    
                    // Refresh the CSRF token
                    fetch('/refresh-csrf', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update the CSRF token in the meta tag
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                        // Update the CSRF token in the form
                        document.querySelector('input[name="_token"]').value = data.token;
                        // Submit the form
                        this.submit();
                    })
                    .catch(error => {
                        console.error('Failed to refresh CSRF token:', error);
                        // Submit the form anyway as a fallback
                        this.submit();
                    });
                });
            }
        });
    </script>

    <script>
        // For SPA authentication
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch CSRF cookie before login attempts
            fetch('/sanctum/csrf-cookie', {
                method: 'GET',
                credentials: 'same-origin'
            }).then(response => {
                console.log('CSRF cookie set');
            }).catch(error => {
                console.error('Failed to set CSRF cookie:', error);
            });
        });
    </script>
</body>
</html>














