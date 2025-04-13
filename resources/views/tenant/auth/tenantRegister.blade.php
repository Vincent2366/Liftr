<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($tenant) && $tenant->name ? $tenant->name : (tenant() ? strtoupper(explode('.', tenant()->domains->first()->domain)[0]) : 'LIFTR') }} - Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        @if(isset($tenant) && $tenant->logo)
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }} Logo" class="img-fluid px-3">
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                @if(isset($tenant) && $tenant->name)
                                    <p class="mb-4">{{ $tenant->name }}</p>
                                @endif
                            </div>
                            
                            <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>
                            
                            <form id="registerForm" class="user" method="POST" action="{{ url('/register') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name"
                                        name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email"
                                        name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="password_confirmation" name="password_confirmation" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
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
    
    <script>
    // Function to show error messages
    function showError(message) {
        const errorContainer = document.getElementById('errorContainer');
        errorContainer.innerHTML = message;
        errorContainer.style.display = 'block';
    }
    
    // Fetch CSRF cookie before form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch the CSRF cookie first
        fetch('/csrf-cookie', {
            method: 'GET',
            credentials: 'include'
        }).then(response => {
            console.log('CSRF cookie fetched');
        }).catch(error => {
            console.error('Error fetching CSRF cookie:', error);
        });

        // Registration form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/register', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'include'
            })
            .then(response => {
                // Check if the response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        if (response.ok) {
                            if (data.success) {
                                window.location.href = data.redirect;
                            } else {
                                // Show error message
                                showError(data.error || 'Registration failed');
                            }
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat();
                                showError(errorMessages.join('<br>'));
                            } else {
                                showError(data.message || 'Registration failed');
                            }
                        }
                    });
                } else if (response.redirected) {
                    window.location.href = response.url;
                } else if (response.ok) {
                    window.location.reload();
                } else {
                    showError('Registration failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            });
        });
    });
    </script>
</body>
</html>