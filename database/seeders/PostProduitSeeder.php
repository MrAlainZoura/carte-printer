<?php

namespace Database\Seeders;

use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produits  = Produit::all();
        $postTravail = PostTravail::first();
        if($produits && $postTravail){
            foreach($produits as $k=>$val){
                PostProduit::firstOrCreate([
                    "post_travail_id"=>$postTravail->id,
                    "produit_id"=>$val->id,
                    "prix"=>'1',
                    "quantite"=>100
                ]);
            }
        }
        
    }
}
