<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'libele',
        'description',
        'marque',
        'categorie',
        'unite',
        'etat'
    ];


}
