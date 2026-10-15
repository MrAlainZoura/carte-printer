@extends('layouts.dashboard')
@section('title', 'Détail du propriétaire')
@section('breadcrumb', 'Détail du propriétaire')
@section('content')
<div class="mx-auto max-w-5xl"><div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center"><div><a href="{{ route('proprietaires.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Propriétaires</a><h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $record->nom_complet ?: $record->raison_sociale ?: 'Propriétaire' }}</h1></div><a href="{{ route('proprietaires.edit', $record) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier</a></div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><dl class="divide-y divide-gray-100">
        @foreach(['Type' => ucfirst($record->type), 'Nom complet' => $record->nom_complet, 'Raison sociale' => $record->raison_sociale, 'Téléphone' => $record->telephone, 'Adresse physique' => $record->adresse_physique, 'Numéro d’impôt' => $record->impot, 'Véhicules associés' => $record->vehicules_count] as $label => $value)<div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">{{ $label }}</dt><dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd></div>@endforeach
    </dl></div>
</div>
@endsection
