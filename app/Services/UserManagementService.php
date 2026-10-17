<?php

namespace App\Services;

use App\Models\CarteRose;
use App\Models\PostTravail;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementService
{
    public function index(Request $request): array
    {
        $query = $this->visibleUsersQuery()->with(['roles', 'postTravails']);
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('tel', 'like', "%{$search}%"));
        }

        return ['message' => 'Liste des utilisateurs chargée.', 'data' => [
            'users' => $query->latest()->paginate(15)->withQueryString(),
        ]];
    }

    public function formData(?int $id = null, string $title = ''): array
    {
        $record = $id === null
            ? new User()
            : $this->visibleUsersQuery()->with(['postTravails', 'roles'])->findOrFail($id);

        $roles = User::assignableRoleLabels();
        if ($record->hasRole('admin') || auth()->user()?->hasRole('admin')) {
            $roles = ['admin' => 'Administrateur'] + $roles;
        }

        return ['message' => 'Formulaire utilisateur chargé.', 'data' => [
            'title' => $title,
            'record' => $record,
            'posts' => PostTravail::query()->where('actif', true)->orderBy('libele')->get(),
            'roles' => $roles,
        ]];
    }

    public function store(Request $request): array
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

        return ['message' => 'Utilisateur créé avec succès.', 'data' => $user];
    }

    public function show(int $id): array
    {
        $record = $this->visibleUsersQuery()->with(['postTravails', 'roles'])->findOrFail($id);

        return ['message' => 'Utilisateur chargé.', 'data' => [
            'record' => $record,
            'stats' => $this->statsFor($record),
        ]];
    }

    public function profile(User $user): array
    {
        $record = $user->load(['postTravails', 'roles']);

        return ['message' => 'Profil chargé.', 'data' => [
            'record' => $record,
            'stats' => $this->statsFor($record),
        ]];
    }

    public function update(Request $request, int $id): array
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

        return ['message' => 'Utilisateur modifié avec succès.', 'data' => $record];
    }

    public function updatePassword(Request $request): array
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return ['message' => 'Votre mot de passe a été modifié.', 'data' => null];
    }

    public function resetPassword(int $id): array
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
        $temporaryPassword = Str::password(16);
        $user = $this->visibleUsersQuery()->findOrFail($id);
        $user->update(['password' => Hash::make($temporaryPassword)]);

        return [
            'message' => 'Mot de passe réinitialisé. Communiquez ce mot de passe temporaire à l’utilisateur.',
            'data' => ['user' => $user, 'temporary_password' => $temporaryPassword],
        ];
    }

    public function delete(int $id): array
    {
        $this->visibleUsersQuery()->findOrFail($id)->delete();

        return ['message' => 'Utilisateur supprimé avec succès.', 'data' => null];
    }

    private function statsFor(User $user): array
    {
        $cards = CarteRose::query()->where('user_id', $user->id);

        return [
            'Cartes créées' => (clone $cards)->count(),
            'Cartes actives' => (clone $cards)->where('statut', 'active')->count(),
            'Véhicules concernés' => (clone $cards)->distinct('vehicule_id')->count('vehicule_id'),
        ];
    }

    private function rules(?User $record = null, bool $manageAccess = true): array
    {
        $emailRule = Rule::unique('users', 'email');
        $telRule = Rule::unique('users', 'tel');
        if ($record) {
            $emailRule->ignore($record->id);
            $telRule->ignore($record->id);
        }
        $assignableRoles = Role::query()->where('name', '!=', 'admin')->pluck('name')->all();
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
            $rules['post_travail_id'] = ['required', 'integer', 'exists:post_travails,id'];
            $rules['role'] = ['required', 'string', Rule::in($assignableRoles)];
        }

        return $rules;
    }

    private function visibleUsersQuery(): Builder
    {
        return User::query()->where(fn ($query) => $query
            ->where('id', '!=', 1)
            ->orWhere('id', auth()->id()));
    }
}
