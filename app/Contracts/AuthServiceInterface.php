<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function login(array $credentials);
    public function logout();
    public function refresh();
    public function me();
}