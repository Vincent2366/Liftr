<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Liftr - Weightlifting Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/css/welcome.css', 'resources/js/app.js'])
        @else
            <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
        @endif
    </head>
    <body>
        <div class="container">
            <header class="header-nav">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    @endauth
                @endif
            </header>

            <div class="flex flex-col md:flex-row gap-8 items-center justify-center min-h-[80vh]">
                <!-- Welcome Content -->
                <div class="card w-full md:w-1/2 max-w-md">
                    <h1 class="text-3xl font-bold mb-4">Welcome to Liftr</h1>
                    <p class="mb-4">The online platform for weightlifting gyms and personal trainers.</p>
                    <p class="mb-6">Request your own domain to get started!</p>
                </div>

                <!-- Domain Request Form -->
                <div class="card w-full md:w-1/2 max-w-md">
                    <x-success-message id="form-success-message">
                        <span>Your domain request is pending for confirmation.</span>
                    </x-success-message>
                    
                    <x-error-message id="form-error-message">
                        <span>There was an error processing your request. Please try again.</span>
                    </x-error-message>
                    
                    <form id="subdomain-form" class="space-y-6">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="subdomain" class="block text-lg font-semibold mb-2">Subdomain Name</label>
                            <p class="text-sm text-gray-400 mb-2">Choose a unique subdomain for your site</p>
                            <div class="domain-input">
                                <input type="text" name="subdomain" id="subdomain" class="form-control rounded-r-none" placeholder="yourname" required>
                                <span class="domain-suffix">.localhost</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-lg font-semibold mb-2">Email Address</label>
                            <p class="text-sm text-gray-400 mb-2">We'll send your approval and login details to this email</p>
                            <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full">
                            Request Subdomain
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('subdomain-form');
    const successMessage = document.getElementById('form-success-message');
    const errorMessage = document.getElementById('form-error-message');
    
    // Function to hide messages after timeout
    function hideMessageAfterTimeout(messageElement, timeout = 3000) {
        setTimeout(() => {
            messageElement.classList.add('hidden');
        }, timeout);
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hide any existing messages
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');
        
        // Use the current domain for the request
        const formData = new FormData(form);
        
        fetch('/subdomain', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                successMessage.classList.remove('hidden');
                hideMessageAfterTimeout(successMessage);
                form.reset();
            } else {
                errorMessage.querySelector('span').textContent = data.message || 'There was an error processing your request.';
                errorMessage.classList.remove('hidden');
                hideMessageAfterTimeout(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMessage.querySelector('span').textContent = 'There was an error connecting to the server.';
            errorMessage.classList.remove('hidden');
            hideMessageAfterTimeout(errorMessage);
        });
    });
});
</script>




