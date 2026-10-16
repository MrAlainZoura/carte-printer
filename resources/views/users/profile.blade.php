@extends('layouts.dashboard')
@section('title', 'Mon profil')
@section('breadcrumb', 'Mon profil')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
            <p class="mt-1 text-sm text-gray-500">Vos coordonnées, vos accès et votre activité.</p>
        </div>
        <a href="{{ route('password.change') }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
            Changer mon mot de passe
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section aria-label="Statistiques de mon activité" class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach($stats as $label => $value)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($value, 0, ',', ' ') }}</p>
            </div>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <h2 class="border-b border-gray-100 px-5 py-4 text-lg font-semibold text-gray-900">Mes informations</h2>
        <dl class="divide-y divide-gray-100">
            @foreach([
                'Nom complet' => $record->name,
                'Courriel' => $record->email,
                'Téléphone' => $record->tel,
                'Rôle' => $record->getRoleNames()->map(fn ($role) => ucfirst(str_replace('_', ' ', $role)))->join(', ') ?: 'Aucun rôle',
                'Postes de travail associés' => $record->postTravails->pluck('libele')->join(', ') ?: 'Aucun poste associé',
                'Compte créé le' => $record->created_at?->format('d/m/Y à H:i'),
            ] as $label => $value)
                <div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
                    <dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
</div>
@endsection
