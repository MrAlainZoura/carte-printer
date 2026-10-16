<?php

namespace App\Http\Controllers;

use App\Models\CarteRose;
use App\Models\PostTravail;
use App\Models\User;
use App\Services\AuthServiceWeb;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected AuthServiceWeb $service;

    public function __construct(AuthServiceWeb $service)
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
        $query = $this->visibleUsersQuery()->with(['roles', 'postTravails']);
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
        $validated = $request->validate($this->rules());

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'tel' => $validated['tel'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);
        $user->postTravails()->attach($validated['post_travail_id'], ['is_default' => true]);

        return to_route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(int $id)
    {
        $record = $this->visibleUsersQuery()->with(['postTravails', 'roles'])->findOrFail($id);
        $cards = CarteRose::query()->where('user_id', $record->id);

        return view('users.show', [
            'record' => $record,
            'stats' => [
                'Cartes créées' => (clone $cards)->count(),
                'Cartes actives' => (clone $cards)->where('statut', 'active')->count(),
                'Véhicules concernés' => (clone $cards)->distinct('vehicule_id')->count('vehicule_id'),
            ],
        ]);
    }

    public function profile()
    {
        $record = auth()->user()->load(['postTravails', 'roles']);
        $cards = CarteRose::query()->where('user_id', $record->id);

        return view('users.profile', [
            'record' => $record,
            'stats' => [
                'Cartes créées' => (clone $cards)->count(),
                'Cartes actives' => (clone $cards)->where('statut', 'active')->count(),
                'Véhicules concernés' => (clone $cards)->distinct('vehicule_id')->count('vehicule_id'),
            ],
        ]);
    }

    public function edit(int $id)
    {
        return $this->form($this->visibleUsersQuery()->with(['postTravails', 'roles'])->findOrFail($id), 'Modifier l’utilisateur');
    }

    public function update(Request $request, int $id)
    {
        $record = $this->visibleUsersQuery()->findOrFail($id);
        $isAdmin = auth()->user()?->hasRole('admin') ?? false;
        $validated = $request->validate($this->rules($record, $isAdmin));

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'tel' => $validated['tel'] ?? null,
        ];

        if (! blank($validated['password'] ?? null)) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $record->update($payload);

        if ($isAdmin) {
            $record->syncRoles([$validated['role']]);
            $record->postTravails()->sync([
                $validated['post_travail_id'] => ['is_default' => true],
            ]);
        }

        return to_route('users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function editPassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return to_route('users.profile')->with('success', 'Votre mot de passe a été modifié.');
    }

    public function resetPassword(int $id)
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        $temporaryPassword = Str::password(16);
        $this->visibleUsersQuery()->findOrFail($id)->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        return to_route('users.show', $id)
            ->with('success', 'Mot de passe réinitialisé. Communiquez ce mot de passe temporaire à l’utilisateur.')
            ->with('temporary_password', $temporaryPassword);
    }

    public function destroy(int $id)
    {
        $this->visibleUsersQuery()->findOrFail($id)->delete();

        return to_route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function rules(?User $record = null, bool $manageAccess = true): array
    {
        $emailRule = Rule::unique('users', 'email');
        $telRule = Rule::unique('users', 'tel');

        if ($record) {
            $emailRule->ignore($record->id);
            $telRule->ignore($record->id);
        }

        $assignableRoles = Role::query()
            ->where('name', '!=', 'admin')
            ->pluck('name')
            ->all();

        if ($record?->hasRole('admin') || auth()->user()?->hasRole('admin')) {
            $assignableRoles[] = 'admin';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', $emailRule],
            'tel' => ['nullable', 'string', 'max:255', $telRule],
        ];

        if (! $record || $manageAccess) {
            $rules['password'] = [$record ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'];
        }

        if (! $record || $manageAccess) {
            $rules['post_travail_id'] = ['required', 'integer', 'exists:post_travails,id'];
            $rules['role'] = ['required', 'string', Rule::in($assignableRoles)];
        }

        return $rules;
    }

    private function form(User $record, string $title)
    {
        $roles = User::assignableRoleLabels();
        if ($record->hasRole('admin') || auth()->user()?->hasRole('admin')) {
            $roles = ['admin' => 'Administrateur'] + $roles;
        }

        return view('users.form', [
            'title' => $title,
            'record' => $record,
            'posts' => PostTravail::query()->where('actif', true)->orderBy('libele')->get(),
            'roles' => $roles,
        ]);
    }

    private function visibleUsersQuery(): Builder
    {
        return User::query()->where(function ($query) {
            $query->where('id', '!=', 1)
                ->orWhere('id', auth()->id());
        });
    }
}
