<?php

namespace App\Http\Controllers\Api;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    public function __construct(private readonly DashboardService $service)
    {
    }

    public function __invoke(): JsonResponse
    {
        return $this->respond($this->service->data());
    }
}
