@extends('layouts.dashboard')
@section('title', 'Produits des postes')
@section('breadcrumb', 'Produits des postes')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div><h1 class="text-2xl font-bold text-gray-900">Produits par poste</h1><p class="mt-1 text-sm text-gray-500">Gérez les produits, tarifs et quantités associés aux postes de travail.</p></div>
        <a href="{{ route('post-produits.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Associer un produit</a>
    </div>
    @if(session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
    <x-data-table :rows="$postProduits" placeholder="Rechercher un produit ou un poste…">
        <x-slot:filters>
            <div class="sm:w-52"><label for="product-filter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Produit</label><select id="product-filter" name="produit_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm"><option value="">Tous les produits</option>@foreach($produits as $id => $label)<option value="{{ $id }}" @selected((string) request('produit_id') === (string) $id)>{{ $label }}</option>@endforeach</select></div>
            <div class="sm:w-52"><label for="post-filter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Poste</label><select id="post-filter" name="post_travail_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm"><option value="">Tous les postes</option>@foreach($postTravails as $id => $label)<option value="{{ $id }}" @selected((string) request('post_travail_id') === (string) $id)>{{ $label }}</option>@endforeach</select></div>
        </x-slot:filters>
        <table class="w-full text-left text-sm text-gray-600"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-4">Produit</th><th class="px-5 py-4">Poste de travail</th><th class="px-5 py-4">Prix</th><th class="px-5 py-4">Quantité</th><th class="px-5 py-4">Actions</th></tr></thead><tbody class="divide-y divide-gray-100">
            @forelse($postProduits as $record)<tr class="hover:bg-gray-50"><td class="px-5 py-4 font-semibold text-gray-900">{{ $record->produit?->libele ?? 'Produit supprimé' }}</td><td class="px-5 py-4">{{ $record->postTravail?->libele ?? 'Poste supprimé' }}</td><td class="px-5 py-4">{{ $record->prix !== null ? number_format($record->prix, 2, ',', ' ') : '—' }}</td><td class="px-5 py-4">{{ $record->quantite }}</td><td class="whitespace-nowrap px-5 py-4"><a href="{{ route('post-produits.show', $record) }}" class="font-medium text-blue-700 hover:underline">Détails</a><span class="px-2 text-gray-300">|</span><a href="{{ route('post-produits.edit', $record) }}" class="font-medium text-gray-700 hover:underline">Modifier</a><form method="POST" action="{{ route('post-produits.destroy', $record) }}" class="ml-2 inline" onsubmit="return confirm('Retirer ce produit du poste ?')">@csrf @method('DELETE')<button class="font-medium text-red-700 hover:underline">Supprimer</button></form></td></tr>
            @empty<tr><td colspan="5" class="px-5 py-12 text-center text-gray-500">Aucune association produit/poste ne correspond aux critères.</td></tr>@endforelse
        </tbody></table>
    </x-data-table>
</div>
@endsection
