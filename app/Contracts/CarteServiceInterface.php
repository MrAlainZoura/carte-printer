<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CarteServiceInterface
{
    public static function print(int $id): array;
    public function edit(int $vehiculeId, int $carteRoseId): array;
    public function update(Request $request, int $vehiculeId, int $carteRoseId): array;
    static public function genereQR(array $contenu);
}