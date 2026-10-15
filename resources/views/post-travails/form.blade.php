@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6"><p class="mb-1 text-sm text-gray-500"><a href="{{ route('post-travails.index') }}" class="text-blue-700 hover:underline">Postes de travail</a> / {{ $title }}</p><h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1><p class="mt-1 text-sm text-gray-500">Coordonnées, paramètres de caisse et utilisateurs autorisés.</p></div>
    @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $record->exists ? route('post-travails.update', $record) : route('post-travails.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf @if($record->exists)@method('PUT')@endif
        <h2 class="mb-4 text-base font-semibold text-gray-900">Informations du poste</h2>
        <div class="grid gap-5 sm:grid-cols-2">
            <div><label for="libele" class="mb-2 block text-sm font-medium">Nom du poste *</label><input id="libele" name="libele" required value="{{ old('libele', $record->libele) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('libele')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="mb-2 block text-sm font-medium">Courriel</label><input id="email" name="email" type="email" value="{{ old('email', $record->email) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="contact1" class="mb-2 block text-sm font-medium">Téléphone principal</label><input id="contact1" name="contact1" value="{{ old('contact1', $record->contact1) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="contact" class="mb-2 block text-sm font-medium">Téléphone secondaire</label><input id="contact" name="contact" value="{{ old('contact', $record->contact) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="pays" class="mb-2 block text-sm font-medium">Pays</label><input id="pays" name="pays" value="{{ old('pays', $record->pays) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="province" class="mb-2 block text-sm font-medium">Province</label><input id="province" name="province" value="{{ old('province', $record->province) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="ville" class="mb-2 block text-sm font-medium">Ville</label><input id="ville" name="ville" value="{{ old('ville', $record->ville) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="cpostal" class="mb-2 block text-sm font-medium">Code postal</label><input id="cpostal" name="cpostal" value="{{ old('cpostal', $record->cpostal) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="avenue" class="mb-2 block text-sm font-medium">Avenue</label><input id="avenue" name="avenue" value="{{ old('avenue', $record->avenue) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="lon" class="mb-2 block text-sm font-medium">Longitude</label><input id="lon" name="lon" type="number" step="any" value="{{ old('lon', $record->lon) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div><label for="lat" class="mb-2 block text-sm font-medium">Latitude</label><input id="lat" name="lat" type="number" step="any" value="{{ old('lat', $record->lat) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
            <div class="sm:col-span-2"><label for="autres" class="mb-2 block text-sm font-medium">Autres informations</label><textarea id="autres" name="autres" rows="3" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">{{ old('autres', $record->autres) }}</textarea></div>
        </div>
        <h2 class="mb-4 mt-8 border-t border-gray-100 pt-6 text-base font-semibold text-gray-900">Paramètres et accès</h2>
        <div class="grid gap-5 sm:grid-cols-2">
            <div><label for="use_cdf" class="mb-2 block text-sm font-medium">Devise utilisée</label><select id="use_cdf" name="use_cdf" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"><option value="1" @selected((string) old('use_cdf', (int) ($record->use_cdf ?? true)) === '1')>CDF</option><option value="0" @selected((string) old('use_cdf', (int) ($record->use_cdf ?? true)) === '0')>USD</option></select></div>
            <div><label for="actif" class="mb-2 block text-sm font-medium">Statut</label><select id="actif" name="actif" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"><option value="1" @selected((string) old('actif', (int) ($record->actif ?? true)) === '1')>Actif</option><option value="0" @selected((string) old('actif', (int) ($record->actif ?? true)) === '0')>Inactif</option></select></div>
            <div class="sm:col-span-2"><label for="user_ids" class="mb-2 block text-sm font-medium">Utilisateurs autorisés</label><select id="user_ids" name="user_ids[]" multiple class="min-h-32 w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@foreach($users as $user)<option value="{{ $user->id }}" @selected(in_array($user->id, array_map('intval', old('user_ids', $record->users->modelKeys()))))>{{ $user->name }}{{ $user->email ? ' — '.$user->email : '' }}</option>@endforeach</select><p class="mt-1 text-xs text-gray-500">Utilisez Ctrl/Cmd pour choisir plusieurs utilisateurs.</p></div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('post-travails.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Créer le poste' }}</button></div>
    </form>
</div>
@endsection
