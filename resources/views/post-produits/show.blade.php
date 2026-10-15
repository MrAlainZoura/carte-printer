@extends('layouts.dashboard')
@section('title', 'Détail du produit de poste')
@section('breadcrumb', 'Détail du produit de poste')
@section('content')
<div class="mx-auto max-w-5xl"><div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><a href="{{ route('post-produits.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Produits par poste</a><h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $record->produit?->libele ?? 'Association produit/poste' }}</h1></div>@can('post_produit.manage')<a href="{{ route('post-produits.edit', $record) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier l’association</a>@endcan</div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><dl class="divide-y divide-gray-100">
        @foreach(['Produit' => $record->produit?->libele, 'Poste de travail' => $record->postTravail?->libele, 'Prix' => $record->prix !== null ? number_format($record->prix, 2, ',', ' ') : null, 'Quantité' => $record->quantite] as $label => $value)<div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">{{ $label }}</dt><dd class="text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd></div>@endforeach
    </dl></div>
</div>
@endsection
