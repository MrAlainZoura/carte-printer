<?php

namespace App\Http\Middleware;

use App\Support\CurrentPostTravail;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePostTravailSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (CurrentPostTravail::isReady()) {
            return $next($request);
        }

        return redirect()->route('postes.select');
    }
}
