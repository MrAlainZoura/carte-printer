<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produit = ['Carte rose', 'Plaque'];
        foreach($produit as $k=>$v){

            $create = Produit::firstOrCreate(
                [
                    'libele'=>$v,
                    'unite'=>"pc",
                    'etat'=>"neuf",
                ]
            );
        }

    }
}
