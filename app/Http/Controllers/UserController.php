<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthServiceWeb;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected AuthServiceWeb $service;

    public function __construct(AuthServiceWeb  $service)
    {
        $this->service = $service;
    }

    public function register(Request $request)
    {
        return $this->service->register($request);
    }

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    public function logout()
    {
        return $this->service->logout();
    }

   

    public function me()
    {
        return $this->service->me();
    }

    public function all()
    {
        return $this->service->all();
    }

    public function dashboard()
    {
        return [];
    }
}