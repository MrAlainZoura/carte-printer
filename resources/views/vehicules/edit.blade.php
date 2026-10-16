@extends('layouts.dashboard')

@section('title', 'Modifier le véhicule')
@section('breadcrumb', 'Modifier véhicule')

@section('content')

<div class="mx-auto max-w-5xl">

    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-700">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('vehicules.index') }}" class="hover:text-blue-700">Véhicules</a>
                <span>/</span>
                <a href="{{ route('vehicules.show', $vehicule->id) }}" class="hover:text-blue-700">{{ $vehicule->immatriculation }}</a>
                <span>/</span>
                <span class="text-gray-700">Modifier</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Modifier le véhicule</h1>
            <p class="mt-2 text-sm text-gray-500">
                Mettez à jour les informations du dossier. Le changement de propriétaire ou de plaque se fait séparément depuis le détail du véhicule.
            </p>
        </div>
        <a href="{{ route('vehicules.show', $vehicule->id) }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            Annuler
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
            <div class="flex items-center gap-2 text-sm font-semibold text-red-800">Veuillez corriger les erreurs suivantes :</div>
            <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vehicules.update', $vehicule->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="font-semibold text-gray-900">Identification</h2>
            </div>
            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                <div>
                    <label for="marque" class="mb-2 block text-sm font-medium">Marque <span class="text-red-500">*</span></label>
                    <input type="text" name="marque" id="marque" required maxlength="255"
                           value="{{ old('marque', $vehicule->marque) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                    @error('marque')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="type" class="mb-2 block text-sm font-medium">Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                        <option value="">Sélectionner un type</option>
                        @foreach(['MOTO','Berline','SUV','Pick-up','Camionnette','Camion','Bus','Minibus','Autre'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $vehicule->type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="couleur" class="mb-2 block text-sm font-medium">Couleur <span class="text-red-500">*</span></label>
                    <input type="text" name="couleur" id="couleur" required maxlength="255"
                           value="{{ old('couleur', $vehicule->couleur) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                    @error('couleur')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="usage" class="mb-2 block text-sm font-medium">Usage <span class="text-red-500">*</span></label>
                    <select name="usage" id="usage" required class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                        <option value="">Sélectionner l’usage</option>
                        @foreach(['TAXI'=>'TAXI','Privé'=>'Personnel','Commercial'=>'Commercial','Transport en commun'=>'Transport en commun','Transport de marchandises'=>'Transport de marchandises','Administratif'=>'Usage administratif','Autre'=>'Autre'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('usage', $vehicule->usage) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('usage')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="font-semibold text-gray-900">Caractéristiques techniques</h2>
            </div>
            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                <div>
                    <label for="chassis" class="mb-2 block text-sm font-medium">Châssis</label>
                    <input type="text" name="chassis" id="chassis" maxlength="255" value="{{ old('chassis', $vehicule->chassis) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm uppercase focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="moteur" class="mb-2 block text-sm font-medium">Moteur</label>
                    <input type="text" name="moteur" id="moteur" maxlength="255" value="{{ old('moteur', $vehicule->moteur) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="puissance" class="mb-2 block text-sm font-medium">Puissance</label>
                    <input type="text" name="puissance" id="puissance" maxlength="255" value="{{ old('puissance', $vehicule->puissance) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="an_fabrication" class="mb-2 block text-sm font-medium">Année de fabrication</label>
                    <input type="number" name="an_fabrication" id="an_fabrication" min="1900" max="{{ date('Y') + 1 }}"
                           value="{{ old('an_fabrication', $vehicule->an_fabrication) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="an_circulation" class="mb-2 block text-sm font-medium">Année de mise en circulation</label>
                    <input type="number" name="an_circulation" id="an_circulation" min="1900" max="{{ date('Y') + 1 }}"
                           value="{{ old('an_circulation', $vehicule->an_circulation) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="cartinspection" class="mb-2 block text-sm font-medium">Carte d’inspection</label>
                    <input type="text" name="cartinspection" id="cartinspection" maxlength="255"
                           value="{{ old('cartinspection', $vehicule->cartinspection) }}"
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                </div>
                <div class="md:col-span-2">
                    <label for="etat" class="mb-2 block text-sm font-medium">État</label>
                    <select name="etat" id="etat" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                        <option value="">Sélectionner l’état</option>
                        @foreach(['Neuf','Bon état','État moyen','À réparer','Hors service'] as $etat)
                            <option value="{{ $etat }}" @selected(old('etat', $vehicule->etat) === $etat)>{{ $etat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a href="{{ route('vehicules.show', $vehicule->id) }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@endsection
