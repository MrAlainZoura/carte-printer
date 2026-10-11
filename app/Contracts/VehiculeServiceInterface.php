<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface VehiculeServiceInterface
{
    public function create();
    public function store(Request $data);
    static public function show(int $id);
    static public function all();
    public function delete(int $id);
    public function edit(int $id);
    public function update(int $id, Request $data);
}