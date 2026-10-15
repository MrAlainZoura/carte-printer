<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Choix du poste | VCarte</title>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    @include('composant.navlogin')

    <main class="mx-auto flex min-h-[85vh] max-w-3xl flex-col justify-center px-4 py-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4"/>
                    </svg>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-gray-900">Choisir un poste de travail</h1>
                <p class="text-sm text-gray-500">
                    Sélectionnez le poste avec lequel vous souhaitez travailler. Le tableau de bord s’adaptera à ce contexte.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($posts->isEmpty())
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    Aucun poste de travail actif n’est associé à votre compte. Contactez un administrateur.
                </div>
            @else
                <form method="POST" action="{{ route('postes.select.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-3">
                        @foreach ($posts as $post)
                            <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input
                                    type="radio"
                                    name="post_travail_id"
                                    value="{{ $post->id }}"
                                    class="mt-1 h-4 w-4 border-gray-300 text-blue-700 focus:ring-blue-600"
                                    @checked((string) old('post_travail_id', $currentPostId) === (string) $post->id)
                                    required
                                >
                                <span class="min-w-0 flex-1">
                                    <span class="block font-semibold text-gray-900">{{ $post->libele }}</span>
                                    <span class="mt-1 block text-sm text-gray-500">
                                        {{ collect([$post->ville, $post->province, $post->pays])->filter()->implode(' · ') ?: 'Adresse non renseignée' }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Continuer vers le tableau de bord
                    </button>
                </form>
            @endif

            @if ($isAdmin)
                <div class="mt-6 border-t border-gray-100 pt-6 text-center">
                    <p class="mb-3 text-sm text-gray-500">Accès réservé à l’administrateur</p>
                    <a href="{{ route('dashboard.global') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:underline">
                        Ouvrir le tableau de bord global
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('password.change') }}" class="text-sm font-semibold text-blue-700 hover:underline">
                    Changer mon mot de passe
                </a>
            </div>

            <form method="POST" action="{{ route('auth.logout') }}" class="mt-6 text-center">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-800 hover:underline">
                    Se déconnecter
                </button>
            </form>
        </div>
    </main>
</body>
</html>
