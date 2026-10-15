@extends('layouts.dashboard')
@section('title', 'Détail de l’approvisionnement')
@section('breadcrumb', 'Approvisionnements')
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('approvisionnements.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Approvisionnements</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $approvisionnement->produit?->libele ?? 'Approvisionnement' }}</h1>
        </div>
        @if(!$approvisionnement->confirm && auth()->user()->can('approvisionnement.manage'))
            <a href="{{ route('approvisionnements.edit', $approvisionnement) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Modifier</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <dl class="divide-y divide-gray-100">
            @foreach([
                'Produit' => $approvisionnement->produit?->libele,
                'Poste de travail' => $approvisionnement->postTravail?->libele,
                'Quantité' => $approvisionnement->quantite,
                'Origine' => $approvisionnement->origine,
                'État' => $approvisionnement->confirm ? 'Réception confirmée' : 'En attente de réception',
                'Enregistré par' => $approvisionnement->user?->name,
                'Réception confirmé par' => $approvisionnement->receptionnaire?->name,
                'Créé le' => $approvisionnement->created_at?->format('d/m/Y à H:i'),
            ] as $label => $value)
                <div class="grid gap-1 px-5 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
                    <dd class="break-words text-sm text-gray-900 sm:col-span-2">{{ filled($value) ? $value : '—' }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    @can('approvisionnement.confirm')
        @if(!$approvisionnement->confirm)
            <form method="POST" action="{{ route('approvisionnements.confirm', $approvisionnement) }}" class="mt-5 text-right" onsubmit="return confirm('Confirmer la réception de cet approvisionnement ?')">
                @csrf
                @method('PATCH')
                <button class="rounded-lg bg-green-700 px-5 py-3 text-sm font-semibold text-white hover:bg-green-800">Confirmer la réception</button>
            </form>
        @endif
    @endcan
</div>
@endsection
