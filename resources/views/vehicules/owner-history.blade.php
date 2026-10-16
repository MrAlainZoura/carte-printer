@extends('layouts.dashboard')

@section('title', 'Historique des propriétaires')
@section('breadcrumb', 'Historique des propriétaires')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="text-sm font-medium text-blue-700 hover:underline">
                ← Retour au véhicule {{ $vehicule->immatriculation }}
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Historique des propriétaires</h1>
            <p class="mt-1 text-sm text-gray-500">Transferts enregistrés pour ce véhicule.</p>
        </div>
        @can('vehicule.transfer')
            <a href="{{ route('vehicules.proprietaires.transfer', $vehicule->id) }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                Changer de propriétaire
            </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Date du transfert</th>
                    <th class="px-5 py-4">Ancien propriétaire</th>
                    <th class="px-5 py-4">Nouveau propriétaire</th>
                    <th class="px-5 py-4">Enregistré par</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($historique as $transfert)
                    <tr>
                        <td class="px-5 py-4">{{ $transfert->transfere_le?->format('d/m/Y à H:i') }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ $transfert->ancien_proprietaire }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ $transfert->nouveau_proprietaire }}</td>
                        <td class="px-5 py-4">{{ $transfert->utilisateur?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-gray-500">Aucun changement de propriétaire enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($historique->hasPages())
            <div class="border-t border-gray-100 px-5 py-4">{{ $historique->links() }}</div>
        @endif
    </div>
</div>
@endsection
