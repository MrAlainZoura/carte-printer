<?php

namespace App\Http\Middleware;

use App\Models\PostTravail;
use App\Support\CurrentPostTravail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePostTravailSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! PostTravail::query()->exists()) {
            if (Auth::user()?->hasRole('admin')) {
                return redirect()->route('post-travails.initial-setup');
            }

            return redirect()->route('postes.select');
        }

        if (CurrentPostTravail::isReady()) {
            return $next($request);
        }

        return redirect()->route('postes.select');
    }
}
