<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $service)
    {
    }

    public function __invoke()
    {
        $result = $this->service->data();

        return view('dashboard.index', $result['data']);
    }
}
