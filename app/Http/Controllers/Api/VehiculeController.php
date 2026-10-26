<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use App\Services\VehiculesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehiculeController extends ApiController
{
    public function __construct(private readonly VehiculesService $service)
    {
    }

    public function index(): JsonResponse
    {
        return $this->respond(VehiculesService::all());
    }

    public function create(): JsonResponse
    {
        return $this->respond($this->service->create());
    }

    public function store(Request $request): JsonResponse
    {
        return $this->respond($this->service->store($request), 201);
    }

    public function show(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->show($id));
    }

    public function edit(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->edit($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->update($id, $request));
    }

    public function transferOwnerForm(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->transferOwnerForm($id));
    }

    public function transferOwner(Request $request, int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->transferOwner($id, $request));
    }

    public function ownerHistory(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->ownerHistory($id));
    }

    public function changePlaqueForm(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->changePlaqueForm($id));
    }

    public function changePlaque(Request $request, int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->changePlaque($id, $request));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->assertVisible($id);

        return $this->respond($this->service->delete($id));
    }

    private function assertVisible(int $id): void
    {
        if ($postId = CurrentPostTravail::id()) {
            abort_unless(
                Vehicule::query()->whereKey($id)->where('post_travail_id', $postId)->exists(),
                404
            );

            return;
        }

        abort_unless(CurrentPostTravail::isGlobal() && auth()->user()?->hasRole('admin'), 403);
    }
}
