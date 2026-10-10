  <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>Inscription | Mon Application</title>
    </head>

    <body class="min-h-screen bg-gray-50 text-gray-800">

        <!-- En-tête -->
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

                <a href="{{ url('/') }}"
                   class="flex items-center gap-2 text-xl font-bold text-blue-700">

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                        </svg>
                    </span>

                    Mon Application
                </a>

                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-blue-700 hover:text-blue-800">
                    Se connecter
                </a>

            </div>
        </nav>

        <!-- Formulaire d'inscription -->
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
                                      d="M18 9v6m3-3h-6m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 21v-2a6 6 0 0112 0v2H3z"/>
                            </svg>
                        </div>

                        <h1 class="mb-2 text-2xl font-bold text-gray-900">
                            Créer un compte
                        </h1>

                        <p class="text-sm text-gray-500">
                            Inscrivez-vous pour commencer votre expérience.
                        </p>

                    </div>

                    <!-- Erreurs de validation -->
                    @if ($errors->any())
                        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"
                             role="alert">

                            <p class="mb-2 font-medium">
                                Veuillez corriger les erreurs suivantes :
                            </p>

                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                     @if (session('success'))
                        <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800"
                             role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('auth.store') }}" class="space-y-5">
                        @csrf

                        <!-- Nom -->
                        <div>
                            <label for="name"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Nom complet
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                placeholder="Votre nom complet"
                                autocomplete="name"
                                required
                                autofocus
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- E-mail -->
                        <div>
                            <label for="email"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Adresse e-mail
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                placeholder="nom@exemple.com"
                                autocomplete="email"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- tel -->
                        <div>
                            <label for="email"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Telephone
                            </label>

                            <input
                                type="text"
                                name="tel"
                                id="tel"
                                value="{{ old('email') }}"
                                placeholder="+243 000 000 000"
                                autocomplete="tel"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Mot de passe
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Minimum 8 caractères"
                                autocomplete="new-password"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >

                            @error('password')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div>
                            <label for="password_confirmation"
                                   class="mb-2 block text-sm font-medium text-gray-900">
                                Confirmer le mot de passe
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="Répétez votre mot de passe"
                                autocomplete="new-password"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-200"
                            >
                        </div>

                        <!-- Bouton -->
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-700 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                        >
                            Créer mon compte
                        </button>

                    </form>

                    <!-- Lien connexion -->
                    <p class="mt-6 text-center text-sm text-gray-500">
                        Vous avez déjà un compte ?

                        <a href="{{ route('login') }}"
                           class="font-semibold text-blue-700 hover:underline">
                            Se connecter
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