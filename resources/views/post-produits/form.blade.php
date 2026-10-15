@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6"><p class="mb-1 text-sm text-gray-500"><a href="{{ route('post-produits.index') }}" class="text-blue-700 hover:underline">Produits par poste</a> / {{ $title }}</p><h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1></div>
    @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $record->exists ? route('post-produits.update', $record) : route('post-produits.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf @if($record->exists)@method('PUT')@endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div><label for="produit_id" class="mb-2 block text-sm font-medium">Produit</label><select id="produit_id" name="produit_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"><option value="">Sélectionner un produit</option>@foreach($produits as $id => $label)<option value="{{ $id }}" @selected((string) old('produit_id', $record->produit_id) === (string) $id)>{{ $label }}</option>@endforeach</select>@error('produit_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="post_travail_id" class="mb-2 block text-sm font-medium">Poste de travail</label><select id="post_travail_id" name="post_travail_id" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"><option value="">Sélectionner un poste</option>@foreach($postTravails as $id => $label)<option value="{{ $id }}" @selected((string) old('post_travail_id', $record->post_travail_id) === (string) $id)>{{ $label }}</option>@endforeach</select>@error('post_travail_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="prix" class="mb-2 block text-sm font-medium">Prix</label><input id="prix" name="prix" type="number" min="0" step="0.01" value="{{ old('prix', $record->prix) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('prix')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="quantite" class="mb-2 block text-sm font-medium">Quantité *</label><input id="quantite" name="quantite" type="number" min="0" step="0.01" required value="{{ old('quantite', $record->quantite ?? 0) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('quantite')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('post-produits.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Associer le produit' }}</button></div>
    </form>
</div>
@endsection
