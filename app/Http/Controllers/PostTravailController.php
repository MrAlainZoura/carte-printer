<?php

namespace App\Http\Controllers;

use App\Services\PostTravailService;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;

class PostTravailController extends Controller
{
    public function __construct(private readonly PostTravailService $service)
    {
    }

    public function initialSetup()
    {
        if (! $this->service->initialSetupAvailable()) {
            return redirect()->route('postes.select');
        }

        return view('post-travails.initial-setup');
    }

    public function storeInitialSetup(Request $request)
    {
        if (! $this->service->initialSetupAvailable()) {
            return redirect()->route('postes.select');
        }

        $result = $this->service->storeInitialSetup($request);
        CurrentPostTravail::set($result['data']->id);

        return redirect()->route('dashboard')->with('success', $result['message']);
    }

    public function index(Request $request)
    {
        $result = $this->service->index($request);

        return view('post-travails.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return $this->form($result['data']['record'], $result['data']['users'], 'Créer un poste de travail');
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('post-travails.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('post-travails.show', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id);

        return $this->form($result['data']['record'], $result['data']['users'], 'Modifier le poste de travail');
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('post-travails.index')->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);
        $flash = $result['deleted'] ? 'success' : 'error';

        return to_route('post-travails.index')->with($flash, $result['message']);
    }

    private function form($record, $users, string $title)
    {
        return view('post-travails.form', compact('title', 'record', 'users'));
    }
}
