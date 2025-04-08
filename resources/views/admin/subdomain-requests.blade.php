<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subdomain Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Pending Requests</h3>
                    
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">ID</th>
                                <th class="py-2 px-4 border-b text-left">Subdomain</th>
                                <th class="py-2 px-4 border-b text-left">Email</th>
                                <th class="py-2 px-4 border-b text-left">Status</th>
                                <th class="py-2 px-4 border-b text-left">Created At</th>
                                <th class="py-2 px-4 border-b text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $request->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $request->subdomain }}.localhost</td>
                                <td class="py-2 px-4 border-b">{{ $request->user->email ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($request->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Approved</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Rejected</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($request->status === 'pending')
                                        <form action="{{ route('subdomain.approve', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 !text-black dark:!text-black font-bold py-1 px-2 rounded text-xs">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('subdomain.reject', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 !text-black dark:!text-black font-bold py-1 px-2 rounded text-xs ml-1">
                                                Reject
                                            </button>
                                        </form>
                                    @elseif($request->status === 'approved')
                                        <a href="http://{{ $request->subdomain }}.localhost" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-1 px-2 rounded text-xs">
                                            Visit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


