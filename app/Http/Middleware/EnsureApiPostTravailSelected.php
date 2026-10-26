<?php

namespace App\Http\Middleware;

use App\Support\CurrentPostTravail;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiPostTravailSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (CurrentPostTravail::isReady()) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Sélectionnez un poste de travail avec l’en-tête X-Post-Travail-Id.',
        ], 409);
    }
}
