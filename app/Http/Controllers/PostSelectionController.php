<?php

namespace App\Http\Controllers;

use App\Services\PostSelectionService;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;

class PostSelectionController extends Controller
{
    public function __construct(private readonly PostSelectionService $service)
    {
    }

    public function show()
    {
        $result = $this->service->options();
        if ($result['data']['needsInitialSetup']) {
            return redirect()->route('post-travails.initial-setup');
        }

        return view('auth.select-poste', [
            'posts' => $result['data']['posts'],
            'isAdmin' => $result['data']['isAdmin'],
            'currentPostId' => CurrentPostTravail::id(),
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->service->select($request);
        $postId = $result['data']['post_id'];
        CurrentPostTravail::set($postId);
        return redirect()->route('dashboard')->with('success', $result['message']);
    }

    public function globalDashboard()
    {
        $this->service->authorizeGlobalDashboard();

        CurrentPostTravail::setGlobal();

        return redirect()->route('dashboard');
    }

    public function openPost(int $id)
    {
        $result = $this->service->openPost($id);
        CurrentPostTravail::set($result['data']['post_id']);

        return redirect()->route('dashboard');
    }
}
