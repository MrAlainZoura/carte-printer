<?php

namespace App\Http\Controllers\Api;

use App\Services\RoleManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends ApiController
{
    public function __construct(private readonly RoleManagementService $service)
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

    public function show(Role $role): JsonResponse
    {
        return $this->respond($this->service->show($role));
    }

    public function edit(Role $role): JsonResponse
    {
        return $this->respond($this->service->formData($role, true));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        return $this->respond($this->service->update($request, $role));
    }

    public function destroy(Role $role): JsonResponse
    {
        return $this->respond($this->service->delete($role));
    }

    public function syncPermissions(Request $request, Role $role): JsonResponse
    {
        return $this->respond($this->service->syncPermissions($request, $role));
    }
}
