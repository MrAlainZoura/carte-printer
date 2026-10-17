<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface AuthServiceInterfaceWeb
{
    public static function login(Request $credentials): array;
    public static function logout(): array;
    public static function me(): array;
    public static function register(Request $request): array;
    public static function all(): array;
}