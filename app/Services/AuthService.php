<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Enums\AuthMessage;
use App\Models\User;
use App\Traits\RespondWithTokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    use RespondWithTokenTrait;

    public function register(Request $request)
    {
        //return response()->json([$request->all()]);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            // Téléphone obligatoire, format international
            'tel'      => [
                'required',
                'string',
                'unique:users',
                'regex:/^\+[1-9][0-9]{7,14}$/'
            ],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email), // normalisation email
            'tel'      => $request->tel,               // correction ici
            'password' => Hash::make($request->password),
        ]);

        // Envoi automatique de l’email de vérification
        //$statusMessageSend = $user->sendEmailVerificationNotification();

        return back()->with('success', 'Utilisateur créé. Vérifiez votre email.');
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
            $loginData = ['name' => strtolower($identifiant), 'password' => $password];
        }

        // Tentative de connexion
        if (!$token = auth('api')->attempt($loginData)) {
            return back()->with('error', 'Identifiants incorrects.');
        }

        return to_route('dashboard')->with('token', $token);
        //return $this->respondWithToken($token);
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
        return response()->json(JWTAuth::user());
    }
}