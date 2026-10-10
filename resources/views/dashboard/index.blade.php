@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('breadcrumb', 'Vue générale')

@section('content')

    <!-- En-tête -->
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Tableau de bord
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Bienvenue sur votre espace de gestion des cartes roses.
            </p>
        </div>

        <a href="#nouvelle-demande"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">

            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            Nouvelle demande
        </a>

    </div>

    <!-- Statistiques -->
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <!-- Total véhicules -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Véhicules enregistrés
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-gray-900">
                        2 458
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2" />
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center gap-2 text-sm">

                <span class="font-semibold text-green-600">
                    +12,5 %
                </span>

                <span class="text-gray-500">
                    par rapport au mois dernier
                </span>

            </div>

        </div>

        <!-- Demandes -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Demandes reçues
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-gray-900">
                        186
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-700">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center gap-2 text-sm">

                <span class="font-semibold text-green-600">
                    +8,2 %
                </span>

                <span class="text-gray-500">
                    ce mois-ci
                </span>

            </div>

        </div>

        <!-- En attente -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        En attente de validation
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-gray-900">
                        18
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

            </div>

            <div class="mt-4 text-sm text-orange-600">
                Nécessitent une vérification
            </div>

        </div>

        <!-- Cartes délivrées -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Cartes délivrées
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-gray-900">
                        1 824
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-700">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center gap-2 text-sm">

                <span class="font-semibold text-green-600">
                    +15,3 %
                </span>

                <span class="text-gray-500">
                    ce mois-ci
                </span>

            </div>

        </div>

    </div>

    <!-- Graphique et répartition -->
    <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-3">

        <!-- Graphique -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-2">

            <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Évolution des demandes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Nombre de demandes reçues au cours des 6 derniers mois.
                    </p>
                </div>

                <span class="w-fit rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-600">
                    6 derniers mois
                </span>

            </div>

            <div class="relative h-72 w-full">
                <canvas id="requestsChart"></canvas>
            </div>

        </div>

        <!-- Répartition -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                État des dossiers
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Répartition des demandes.
            </p>

            <div class="relative mx-auto mt-6 h-52 max-w-52">
                <canvas id="statusChart"></canvas>
            </div>

            <div class="mt-6 space-y-4">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-green-500"></span>

                        <span class="text-sm text-gray-600">
                            Validées
                        </span>
                    </div>

                    <span class="text-sm font-semibold text-gray-900">
                        112
                    </span>

                </div>

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-orange-400"></span>

                        <span class="text-sm text-gray-600">
                            En attente
                        </span>
                    </div>

                    <span class="text-sm font-semibold text-gray-900">
                        48
                    </span>

                </div>

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-red-500"></span>

                        <span class="text-sm text-gray-600">
                            Rejetées
                        </span>
                    </div>

                    <span class="text-sm font-semibold text-gray-900">
                        26
                    </span>

                </div>

            </div>

        </div>

    </div>

    <!-- Demandes récentes -->
    <div id="demandes" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col justify-between gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center">

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Demandes récentes
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Suivez les dernières demandes de cartes roses.
                </p>
            </div>

            <a href="#toutes-les-demandes"
                class="text-sm font-semibold text-blue-700 hover:underline">
                Voir toutes les demandes →
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>
                        <th class="whitespace-nowrap px-6 py-4">
                            Référence
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Propriétaire
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Véhicule
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Immatriculation
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Date
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Statut
                        </th>

                        <th class="whitespace-nowrap px-6 py-4">
                            Action
                        </th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @php
                        $demandes = [
                            [
                                'reference' => 'CR-2026-001',
                                'proprietaire' => 'Jean Mukendi',
                                'email' => 'jean@example.com',
                                'vehicule' => 'Toyota Corolla',
                                'annee' => '2022',
                                'immatriculation' => 'CD-1234-AB',
                                'date' => '09/10/2026',
                                'statut' => 'Validée',
                            ],
                            [
                                'reference' => 'CR-2026-002',
                                'proprietaire' => 'Marie Ilunga',
                                'email' => 'marie@example.com',
                                'vehicule' => 'Hyundai Tucson',
                                'annee' => '2023',
                                'immatriculation' => 'CD-5678-CD',
                                'date' => '08/10/2026',
                                'statut' => 'En attente',
                            ],
                            [
                                'reference' => 'CR-2026-003',
                                'proprietaire' => 'Patrick Kabeya',
                                'email' => 'patrick@example.com',
                                'vehicule' => 'Toyota Hilux',
                                'annee' => '2021',
                                'immatriculation' => 'CD-9012-EF',
                                'date' => '08/10/2026',
                                'statut' => 'En cours',
                            ],
                            [
                                'reference' => 'CR-2026-004',
                                'proprietaire' => 'Sarah Mbuyi',
                                'email' => 'sarah@example.com',
                                'vehicule' => 'Nissan X-Trail',
                                'annee' => '2024',
                                'immatriculation' => 'CD-3456-GH',
                                'date' => '07/10/2026',
                                'statut' => 'Rejetée',
                            ],
                            [
                                'reference' => 'CR-2026-005',
                                'proprietaire' => 'David Tshibanda',
                                'email' => 'david@example.com',
                                'vehicule' => 'Mitsubishi Pajero',
                                'annee' => '2020',
                                'immatriculation' => 'CD-7890-IJ',
                                'date' => '06/10/2026',
                                'statut' => 'Validée',
                            ],
                        ];
                    @endphp

                    @foreach ($demandes as $demande)

                        <tr class="transition hover:bg-gray-50">

                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-blue-700">
                                {{ $demande['reference'] }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $demande['proprietaire'] }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $demande['email'] }}
                                </div>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $demande['vehicule'] }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Année {{ $demande['annee'] }}
                                </div>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $demande['immatriculation'] }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $demande['date'] }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">

                                @php
                                    $badgeClasses = [
                                        'Validée' => 'bg-green-100 text-green-700',
                                        'En attente' => 'bg-orange-100 text-orange-700',
                                        'En cours' => 'bg-blue-100 text-blue-700',
                                        'Rejetée' => 'bg-red-100 text-red-700',
                                    ];

                                    $badgeClass = $badgeClasses[$demande['statut']]
                                        ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ $demande['statut'] }}
                                </span>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4">

                                <button
                                    type="button"
                                    class="rounded-lg p-2 text-gray-500 transition hover:bg-blue-50 hover:text-blue-700"
                                    title="Voir les détails"
                                    aria-label="Voir les détails de {{ $demande['reference'] }}">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c-1.5 4-5 6-9 6s-7.5-2-9-6c1.5-4 5-6 9-6s7.5 2 9 6z" />
                                    </svg>

                                </button>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4">

            <p class="text-xs text-gray-500">
                Affichage de 5 demandes fictives
            </p>

            <a href="#toutes-les-demandes"
                class="text-sm font-semibold text-blue-700 hover:underline">
                Consulter la liste complète
            </a>

        </div>

    </div>

    <!-- Actions rapides -->
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

        <a href="#nouvelle-demande"
            class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-sm">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                    Nouvelle demande
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Enregistrer un nouveau véhicule.
                </p>
            </div>

        </a>

        <a href="#validation"
            class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-orange-300 hover:shadow-sm">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-700">

                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>

            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                    Valider les dossiers
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Consulter les dossiers en attente.
                </p>
            </div>

        </a>

        <a href="#rapports"
            class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-purple-300 hover:shadow-sm">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700">

                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 17v-6m3 6V7m3 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>

            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                    Générer un rapport
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Consulter les statistiques administratives.
                </p>
            </div>

        </a>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Graphique des demandes mensuelles
            const requestsCanvas = document.getElementById('requestsChart');

            if (requestsCanvas) {
                new Chart(requestsCanvas, {
                    type: 'line',

                    data: {
                        labels: [
                            'Mai',
                            'Juin',
                            'Juillet',
                            'Août',
                            'Septembre',
                            'Octobre'
                        ],

                        datasets: [{
                            label: 'Demandes reçues',
                            data: [85, 110, 95, 145, 160, 186],

                            borderColor: '#1d4ed8',
                            backgroundColor: 'rgba(29, 78, 216, 0.10)',

                            fill: true,
                            tension: 0.4,

                            pointBackgroundColor: '#1d4ed8',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            }
                        },

                        scales: {
                            y: {
                                beginAtZero: true,

                                grid: {
                                    color: '#f3f4f6'
                                },

                                ticks: {
                                    precision: 0
                                }
                            },

                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Graphique des statuts
            const statusCanvas = document.getElementById('statusChart');

            if (statusCanvas) {
                new Chart(statusCanvas, {
                    type: 'doughnut',

                    data: {
                        labels: [
                            'Validées',
                            'En attente',
                            'Rejetées'
                        ],

                        datasets: [{
                            data: [112, 48, 26],

                            backgroundColor: [
                                '#22c55e',
                                '#fb923c',
                                '#ef4444'
                            ],

                            borderWidth: 0,
                            hoverOffset: 5
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',

                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

        });
    </script>
@endpush
