<?php

namespace App\Http\Controllers;

use App\Models\CarteRose;
use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Models\Propietaire;
use App\Models\User;
use App\Models\Vehicule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $monthStarts = collect(range(5, 0))
            ->map(fn (int $monthsAgo) => now()->startOfMonth()->subMonths($monthsAgo));

        $requestsByMonth = $monthStarts->map(fn (Carbon $month) => [
            'label' => $month->format('m/Y'),
            'count' => CarteRose::query()
                ->whereBetween('created_at', [$month->copy(), $month->copy()->endOfMonth()])
                ->count(),
        ]);

        $statuses = CarteRose::query()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->orderBy('statut')
            ->get()
            ->map(fn ($row) => [
                'key' => $row->statut,
                'label' => match ($row->statut) {
                    'active' => 'Actives',
                    'expiree' => 'Expirées',
                    'annulee' => 'Annulées',
                    'perdue' => 'Perdues',
                    default => ucfirst(str_replace('_', ' ', $row->statut)),
                },
                'count' => (int) $row->total,
            ]);

        return view('dashboard.index', [
            'stats' => [
                ['label' => 'Véhicules enregistrés', 'value' => Vehicule::count(), 'hint' => 'dans le parc', 'route' => 'vehicules.index', 'color' => 'blue'],
                ['label' => 'Cartes roses', 'value' => CarteRose::count(), 'hint' => 'dossiers enregistrés', 'route' => null, 'color' => 'purple'],
                ['label' => 'Cartes actives', 'value' => CarteRose::where('statut', 'active')->count(), 'hint' => 'statut actif', 'route' => null, 'color' => 'green'],
                ['label' => 'Produits référencés', 'value' => Produit::count(), 'hint' => 'au catalogue', 'route' => 'produits.index', 'color' => 'orange'],
            ],
            'supportingStats' => [
                ['label' => 'Propriétaires', 'value' => Propietaire::count(), 'route' => 'proprietaires.index'],
                ['label' => 'Postes actifs', 'value' => PostTravail::where('actif', true)->count(), 'route' => 'post-travails.index'],
                ['label' => 'Utilisateurs', 'value' => User::count(), 'route' => 'users.index'],
                ['label' => 'Quantités affectées', 'value' => PostProduit::sum('quantite'), 'route' => 'post-produits.index'],
            ],
            'requestsByMonth' => $requestsByMonth,
            'statuses' => $statuses,
            'recentCards' => CarteRose::with(['vehicule.proprietaire', 'postTravail'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
