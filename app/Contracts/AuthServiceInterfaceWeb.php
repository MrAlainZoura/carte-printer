<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface AuthServiceInterfaceWeb
{
    static public function login(Request $credentials);
    static public function logout();
    static public function me();
    static public function register(Request $request);
    static public function all();
}