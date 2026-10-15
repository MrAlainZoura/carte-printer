@extends('layouts.dashboard')
@section('title', 'Approvisionnements')
@section('breadcrumb', 'Approvisionnements')
@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Approvisionnements</h1>
            <p class="mt-1 text-sm text-gray-500">Suivez les entrées de produits et confirmez leur réception au poste.</p>
        </div>
        @can('approvisionnement.create')
            <a href="{{ route('approvisionnements.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Nouvel approvisionnement
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-4">Produit</th>
                        <th class="px-5 py-4">Poste</th>
                        <th class="px-5 py-4">Quantité</th>
                        <th class="px-5 py-4">Origine</th>
                        <th class="px-5 py-4">État</th>
                        <th class="px-5 py-4">Enregistré par</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($approvisionnements as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $item->produit?->libele ?? 'Produit supprimé' }}</td>
                            <td class="px-5 py-4">{{ $item->postTravail?->libele ?? 'Poste supprimé' }}</td>
                            <td class="px-5 py-4">{{ $item->quantite }}</td>
                            <td class="px-5 py-4">{{ $item->origine }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->confirm ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $item->confirm ? 'Réception confirmée' : 'En attente' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">{{ $item->user?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <a href="{{ route('approvisionnements.show', $item) }}" class="font-medium text-blue-700 hover:underline">Détails</a>
                                @can('approvisionnement.manage')
                                    @if(!$item->confirm)
                                        <span class="px-2 text-gray-300">|</span>
                                        <a href="{{ route('approvisionnements.edit', $item) }}" class="font-medium text-gray-700 hover:underline">Modifier</a>
                                        <form method="POST" action="{{ route('approvisionnements.destroy', $item) }}" class="ml-2 inline" onsubmit="return confirm('Supprimer cet approvisionnement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-medium text-red-700 hover:underline">Supprimer</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-gray-500">Aucun approvisionnement enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($approvisionnements->hasPages())
            <div class="border-t border-gray-200 p-4">{{ $approvisionnements->links() }}</div>
        @endif
    </div>
</div>
@endsection
