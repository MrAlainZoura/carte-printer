<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->withCount(['permissions', 'users'])
            ->with('permissions')
            ->orderBy('name')
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.form', [
            'title' => 'Créer un rôle',
            'role' => new Role(),
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissions' => old('permissions', []),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:125', 'regex:/^[a-z0-9_\-]+$/', Rule::unique('roles', 'name')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.regex' => 'Le nom du rôle doit être en minuscules, sans espaces (ex. chef_poste).',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return to_route('roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        return view('roles.show', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function edit(Role $role)
    {
        return view('roles.form', [
            'title' => 'Modifier le rôle',
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissions' => old('permissions', $role->permissions->pluck('name')->all()),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:125',
                'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.regex' => 'Le nom du rôle doit être en minuscules, sans espaces (ex. chef_poste).',
        ]);

        if ($role->name === 'admin' && $validated['name'] !== 'admin') {
            return back()->withErrors(['name' => 'Le rôle administrateur ne peut pas être renommé.'])->withInput();
        }

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return to_route('roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Le rôle administrateur ne peut pas être supprimé.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un rôle encore attribué à des utilisateurs.');
        }

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return to_route('roles.index')->with('success', 'Rôle supprimé avec succès.');
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Permissions du rôle mises à jour.');
    }

    private function permissionGroups(): array
    {
        $labels = [
            'vehicule' => 'Véhicules',
            'carte_rose' => 'Cartes roses',
            'plaque' => 'Plaques',
            'post_travail' => 'Postes de travail',
            'proprietaire' => 'Propriétaires',
            'produit' => 'Produits',
            'post_produit' => 'Produits des postes',
            'approvisionnement' => 'Approvisionnements',
            'user' => 'Utilisateurs',
            'role' => 'Rôles & permissions',
            'dashboard' => 'Tableau de bord',
        ];

        $actionLabels = [
            'view' => 'Lire',
            'create' => 'Créer',
            'update' => 'Modifier',
            'delete' => 'Supprimer',
            'manage' => 'Gérer (modifier / supprimer)',
            'print' => 'Imprimer',
            'cancel' => 'Annuler',
            'assign' => 'Attribuer',
            'confirm' => 'Confirmer la réception',
            'global' => 'Vue globale',
        ];

        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => explode('.', $permission->name)[0] ?? 'autres')
            ->map(function ($permissions, $group) use ($labels, $actionLabels) {
                return [
                    'key' => $group,
                    'label' => $labels[$group] ?? ucfirst(str_replace('_', ' ', $group)),
                    'permissions' => $permissions->map(function (Permission $permission) use ($actionLabels) {
                        $action = explode('.', $permission->name)[1] ?? $permission->name;

                        return [
                            'name' => $permission->name,
                            'label' => $actionLabels[$action] ?? ucfirst(str_replace('_', ' ', $action)),
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }
}
