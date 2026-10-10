<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propietaire extends Model
{
    
    protected $fillable = [
        'type',
        'nom_complet',
        'raison_sociale',
        'adresse_physique',
        'telephone',
        'impot',
    ];

    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }
}
