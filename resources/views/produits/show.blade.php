@extends('layouts.dashboard')
@section('title', 'Détail du produit')
@section('breadcrumb', 'Détail du produit')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><a href="{{ route('produits.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Catalogue des produits</a><h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $produit->libele }}</h1></div>@can('produit.manage')<a href="{{ route('produits.edit', $produit) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier le produit</a>@endcan</div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><dl class="divide-y divide-gray-100">
        @foreach(['Marque' => $produit->marque, 'Catégorie' => $produit->categorie, 'Unité' => $produit->unite, 'État' => $produit->etat, 'Description' => $produit->description, 'Utilisé dans des postes' => $produit->post_produits_count] as $label => $value)
            <div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">{{ $label }}</dt><dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd></div>
        @endforeach
    </dl></div>
</div>
@endsection
