@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Manage Users</h1>
            <p class="mt-1 text-sm text-gray-400">
                List of all registered users (Customers & Staff).
            </p>
        </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-3">User Info</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Tier</th>
                        <th scope="col" class="px-6 py-3 text-center w-48">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750 transition duration-200">
                            <td class="px-6 py-4 text-center font-medium text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    <div>
                                        <div class="font-bold text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-500">NIK: {{ $user->nik ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-900 text-red-200' : 'bg-gray-700 text-gray-300' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-900 text-blue-200">
                                    {{ ucfirst($user->tier ?? 'Standard') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="font-medium text-blue-500 hover:text-blue-400 transition-colors">
                                        Details
                                    </a>
                                    <form id="delete-form-{{ $user->id }}" 
                                          action="{{ route('admin.users.destroy', $user->id) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmDelete('delete-form-{{ $user->id }}', '{{ $user->name }}')"
                                                class="font-medium text-red-500 hover:text-red-400 transition-colors">
                                            Ban User
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection