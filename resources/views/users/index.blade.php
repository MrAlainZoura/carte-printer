@extends('layouts.dashboard')
@section('title', 'Utilisateurs')
@section('breadcrumb', 'Utilisateurs')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1><p class="mt-1 text-sm text-gray-500">Administrez les comptes et coordonnées des utilisateurs.</p></div>@can('user.create')<a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Créer un utilisateur</a>@endcan</div>
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif
    <x-data-table :rows="$users" placeholder="Nom, courriel ou téléphone…">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Nom</th>
                    <th class="px-5 py-4">Courriel</th>
                    <th class="px-5 py-4">Téléphone</th>
                    <th class="px-5 py-4">Date d’inscription</th>
                    <th class="px-5 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                    <td class="px-5 py-4">{{ $user->email ?: '—' }}</td>
                    <td class="px-5 py-4">{{ $user->tel ?: '—' }}</td>
                    <td class="px-5 py-4">{{ $user->created_at?->format('d/m/Y') }}</td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <a href="{{ route('users.show', $user) }}" class="font-medium text-blue-700 hover:underline">
                            Détails
                        </a>
                        @can('user.manage')<span class="px-2 text-gray-300">|</span>
                        <a href="{{ route('users.edit', $user) }}" class="font-medium text-gray-700 hover:underline">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="ml-2 inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf @method('DELETE')
                            <button class="font-medium text-red-700 hover:underline">Supprimer</button>
                        </form>@endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">Aucun utilisateur ne correspond à cette recherche.</td>
                </tr>
            @endforelse
        </tbody></table>
    </x-data-table>
</div>
@endsection
