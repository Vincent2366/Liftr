<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subdomain Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

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
                                @forelse($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">{{ $request->subdomain }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">{{ $request->email }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-600">{{ $request->description ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 dark:border-gray-600">
                                            <div class="flex space-x-2">
                                                @if($request->status === 'pending')
                                                    <form action="{{ route('subdomain.approve', $request->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                                                    </form>
                                                    <form action="{{ route('subdomain.reject', $request->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center border-b border-gray-300 dark:border-gray-600">No subdomain requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>