<?php

namespace App\Services;

use App\Contracts\AuthServiceInterfaceWeb;
use App\Models\PostTravail;
use App\Models\User;
use App\Support\CurrentPostTravail;
use App\Traits\RespondWithTokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuthServiceWeb implements AuthServiceInterfaceWeb
{
    use RespondWithTokenTrait;

    private const SEEDED_ADMIN_EMAIL = 'admin@gmail.com';

    public static function hasSecondaryAdmin(): bool
    {
        return User::role('admin')
            ->where('email', '!=', self::SEEDED_ADMIN_EMAIL)
            ->exists();
    }

    public static function register(Request $request): array
    {
        if (self::hasSecondaryAdmin()) {
            return [
                'message' => 'Les inscriptions sont fermées.',
                'data' => null,
            ];
        }

        $assignableRoles = Role::query()
            ->where('name', '!=', 'admin')
            ->pluck('name')
            ->all();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tel' => [
                'required',
                'string',
                'unique:users',
                'regex:/^\+[1-9][0-9]{7,14}$/',
            ],
            'post_travail_id' => ['required', 'integer', 'exists:post_travails,id'],
            'role' => ['required', 'string', Rule::in($assignableRoles)],
        ]);

        $post = PostTravail::query()
            ->where('id', $request->post_travail_id)
            ->where('actif', true)
            ->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'tel' => $request->tel,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);
        $user->postTravails()->attach($post->id, ['is_default' => true]);

        return [
            'message' => 'Utilisateur créé. Connectez-vous avec votre email ou votre numéro de téléphone.',
            'data' => $user,
        ];
    }

    public static function login(Request $request): array
    {
        if (Auth::check()) {
            return [
                'message' => 'Vous êtes déjà connecté.',
                'data' => Auth::user(),
            ];
        }

        $validation = Validator::make($request->all(), [
            'identifiant' => 'required',
            'password' => 'required|min:4',
            'remember' => ['sometimes', 'boolean'],
        ]);

        $validation->validate();

        $identifier = trim((string) $request->identifiant);
        $password = $request->password;
        $remember = $request->boolean('remember');
        $kind = self::loginKind($identifier);

        if ($kind === 'email') {
            $userByEmail = User::whereRaw('LOWER(email) = ?', [mb_strtolower($identifier)])->first();
            if ($userByEmail && Hash::check($password, $userByEmail->password)) {
                return self::completeLogin($request, $userByEmail, $remember);
            }
        }

        if ($kind === 'tel') {
            $normalized = preg_replace('/\D+/', '', $identifier) ?? '';
            $matchingUsers = User::whereNotNull('tel')
                ->get()
                ->filter(function (User $user) use ($normalized, $password) {
                    $userDigits = preg_replace('/\D+/', '', (string) $user->tel);

                    return $userDigits === $normalized && Hash::check($password, $user->password);
                });

            if ($matchingUsers->count() > 1) {
                return [
                    'message' => 'Ce numéro est lié à plusieurs comptes. Connectez-vous avec votre email.',
                    'data' => null,
                ];
            }
            if ($matchingUsers->count() === 1) {
                return self::completeLogin($request, $matchingUsers->first(), $remember);
            }
        }

        $users = User::whereRaw('LOWER(name) = ?', [mb_strtolower($identifier)])->get();
        $matchingUsers = $users->filter(function ($user) use ($password) {
            return Hash::check($password, $user->password);
        });

        if ($matchingUsers->count() > 1) {
            return [
                'message' => "Ce nom d’utilisateur et mot de passe sont utilisés par plusieurs comptes. Veuillez vous connecter avec votre adresse email.",
                'data' => null,
            ];
        }
        if ($matchingUsers->count() === 1) {
            return self::completeLogin($request, $matchingUsers->first(), $remember);
        }

        return [
            'message' => "email / téléphone / nom d'utilisateur ou mot de passe incorrect",
            'data' => null,
        ];
    }

    protected static function completeLogin(Request $request, User $user, bool $remember = false): array
    {
        Auth::login($user, $remember);
        $request->session()->regenerate();
        CurrentPostTravail::clear();

        return [
            'message' => 'Connexion réussie.',
            'data' => $user,
        ];
    }

    protected static function loginKind(string $identifier): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        $digits = preg_replace('/[\s.\-()]/', '', $identifier) ?? '';
        if (preg_match('/^\+?\d{7,15}$/', $digits) === 1) {
            return 'tel';
        }

        return 'name';
    }

    public static function logout(): array
    {
        CurrentPostTravail::clear();
        Auth::logout();

        return [
            'message' => 'Déconnexion réussie.',
            'data' => null,
        ];
    }

    public static function me(): array
    {
        return [
            'message' => 'Utilisateur connecté.',
            'data' => Auth::user(),
        ];
    }

    public static function all(): array
    {
        return [
            'message' => 'Liste des utilisateurs chargée.',
            'data' => User::latest()->get(),
        ];
    }
}
