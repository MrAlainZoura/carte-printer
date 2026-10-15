@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><p class="mb-1 text-sm text-gray-500"><a href="{{ route('produits.index') }}" class="hover:text-blue-700">Catalogue produits</a> / {{ $title }}</p><h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1></div><a href="{{ route('produits.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Retour au catalogue</a></div>
    @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $record->exists ? route('produits.update', $record) : route('produits.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf @if($record->exists)@method('PUT')@endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div><label for="libele" class="mb-2 block text-sm font-medium">Libellé *</label><input id="libele" name="libele" value="{{ old('libele', $record->libele) }}" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('libele')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="marque" class="mb-2 block text-sm font-medium">Marque</label><input id="marque" name="marque" value="{{ old('marque', $record->marque) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="categorie" class="mb-2 block text-sm font-medium">Catégorie</label><input id="categorie" name="categorie" value="{{ old('categorie', $record->categorie) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="unite" class="mb-2 block text-sm font-medium">Unité</label><input id="unite" name="unite" value="{{ old('unite', $record->unite) }}" placeholder="Ex. pièce, litre, kg" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="etat" class="mb-2 block text-sm font-medium">État</label><input id="etat" name="etat" value="{{ old('etat', $record->etat) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div class="sm:col-span-2"><label for="description" class="mb-2 block text-sm font-medium">Description</label><textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">{{ old('description', $record->description) }}</textarea></div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('produits.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Créer le produit' }}</button></div>
    </form>
</div>
@endsection
