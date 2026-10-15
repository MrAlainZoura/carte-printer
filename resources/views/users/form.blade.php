@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6"><p class="mb-1 text-sm text-gray-500"><a href="{{ route('users.index') }}" class="text-blue-700 hover:underline">Utilisateurs</a> / {{ $title }}</p><h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1></div>
    @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $record->exists ? route('users.update', $record) : route('users.store') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
        @csrf @if($record->exists)@method('PUT')@endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2"><label for="name" class="mb-2 block text-sm font-medium">Nom complet *</label><input id="name" name="name" required value="{{ old('name', $record->name) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="mb-2 block text-sm font-medium">Courriel</label><input id="email" name="email" type="email" value="{{ old('email', $record->email) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="tel" class="mb-2 block text-sm font-medium">Téléphone</label><input id="tel" name="tel" type="tel" value="{{ old('tel', $record->tel) }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('tel')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="password" class="mb-2 block text-sm font-medium">{{ $record->exists ? 'Nouveau mot de passe' : 'Mot de passe *' }}</label><input id="password" name="password" type="password" @required(!$record->exists) autocomplete="new-password" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">@error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @if($record->exists)<p class="mt-1 text-xs text-gray-500">Laissez vide pour conserver le mot de passe actuel.</p>@endif</div>
            <div><label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirmation {{ $record->exists ? '' : 'du mot de passe *' }}</label><input id="password_confirmation" name="password_confirmation" type="password" @required(!$record->exists) autocomplete="new-password" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm"></div>
        </div>
        <div class="mt-7 flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('users.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ $record->exists ? 'Enregistrer' : 'Créer le compte' }}</button></div>
    </form>
</div>
@endsection
