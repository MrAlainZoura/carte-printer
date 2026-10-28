@extends('layouts.dashboard')

@section('title', 'Changer de propriétaire')
@section('breadcrumb', 'Transfert de propriété')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-sm font-medium text-blue-700 hover:underline">
            ← Retour au véhicule {{ $vehicule->immatriculation }}
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Changer de propriétaire</h1>
        <p class="mt-1 text-sm text-gray-500">Choisissez un propriétaire existant, créez-en un nouveau ou corrigez les informations du propriétaire actuel.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vehicules.proprietaires.transfer.store', $vehicule->id) }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="mb-5 rounded-lg bg-gray-50 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Propriétaire actuel</p>
            <p class="mt-1 font-semibold text-gray-900">
                {{ $vehicule->proprietaire?->nom_complet ?: $vehicule->proprietaire?->raison_sociale ?: 'Propriétaire #'.$vehicule->propietaire_id }}
            </p>
        </div>

        <fieldset class="mb-6 space-y-3">
            <legend class="mb-2 text-sm font-semibold text-gray-900">Action à effectuer</legend>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="mode" value="existing" @checked(old('mode', 'existing') === 'existing')>
                Associer un propriétaire existant
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="mode" value="new" @checked(old('mode') === 'new')>
                Créer un nouveau propriétaire et lui transférer le véhicule
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="mode" value="update_current" @checked(old('mode') === 'update_current')>
                Modifier les informations du propriétaire actuel
            </label>
        </fieldset>

        <div data-owner-panel="existing">
            <label for="propietaire_id" class="mb-2 block text-sm font-medium text-gray-900">Nouveau propriétaire *</label>
            <select name="propietaire_id" id="propietaire_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
                <option value="">Sélectionner un propriétaire</option>
                @foreach($proprietaires as $proprietaire)
                    @if((int) $proprietaire->id !== (int) $vehicule->propietaire_id)
                        <option value="{{ $proprietaire->id }}" @selected((string) old('propietaire_id') === (string) $proprietaire->id)>
                            {{ $proprietaire->nom_complet ?: $proprietaire->raison_sociale ?: 'Propriétaire #'.$proprietaire->id }}
                            @if($proprietaire->telephone) — {{ $proprietaire->telephone }} @endif
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        @foreach([
            'new' => ['prefix' => 'nouveau_proprietaire', 'owner' => null, 'title' => 'Nouveau propriétaire'],
            'update_current' => ['prefix' => 'proprietaire', 'owner' => $vehicule->proprietaire, 'title' => 'Informations du propriétaire actuel'],
        ] as $mode => $section)
            <section data-owner-panel="{{ $mode }}" class="hidden mt-5 space-y-4">
                <h2 class="font-semibold text-gray-900">{{ $section['title'] }}</h2>
                <label class="block text-sm font-medium text-gray-900">
                    Type *
                    <select name="{{ $section['prefix'] }}[type]" data-owner-type class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                        <option value="">Sélectionner un type</option>
                        @foreach(['Personne Physique', 'Entreprise', 'Organisation', 'Autres'] as $type)
                            <option value="{{ $type }}" @selected(old($section['prefix'].'.type', $section['owner']?->type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm font-medium text-gray-900" data-person-name>
                    Nom complet *
                    <input type="text" name="{{ $section['prefix'] }}[nom_complet]" value="{{ old($section['prefix'].'.nom_complet', $section['owner']?->nom_complet) }}" maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </label>
                <label class="block text-sm font-medium text-gray-900" data-business-name>
                    Raison sociale *
                    <input type="text" name="{{ $section['prefix'] }}[raison_sociale]" value="{{ old($section['prefix'].'.raison_sociale', $section['owner']?->raison_sociale) }}" maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </label>
                <label class="block text-sm font-medium text-gray-900">
                    Adresse physique *
                    <input type="text" name="{{ $section['prefix'] }}[adresse_physique]" value="{{ old($section['prefix'].'.adresse_physique', $section['owner']?->adresse_physique) }}" maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </label>
                <label class="block text-sm font-medium text-gray-900">
                    Téléphone *
                    <input type="text" name="{{ $section['prefix'] }}[telephone]" value="{{ old($section['prefix'].'.telephone', $section['owner']?->telephone) }}" maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </label>
                <label class="block text-sm font-medium text-gray-900" data-owner-tax>
                    Numéro d’impôt *
                    <input type="text" name="{{ $section['prefix'] }}[impot]" value="{{ old($section['prefix'].'.impot', $section['owner']?->impot) }}" maxlength="255" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </label>
            </section>
        @endforeach

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Enregistrer</button>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="/proprietaire"]');
    if (!form) return;

    const panels = form.querySelectorAll('[data-owner-panel]');
    const radios = form.querySelectorAll('input[name="mode"]');

    function updatePanels() {
        const mode = form.querySelector('input[name="mode"]:checked')?.value || 'existing';

        panels.forEach(function (panel) {
            const active = panel.dataset.ownerPanel === mode;
            panel.classList.toggle('hidden', !active);
            panel.querySelectorAll('input, select').forEach(function (input) {
                input.disabled = !active;
                input.required = false;
            });

            if (!active || mode === 'existing') return;

            const type = panel.querySelector('[data-owner-type]');
            const isPerson = type.value === 'Personne Physique';
            const name = panel.querySelector('[data-person-name] input');
            const businessName = panel.querySelector('[data-business-name] input');
            const tax = panel.querySelector('[data-owner-tax] input');

            type.required = true;
            panel.querySelector('[data-person-name]').classList.toggle('hidden', !isPerson);
            panel.querySelector('[data-business-name]').classList.toggle('hidden', isPerson);
            panel.querySelector('[data-owner-tax]').classList.toggle('hidden', isPerson);
            name.disabled = !isPerson;
            name.required = isPerson;
            businessName.disabled = isPerson;
            businessName.required = !isPerson;
            tax.disabled = isPerson;
            tax.required = !isPerson;
            panel.querySelectorAll('input[name$="[adresse_physique]"], input[name$="[telephone]"]').forEach(function (input) {
                input.required = true;
            });
        });

        const existingSelect = form.querySelector('[name="propietaire_id"]');
        existingSelect.disabled = mode !== 'existing';
        existingSelect.required = mode === 'existing';
    }

    radios.forEach(function (radio) { radio.addEventListener('change', updatePanels); });
    form.querySelectorAll('[data-owner-type]').forEach(function (select) {
        select.addEventListener('change', updatePanels);
    });
    updatePanels();
});
</script>
