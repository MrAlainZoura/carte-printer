@php 
    $proprietaire = $vehicule->proprietaire;
@endphp
@extends('layouts.dashboard')

@section('title', 'Détail du véhicule')
@section('breadcrumb', 'Détail Véhicule')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- En-tête --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-700">
                    Tableau de bord
                </a>

                <span>/</span>

                 <a href="{{ route('vehicules.index') }}" class="hover:text-blue-700">
                    Véhicules
                </a>
                <span>/</span>

                <span class="text-gray-700">Détails</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Détail du véhicule
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Les informations du véhicule qui constituent son dossier administratif.
            </p>
        </div>

        <a href="{{ route('vehicules.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>

            Retour à la liste
        </a>

    </div>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
             role="alert">

            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4"
             role="alert">

            <div class="flex items-center gap-2 text-sm font-semibold text-red-800">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v4m0 4h.01M10.3 3.9L2.5 17.4A2 2 0 004.2 20h15.6a2 2 0 001.7-2.6L13.7 3.9a2 2 0 00-3.4 0z"/>
                </svg>

                Veuillez corriger les erreurs suivantes :
            </div>

            <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- Section Information --}}
    <section
          class="space-y-6">

        

        {{-- SECTION 1 : PROPRIÉTAIRE --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            {{-- En-tête --}}
            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 017.75-1.33M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Propriétaire du véhicule
                    </h2>

                    <p class="hidden mt-1 text-xs text-gray-500">
                    </p>
                </div>

            </div>

            <div class="space-y-6 p-6">

                {{-- Messages d'erreur --}}
                @error('propietaire_id')
                    <p class="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                @error('nouveau_proprietaire')
                    <p class="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                {{-- Propriétaire existant --}}
                <div id="proprietaire-existant">
                    <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$proprietaire->nom_complet ?? $proprietaire->raison_sociale}} / {{$proprietaire->type}}
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Nom complet ou Raison Sociale
                        </h3>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$proprietaire->impot ?? 'Non Renseigné'}}
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Numero Impôt
                        </h3>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$proprietaire->adresse_physique  ?? 'Non Renseigné'}} /
                            {{$proprietaire->telephone  ?? 'Non Renseigné'}}
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Adresse physique / Téléphone
                        </h3>
                    </div>

                </div>

                {{-- Séparateur --}}
                <div class="relative flex items-center">

                    <div class="flex-grow border-t border-gray-200"></div>

                    <span class="mx-4 shrink-0 text-xs font-medium uppercase text-gray-400">
                        Fin propriétaire
                    </span>

                    <div class="flex-grow border-t border-gray-200"></div>

                </div>

            </div>

        </div>

        {{-- SECTION 2 : IDENTIFICATION --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2M7 18h.01M17 18h.01"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Identification du véhicule
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Informations générales et identification administrative.
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                {{-- Marque --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->marque  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Marque
                        </h3>
                </div>
                

                {{-- Type --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->type  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Type
                        </h3>
                </div>
                

                {{-- Couleur --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->couleur  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Couleur
                        </h3>
                </div>

                {{-- Immatriculation --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->immatriculation  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Immatriculation
                        </h3>
                </div>

                {{-- Usage --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->usage  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Usage
                        </h3>
                </div>

            </div>

        </div>

        {{-- SECTION 3 : CARACTÉRISTIQUES TECHNIQUES --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 3h4v3h-4zM8 6h8l2 3v10H6V9l2-3zm1 5h6m-6 4h6"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Caractéristiques techniques
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Informations techniques figurant sur les documents du véhicule.
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                {{-- Châssis --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->chassis  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Chassis
                        </h3>
                </div>

                {{-- Moteur --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->moteur  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Moteur
                        </h3>
                </div>

                {{-- Puissance --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->puissance  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Puissance
                        </h3>
                </div>

                {{-- Année fabrication --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->an_fabrication  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Année de Fabrication
                        </h3>
                </div>

                {{-- Année de circulation --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->an_circulation  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Année de circulation
                        </h3>
                </div>

                {{-- Carte d'inspection --}}
                <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->cartinspection  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            Carte d'inspection
                        </h3>
                </div>

            </div>

        </div>

        {{-- SECTION 4 : ÉTAT DU VÉHICULE --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m5.6-1A9 9 0 1112 3"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        État du véhicule
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Indiquez l'état général constaté lors de l'enregistrement.
                    </p>
                </div>

            </div>

            <div class="rounded-lg bg-gray-50 p-4 mb-2">
                        <p class="font-semibold text-gray-900">
                            {{$vehicule->etat  ?? 'Non Renseigné'}} 
                        </p>
                        <h3 class="mt-1 text-sm text-gray-500">
                            État
                        </h3>
                </div>
            

        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">

                Mettre à jour
            </button>

            <button
                type="button"
                onclick="printCarte('{{route('carte.imprimer',['id'=>$vehicule->id])}}')"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>

                Imprimer
            </button>

        </div>

    </section>

</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

    });
    function printCarte(url) {
       if (!url || typeof url !== 'string') {
           console.log("URL invalide. Impossible d'ouvrir cette page.");
           return;
       }
        const fenetre = window.open(url, '_blank');
        
    }
</script>
