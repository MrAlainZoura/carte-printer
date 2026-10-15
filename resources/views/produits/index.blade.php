@extends('layouts.dashboard')
@section('title', 'Produits')
@section('breadcrumb', 'Produits')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div><h1 class="text-2xl font-bold text-gray-900">Catalogue des produits</h1><p class="mt-1 text-sm text-gray-500">Consultez et gérez les produits disponibles.</p></div>
        @can('produit.create')
        <a href="{{ route('produits.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau produit
        </a>
        @endcan
    </div>
    @if(session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
    <x-data-table :rows="$produits" placeholder="Libellé, marque ou catégorie…">
        <x-slot:filters>
            <div class="sm:w-56">
                <label for="categorie-filter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Catégorie</label>
                <select id="categorie-filter" name="categorie" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-900">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)<option value="{{ $categorie }}" @selected(request('categorie') === $categorie)>{{ $categorie }}</option>@endforeach
                </select>
            </div>
        </x-slot:filters>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-5 py-4">Libellé</th><th class="px-5 py-4">Marque</th><th class="px-5 py-4">Catégorie</th><th class="px-5 py-4">Unité</th><th class="px-5 py-4">État</th><th class="px-5 py-4">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($produits as $produit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $produit->libele }}</td><td class="px-5 py-4">{{ $produit->marque ?: '—' }}</td><td class="px-5 py-4">{{ $produit->categorie ?: '—' }}</td><td class="px-5 py-4">{{ $produit->unite ?: '—' }}</td><td class="px-5 py-4">{{ $produit->etat ?: '—' }}</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <a class="font-medium text-blue-700 hover:underline" href="{{ route('produits.show', $produit) }}">Détails</a>
                            @can('produit.manage')
                                <span class="px-2 text-gray-300">|</span>
                                <a class="font-medium text-gray-700 hover:underline" href="{{ route('produits.edit', $produit) }}">Modifier</a>
                                <form method="POST" action="{{ route('produits.destroy', $produit) }}" class="ml-2 inline" onsubmit="return confirm('Supprimer ce produit ?')">@csrf @method('DELETE')<button class="font-medium text-red-700 hover:underline">Supprimer</button></form>
                            @endcan
                        </td>
                    </tr>
                @empty<tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">Aucun produit ne correspond à ces critères.</td></tr>@endforelse
            </tbody>
        </table>
    </x-data-table>
</div>
@endsection
