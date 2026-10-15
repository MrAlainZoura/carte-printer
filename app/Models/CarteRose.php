<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarteRose extends Model
{
    protected $table = 'carte_roses';

    protected $fillable = [
        'post_travail_id',
        'vehicule_id',
        'user_id',
        'libele',
        'numero',
        'date_octroi',
        'date_expiration',
        'statut',
        'qr_token',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'date_octroi' => 'date',
            'date_expiration' => 'date',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
