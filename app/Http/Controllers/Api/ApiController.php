<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function respond(array $result, int $status = 200): JsonResponse
    {
        return response()->json($result, $status);
    }
}
