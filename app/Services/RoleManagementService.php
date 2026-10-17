<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleManagementService
{
    public function index(): array
    {
        $roles = Role::query()->withCount(['permissions', 'users'])->with('permissions')->orderBy('name')->get();

        return ['message' => 'Liste des rôles chargée.', 'data' => compact('roles')];
    }

    public function formData(?Role $role = null, bool $editing = false): array
    {
        $role ??= new Role();

        return [
            'message' => 'Formulaire rôle chargé.',
            'data' => [
                'role' => $role,
                'title' => $editing ? 'Modifier le rôle' : 'Créer un rôle',
                'permissionGroups' => $this->permissionGroups(),
                'selectedPermissions' => old('permissions', $editing ? $role->permissions->pluck('name')->all() : []),
            ],
        ];
    }

    public function store(Request $request): array
    {
        $validated = $request->validate($this->rules());
        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return ['message' => 'Rôle créé avec succès.', 'data' => $role];
    }

    public function show(Role $role): array
    {
        $role->load(['permissions', 'users']);

        return ['message' => 'Rôle chargé.', 'data' => [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
        ]];
    }

    public function update(Request $request, Role $role): array
    {
        $validated = $request->validate($this->rules($role));
        if ($role->name === 'admin' && $validated['name'] !== 'admin') {
            return [
                'message' => 'Le rôle administrateur ne peut pas être renommé.',
                'data' => null,
                'updated' => false,
            ];
        }

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return ['message' => 'Rôle mis à jour avec succès.', 'data' => $role, 'updated' => true];
    }

    public function delete(Role $role): array
    {
        if ($role->name === 'admin') {
            return ['message' => 'Le rôle administrateur ne peut pas être supprimé.', 'data' => null, 'deleted' => false];
        }
        if ($role->users()->count() > 0) {
            return ['message' => 'Impossible de supprimer un rôle encore attribué à des utilisateurs.', 'data' => null, 'deleted' => false];
        }

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return ['message' => 'Rôle supprimé avec succès.', 'data' => null, 'deleted' => true];
    }

    public function syncPermissions(Request $request, Role $role): array
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);
        $role->syncPermissions($validated['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return ['message' => 'Permissions du rôle mises à jour.', 'data' => $role];
    }

    private function rules(?Role $role = null): array
    {
        $nameRule = Rule::unique('roles', 'name');
        if ($role) {
            $nameRule->ignore($role->id);
        }

        return [
            'name' => [
                'required',
                'string',
                'max:125',
                'regex:/^[a-z0-9_\-]+$/',
                $nameRule,
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    private function permissionGroups(): array
    {
        $labels = [
            'vehicule' => 'Véhicules', 'carte_rose' => 'Cartes roses', 'plaque' => 'Plaques',
            'post_travail' => 'Postes de travail', 'proprietaire' => 'Propriétaires',
            'produit' => 'Produits', 'post_produit' => 'Produits des postes',
            'approvisionnement' => 'Approvisionnements', 'user' => 'Utilisateurs',
            'role' => 'Rôles & permissions', 'dashboard' => 'Tableau de bord',
        ];
        $actionLabels = [
            'view' => 'Lire', 'create' => 'Créer', 'update' => 'Modifier', 'delete' => 'Supprimer',
            'manage' => 'Gérer (modifier / supprimer)', 'print' => 'Imprimer', 'cancel' => 'Annuler',
            'assign' => 'Attribuer', 'confirm' => 'Confirmer la réception', 'global' => 'Vue globale',
            'transfer' => 'Transférer',
        ];

        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => explode('.', $permission->name)[0] ?? 'autres')
            ->map(fn ($permissions, $group) => [
                'key' => $group,
                'label' => $labels[$group] ?? ucfirst(str_replace('_', ' ', $group)),
                'permissions' => $permissions->map(function (Permission $permission) use ($actionLabels) {
                    $action = explode('.', $permission->name)[1] ?? $permission->name;

                    return [
                        'name' => $permission->name,
                        'label' => $actionLabels[$action] ?? ucfirst(str_replace('_', ' ', $action)),
                    ];
                })->values()->all(),
            ])
            ->values()
            ->all();
    }
}
