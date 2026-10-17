<?php

namespace App\Http\Controllers;

use App\Services\RoleManagementService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(private readonly RoleManagementService $service)
    {
    }

    public function index()
    {
        $result = $this->service->index();

        return view('roles.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return view('roles.form', $result['data']);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('roles.index')->with('success', $result['message']);
    }

    public function show(Role $role)
    {
        $result = $this->service->show($role);

        return view('roles.show', $result['data']);
    }

    public function edit(Role $role)
    {
        $result = $this->service->formData($role, true);

        return view('roles.form', $result['data']);
    }

    public function update(Request $request, Role $role)
    {
        $result = $this->service->update($request, $role);
        if (! $result['updated']) {
            return back()->withErrors(['name' => $result['message']])->withInput();
        }

        return to_route('roles.index')->with('success', $result['message']);
    }

    public function destroy(Role $role)
    {
        $result = $this->service->delete($role);
        if (! $result['deleted']) {
            return back()->with('error', $result['message']);
        }

        return to_route('roles.index')->with('success', $result['message']);
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $result = $this->service->syncPermissions($request, $role);

        return back()->with('success', $result['message']);
    }
}
