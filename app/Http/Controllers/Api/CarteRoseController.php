<?php

namespace App\Http\Controllers\Api;

use App\Services\CarteService;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CarteRoseController extends ApiController
{
    public function __construct(private readonly CarteService $service)
    {
    }

    public function edit(int $vehiculeId, int $carteRoseId): JsonResponse
    {
        return $this->respond($this->service->edit($vehiculeId, $carteRoseId));
    }

    public function update(Request $request, int $vehiculeId, int $carteRoseId): JsonResponse
    {
        return $this->respond($this->service->update($request, $vehiculeId, $carteRoseId));
    }

    public function print(int $id): Response
    {
        if ($postId = CurrentPostTravail::id()) {
            abort_unless(
                Vehicule::query()->whereKey($id)->where('post_travail_id', $postId)->exists(),
                404
            );
        } else {
            abort_unless(CurrentPostTravail::isGlobal() && auth()->user()?->hasRole('admin'), 403);
        }

        $result = CarteService::print($id);
        if ($result['data'] === null) {
            return response()->json($result, 404);
        }

        return response($result['data'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="carte.pdf"',
        ]);
    }
}
