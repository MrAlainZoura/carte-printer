<?php

namespace App\Services;

use App\Models\PostTravail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostSelectionService
{
    public function options(): array
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $posts = $isAdmin
            ? PostTravail::query()->orderBy('libele')->get()
            : $user->postTravails()->orderBy('libele')->get();

        return [
            'message' => 'Postes de travail disponibles chargés.',
            'data' => [
                'posts' => $posts,
                'isAdmin' => $isAdmin,
                'needsInitialSetup' => $isAdmin && $posts->isEmpty(),
            ],
        ];
    }

    public function select(Request $request): array
    {
        /** @var User $user */
        $user = Auth::user();
        $allowedIds = $user->hasRole('admin')
            ? PostTravail::query()->pluck('id')->all()
            : $user->postTravails()->pluck('post_travails.id')->all();
        $validated = $request->validate([
            'post_travail_id' => ['required', 'integer', Rule::in($allowedIds)],
        ]);
        $postId = (int) $validated['post_travail_id'];

        if ($user->postTravails()->where('post_travails.id', $postId)->exists()) {
            foreach ($user->postTravails as $post) {
                $user->postTravails()->updateExistingPivot($post->id, [
                    'is_default' => $post->id === $postId,
                ]);
            }
        }

        return ['message' => 'Poste de travail sélectionné.', 'data' => ['post_id' => $postId]];
    }

    public function openPost(int $id): array
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);
        $post = PostTravail::query()->findOrFail($id);

        return ['message' => 'Poste de travail sélectionné.', 'data' => ['post_id' => $post->id]];
    }

    public function authorizeGlobalDashboard(): array
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        return ['message' => 'Vue globale sélectionnée.', 'data' => null];
    }
}
