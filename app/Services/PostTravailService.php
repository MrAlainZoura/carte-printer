<?php

namespace App\Services;

use App\Models\PostTravail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostTravailService
{
    public function initialSetupAvailable(): bool
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        return ! PostTravail::query()->exists();
    }

    public function storeInitialSetup(Request $request): array
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);
        abort_if(PostTravail::query()->exists(), 409, 'Un poste de travail existe déjà.');

        $validated = $request->validate([
            'libele' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contact1' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'pays' => ['nullable', 'string', 'max:255'],
            'use_cdf' => ['required', 'boolean'],
        ]);

        $postTravail = DB::transaction(function () use ($validated) {
            $post = PostTravail::create([...$validated, 'actif' => true]);
            Auth::user()->postTravails()->attach($post->id, ['is_default' => true]);

            return $post;
        });

        return ['message' => 'Premier poste de travail créé avec succès.', 'data' => $postTravail];
    }

    public function index(Request $request): array
    {
        $query = PostTravail::withCount('users');
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->where('libele', 'like', "%{$search}%")
                ->orWhere('ville', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%"));
        }
        if ($request->filled('actif') && in_array($request->input('actif'), ['0', '1'], true)) {
            $query->where('actif', $request->input('actif'));
        }

        return ['message' => 'Liste des postes chargée.', 'data' => [
            'postTravails' => $query->latest()->paginate(15)->withQueryString(),
        ]];
    }

    public function formData(?int $id = null): array
    {
        $record = $id === null ? new PostTravail() : PostTravail::with('users')->findOrFail($id);

        return ['message' => 'Formulaire poste de travail chargé.', 'data' => [
            'record' => $record,
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
        ]];
    }

    public function store(Request $request): array
    {
        $validated = $request->validate($this->rules());
        $userIds = $validated['user_ids'] ?? [];
        unset($validated['user_ids']);

        $postTravail = DB::transaction(function () use ($validated, $userIds) {
            $post = PostTravail::create($validated);
            $post->users()->sync($userIds);

            return $post;
        });

        return ['message' => 'Poste de travail créé avec succès.', 'data' => $postTravail];
    }

    public function show(int $id): array
    {
        $record = PostTravail::with('users')->findOrFail($id);

        return ['message' => 'Poste de travail chargé.', 'data' => compact('record')];
    }

    public function update(Request $request, int $id): array
    {
        $record = PostTravail::findOrFail($id);
        $validated = $request->validate($this->rules());
        $userIds = $validated['user_ids'] ?? [];
        unset($validated['user_ids']);

        DB::transaction(function () use ($record, $validated, $userIds) {
            $record->update($validated);
            $record->users()->sync($userIds);
        });

        return ['message' => 'Poste de travail modifié avec succès.', 'data' => $record];
    }

    public function delete(int $id): array
    {
        $postTravail = PostTravail::findOrFail($id);
        if ($postTravail->carteRoses()->exists()) {
            return [
                'message' => 'Ce poste ne peut pas être supprimé tant que des demandes de carte y sont associées.',
                'data' => null,
                'deleted' => false,
            ];
        }

        $postTravail->delete();

        return ['message' => 'Poste de travail supprimé avec succès.', 'data' => null, 'deleted' => true];
    }

    private function rules(): array
    {
        return [
            'libele' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contact1' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'cpostal' => ['nullable', 'string', 'max:255'],
            'pays' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'avenue' => ['nullable', 'string', 'max:255'],
            'autres' => ['nullable', 'string'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'use_cdf' => ['required', 'boolean'],
            'actif' => ['required', 'boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'distinct', 'exists:users,id'],
        ];
    }
}
