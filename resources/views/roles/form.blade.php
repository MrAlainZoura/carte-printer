@extends('layouts.dashboard')
@section('title', $title)
@section('breadcrumb', $title)
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6">
        <p class="mb-1 text-sm text-gray-500">
            <a href="{{ route('roles.index') }}" class="text-blue-700 hover:underline">Rôles</a> / {{ $title }}
        </p>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500">Cochez les permissions à attribuer ou à retirer pour ce rôle.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $role->exists ? route('roles.update', $role) : route('roles.store') }}" class="space-y-6">
        @csrf
        @if($role->exists) @method('PUT') @endif

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nom du rôle *</label>
            <input id="name" name="name" required
                   value="{{ old('name', $role->name) }}"
                   @disabled($role->exists && $role->name === 'admin')
                   placeholder="ex. agent, chef_poste"
                   class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm @if($role->exists && $role->name === 'admin') opacity-70 @endif">
            @if($role->exists && $role->name === 'admin')
                <input type="hidden" name="name" value="admin">
                <p class="mt-2 text-xs text-gray-500">Le rôle administrateur ne peut pas être renommé.</p>
            @endif
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Permissions</h2>
                    <p class="mt-1 text-sm text-gray-500">Ajoutez ou retirez des droits pour ce rôle.</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="select-all" class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Tout cocher</button>
                    <button type="button" id="clear-all" class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Tout décocher</button>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($permissionGroups as $group)
                    <div class="rounded-lg border border-gray-100 p-4">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h3 class="font-semibold text-gray-900">{{ $group['label'] }}</h3>
                            <button type="button" data-group-toggle="{{ $group['key'] }}" class="text-xs font-semibold text-blue-700 hover:underline">Basculer le groupe</button>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($group['permissions'] as $permission)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission['name'] }}"
                                           data-group="{{ $group['key'] }}"
                                           class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-blue-600"
                                           @checked(in_array($permission['name'], $selectedPermissions, true))>
                                    <span>
                                        <span class="font-medium text-gray-900">{{ $permission['label'] }}</span>
                                        <span class="block text-xs text-gray-500">{{ $permission['name'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">Annuler</a>
            <button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                {{ $role->exists ? 'Enregistrer' : 'Créer le rôle' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const boxes = () => [...document.querySelectorAll('.permission-checkbox')];
    document.getElementById('select-all')?.addEventListener('click', () => boxes().forEach(b => b.checked = true));
    document.getElementById('clear-all')?.addEventListener('click', () => boxes().forEach(b => b.checked = false));
    document.querySelectorAll('[data-group-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.getAttribute('data-group-toggle');
            const groupBoxes = boxes().filter(b => b.dataset.group === group);
            const allChecked = groupBoxes.every(b => b.checked);
            groupBoxes.forEach(b => b.checked = !allChecked);
        });
    });
});
</script>
@endsection
