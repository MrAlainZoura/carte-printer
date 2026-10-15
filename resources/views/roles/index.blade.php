@extends('layouts.dashboard')
@section('title', 'Rôles & permissions')
@section('breadcrumb', 'Rôles')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rôles & permissions</h1>
            <p class="mt-1 text-sm text-gray-500">Créez des rôles et attribuez-leur des permissions Spatie.</p>
        </div>
        @can('role.manage')
            <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Nouveau rôle
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-4">Rôle</th>
                        <th class="px-5 py-4">Permissions</th>
                        <th class="px-5 py-4">Utilisateurs</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900">{{ $role->name }}</p>
                                @if($role->name === 'admin')
                                    <p class="mt-1 text-xs text-blue-700">Accès complet</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-900">{{ $role->permissions_count }}</p>
                                <p class="mt-1 max-w-md text-xs text-gray-500">
                                    {{ $role->permissions->take(6)->pluck('name')->implode(', ') }}
                                    @if($role->permissions_count > 6)…@endif
                                </p>
                            </td>
                            <td class="px-5 py-4">{{ $role->users_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <a href="{{ route('roles.show', $role) }}" class="font-medium text-blue-700 hover:underline">Détails</a>
                                @can('role.manage')
                                    <span class="px-2 text-gray-300">|</span>
                                    <a href="{{ route('roles.edit', $role) }}" class="font-medium text-gray-700 hover:underline">Modifier</a>
                                    @if($role->name !== 'admin')
                                        <form method="POST" action="{{ route('roles.destroy', $role) }}" class="ml-2 inline" onsubmit="return confirm('Supprimer ce rôle ?')">
                                            @csrf @method('DELETE')
                                            <button class="font-medium text-red-700 hover:underline">Supprimer</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500">Aucun rôle défini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
