<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            

            @if(auth()->user() && !auth()->user()->isSuperAdmin())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Domain Management') }}</h3>
                        
                        @if(session('success'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Domain Status: 
                                <strong>
                                    @if(!auth()->user()->domain)
                                        Not Requested
                                    @elseif(auth()->user()->domain_status === 'pending')
                                        Pending Approval
                                    @elseif(auth()->user()->domain_status === 'approved')
                                        <a href="https://{{ auth()->user()->domain }}" target="_blank" class="text-blue-600 hover:underline">{{ auth()->user()->domain }}</a>
                                    @elseif(auth()->user()->domain_status === 'rejected')
                                        Rejected: {{ auth()->user()->domain }}
                                    @endif
                                </strong>
                            </p>
                            <p class="text-sm text-gray-600">Current Subscription: <strong>{{ auth()->user()->subscription ?? 'None' }}</strong></p>
                        </div>
                        
                        @if(!auth()->user()->domain || auth()->user()->domain_status === 'rejected')
                            <form method="POST" action="{{ route('user.request-domain') }}" class="mt-4">
                                @csrf
                                <div>
                                    <x-input-label for="domain" :value="__('Request Domain')" />
                                    <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain" :value="old('domain')" required />
                                    <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                                    <p class="text-xs text-gray-500 mt-1">Enter your desired domain without http:// or https:// (e.g., example.com)</p>
                                </div>
                                
                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button class="ml-4">
                                        {{ __('Request Domain') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            @if(auth()->user() && auth()->user()->isSuperAdmin())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('All Users') }}</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users->where('is_super_admin', false) as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->isSuperAdmin() ? 'Super Admin' : 'User' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->domain ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->domain)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $user->domain_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($user->domain_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($user->domain_status ?? 'pending') }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->subscription ?? 'Regular' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($user->domain && $user->domain_status === 'pending')
                                                <form method="POST" action="{{ route('admin.approve-domain', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reject-domain', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>





