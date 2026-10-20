<?php

namespace App\Http\Controllers\Api;

use App\Models\Plaque;
use App\Services\PlaqueService;
use App\Support\CurrentPostTravail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaqueController extends ApiController
{
    public function __construct(private readonly PlaqueService $service)
    {
    }

    public function show(Plaque $plaque): JsonResponse
    {
        if ($postId = CurrentPostTravail::id()) {
            abort_unless((int) $plaque->post_travail_id === $postId, 404);
        } else {
            abort_unless(CurrentPostTravail::isGlobal() && auth()->user()?->hasRole('admin'), 403);
        }

        return $this->respond($this->service->show($plaque));
    }

    public function edit(int $vehiculeId, int $plaqueId): JsonResponse
    {
        return $this->respond($this->service->edit($vehiculeId, $plaqueId));
    }

    public function update(Request $request, int $vehiculeId, int $plaqueId): JsonResponse
    {
        return $this->respond($this->service->update($request, $vehiculeId, $plaqueId));
    }
}
