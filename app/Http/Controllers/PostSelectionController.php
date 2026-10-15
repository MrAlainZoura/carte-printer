<?php

namespace App\Http\Controllers;

use App\Models\PostTravail;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostSelectionController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') && ! PostTravail::query()->exists()) {
            return redirect()->route('post-travails.initial-setup');
        }

        $posts = $user->hasRole('admin')
            ? PostTravail::query()->orderBy('libele')->get()
            : $user->postTravails()->orderBy('libele')->get();

        return view('auth.select-poste', [
            'posts' => $posts,
            'isAdmin' => $user->hasRole('admin'),
            'currentPostId' => CurrentPostTravail::id(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $allowedIds = $user->hasRole('admin')
            ? PostTravail::query()->pluck('id')->all()
            : $user->postTravails()->pluck('post_travails.id')->all();

        $validated = $request->validate([
            'post_travail_id' => ['required', 'integer', Rule::in($allowedIds)],
        ]);

        $postId = (int) $validated['post_travail_id'];
        CurrentPostTravail::set($postId);

        if ($user->postTravails()->where('post_travails.id', $postId)->exists()) {
            foreach ($user->postTravails as $post) {
                $user->postTravails()->updateExistingPivot($post->id, [
                    'is_default' => $post->id === $postId,
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    public function globalDashboard()
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);

        CurrentPostTravail::setGlobal();

        return redirect()->route('dashboard');
    }

    public function openPost(int $id)
    {
        $post = PostTravail::query()->findOrFail($id);
        CurrentPostTravail::set($post->id);

        return redirect()->route('dashboard');
    }
}
