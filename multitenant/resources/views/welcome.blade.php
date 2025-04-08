<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>
                      
                    @endauth
                </nav>
            @endif
        </header>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

        <!-- Add this after the auth section -->
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex flex-col lg:flex-row w-full max-w-4xl gap-8 justify-center">
                @if(session('success'))
                    <div class="w-full max-w-md mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ url('/subdomain') }}" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
                    @csrf
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Request Your Subdomain</h2>
                    
                    <div class="mb-4">
                        <label for="subdomain" class="block text-lg font-semibold text-gray-700">Subdomain</label>
                        <div class="flex items-center space-x-3">
                            <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your subdomain" required>
                            <span class="text-gray-500">.localhost</span>
                        </div>
                        @error('subdomain')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-lg font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your email" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- <div class="mb-6">
                        <label for="description" class="block text-lg font-semibold text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Describe your subdomain purpose">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> -->
                    
                    <div class="mb-6">
                        <button type="submit"
                            class="p-3 w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-black font-semibold py-3 px-4 rounded shadow">
                            Request Subdomain
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>












