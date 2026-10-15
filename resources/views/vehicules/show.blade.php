@php
    $proprietaire = $vehicule->proprietaire;
    $plaque = $vehicule->plaque;
    $carte = $vehicule->carteRoses->sortByDesc('id')->first();
    $nr = 'Non renseigne';
@endphp
@extends('layouts.dashboard')

@section('title', 'Détail du véhicule')
@section('breadcrumb', 'Détail Véhicule')

@section('content')

<div class="mx-auto max-w-5xl">

    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-700">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('vehicules.index') }}" class="hover:text-blue-700">Véhicules</a>
                <span>/</span>
                <span class="text-gray-700">{{ $vehicule->immatriculation }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Détail du véhicule</h1>
            <p class="mt-2 text-sm text-gray-500">
                Dossier administratif, plaque et carte rose associés.
                @if($vehicule->postTravail)
                    · Poste : {{ $vehicule->postTravail->libele }}
                @endif
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('vehicule.update')
            <a href="{{ route('vehicules.edit', $vehicule->id) }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Modifier
            </a>
            @endcan
            <a href="{{ route('vehicules.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <section class="space-y-6">

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 017.75-1.33M15 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Propriétaire du véhicule</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="font-semibold text-gray-900">{{ $proprietaire?->nom_complet ?? $proprietaire?->raison_sociale ?? $nr }} / {{ $proprietaire?->type ?? $nr }}</p>
                    <h3 class="mt-1 text-sm text-gray-500">Nom complet ou raison sociale</h3>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="font-semibold text-gray-900">{{ $proprietaire?->impot ?? $nr }}</p>
                    <h3 class="mt-1 text-sm text-gray-500">Numéro d’impôt</h3>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 md:col-span-2">
                    <p class="font-semibold text-gray-900">{{ $proprietaire?->adresse_physique ?? $nr }} / {{ $proprietaire?->telephone ?? $nr }}</p>
                    <h3 class="mt-1 text-sm text-gray-500">Adresse physique / Téléphone</h3>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2"/></svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Identification du véhicule</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                @foreach([
                    'Marque' => $vehicule->marque,
                    'Type' => $vehicule->type,
                    'Couleur' => $vehicule->couleur,
                    'Immatriculation' => $vehicule->immatriculation,
                    'Usage' => $vehicule->usage,
                    'État' => $vehicule->etat,
                ] as $label => $value)
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">{{ $value ?: $nr }}</p>
                        <h3 class="mt-1 text-sm text-gray-500">{{ $label }}</h3>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3h4v3h-4zM8 6h8l2 3v10H6V9l2-3zm1 5h6m-6 4h6"/></svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Caractéristiques techniques</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                @foreach([
                    'Châssis' => $vehicule->chassis,
                    'Moteur' => $vehicule->moteur,
                    'Puissance' => $vehicule->puissance,
                    'Année de fabrication' => $vehicule->an_fabrication,
                    'Année de circulation' => $vehicule->an_circulation,
                    'Carte d’inspection' => $vehicule->cartinspection,
                ] as $label => $value)
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">{{ $value ?: $nr }}</p>
                        <h3 class="mt-1 text-sm text-gray-500">{{ $label }}</h3>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h10"/></svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Plaque</h2>
                    <p class="mt-1 text-xs text-gray-500">Créée automatiquement à l’enregistrement du véhicule.</p>
                </div>
                @canany(['vehicule.update', 'plaque.update'])
                    @if($plaque)
                        <a href="{{ route('plaques.edit', [$vehicule, $plaque]) }}" class="ml-auto rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Modifier la plaque
                        </a>
                    @endif
                @endcan
            </div>
            @if($plaque)
                <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                    @foreach([
                        'Code' => $plaque->code,
                        'Ville' => $plaque->ville,
                        'Série' => $plaque->serie,
                        'Statut' => $plaque->statut,
                        'Date d’attribution' => optional($plaque->date_attribution)->format('d/m/Y'),
                    ] as $label => $value)
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="font-semibold text-gray-900">{{ $value ?: $nr }}</p>
                            <h3 class="mt-1 text-sm text-gray-500">{{ $label }}</h3>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="p-6 text-sm text-gray-500">Aucune plaque associée.</p>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Carte rose</h2>
                    <p class="mt-1 text-xs text-gray-500">Créée automatiquement à l’enregistrement du véhicule.</p>
                </div>
                @canany(['vehicule.update', 'carte_rose.update'])
                    @if($carte)
                        <a href="{{ route('carte-roses.edit', [$vehicule, $carte]) }}" class="ml-auto rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Modifier la carte rose
                        </a>
                    @endif
                @endcan
            </div>
            @if($carte)
                <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                    @foreach([
                        'Libellé' => $carte->libele,
                        'Numéro' => $carte->numero,
                        'Statut' => $carte->statut,
                        'Date d’octroi' => optional($carte->date_octroi)->format('d/m/Y'),
                        'Date d’expiration' => optional($carte->date_expiration)->format('d/m/Y'),
                        'Observations' => $carte->observations,
                    ] as $label => $value)
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="font-semibold text-gray-900">{{ $value ?: $nr }}</p>
                            <h3 class="mt-1 text-sm text-gray-500">{{ $label }}</h3>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="p-6 text-sm text-gray-500">Aucune carte rose associée.</p>
            @endif
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
            @can('vehicule.update')
            <a href="{{ route('vehicules.edit', $vehicule->id) }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Mettre à jour
            </a>
            @endcan
            @can('carte_rose.print')
            <button
                type="button"
                onclick="printCarte('{{ route('carte.imprimer', ['id' => $vehicule->id]) }}')"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                Imprimer la carte
            </button>
            @endcan
        </div>
    </section>
</div>

@endsection

<script>
    function printCarte(url) {
        if (!url || typeof url !== 'string') return;
        window.open(url, '_blank');
    }
</script>
