<?php

namespace App\Services;

use App\Models\CarteRose;
use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Models\Propietaire;
use App\Models\User;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DashboardService
{
    public function data(): array
    {
        $post = CurrentPostTravail::get();
        $isGlobal = CurrentPostTravail::isGlobal();
        $postId = $post?->id;
        $monthStarts = collect(range(5, 0))
            ->map(fn (int $monthsAgo) => now()->startOfMonth()->subMonths($monthsAgo));

        $requestsByMonth = $monthStarts->map(fn (Carbon $month) => [
            'label' => $month->format('m/Y'),
            'count' => $this->scopeByPost(CarteRose::query(), $postId)
                ->whereBetween('created_at', [$month->copy(), $month->copy()->endOfMonth()])
                ->count(),
        ]);
        $statuses = $this->scopeByPost(CarteRose::query(), $postId)
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
        $stats = [
            [
                'label' => 'Véhicules enregistrés',
                'value' => $this->scopeByPost(Vehicule::query(), $postId)->count(),
                'hint' => $post ? 'sur ce poste' : 'dans le parc',
                'route' => $isGlobal ? null : 'vehicules.index',
                'color' => 'blue',
            ],
            [
                'label' => 'Cartes roses',
                'value' => $this->scopeByPost(CarteRose::query(), $postId)->count(),
                'hint' => $post ? 'dossiers du poste' : 'dossiers enregistrés',
                'route' => null,
                'color' => 'purple',
            ],
            [
                'label' => 'Cartes actives',
                'value' => $this->scopeByPost(CarteRose::query(), $postId)->where('statut', 'active')->count(),
                'hint' => 'statut actif',
                'route' => null,
                'color' => 'green',
            ],
            [
                'label' => $post ? 'Produits du poste' : 'Produits référencés',
                'value' => $post
                    ? PostProduit::query()->where('post_travail_id', $postId)->count()
                    : Produit::count(),
                'hint' => $post ? 'affectés à ce poste' : 'au catalogue',
                'route' => null,
                'color' => 'orange',
            ],
        ];
        $supportingStats = $isGlobal
            ? [
                ['label' => 'Propriétaires', 'value' => Propietaire::count(), 'route' => null],
                ['label' => 'Postes actifs', 'value' => PostTravail::where('actif', true)->count(), 'route' => null],
                ['label' => 'Utilisateurs', 'value' => User::count(), 'route' => null],
                ['label' => 'Quantités affectées', 'value' => PostProduit::sum('quantite'), 'route' => null],
            ]
            : [
                ['label' => 'Propriétaires liés', 'value' => Propietaire::query()
                    ->whereHas('vehicules', fn (Builder $q) => $q->where('post_travail_id', $postId))
                    ->count(), 'route' => null],
                ['label' => 'Agents du poste', 'value' => $post?->users()->count() ?? 0, 'route' => null],
                ['label' => 'Stock produits', 'value' => PostProduit::query()->where('post_travail_id', $postId)->sum('quantite'), 'route' => null],
                ['label' => 'Cartes du mois', 'value' => $this->scopeByPost(CarteRose::query(), $postId)
                    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count(), 'route' => null],
            ];

        return [
            'message' => 'Indicateurs du tableau de bord chargés.',
            'data' => [
                'stats' => $stats,
                'supportingStats' => $supportingStats,
                'requestsByMonth' => $requestsByMonth,
                'statuses' => $statuses,
                'recentCards' => $this->scopeByPost(CarteRose::query(), $postId)
                    ->with(['vehicule.proprietaire', 'postTravail'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'posts' => $isGlobal ? PostTravail::query()->orderBy('libele')->get() : collect(),
                'currentPost' => $post,
                'isGlobal' => $isGlobal,
            ],
        ];
    }

    private function scopeByPost(Builder $query, ?int $postId): Builder
    {
        if ($postId) {
            $query->where('post_travail_id', $postId);
        }

        return $query;
    }
}
