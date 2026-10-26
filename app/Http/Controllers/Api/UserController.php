<?php

namespace App\Http\Controllers\Api;

use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct(private readonly UserManagementService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->respond($this->service->index($request));
    }

    public function create(): JsonResponse
    {
        return $this->respond($this->service->formData(title: 'Créer un utilisateur'));
    }

    public function store(Request $request): JsonResponse
    {
        return $this->respond($this->service->store($request), 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->respond($this->service->show($id));
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->respond($this->service->profile($request->user()));
    }

    public function edit(int $id): JsonResponse
    {
        return $this->respond($this->service->formData($id, 'Modifier l’utilisateur'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->respond($this->service->update($request, $id));
    }

    public function updatePassword(Request $request): JsonResponse
    {
        return $this->respond($this->service->updatePassword($request));
    }

    public function resetPassword(int $id): JsonResponse
    {
        return $this->respond($this->service->resetPassword($id));
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->respond($this->service->delete($id));
    }
}
