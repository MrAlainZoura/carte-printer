@extends('layouts.dashboard')
@section('title', 'Rôle '.$role->name)
@section('breadcrumb', 'Rôle')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('roles.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Rôles</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $role->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $role->permissions->count() }} permission(s) · {{ $role->users->count() }} utilisateur(s)</p>
        </div>
        @can('role.manage')
            <a href="{{ route('roles.edit', $role) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier les permissions</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="hidden mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Permissions attribuées</h2>
        @if($role->permissions->isEmpty())
            <p class="text-sm text-gray-500">Aucune permission.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($role->permissions->sortBy('name') as $permission)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $permission->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Utilisateurs</h2>
        @forelse($role->users as $user)
            <div class="flex items-center justify-between border-b border-gray-100 py-3 last:border-0">
                <div>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email ?: $user->tel ?: '—' }}</p>
                </div>
                @can('user.view')
                    <a href="{{ route('users.show', $user) }}" class="text-sm font-semibold text-blue-700 hover:underline">Voir</a>
                @endcan
            </div>
        @empty
            <p class="text-sm text-gray-500">Aucun utilisateur avec ce rôle.</p>
        @endforelse
    </div>
</div>
@endsection
