<?php

namespace App\Http\Controllers;

use App\Models\Plaque;
use App\Services\PlaqueService;
use Illuminate\Http\Request;

class PlaqueController extends Controller
{
    public function __construct(private readonly PlaqueService $service)
    {
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Plaque $plaque)
    {
        $result = $this->service->show($plaque);

        return redirect()->route('vehicules.show', $result['data']['vehicule_id']);
    }

    public function edit(int $vehiculeId, int $plaqueId)
    {
        $result = $this->service->edit($vehiculeId, $plaqueId);

        return view('plaques.form', $result['data']);
    }

    public function update(Request $request, int $vehiculeId, int $plaqueId)
    {
        $result = $this->service->update($request, $vehiculeId, $plaqueId);

        return to_route('vehicules.show', $result['data']['vehicule'])
            ->with('success', $result['message']);
    }

    public function destroy(Plaque $plaque)
    {
        //
    }
}
