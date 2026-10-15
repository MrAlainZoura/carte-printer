<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propietaire extends Model
{
        use SoftDeletes;

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
        return $this->hasMany(Vehicule::class, 'propietaire_id');
    }
}
