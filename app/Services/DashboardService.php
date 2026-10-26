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
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function data(): array
    {
        $postId = CurrentPostTravail::id();
        $post = $postId
            ? PostTravail::query()->withCount('users')->find($postId)
            : null;
        $isGlobal = CurrentPostTravail::isGlobal();
        $postId = $post?->id;
        $monthStarts = collect(range(5, 0))
            ->map(fn (int $monthsAgo) => now()->startOfMonth()->subMonths($monthsAgo));
        $monthColumns = [];
        $monthBindings = [];

        foreach ($monthStarts as $index => $month) {
            $monthColumns[] = "SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as month_{$index}";
            $monthBindings[] = $month->copy();
            $monthBindings[] = $month->copy()->addMonth();
        }

        $cardsByStatus = $this->scopeByPost(CarteRose::query(), $postId)
            ->select('statut')
            ->selectRaw('COUNT(*) as total, '.implode(', ', $monthColumns), $monthBindings)
            ->groupBy('statut')
            ->orderBy('statut')
            ->get();
        $requestsByMonth = $monthStarts->map(fn (Carbon $month, int $index) => [
            'label' => $month->format('m/Y'),
            'count' => (int) $cardsByStatus->sum(fn ($row) => (int) $row->{"month_{$index}"}),
        ]);
        $statuses = $cardsByStatus->map(fn ($row) => [
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
        $cardCount = (int) $cardsByStatus->sum('total');
        $activeCardCount = (int) ($cardsByStatus->firstWhere('statut', 'active')?->total ?? 0);
        $summaryQuery = DB::query()
            ->selectSub(
                $this->scopeByPost(Vehicule::query(), $postId)->selectRaw('COUNT(*)'),
                'vehicles_count'
            )
            ->selectSub(
                PostProduit::query()
                    ->when(! $isGlobal, fn (Builder $query) => $query->where('post_travail_id', $postId))
                    ->selectRaw('COUNT(*)'),
                'post_products_count'
            )
            ->selectSub(
                PostProduit::query()
                    ->when(! $isGlobal, fn (Builder $query) => $query->where('post_travail_id', $postId))
                    ->selectRaw('COALESCE(SUM(quantite), 0)'),
                'post_products_quantity'
            );

        if ($isGlobal || ! $post) {
            $summaryQuery->selectSub(Produit::query()->selectRaw('COUNT(*)'), 'products_count');
        }

        if ($isGlobal) {
            $summaryQuery
                ->selectSub(Propietaire::query()->selectRaw('COUNT(*)'), 'owners_count')
                ->selectSub(PostTravail::query()->where('actif', true)->selectRaw('COUNT(*)'), 'active_posts_count')
                ->selectSub(User::query()->selectRaw('COUNT(*)'), 'users_count');
        } else {
            $summaryQuery
                ->selectSub(
                    Propietaire::query()
                        ->whereHas('vehicules', fn (Builder $query) => $query->where('post_travail_id', $postId))
                        ->selectRaw('COUNT(*)'),
                    'owners_count'
                );
        }

        $summary = $summaryQuery->first();
        $stats = [
            [
                'label' => 'Véhicules enregistrés',
                'value' => (int) $summary->vehicles_count,
                'hint' => $post ? 'sur ce poste' : 'dans le parc',
                'route' => $isGlobal ? null : 'vehicules.index',
                'color' => 'blue',
            ],
            [
                'label' => 'Cartes roses',
                'value' => $cardCount,
                'hint' => $post ? 'dossiers du poste' : 'dossiers enregistrés',
                'route' => null,
                'color' => 'purple',
            ],
            [
                'label' => 'Cartes actives',
                'value' => $activeCardCount,
                'hint' => 'statut actif',
                'route' => null,
                'color' => 'green',
            ],
            [
                'label' => $post ? 'Produits du poste' : 'Produits référencés',
                'value' => $post
                    ? (int) $summary->post_products_count
                    : (int) $summary->products_count,
                'hint' => $post ? 'affectés à ce poste' : 'au catalogue',
                'route' => null,
                'color' => 'orange',
            ],
        ];
        $supportingStats = $isGlobal
            ? [
                ['label' => 'Propriétaires', 'value' => (int) $summary->owners_count, 'route' => null],
                ['label' => 'Postes actifs', 'value' => (int) $summary->active_posts_count, 'route' => null],
                ['label' => 'Utilisateurs', 'value' => (int) $summary->users_count, 'route' => null],
                ['label' => 'Quantités affectées', 'value' => $summary->post_products_quantity, 'route' => null],
            ]
            : [
                ['label' => 'Propriétaires liés', 'value' => (int) $summary->owners_count, 'route' => null],
                ['label' => 'Agents du poste', 'value' => (int) ($post?->users_count ?? 0), 'route' => null],
                ['label' => 'Stock produits', 'value' => $summary->post_products_quantity, 'route' => null],
                ['label' => 'Cartes du mois', 'value' => $requestsByMonth->last()['count'], 'route' => null],
            ];

        return [
            'message' => 'Indicateurs du tableau de bord chargés.',
            'data' => [
                'stats' => $stats,
                'supportingStats' => $supportingStats,
                'requestsByMonth' => $requestsByMonth,
                'statuses' => $statuses,
                'recentCards' => $isGlobal
                    ? collect()
                    : $this->scopeByPost(CarteRose::query(), $postId)
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
