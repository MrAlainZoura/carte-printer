<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Services\ApprovisionnementService;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
{
    public function __construct(private readonly ApprovisionnementService $service)
    {
    }

    public function index()
    {
        $result = $this->service->index();

        return view('approvisionnements.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return view('approvisionnements.form', $result['data']);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('approvisionnements.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('approvisionnements.show', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id);

        return view('approvisionnements.form', $result['data']);
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('approvisionnements.show', $result['data'])->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);

        return to_route('approvisionnements.index')->with('success', $result['message']);
    }

    public function confirm(int $id, Request $request)
    {
        $result = $this->service->confirm($id, $request);

        return to_route('approvisionnements.show', $id)->with('success', $result['message']);
    }
}
