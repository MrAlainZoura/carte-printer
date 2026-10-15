@extends('layouts.dashboard')

@section('title', 'Modifier la carte rose')
@section('breadcrumb', 'Modifier la carte rose')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('vehicules.show', $vehicule) }}" class="text-sm font-medium text-blue-700 hover:underline">← Dossier du véhicule</a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Modifier la carte rose</h1>
        <p class="mt-1 text-sm text-gray-500">Cette modification concerne uniquement la carte liée à {{ $vehicule->immatriculation }}.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('carte-roses.update', [$vehicule, $carteRose]) }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf
        @method('PUT')

        <div>
            <label for="libele" class="mb-2 block text-sm font-medium">Libellé *</label>
            <input id="libele" name="libele" type="text" maxlength="255" required value="{{ old('libele', $carteRose->libele) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('libele')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="numero" class="mb-2 block text-sm font-medium">Numéro *</label>
            <input id="numero" name="numero" type="text" maxlength="255" required value="{{ old('numero', $carteRose->numero) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('numero')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="statut" class="mb-2 block text-sm font-medium">Statut *</label>
            <select id="statut" name="statut" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                @foreach(['active' => 'Active', 'expiree' => 'Expirée', 'annulee' => 'Annulée', 'perdue' => 'Perdue'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('statut', $carteRose->statut) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('statut')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="date_octroi" class="mb-2 block text-sm font-medium">Date d’octroi</label>
            <input id="date_octroi" name="date_octroi" type="date" value="{{ old('date_octroi', optional($carteRose->date_octroi)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('date_octroi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="date_expiration" class="mb-2 block text-sm font-medium">Date d’expiration</label>
            <input id="date_expiration" name="date_expiration" type="date" value="{{ old('date_expiration', optional($carteRose->date_expiration)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('date_expiration')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="observations" class="mb-2 block text-sm font-medium">Observations</label>
            <textarea id="observations" name="observations" rows="4" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">{{ old('observations', $carteRose->observations) }}</textarea>
            @error('observations')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('vehicules.show', $vehicule) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Enregistrer la carte rose</button>
        </div>
    </form>
</div>
@endsection
