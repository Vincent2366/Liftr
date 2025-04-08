<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <?php else: ?>
        <?php endif; ?>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            <?php if(Route::has('login')): ?>
                <nav class="flex items-center justify-end gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>
                      
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </header>

        <?php if(Route::has('login')): ?>
            <div class="h-14.5 hidden lg:block"></div>
        <?php endif; ?>

        <!-- Add this after the auth section -->
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex flex-col lg:flex-row w-full max-w-4xl gap-8 justify-center">
                <?php if(session('success')): ?>
                    <div class="w-full max-w-md mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(url('/subdomain')); ?>" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
                    <?php echo csrf_field(); ?>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Request Your Subdomain</h2>
                    
                    <div class="mb-4">
                        <label for="subdomain" class="block text-lg font-semibold text-gray-700">Subdomain</label>
                        <div class="flex items-center space-x-3">
                            <input type="text" name="subdomain" id="subdomain" value="<?php echo e(old('subdomain')); ?>" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your subdomain" required>
                            <span class="text-gray-500">.localhost</span>
                        </div>
                        <?php $__errorArgs = ['subdomain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-lg font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your email" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- <div class="mb-6">
                        <label for="description" class="block text-lg font-semibold text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Describe your subdomain purpose"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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












<?php /**PATH E:\SCHOOL\Websys\Liftr\multitenant\resources\views/welcome.blade.php ENDPATH**/ ?>