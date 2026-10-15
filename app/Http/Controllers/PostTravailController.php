<?php

namespace App\Http\Controllers;

use App\Models\PostTravail;
use App\Models\User;
use App\Support\CurrentPostTravail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostTravailController extends Controller
{
    public function initialSetup()
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        if (PostTravail::query()->exists()) {
            return redirect()->route('postes.select');
        }

        return view('post-travails.initial-setup');
    }

    public function storeInitialSetup(Request $request)
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        if (PostTravail::query()->exists()) {
            return redirect()->route('postes.select');
        }

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
            $postTravail = PostTravail::create([
                ...$validated,
                'actif' => true,
            ]);

            Auth::user()->postTravails()->attach($postTravail->id, ['is_default' => true]);

            return $postTravail;
        });

        CurrentPostTravail::set($postTravail->id);

        return redirect()->route('dashboard')
            ->with('success', 'Premier poste de travail créé avec succès.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

        return view('post-travails.index', [
            'postTravails' => $query->latest()->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->form(new PostTravail(), 'Créer un poste de travail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $userIds = $validated['user_ids'] ?? [];
        unset($validated['user_ids']);

        $postTravail = PostTravail::create($validated);
        $postTravail->users()->sync($userIds);

        return to_route('post-travails.index')->with('success', 'Poste de travail créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $record = PostTravail::with('users')->findOrFail($id);

        return view('post-travails.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return $this->form(PostTravail::with('users')->findOrFail($id), 'Modifier le poste de travail');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $record = PostTravail::findOrFail($id);
        $validated = $request->validate($this->rules());
        $userIds = $validated['user_ids'] ?? [];
        unset($validated['user_ids']);

        $record->update($validated);
        $record->users()->sync($userIds);

        return to_route('post-travails.index')->with('success', 'Poste de travail modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $postTravail = PostTravail::findOrFail($id);
        if ($postTravail->carteRoses()->exists()) {
            return to_route('post-travails.index')
                ->with('error', 'Ce poste ne peut pas être supprimé tant que des demandes de carte y sont associées.');
        }

        $postTravail->delete();

        return to_route('post-travails.index')->with('success', 'Poste de travail supprimé avec succès.');
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

    private function form(PostTravail $record, string $title)
    {
        return view('post-travails.form', [
            'title' => $title,
            'record' => $record,
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }
}
