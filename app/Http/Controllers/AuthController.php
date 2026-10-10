<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        //$user = User::first();
        //return event(new Registered($user));
        //return $user->sendEmailVerificationNotification();
        return $this->authService->register($request);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request->only('identifiant', 'password'));
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function refresh()
    {
        return $this->authService->refresh();
    }

    public function me()
    {
        return $this->authService->me();
    }
}