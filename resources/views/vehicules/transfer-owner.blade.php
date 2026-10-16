@extends('layouts.dashboard')

@section('title', 'Changer de propriétaire')
@section('breadcrumb', 'Transfert de propriété')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-sm font-medium text-blue-700 hover:underline">
            ← Retour au véhicule {{ $vehicule->immatriculation }}
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Changer de propriétaire</h1>
        <p class="mt-1 text-sm text-gray-500">Le transfert conserve la plaque et ajoute le changement à l’historique du véhicule.</p>
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

    <form method="POST" action="{{ route('vehicules.proprietaires.transfer.store', $vehicule->id) }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="mb-5 rounded-lg bg-gray-50 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Propriétaire actuel</p>
            <p class="mt-1 font-semibold text-gray-900">
                {{ $vehicule->proprietaire?->nom_complet ?: $vehicule->proprietaire?->raison_sociale ?: 'Propriétaire #'.$vehicule->propietaire_id }}
            </p>
        </div>

        <label for="propietaire_id" class="mb-2 block text-sm font-medium text-gray-900">Nouveau propriétaire *</label>
        <select name="propietaire_id" id="propietaire_id" required class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
            <option value="">Sélectionner un propriétaire</option>
            @foreach($proprietaires as $proprietaire)
                <option value="{{ $proprietaire->id }}" @selected((string) old('propietaire_id') === (string) $proprietaire->id)>
                    {{ $proprietaire->nom_complet ?: $proprietaire->raison_sociale ?: 'Propriétaire #'.$proprietaire->id }}
                    @if($proprietaire->telephone) — {{ $proprietaire->telephone }} @endif
                </option>
            @endforeach
        </select>
        @error('propietaire_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Confirmer le transfert</button>
        </div>
    </form>
</div>
@endsection
