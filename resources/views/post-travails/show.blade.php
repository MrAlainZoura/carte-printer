@extends('layouts.dashboard')
@section('title', 'Détail du poste')
@section('breadcrumb', 'Détail du poste')
@section('content')
<div class="mx-auto max-w-5xl"><div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><a href="{{ route('post-travails.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Postes de travail</a><h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $record->libele }}</h1></div><a href="{{ route('post-travails.edit', $record) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier le poste</a></div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><dl class="divide-y divide-gray-100">
        @foreach(['Courriel' => $record->email, 'Téléphone principal' => $record->contact1, 'Téléphone secondaire' => $record->contact, 'Code postal' => $record->cpostal, 'Pays' => $record->pays, 'Province' => $record->province, 'Ville' => $record->ville, 'Avenue' => $record->avenue, 'Autres informations' => $record->autres, 'Longitude' => $record->lon, 'Latitude' => $record->lat, 'Devise' => $record->use_cdf ? 'CDF' : 'USD', 'Statut' => $record->actif ? 'Actif' : 'Inactif', 'Utilisateurs' => $record->users->pluck('name')->join(', ') ?: 'Aucun utilisateur associé'] as $label => $value)<div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">{{ $label }}</dt><dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd></div>@endforeach
    </dl></div>
</div>
@endsection
