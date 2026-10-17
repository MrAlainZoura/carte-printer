<?php

namespace App\Http\Controllers;

use App\Services\ProprietaireWebService;
use Illuminate\Http\Request;

class PropietaireController extends Controller
{
    public function __construct(private readonly ProprietaireWebService $service)
    {
    }

    public function index(Request $request)
    {
        $result = $this->service->index($request);

        return view('proprietaires.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return $this->form($result['data']['record'], 'Créer un propriétaire');
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('proprietaires.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('proprietaires.show', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id);

        return $this->form($result['data']['record'], 'Modifier le propriétaire');
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('proprietaires.index')->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);
        $flash = $result['deleted'] ? 'success' : 'error';

        return to_route('proprietaires.index')->with($flash, $result['message']);
    }

    private function form($record, string $title)
    {
        return view('proprietaires.form', compact('title', 'record'));
    }
}
