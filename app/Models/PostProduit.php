<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostProduit extends Model
{
    protected $fillable = [
        'post_travail_id',
        'produit_id',
        'prix',
        'quantite',
    ];

    public function postTravail(): BelongsTo
    {
        return $this->belongsTo(PostTravail::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }
}
