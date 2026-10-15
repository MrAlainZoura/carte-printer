<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostTravail extends Model
{
    protected $fillable = [
        'libele',
        'email',
        'contact1',
        'contact',
        'cpostal',
        'pays',
        'province',
        'ville',
        'avenue',
        'autres',
        'lon',
        'lat',
        //'type',
        'use_cdf',
        //'tva_rate',
        //'show_tva',
        'actif',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'use_cdf' => 'boolean',
            'show_tva' => 'boolean',
            'actif' => 'boolean',
            'tva_rate' => 'decimal:2',
            'lon' => 'decimal:7',
            'lat' => 'decimal:7',
        ];
    }

    public function carteRoses(): HasMany
    {
        return $this->hasMany(CarteRose::class);
    }

    public function plaques(): HasMany
    {
        return $this->hasMany(Plaque::class);
    }

    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    public function postProduits(): HasMany
    {
        return $this->hasMany(PostProduit::class);
    }

    public function approvisionnements(): HasMany
    {
        return $this->hasMany(Approvisionnement::class, 'posttravail_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_default')
            ->withTimestamps();
    }
}
