<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicule extends Model
{
     protected $fillable = [
        'propietaire_id',
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

    /**
     * Propriétaire du véhicule.
     */
    public function propietaire(): BelongsTo
    {
        return $this->belongsTo(Propietaire::class);
    }
}
