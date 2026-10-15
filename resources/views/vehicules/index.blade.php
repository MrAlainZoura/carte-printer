@extends('layouts.dashboard')

@section('title', 'Véhicules')
@section('breadcrumb', 'Liste des véhicules')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Véhicules enregistrés
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Consultez les véhicules enregistrés dans le système.
            </p>
        </div>

        @can('vehicule.create')
        <a href="{{ route('vehicules.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau véhicule
        </a>
        @endcan

    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('echec'))
        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800">
            {{ session('echec') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Immatriculation</th>
                        <th class="px-6 py-4">Marque</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Couleur</th>
                        <th class="px-6 py-4">Propriétaire</th>
                        <th class="px-6 py-4">Usage</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($vehicules as $vehicule)

                        <tr class="hover:bg-gray-50">

                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-blue-700">
                                {{ $vehicule->immatriculation }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                                {{ $vehicule->marque }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $vehicule->type }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $vehicule->couleur }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $vehicule->proprietaire?->nom_complet ??  $vehicule->proprietaire?->raison_sociale ?? 'Non renseigné' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $vehicule->usage }}
                            </td>
                             <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('vehicules.show', $vehicule->id) }}"
                                       class="rounded-lg px-2 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-50"
                                       title="Voir les détails">
                                        Voir
                                    </a>
                                    @can('vehicule.update')
                                    <a href="{{ route('vehicules.edit', $vehicule->id) }}"
                                       class="rounded-lg px-2 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
                                       title="Modifier">
                                        Modifier
                                    </a>
                                    @endcan
                                    @can('vehicule.delete')
                                    <form method="POST" action="{{ route('vehicules.destroy', $vehicule->id) }}"
                                          onsubmit="return confirm('Supprimer ce véhicule ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-lg px-2 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                title="Supprimer">
                                            Supprimer
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">

                                <p class="font-medium text-gray-900">
                                    Aucun véhicule enregistré
                                </p>

                                <p class="mt-2 text-sm text-gray-500">
                                    Commencez par ajouter votre premier véhicule.
                                </p>

                                @can('vehicule.create')
                                <a href="{{ route('vehicules.create') }}"
                                   class="mt-4 inline-block font-semibold text-blue-700 hover:underline">
                                    Enregistrer un véhicule
                                </a>
                                @endcan

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($vehicules->hasPages())
            <div class="border-t border-gray-200 p-4">
                {{ $vehicules->links() }}
            </div>
        @endif

    </div>

</div>

@endsection