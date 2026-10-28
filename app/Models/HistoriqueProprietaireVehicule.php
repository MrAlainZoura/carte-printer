<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueProprietaireVehicule extends Model
{
    protected $table = 'historique_proprietaires_vehicules';
    protected $fillable = [
        'vehicule_id',
        'ancien_proprietaire_id',
        'nouveau_proprietaire_id',
        'ancien_proprietaire',
        'nouveau_proprietaire',
        'user_id',
        'transfere_le',
    ];

    protected function casts(): array
    {
        return [
            'transfere_le' => 'datetime',
        ];
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function ancienProprietaire(): BelongsTo
    {
        return $this->belongsTo(Propietaire::class, 'ancien_proprietaire_id')->withTrashed();
    }

    public function nouveauProprietaire(): BelongsTo
    {
        return $this->belongsTo(Propietaire::class, 'nouveau_proprietaire_id')->withTrashed();
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
