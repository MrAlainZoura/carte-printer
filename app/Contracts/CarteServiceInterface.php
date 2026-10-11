<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CarteServiceInterface
{
    static public function print(int $id);
    static public function genereQR(array $contenu);
}