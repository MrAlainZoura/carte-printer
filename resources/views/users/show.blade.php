@extends('layouts.dashboard')
@section('title', 'Détail utilisateur')
@section('breadcrumb', 'Détail utilisateur')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('users.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Utilisateurs</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $record->name }}</h1>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(auth()->id() === $record->id)
                <a href="{{ route('password.change') }}" class="rounded-lg border border-blue-700 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-50">
                    Changer mon mot de passe
                </a>
            @endif
            @can('user.manage')
                <a href="{{ route('users.edit', $record) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Modifier l’utilisateur</a>
            @endcan
            @can('user.manage')
                @role('admin')
                    <form method="POST" action="{{ route('users.password.reset', $record) }}" onsubmit="return confirm('Réinitialiser le mot de passe de cet utilisateur ?')">
                        @csrf
                        <button class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                            Réinitialiser le mot de passe
                        </button>
                    </form>
                @endrole
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">
            {{ session('success') }}
            @if(session('temporary_password'))
                <p class="mt-2 font-semibold">Mot de passe temporaire : <code class="rounded bg-white px-2 py-1">{{ session('temporary_password') }}</code></p>
            @endif
        </div>
    @endif

    <section aria-label="Statistiques de l’activité" class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach($stats as $label => $value)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($value, 0, ',', ' ') }}</p>
            </div>
        @endforeach
    </section>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><dl class="divide-y divide-gray-100">
        @foreach(['Courriel' => $record->email, 'Téléphone' => $record->tel, 'Postes de travail' => $record->postTravails->pluck('libele')->join(', ') ?: 'Aucun poste associé', 'Compte créé le' => $record->created_at?->format('d/m/Y à H:i')] as $label => $value)<div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">{{ $label }}</dt><dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd></div>@endforeach
    </dl></div>
</div>
@endsection
