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
    public function proprietaire0(): BelongsTo
    {
        return $this->belongsTo(Propietaire::class);
    }

    public function proprietaire(): BelongsTo
{
    return $this->belongsTo(
        Propietaire::class,  // Modèle associé
        'propietaire_id',    // Clé étrangère dans la table actuelle
        'id'                 // Clé primaire dans la table proprietaires
    );
}
}
