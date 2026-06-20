<?php

namespace App\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;

trait RespondWithTokenTrait
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
