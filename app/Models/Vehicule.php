<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicule extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'propietaire_id',
        'plaque_id',
        'post_travail_id',
        'marque',
        'type',
        'couleur',
        'immatriculation',
        'usage',
        'etat',
        'an_fabrication',
        'an_circulation',
        'moteur',
        'puissance',
        'chassis',
        'cartinspection',
    ];

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(
            Propietaire::class,
            'propietaire_id',
            'id'
        );
    }

    public function postTravail(): BelongsTo
    {
        return $this->belongsTo(PostTravail::class);
    }

    public function carteRoses(): HasMany
    {
        return $this->hasMany(CarteRose::class);
    }

    public function plaque(): BelongsTo
    {
        return $this->belongsTo(Plaque::class);
    }

    public function historiqueProprietaires(): HasMany
    {
        return $this->hasMany(HistoriqueProprietaireVehicule::class);
    }
}
