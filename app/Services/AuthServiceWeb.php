<?php

namespace App\Services;

use App\Enums\AuthMessage;
use App\Models\User;
use App\Traits\RespondWithTokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\AuthServiceInterfaceWeb;

class AuthServiceWeb implements AuthServiceInterfaceWeb
{
    use RespondWithTokenTrait;
    static public function register(Request $request)
    {
        //return response()->json([$request->all()]);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',            // Téléphone obligatoire, format international
            'tel'      => [
                'required',
                'string',
                'unique:users',
                'regex:/^\+[1-9][0-9]{7,14}$/'
            ],
        ]);
        if($request->password !== $request->password_confirmation){
            return back()->with('echec',"Les mots de passe ne correspondent pas.");
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email), // normalisation email
            'tel'      => $request->tel,               // correction ici
            'password' => Hash::make($request->password),
        ]);

        // Envoi automatique de l’email de vérification
        //$statusMessageSend = $user->sendEmailVerificationNotification();

        return back()->with('success', 'Utilisateur créé. Connectez-vous avec votre email ou votre numéro de téléphone.');
    }

      static public function login(Request $request){
        
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        $daliation = Validator::make($request->all(),[
            'identifiant'=>'required',
            'password'=>'required|min:4'
        ]);
        if($daliation->fails()){
            return back()->with('echec',$daliation->errors());
        }

        $identifier = trim((string) $request->identifiant);
        $password = $request->password;
        $kind = self::loginKind($identifier);

        if ($kind === 'email') {
            $userByEmail = User::whereRaw('LOWER(email) = ?', [mb_strtolower($identifier)])->first();
            if ($userByEmail && Hash::check($password, $userByEmail->password)) {
                Auth::login($userByEmail);
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
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
                return back()->with('echec', "Ce numéro est lié à plusieurs comptes. Connectez-vous avec votre email.");
            }
            if ($matchingUsers->count() === 1) {
                Auth::login($matchingUsers->first());
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }
        }

        // Nom d'utilisateur : insensible à la casse
        $users = User::whereRaw('LOWER(name) = ?', [mb_strtolower($identifier)])->get();
        $matchingUsers = $users->filter(function ($user) use ($password) {
            return Hash::check($password, $user->password);
        });

        if ($matchingUsers->count() > 1) {
            return back()->with('echec',"Ce nom d’utilisateur et mot de passe sont utilisés par plusieurs comptes. Veuillez vous connecter avec votre adresse email.");
        }
        if ($matchingUsers->count() === 1) {
            Auth::login($matchingUsers->first());
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

       return back()->with('echec',"email / téléphone / nom d'utilisateur ou mot de passe incorrect");
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

    static public function logout(){
        Auth::logout();
        return redirect(url("/"));
    }

    static public function me(){
        return Auth::user();
    }

    static function all(){
        return User::latest()->get();
    }
}