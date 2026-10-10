<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface ProprietaireServiceInterface
{
    public function create();
    static public function store(Request $data);
    public function show(int $id);
    static public function index();
    public function delete(int $id);
    public function edit(int $id);
    public function update(int $id, Request $data);
}