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

        <a href="{{ route('vehicules.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">

            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>

            Nouveau véhicule
        </a>

    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
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
                                {{ $vehicule->proprietaire?->nom_complet ?? 'Non renseigné' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $vehicule->usage }}
                            </td>
                             <td class="whitespace-nowrap px-6 py-4">

                                <button
                                    type="button"
                                    class="rounded-lg p-2 text-gray-500 transition hover:bg-blue-50 hover:text-blue-700"
                                    title="Voir les détails"
                                    aria-label="Voir les détails de {{ $vehicule->immatriculation }}">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c-1.5 4-5 6-9 6s-7.5-2-9-6c1.5-4 5-6 9-6s7.5 2 9 6z" />
                                    </svg>

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">

                                <p class="font-medium text-gray-900">
                                    Aucun véhicule enregistré
                                </p>

                                <p class="mt-2 text-sm text-gray-500">
                                    Commencez par ajouter votre premier véhicule.
                                </p>

                                <a href="{{ route('vehicules.create') }}"
                                   class="mt-4 inline-block font-semibold text-blue-700 hover:underline">
                                    Enregistrer un véhicule
                                </a>

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