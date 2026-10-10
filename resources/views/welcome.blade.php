<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Accueil | Mon Application</title>
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <!-- Navigation -->
    <nav class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            <a href="{{ url('/') }}"
               class="flex items-center gap-2 text-xl font-bold text-blue-700">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                    </svg>
                </span>

                Mon Application
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                    Connexion
                </a>

                <a href="{{ route('register') }}"
                   class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    S'inscrire
                </a>
            </div>

        </div>
    </nav>

    <!-- Section principale -->
    <main class="flex min-h-[80vh] items-center justify-center px-6 py-16">

        <div class="mx-auto max-w-3xl text-center">

            <span class="mb-6 inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700">
                Bienvenue sur notre plateforme
            </span>

            <h1 class="mb-6 text-4xl font-extrabold tracking-tight text-gray-900 sm:text-6xl">
                Une expérience simple,
                <span class="text-blue-700">moderne et efficace.</span>
            </h1>

            <p class="mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-gray-500">
                Découvrez une plateforme conçue pour vous offrir une expérience
                agréable, sécurisée et facile à utiliser.
            </p>

            <div class="flex flex-col justify-center gap-4 sm:flex-row">

                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3.5 font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Commencer maintenant

                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>

                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3.5 font-semibold text-gray-700 transition hover:bg-gray-100">
                    J'ai déjà un compte
                </a>

            </div>

            <!-- Technologies -->
            <div class="mt-14 border-t border-gray-200 pt-8">
                <p class="mb-4 text-sm text-gray-500">
                    Une interface construite avec des technologies modernes
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3">

                    <span class="rounded-full bg-red-50 px-4 py-2 text-sm font-medium text-red-700">
                        Laravel
                    </span>

                    <span class="rounded-full bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700">
                        Tailwind CSS
                    </span>

                    <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">
                        Flowbite
                    </span>

                </div>
            </div>

        </div>

    </main>

    <!-- Pied de page -->
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-5 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Mon Application. Tous droits réservés.
        </div>
    </footer>

</body>
</html>