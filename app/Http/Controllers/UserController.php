<?php

namespace App\Http\Controllers;

use App\Services\AuthServiceWeb;
use App\Services\UserManagementService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected AuthServiceWeb $authService;
    protected UserManagementService $service;

    public function __construct(AuthServiceWeb $authService, UserManagementService $service)
    {
        $this->authService = $authService;
        $this->service = $service;
    }

    public function register(Request $request)
    {
        $result = $this->authService->register($request);

        if (! $result['data']) {
            return to_route('login')->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function login(Request $request)
    {
        $result = $this->authService->login($request);

        if (! $result['data']) {
            return back()->withInput($request->only('identifiant'))->with('echec', $result['message']);
        }

        return to_route('postes.select')->with('success', $result['message']);
    }

    public function logout()
    {
        $result = $this->authService->logout();

        return redirect('/')->with('success', $result['message']);
    }

    public function me()
    {
        return $this->authService->me()['data'];
    }

    public function all()
    {
        return $this->authService->all()['data'];
    }

    public function dashboard()
    {
        return [];
    }

    public function index(Request $request)
    {
        $result = $this->service->index($request);

        return view('users.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData(title: 'Créer un utilisateur');

        return view('users.form', $result['data']);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('users.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('users.show', $result['data']);
    }

    public function profile()
    {
        $result = $this->service->profile(auth()->user());

        return view('users.profile', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id, 'Modifier l’utilisateur');

        return view('users.form', $result['data']);
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('users.index')->with('success', $result['message']);
    }

    public function editPassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $result = $this->service->updatePassword($request);

        return to_route('users.profile')->with('success', $result['message']);
    }

    public function resetPassword(int $id)
    {
        $result = $this->service->resetPassword($id);

        return to_route('users.show', $id)
            ->with('success', $result['message'])
            ->with('temporary_password', $result['data']['temporary_password']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);

        return to_route('users.index')->with('success', $result['message']);
    }
}
