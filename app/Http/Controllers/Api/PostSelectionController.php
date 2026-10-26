<?php

namespace App\Http\Controllers\Api;

use App\Services\PostSelectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostSelectionController extends ApiController
{
    public function __construct(private readonly PostSelectionService $service)
    {
    }

    public function show(): JsonResponse
    {
        return $this->respond($this->service->options());
    }

    public function store(Request $request): JsonResponse
    {
        return $this->respond($this->service->select($request));
    }

    public function globalDashboard(): JsonResponse
    {
        return $this->respond($this->service->authorizeGlobalDashboard());
    }

    public function openPost(int $id): JsonResponse
    {
        return $this->respond($this->service->openPost($id));
    }
}
