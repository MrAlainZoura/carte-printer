<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plaque extends Model
{
    protected $fillable = [
        'post_travail_id',
        'code',
        'ville',
        'serie',
        'statut',
        'vehicule_id',
        'date_attribution',
    ];

    protected function casts(): array
    {
        return [
            'date_attribution' => 'date',
        ];
    }

    public function postTravail(): BelongsTo
    {
        return $this->belongsTo(PostTravail::class);
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }
}
