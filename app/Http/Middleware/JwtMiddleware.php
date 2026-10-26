<?php

namespace App\Http\Middleware;

use App\Models\PostTravail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('api');

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token expiré'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token invalide'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token absent ou non fourni'
            ], 401);
        }

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur du token introuvable.',
            ], 401);
        }

        Auth::setUser($user);
        $request->attributes->set('api_current_post_global', false);
        $request->attributes->set('api_current_post_id', null);

        if ($request->boolean('global') || $request->header('X-Post-Travail-Global') === 'true') {
            if (! $user->hasRole('admin')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La vue globale est réservée aux administrateurs.',
                ], 403);
            }

            $request->attributes->set('api_current_post_global', true);
        } else {
            $postId = $request->header('X-Post-Travail-Id');
            if ($postId !== null) {
                if (! ctype_digit((string) $postId) || (int) $postId < 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Le poste de travail sélectionné est invalide.',
                    ], 422);
                }

                $allowed = $user->hasRole('admin')
                    ? PostTravail::query()->whereKey((int) $postId)->exists()
                    : $user->postTravails()->whereKey((int) $postId)->exists();

                if (! $allowed) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Vous ne pouvez pas accéder à ce poste de travail.',
                    ], 403);
                }

                $request->attributes->set('api_current_post_id', (int) $postId);
            } elseif ($defaultPost = $user->defaultPostTravail()) {
                $request->attributes->set('api_current_post_id', $defaultPost->id);
            }
        }

        return $next($request);
    }
}
