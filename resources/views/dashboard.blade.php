<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex flex-col w-full max-w-4xl gap-8 justify-center p-6">
            <!-- Admin Panel -->
            <div class="w-full bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Domain Requests</h3>
                
                <!-- Domain Requests Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">ID</th>
                                <th class="py-2 px-4 border-b text-left">Subdomain</th>
                                <th class="py-2 px-4 border-b text-left">Created At</th>
                                <th class="py-2 px-4 border-b text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\SubdomainRequest::where('status', 'pending')->get() as $request)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $request->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $request->subdomain }}.localhost</td>
                                <td class="py-2 px-4 border-b">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2 px-4 border-b">
                                <form action="{{ route('subdomain.approve', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white dark:text-white font-bold py-1 px-2 rounded text-xs">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('subdomain.reject', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white dark:text-white font-bold py-1 px-2 rounded text-xs ml-1">
                                        Reject
                                    </button>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('subdomain.index') }}" class="text-blue-500 hover:underline">View all requests</a>
                </div>
            </div>
            
            <!-- Approved Tenants -->
            <div class="w-full bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Active Tenants</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">ID</th>
                                <th class="py-2 px-4 border-b text-left">Domain</th>
                                <th class="py-2 px-4 border-b text-left">Created At</th>
                                <th class="py-2 px-4 border-b text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Tenant::all() as $tenant)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $tenant->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $tenant->id }}.localhost</td>
                                <td class="py-2 px-4 border-b">
                                    <a href="http://{{ $tenant->id }}.localhost" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>





