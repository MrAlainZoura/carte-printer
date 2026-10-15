<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthServiceWeb;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected AuthServiceWeb $service;

    public function __construct(AuthServiceWeb  $service)
    {
        $this->service = $service;
    }

    public function register(Request $request)
    {
        return $this->service->register($request);
    }

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    public function logout()
    {
        return $this->service->logout();
    }

   

    public function me()
    {
        return $this->service->me();
    }

    public function all()
    {
        return $this->service->all();
    }

    public function dashboard()
    {
        return [];
    }

    public function index(Request $request)
    {
        $query = User::query();
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('tel', 'like', "%{$search}%"));
        }

        return view('users.index', ['users' => $query->latest()->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return $this->form(new User(), 'Créer un utilisateur');
    }

    public function store(Request $request)
    {
        User::create($request->validate($this->rules()));

        return to_route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(int $id)
    {
        $record = User::with('postTravails')->findOrFail($id);

        return view('users.show', compact('record'));
    }

    public function edit(int $id)
    {
        return $this->form(User::findOrFail($id), 'Modifier l’utilisateur');
    }

    public function update(Request $request, int $id)
    {
        $record = User::findOrFail($id);
        $validated = $request->validate($this->rules($record));
        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }
        $record->update($validated);

        return to_route('users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(int $id)
    {
        User::findOrFail($id)->delete();

        return to_route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function rules(?User $record = null): array
    {
        $emailRule = Rule::unique('users', 'email');
        $telRule = Rule::unique('users', 'tel');

        if ($record) {
            $emailRule->ignore($record->id);
            $telRule->ignore($record->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', $emailRule],
            'tel' => ['nullable', 'string', 'max:255', $telRule],
            'password' => [$record ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    private function form(User $record, string $title)
    {
        return view('users.form', [
            'title' => $title,
            'record' => $record,
        ]);
    }
}