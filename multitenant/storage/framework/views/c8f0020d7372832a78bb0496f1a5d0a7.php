<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Subdomain Requests')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subdomain</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600"><?php echo e($request->subdomain); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600"><?php echo e($request->email); ?></td>
                                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-600"><?php echo e($request->description ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo e($request->status === 'approved' ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                                <?php echo e($request->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>">
                                                <?php echo e(ucfirst($request->status)); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600"><?php echo e($request->created_at->format('M d, Y')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">
                                            <div class="flex space-x-2">
                                                <?php if($request->status === 'pending'): ?>
                                                    <form action="<?php echo e(route('subdomain.approve', $request->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                                                    </form>
                                                    <form action="<?php echo e(route('subdomain.reject', $request->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center border-b border-gray-300 dark:border-gray-600">No subdomain requests found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <?php echo e($requests->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\SCHOOL\Websys\Liftr\multitenant\resources\views/admin/subdomain-requests.blade.php ENDPATH**/ ?>