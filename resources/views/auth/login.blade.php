    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="{{ asset('build/assets/app-DCw2KBNL.css') }}">

            <script src="{{ asset('build/assets/app-BvRk9kiK.js') }}" defer></script>


        <title>Connexion | VCarte</title>
    </head>

    <body class="min-h-screen bg-gray-50 text-gray-800">

        <!-- En-tête -->
           @include('composant.navlogin')


        <!-- Formulaire -->
        <main class="flex min-h-[85vh] items-center justify-center px-4 py-12">

            <div class="w-full max-w-md">

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

                    <!-- Titre -->
                    <div class="mb-8 text-center">

                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4m-6-4l5-5m0 0l-5-5m5 5H3"/>
                            </svg>
                        </div>

                        <h1 class="mb-2 text-2xl font-bold text-gray-900">
                            Bon retour !
                        </h1>

                        <p class="text-sm text-gray-500">
                            Connectez-vous à votre compte pour continuer.
                        </p>

                    </div>

                    <!-- Message de statut -->
                    @if (session('status'))
                        <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800"
                             role="status">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Erreurs de validation -->
                    @if ($errors->any())
                        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"
                             role="alert">
                            <p class="mb-2 font-medium">
                                Impossible de vous connecter.
                            </p>

                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     <!-- Erreurs de validation -->
                    @if (session('echec'))
                        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"
                             role="alert">
                            <p class="mb-2 font-medium">
                                Impossible de vous connecter.
                            </p>

                            <ul class="list-inside list-disc">
                                <li>{{ session('echec') }}</li>
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire de connexion -->
                    <form method="POST" action="{{ route('auth.login') }}" class="space-y-5">
                        @csrf

                        <!-- E-mail -->
                        <div>
                            <label for="email"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Adresse e-mail
                            </label>

                            <input
                                type="text"
                                name="identifiant"
                                id="email"
                                value="{{ old('identifiant') }}"
                                placeholder="nom@exemple.com"
                                autocomplete="username"
                                required
                                autofocus
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('identifiant')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <div class="mb-2 flex items-center justify-between">

                                <label for="password"
                                       class="block text-sm font-medium text-gray-900">
                                    Mot de passe
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-sm font-medium text-blue-700 hover:underline">
                                        Mot de passe oublié ?
                                    </a>
                                @endif

                            </div>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('password')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Se souvenir de moi -->
                        <div class="flex items-center">

                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                value="1"
                                @checked(old('remember'))
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-700 focus:ring-2 focus:ring-blue-300"
                            >

                            <label for="remember"
                                   class="ms-2 text-sm text-gray-600">
                                Se souvenir de moi
                            </label>

                        </div>

                        <!-- Bouton -->
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-700 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                        >
                            Se connecter
                        </button>

                    </form>

                    <!-- Lien inscription -->
                    <p class="mt-6 text-center text-sm text-gray-500">
                        Vous n'avez pas encore de compte ?

                        <a href="{{ route('register') }}"
                           class="font-semibold text-blue-700 hover:underline">
                            Créer un compte
                        </a>
                    </p>

                </div>

                <p class="mt-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Mon Application
                </p>

            </div>

        </main>

    </body>
    </html>

