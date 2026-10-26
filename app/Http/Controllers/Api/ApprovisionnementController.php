<?php

namespace App\Http\Controllers\Api;

use App\Services\ApprovisionnementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovisionnementController extends ApiController
{
    public function __construct(private readonly ApprovisionnementService $service)
    {
    }

    public function index(): JsonResponse
    {
        return $this->respond($this->service->index());
    }

    public function create(): JsonResponse
    {
        return $this->respond($this->service->formData());
    }

    public function store(Request $request): JsonResponse
    {
        return $this->respond($this->service->store($request), 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->respond($this->service->show($id));
    }

    public function edit(int $id): JsonResponse
    {
        return $this->respond($this->service->formData($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->respond($this->service->update($request, $id));
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->respond($this->service->delete($id));
    }

    public function confirm(Request $request, int $id): JsonResponse
    {
        return $this->respond($this->service->confirm($id, $request));
    }
}
