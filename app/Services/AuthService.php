<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Enums\AuthMessage;
use App\Models\PostTravail;
use App\Models\User;
use App\Traits\RespondWithTokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    use RespondWithTokenTrait;

    public function register(Request $request)
    {
        abort_if(AuthServiceWeb::hasSecondaryAdmin(), 403, 'Les inscriptions sont fermées.');

        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => mb_strtolower(trim((string) $request->input('email'))),
            'tel' => trim((string) $request->input('tel')),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'tel' => [
                'required',
                'string',
                Rule::unique('users', 'tel'),
                'regex:/^\+[1-9][0-9]{7,14}$/',
            ],
            'post_travail_id' => [
                'required',
                'integer',
                Rule::exists('post_travails', 'id')->where('actif', true),
            ],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where(fn ($query) => $query
                    ->where('guard_name', 'web')
                    ->where('name', '!=', 'admin')),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'tel' => $validated['tel'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole($validated['role']);
            $user->postTravails()->attach($validated['post_travail_id'], ['is_default' => true]);

            return $user->load(['roles', 'postTravails']);
        });

        return response()->json([
            'message' => 'Compte créé. Vous pouvez maintenant vous connecter.',
            'data' => $user,
        ], 201);
    }

    public function registrationOptions()
    {
        abort_if(AuthServiceWeb::hasSecondaryAdmin(), 403, 'Les inscriptions sont fermées.');

        return response()->json([
            'message' => "Options d’inscription chargées.",
            'data' => [
                'posts' => PostTravail::query()
                    ->where('actif', true)
                    ->orderBy('libele')
                    ->get(['id', 'libele']),
                'roles' => User::assignableRoleLabels(),
            ],
        ]);
    }


   public function login(array $credentials)
    {
        $identifiant = $credentials['identifiant'] ?? null;
        $password    = $credentials['password'] ?? null;

        if (!$identifiant || !$password) {
            return response()->json(['error' => AuthMessage::Unauthorized->value], 401);
        }

        // Détection du type d’identifiant
        if (filter_var($identifiant, FILTER_VALIDATE_EMAIL)) {
            // Cas email
            $identifiant = strtolower($identifiant);
            $loginData = ['email' => $identifiant, 'password' => $password];
        } elseif (preg_match('/^\+[1-9][0-9]{7,14}$/', $identifiant)) {
            // Cas téléphone : format international obligatoire (+ suivi de chiffres)
            $loginData = ['tel' => $identifiant, 'password' => $password];
        } else {
            // Cas nom d’utilisateur
            $loginData = ['name' => $identifiant, 'password' => $password];
        }
        // Tentative de connexion
        if (!$token = auth('api')->attempt($loginData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants incorrects.',
            ], 401);
        }

        //return to_route('dashboard')->with('token', $token);
        return $this->respondWithToken($token);
    }



    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => AuthMessage::LogoutSuccess->value]);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    public function me()
    {
        $user = JWTAuth::user();
        $user->loadMissing(['roles.permissions', 'permissions']);

        return response()->json($user);
    }
}