@extends('layouts.dashboard')
@section('title', 'Propriétaires')
@section('breadcrumb', 'Propriétaires')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Propriétaires</h1>
            <p class="mt-1 text-sm text-gray-500">Gérez les personnes et organisations propriétaires de véhicules.</p>
        </div>
        {{--@can('proprietaire.create')
            <a href="{{ route('proprietaires.create') }}" class=" inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau propriétaire
            </a>
        @endcan--}}
    </div>
    @if(session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>@endif
    <x-data-table :rows="$proprietaires" placeholder="Nom, raison sociale, téléphone ou numéro d’impôt…">
        <x-slot:filters><div class="sm:w-52"><label for="type-filter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Type</label><select id="type-filter" name="type" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm"><option value="">Tous les types</option>@foreach(['personne physique' => 'Personne physique', 'entreprise' => 'Entreprise', 'organisation' => 'Organisation'] as $value => $label)<option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>@endforeach</select></div></x-slot:filters>
        <table class="w-full text-left text-sm text-gray-600"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-4">Propriétaire</th><th class="px-5 py-4">Type</th><th class="px-5 py-4">Téléphone</th><th class="px-5 py-4">Adresse</th><th class="px-5 py-4">Véhicules</th><th class="px-5 py-4">Actions</th></tr></thead><tbody class="divide-y divide-gray-100">
            @forelse($proprietaires as $proprietaire)<tr class="hover:bg-gray-50"><td class="px-5 py-4 font-semibold text-gray-900">{{ $proprietaire->nom_complet ?: $proprietaire->raison_sociale ?: 'Sans nom' }}</td><td class="px-5 py-4">{{ ucfirst($proprietaire->type) }}</td><td class="px-5 py-4">{{ $proprietaire->telephone ?: '—' }}</td><td class="max-w-xs truncate px-5 py-4">{{ $proprietaire->adresse_physique ?: '—' }}</td><td class="px-5 py-4">{{ $proprietaire->vehicules_count }}</td><td class="whitespace-nowrap px-5 py-4"><a href="{{ route('proprietaires.show', $proprietaire) }}" class="font-medium text-blue-700 hover:underline">Détails</a>@can('proprietaire.manage')<span class="px-2 text-gray-300">|</span><a href="{{ route('proprietaires.edit', $proprietaire) }}" class="font-medium text-gray-700 hover:underline">Modifier</a><form method="POST" action="{{ route('proprietaires.destroy', $proprietaire) }}" class="ml-2 inline" onsubmit="return confirm('Supprimer ce propriétaire ?')">@csrf @method('DELETE')<button class="font-medium text-red-700 hover:underline">Supprimer</button></form>@endcan</td></tr>
            @empty<tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">Aucun propriétaire ne correspond aux critères.</td></tr>@endforelse
        </tbody></table>
    </x-data-table>
</div>
@endsection
