@extends('layouts.dashboard')

@section('title', 'Modifier la plaque')
@section('breadcrumb', 'Modifier la plaque')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('vehicules.show', $vehicule) }}" class="text-sm font-medium text-blue-700 hover:underline">← Dossier du véhicule</a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Modifier la plaque</h1>
        <p class="mt-1 text-sm text-gray-500">Cette modification concerne uniquement la plaque liée à {{ $vehicule->immatriculation }}.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('plaques.update', [$vehicule, $plaque]) }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf
        @method('PUT')

        <div>
            <span class="mb-2 block text-sm font-medium">Code de la plaque</span>
            <p class="rounded-lg border border-gray-200 bg-gray-100 p-3 text-sm text-gray-700">{{ $plaque->code }}</p>
            <p class="mt-1 text-xs text-gray-500">Pour remplacer la plaque, utilisez l’action dédiée depuis le détail du véhicule.</p>
        </div>
        <div>
            <label for="ville" class="mb-2 block text-sm font-medium">Ville</label>
            <input id="ville" name="ville" type="text" maxlength="255" value="{{ old('ville', $plaque->ville) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('ville')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="serie" class="mb-2 block text-sm font-medium">Série</label>
            <input id="serie" name="serie" type="text" maxlength="255" value="{{ old('serie', $plaque->serie) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('serie')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="statut" class="mb-2 block text-sm font-medium">Statut *</label>
            <select id="statut" name="statut" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                @foreach(['disponible' => 'Disponible', 'attribuee' => 'Attribuée', 'annulee' => 'Annulée'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('statut', $plaque->statut) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('statut')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="date_attribution" class="mb-2 block text-sm font-medium">Date d’attribution</label>
            <input id="date_attribution" name="date_attribution" type="date" value="{{ old('date_attribution', optional($plaque->date_attribution)->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
            @error('date_attribution')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('vehicules.show', $vehicule) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Enregistrer la plaque</button>
        </div>
    </form>
</div>
@endsection
