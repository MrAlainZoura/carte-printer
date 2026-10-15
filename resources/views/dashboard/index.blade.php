@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('breadcrumb', 'Vue générale')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Tableau de bord</h1>
        <p class="mt-1 text-sm text-gray-500">Vue d’ensemble basée sur les données enregistrées dans votre système.</p>
    </div>

    <section aria-label="Indicateurs principaux" class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($stats as $stat)
            @php
                $colors = [
                    'blue' => 'bg-blue-50 text-blue-700',
                    'purple' => 'bg-purple-50 text-purple-700',
                    'green' => 'bg-green-50 text-green-700',
                    'orange' => 'bg-orange-50 text-orange-700',
                ];
            @endphp
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                        <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($stat['value'], 0, ',', ' ') }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ $stat['hint'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $colors[$stat['color']] }}">
                        @if($stat['color'] === 'blue')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2"/></svg>
                        @elseif($stat['color'] === 'purple')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                        @elseif($stat['color'] === 'green')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                        @else
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        @endif
                    </div>
                </div>
                @if($stat['route'])
                    <a href="{{ route($stat['route']) }}" class="mt-4 inline-flex text-sm font-semibold text-blue-700 hover:underline">Consulter <span class="ml-1" aria-hidden="true">→</span></a>
                @endif
            </div>
        @endforeach
    </section>

    <section aria-label="Autres indicateurs" class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach($supportingStats as $stat)
            <a href="{{ route($stat['route']) }}" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-blue-200 hover:shadow">
                <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($stat['value'], 0, ',', ' ') }}</p>
            </a>
        @endforeach
    </section>

    <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Cartes roses enregistrées</h2>
                <p class="mt-1 text-sm text-gray-500">Évolution réelle des créations au cours des six derniers mois.</p>
            </div>
            <div class="grid h-56 grid-cols-6 items-end gap-3 sm:gap-5" role="img" aria-label="Nombre de cartes roses créées par mois">
                @php
                    $maxMonthlyCount = max(1, (int) $requestsByMonth->max('count'));
                @endphp
                @foreach($requestsByMonth as $month)
                    <div class="flex h-full flex-col items-center justify-end gap-2">
                        <span class="text-xs font-semibold text-gray-700">{{ $month['count'] }}</span>
                        <div class="w-full rounded-t-md bg-blue-600" style="height: {{ max(3, (int) round(($month['count'] / $maxMonthlyCount) * 100)) }}%"></div>
                        <span class="text-center text-[10px] leading-tight text-gray-500 sm:text-xs">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">État des cartes</h2>
            <p class="mt-1 text-sm text-gray-500">Répartition selon le statut enregistré.</p>
            @if($statuses->isEmpty())
                <p class="py-12 text-center text-sm text-gray-500">Aucune carte rose enregistrée.</p>
            @else
                @php
                    $statusColors = [
                        'active' => 'bg-green-500',
                        'expiree' => 'bg-orange-400',
                        'annulee' => 'bg-red-500',
                        'perdue' => 'bg-gray-500',
                    ];
                @endphp
                <ul class="mt-6 space-y-4">
                    @foreach($statuses as $status)
                        <li class="flex items-center justify-between gap-3">
                            <span class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="h-3 w-3 rounded-full {{ $statusColors[$status['key']] ?? 'bg-blue-500' }}"></span>
                                {{ $status['label'] }}
                            </span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($status['count'], 0, ',', ' ') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Dernières cartes roses</h2>
                <p class="mt-1 text-sm text-gray-500">Les cinq dossiers les plus récemment créés.</p>
            </div>
            <a href="{{ route('vehicules.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">Consulter les véhicules →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Référence</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Propriétaire</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Véhicule</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Poste</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Statut</th>
                        <th scope="col" class="whitespace-nowrap px-5 py-4">Créée le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentCards as $card)
                        @php
                            $badgeClasses = [
                                'active' => 'bg-green-100 text-green-700',
                                'expiree' => 'bg-orange-100 text-orange-700',
                                'annulee' => 'bg-red-100 text-red-700',
                                'perdue' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-blue-700">{{ $card->numero }}</td>
                            <td class="px-5 py-4">{{ $card->vehicule?->proprietaire?->nom_complet ?? $card->vehicule?->proprietaire?->raison_sociale ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $card->vehicule?->immatriculation ?? '—' }}</td>
                            <td class="px-5 py-4">{{ $card->postTravail?->libele ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClasses[$card->statut] ?? 'bg-blue-100 text-blue-700' }}">{{ ucfirst(str_replace('_', ' ', $card->statut)) }}</span></td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $card->created_at?->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center">
                            <p class="font-medium text-gray-900">Aucune carte rose enregistrée</p>
                            <p class="mt-1 text-sm text-gray-500">Les dossiers apparaîtront ici après leur création.</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
