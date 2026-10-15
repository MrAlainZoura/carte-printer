@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6"><p class="mb-1 text-sm text-gray-500"><a href="{{ route('proprietaires.index') }}" class="text-blue-700 hover:underline">Propriétaires</a> / {{ $title }}</p><h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1></div>
    @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $record->exists ? route('proprietaires.update', $record) : route('proprietaires.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf @if($record->exists)@method('PUT')@endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2"><label for="type" class="mb-2 block text-sm font-medium">Type de propriétaire *</label><select id="type" name="type" required class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@foreach(['personne physique' => 'Personne physique', 'entreprise' => 'Entreprise', 'organisation' => 'Organisation'] as $value => $label)<option value="{{ $value }}" @selected(old('type', $record->type ?? 'personne physique') === $value)>{{ $label }}</option>@endforeach</select>@error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="nom_complet" class="mb-2 block text-sm font-medium">Nom complet</label><input id="nom_complet" name="nom_complet" value="{{ old('nom_complet', $record->nom_complet) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('nom_complet')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="raison_sociale" class="mb-2 block text-sm font-medium">Raison sociale</label><input id="raison_sociale" name="raison_sociale" value="{{ old('raison_sociale', $record->raison_sociale) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('raison_sociale')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="telephone" class="mb-2 block text-sm font-medium">Téléphone</label><input id="telephone" name="telephone" type="tel" value="{{ old('telephone', $record->telephone) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('telephone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="impot" class="mb-2 block text-sm font-medium">Numéro d’impôt</label><input id="impot" name="impot" value="{{ old('impot', $record->impot) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('impot')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label for="adresse_physique" class="mb-2 block text-sm font-medium">Adresse physique</label><textarea id="adresse_physique" name="adresse_physique" rows="3" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">{{ old('adresse_physique', $record->adresse_physique) }}</textarea>@error('adresse_physique')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('proprietaires.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Créer le propriétaire' }}</button></div>
    </form>
</div>
@endsection
