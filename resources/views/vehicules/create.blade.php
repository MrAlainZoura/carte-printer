@extends('layouts.dashboard')

@section('title', 'Enregistrer un véhicule')
@section('breadcrumb', 'Nouveau véhicule')

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

                <span class="text-gray-700">Véhicules</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Enregistrer un véhicule
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Renseignez les informations du véhicule pour constituer son dossier administratif.
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

    {{-- Formulaire --}}
    <form action="{{ route('vehicules.store') }}"
          method="POST"
          class="space-y-6">

        @csrf

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

                    <p class="mt-1 text-xs text-gray-500">
                        Sélectionnez un propriétaire existant ou enregistrez-en un nouveau.
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

                    <label for="propietaire_id"
                        class="mb-2 block text-sm font-medium text-gray-900">
                        Choisir un propriétaire
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="propietaire_id"
                        id="propietaire_id"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        <option value="">Sélectionner un propriétaire</option>

                        @foreach($proprietaires as $proprietaire)
                            <option
                                value="{{ $proprietaire->id }}"
                                @selected(old('propietaire_id') == $proprietaire->id)>

                                {{ $proprietaire->nom_complet
                                    ?: $proprietaire->raison_sociale
                                    ?: 'Propriétaire #' . $proprietaire->id }}

                                @if($proprietaire->telephone)
                                    — {{ $proprietaire->telephone }}
                                @endif

                            </option>
                        @endforeach

                    </select>

                    @if($proprietaires->isEmpty())
                        <p class="mt-2 text-sm text-orange-600">
                            Aucun propriétaire enregistré. Vous pouvez en créer un ci-dessous.
                        </p>
                    @endif

                </div>

                {{-- Séparateur --}}
                <div class="relative flex items-center">

                    <div class="flex-grow border-t border-gray-200"></div>

                    <span class="mx-4 shrink-0 text-xs font-medium uppercase text-gray-400">
                        Nouveau propriétaire
                    </span>

                    <div class="flex-grow border-t border-gray-200"></div>

                </div>

                {{-- Activation du formulaire --}}
                <div class="rounded-xl border border-dashed border-blue-300 bg-blue-50/50 p-4">

                    <label for="creer_proprietaire"
                        class="flex cursor-pointer items-start gap-3">

                        <input
                            type="checkbox"
                            id="creer_proprietaire"
                            name="creer_proprietaire"
                            value="1"
                            @checked(old('creer_proprietaire'))
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-blue-500">

                        <span>
                            <span class="block text-sm font-semibold text-gray-900">
                                Enregistrer un nouveau propriétaire
                            </span>

                            <span class="mt-1 block text-xs leading-5 text-gray-500">
                                Cochez cette case pour saisir les informations d'un nouveau propriétaire.
                                Il sera automatiquement associé au véhicule.
                            </span>
                        </span>

                    </label>

                </div>

                {{-- Formulaire du nouveau propriétaire --}}
                <div id="nouveau-proprietaire-form"
                    class="{{ old('creer_proprietaire') ? '' : 'hidden' }} space-y-5">

                    <div class="rounded-lg bg-gray-50 p-4">

                        <h3 class="font-semibold text-gray-900">
                            Informations du nouveau propriétaire
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Complétez les informations ci-dessous.
                        </p>

                    </div>

                    {{-- Type --}}
                    <div>

                        <label for="nouveau_type"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Type de propriétaire
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="nouveau_proprietaire[type]"
                            id="nouveau_type"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                            <option value="Personne Physique"
                                @selected(old('nouveau_proprietaire.type', 'Personne Physique') === 'Personne Physique')>
                                Personne Physique
                            </option>

                            <option value="Organisation"
                                @selected(old('nouveau_proprietaire.type') === 'Organisation')>
                                Organisation
                            </option>

                            <option value="Entreprise"
                                @selected(old('nouveau_proprietaire.type') === 'Entreprise')>
                                Entreprise
                            </option>

                            <option value="Autres"
                                @selected(old('nouveau_proprietaire.type') === 'Autres')>
                                Autre
                            </option>

                        </select>

                        @error('nouveau_proprietaire.type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Nom complet --}}
                    <div id="champ-nom-complet">

                        <label for="nom_complet"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Nom complet
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nouveau_proprietaire[nom_complet]"
                            id="nom_complet"
                            value="{{ old('nouveau_proprietaire.nom_complet') }}"
                            placeholder="Ex. Jean Mukendi"
                            maxlength="255"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        @error('nouveau_proprietaire.nom_complet')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Raison sociale --}}
                    <div id="champ-raison-sociale" class="hidden">

                        <label for="raison_sociale"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Raison sociale
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nouveau_proprietaire[raison_sociale]"
                            id="raison_sociale"
                            value="{{ old('nouveau_proprietaire.raison_sociale') }}"
                            placeholder="Ex. Société de transport ABC"
                            maxlength="255"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        @error('nouveau_proprietaire.raison_sociale')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Adresse physique --}}
                    <div>

                        <label for="adresse_physique"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Adresse physique
                        </label>

                        <input
                            type="text"
                            name="nouveau_proprietaire[adresse_physique]"
                            id="adresse_physique"
                            value="{{ old('nouveau_proprietaire.adresse_physique') }}"
                            placeholder="Ex. Kinshasa, Gombe"
                            maxlength="255"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        @error('nouveau_proprietaire.adresse_physique')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Téléphone --}}
                    <div>

                        <label for="telephone"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Numéro de téléphone
                        </label>

                        <input
                            type="tel"
                            name="nouveau_proprietaire[telephone]"
                            id="telephone"
                            value="{{ old('nouveau_proprietaire.telephone') }}"
                            placeholder="Ex. +243 000 000 000"
                            maxlength="255"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        @error('nouveau_proprietaire.telephone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Numéro d'impôt --}}
                    <div id="champ-impot" class="hidden">

                        <label for="impot"
                            class="mb-2 block text-sm font-medium text-gray-900">
                            Numéro d'impôt
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nouveau_proprietaire[impot]"
                            id="impot"
                            value="{{ old('nouveau_proprietaire.impot') }}"
                            placeholder="Numéro d'identification fiscale"
                            maxlength="255"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        <p class="mt-1 text-xs text-gray-500">
                            Ce champ est affiché pour les organisations, entreprises et autres types de propriétaires.
                        </p>

                        @error('nouveau_proprietaire.impot')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

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
                <div>
                    <label for="marque"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Marque <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="marque"
                        id="marque"
                        value="{{ old('marque') }}"
                        placeholder="Ex. Toyota"
                        maxlength="255"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('marque')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Type de véhicule <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="type"
                        id="type"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        <option value="">Sélectionner un type</option>

                        @foreach([
                            'MOTO',
                            'Berline',
                            'SUV',
                            'Pick-up',
                            'Camionnette',
                            'Camion',
                            'Bus',
                            'Minibus',
                            'Autre'
                        ] as $type)
                            <option value="{{ $type }}"
                                @selected(old('type') === $type)>
                                {{ $type }}
                            </option>
                        @endforeach

                    </select>

                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Couleur --}}
                <div>
                    <label for="couleur"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Couleur <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="couleur"
                        id="couleur"
                        value="{{ old('couleur') }}"
                        placeholder="Ex. Noir"
                        maxlength="255"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('couleur')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Immatriculation --}}
                <div>
                    <label for="immatriculation"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Numéro d'immatriculation <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="immatriculation"
                        id="immatriculation"
                        value="{{ old('immatriculation') }}"
                        placeholder="Ex. CD-1234-AB"
                        maxlength="255"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm uppercase focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('immatriculation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Usage --}}
                <div class="md:col-span-2">

                    <label for="usage"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Usage du véhicule <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="usage"
                        id="usage"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                        <option value="">Sélectionner l'usage</option>

                        @foreach([
                            'TAXI'=>"TAXI",
                            'Privé' => 'Personnel',
                            'Commercial' => 'Commercial',
                            'Transport en commun' => 'Transport en commun',
                            'Transport de marchandises' => 'Transport de marchandises',
                            'Administratif' => 'Usage administratif',
                            'Autre' => 'Autre'
                        ] as $value => $label)

                            <option value="{{ $value }}"
                                @selected(old('usage') === $value)>
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                    @error('usage')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

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
                <div>
                    <label for="chassis"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Numéro de châssis
                    </label>

                    <input
                        type="text"
                        name="chassis"
                        id="chassis"
                        value="{{ old('chassis') }}"
                        placeholder="Numéro de châssis"
                        maxlength="255"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm uppercase focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('chassis')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Moteur --}}
                <div>
                    <label for="moteur"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Numéro de moteur
                    </label>

                    <input
                        type="text"
                        name="moteur"
                        id="moteur"
                        value="{{ old('moteur') }}"
                        placeholder="Numéro de moteur"
                        maxlength="255"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('moteur')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Puissance --}}
                <div>
                    <label for="puissance"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Puissance
                    </label>

                    <input
                        type="text"
                        name="puissance"
                        id="puissance"
                        value="{{ old('puissance') }}"
                        placeholder="Ex. 8 CV"
                        maxlength="255"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('puissance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Année fabrication --}}
                <div>
                    <label for="an_fabrication"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Année de fabrication
                    </label>

                    <input
                        type="number"
                        name="an_fabrication"
                        id="an_fabrication"
                        value="{{ old('an_fabrication') }}"
                        placeholder="Ex. 2022"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('an_fabrication')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Année de circulation --}}
                <div>
                    <label for="an_circulation"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Année de mise en circulation
                    </label>

                    <input
                        type="number"
                        name="an_circulation"
                        id="an_circulation"
                        value="{{ old('an_circulation') }}"
                        placeholder="Ex. 2023"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('an_circulation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Carte d'inspection --}}
                <div>
                    <label for="cartinspection"
                           class="mb-2 block text-sm font-medium text-gray-900">
                        Carte d'inspection
                    </label>

                    <input
                        type="text"
                        name="cartinspection"
                        id="cartinspection"
                        value="{{ old('cartinspection') }}"
                        placeholder="Numéro de la carte d'inspection"
                        maxlength="255"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    @error('cartinspection')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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

            <div class="p-6">

                <label for="etat"
                       class="mb-2 block text-sm font-medium text-gray-900">
                    État
                </label>

                <select
                    name="etat"
                    id="etat"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">

                    <option value="">Sélectionner l'état</option>

                    @foreach([
                        'Neuf',
                        'Bon état',
                        'État moyen',
                        'À réparer',
                        'Hors service'
                    ] as $etat)

                        <option value="{{ $etat }}"
                            @selected(old('etat') === $etat)>
                            {{ $etat }}
                        </option>

                    @endforeach

                </select>

                @error('etat')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a href="{{ route('vehicules.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">

                Annuler
            </a>

            <button
                type="reset"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">

                Réinitialiser
            </button>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>

                Enregistrer le véhicule
            </button>

        </div>

    </form>

</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxNouveau = document.getElementById('creer_proprietaire');
    const formulaireNouveau = document.getElementById('nouveau-proprietaire-form');

    const selectExistant = document.getElementById('propietaire_id');

    const typeSelect = document.getElementById('nouveau_type');

    const champNomComplet = document.getElementById('champ-nom-complet');
    const champRaisonSociale = document.getElementById('champ-raison-sociale');
    const champImpot = document.getElementById('champ-impot');

    const inputNomComplet = document.getElementById('nom_complet');
    const inputRaisonSociale = document.getElementById('raison_sociale');
    const inputImpot = document.getElementById('impot');

    function actualiserType() {

        const type = typeSelect.value;

        const personnePhysique = type === 'Personne Physique';

        const organisation = [
            'Organisation',
            'Entreprise'
        ].includes(type);

        // Personne physique : nom complet.
        champNomComplet.classList.toggle('hidden', !personnePhysique);
        inputNomComplet.required = personnePhysique;
        inputNomComplet.disabled = !personnePhysique;

        // Organisation et entreprise : raison sociale.
        champRaisonSociale.classList.toggle('hidden', personnePhysique);
        inputRaisonSociale.required = !personnePhysique;
        inputRaisonSociale.disabled = personnePhysique;

        // Impôt : tous les types sauf personne physique.
        champImpot.classList.toggle('hidden', personnePhysique);
        inputImpot.required = !personnePhysique;
        inputImpot.disabled = personnePhysique;

        // Pour le type "autre", on autorise la raison sociale.
        if (!personnePhysique && !organisation) {
            inputRaisonSociale.required = false;
        }
    }

    function actualiserModeProprietaire() {

        const nouveau = checkboxNouveau.checked;

        formulaireNouveau.classList.toggle('hidden', !nouveau);

        // La sélection existante devient indisponible si un nouveau
        // propriétaire est en cours de création.
        selectExistant.disabled = nouveau;
        selectExistant.required = !nouveau;

        // Désactiver les champs cachés permet d'éviter leur validation HTML.
        if (nouveau) {
            actualiserType();
        }
    }

    checkboxNouveau.addEventListener('change', actualiserModeProprietaire);
    typeSelect.addEventListener('change', actualiserType);

    // Initialisation avec les anciennes valeurs Laravel.
    actualiserModeProprietaire();
    actualiserType();

});
</script>
