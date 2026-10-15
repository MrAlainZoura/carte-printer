<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostProduit extends Model
{
    protected $fillable = [
        'post_travail_id',
        'produit_id',
        'prix'
    ];
}
