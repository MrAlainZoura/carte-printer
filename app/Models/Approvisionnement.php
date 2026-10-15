<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approvisionnement extends Model
{
    protected $fillable = [
        'user_id',
        'posttravail_id',
        'produit_id',
        'quantite',
        'confirm',
        'receptionUser',
        'origine',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
            'confirm' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function postTravail(): BelongsTo
    {
        return $this->belongsTo(PostTravail::class, 'posttravail_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function receptionnaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptionUser');
    }
}
