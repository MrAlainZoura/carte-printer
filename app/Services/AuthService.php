<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Traits\RespondWithTokenTrait;
use App\Enums\AuthMessage;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    use RespondWithTokenTrait;

    public function login(array $credentials)
    {
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => AuthMessage::Unauthorized->value], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => AuthMessage::LogoutSuccess->value]);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
