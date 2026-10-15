@extends('layouts.dashboard')
@section('title', $record->exists ? 'Modifier un approvisionnement' : 'Nouvel approvisionnement')
@section('breadcrumb', 'Approvisionnements')
@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('approvisionnements.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Approvisionnements</a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $record->exists ? 'Modifier un approvisionnement' : 'Nouvel approvisionnement' }}</h1>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $record->exists ? route('approvisionnements.update', $record) : route('approvisionnements.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf
        @if($record->exists) @method('PUT') @endif
        <div class="space-y-5">
            <div>
                <label for="posttravail_id" class="mb-2 block text-sm font-medium">Poste de travail *</label>
                <select id="posttravail_id" name="posttravail_id" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    <option value="">Sélectionner un poste</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" @selected((string) old('posttravail_id', $record->posttravail_id) === (string) $post->id)>{{ $post->libele }}</option>
                    @endforeach
                </select>
                @error('posttravail_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="produit_id" class="mb-2 block text-sm font-medium">Produit *</label>
                <select id="produit_id" name="produit_id" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    <option value="">Sélectionner un produit</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}" @selected((string) old('produit_id', $record->produit_id) === (string) $produit->id)>{{ $produit->libele }}</option>
                    @endforeach
                </select>
                @error('produit_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="quantite" class="mb-2 block text-sm font-medium">Quantité *</label>
                <input id="quantite" name="quantite" type="number" min="0.01" step="0.01" required value="{{ old('quantite', $record->quantite) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                @error('quantite')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="origine" class="mb-2 block text-sm font-medium">Origine *</label>
                <input id="origine" name="origine" type="text" required maxlength="255" value="{{ old('origine', $record->origine ?? 'central') }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                <p class="mt-1 text-xs text-gray-500">La valeur par défaut est « central ».</p>
                @error('origine')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('approvisionnements.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Créer l’approvisionnement' }}</button>
        </div>
    </form>
</div>
@endsection
