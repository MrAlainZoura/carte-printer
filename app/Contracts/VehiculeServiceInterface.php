<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface VehiculeServiceInterface
{
    public function create(): array;
    public function store(Request $data): array;
    public static function show(int $id): array;
    public static function all(): array;
    public function delete(int $id): array;
    public function edit(int $id): array;
    public function update(int $id, Request $data): array;
}