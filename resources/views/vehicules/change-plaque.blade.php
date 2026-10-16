@extends('layouts.dashboard')

@section('title', 'Changer la plaque')
@section('breadcrumb', 'Changer la plaque')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-sm font-medium text-blue-700 hover:underline">
            ← Retour au véhicule {{ $vehicule->immatriculation }}
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Changer la plaque</h1>
        <p class="mt-1 text-sm text-gray-500">Une nouvelle plaque sera attribuée au véhicule et débitée du stock du poste. L’ancienne plaque sera conservée comme annulée.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vehicules.plaque.change.store', $vehicule->id) }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="rounded-lg bg-gray-50 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Plaque actuelle</p>
            <p class="mt-1 font-semibold text-gray-900">{{ $vehicule->plaque?->code ?? $vehicule->immatriculation }}</p>
        </div>

        <div>
            <label for="code" class="mb-2 block text-sm font-medium text-gray-900">Code de la nouvelle plaque *</label>
            <input id="code" name="code" type="text" maxlength="255" required value="{{ old('code') }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm uppercase focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="ville" class="mb-2 block text-sm font-medium text-gray-900">Ville</label>
                <input id="ville" name="ville" type="text" maxlength="255" value="{{ old('ville', $vehicule->plaque?->ville) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                @error('ville')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="serie" class="mb-2 block text-sm font-medium text-gray-900">Série</label>
                <input id="serie" name="serie" type="text" maxlength="255" value="{{ old('serie', $vehicule->plaque?->serie) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                @error('serie')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Confirmer le changement</button>
        </div>
    </form>
</div>
@endsection
