<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Créer le premier poste | VCarte</title>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col justify-center px-4 py-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-8 text-center">
                <h1 class="mb-2 text-2xl font-bold text-gray-900">Première étape : créer un poste de travail</h1>
                <p class="text-sm text-gray-500">
                    Aucun poste n’existe encore. Créez le premier poste pour commencer à utiliser l’application.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('post-travails.initial-setup.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="libele" class="mb-2 block text-sm font-medium text-gray-900">Nom du poste *</label>
                    <input id="libele" name="libele" value="{{ old('libele') }}" required autofocus
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    @error('libele')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-900">Courriel</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    </div>
                    <div>
                        <label for="contact1" class="mb-2 block text-sm font-medium text-gray-900">Téléphone</label>
                        <input id="contact1" name="contact1" value="{{ old('contact1') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    </div>
                    <div>
                        <label for="pays" class="mb-2 block text-sm font-medium text-gray-900">Pays</label>
                        <input id="pays" name="pays" value="{{ old('pays') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    </div>
                    <div>
                        <label for="province" class="mb-2 block text-sm font-medium text-gray-900">Province</label>
                        <input id="province" name="province" value="{{ old('province') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    </div>
                    <div>
                        <label for="ville" class="mb-2 block text-sm font-medium text-gray-900">Ville</label>
                        <input id="ville" name="ville" value="{{ old('ville') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                    </div>
                    <div>
                        <label for="use_cdf" class="mb-2 block text-sm font-medium text-gray-900">Devise</label>
                        <select id="use_cdf" name="use_cdf" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                            <option value="1" @selected(old('use_cdf', '1') === '1')>CDF</option>
                            <option value="0" @selected(old('use_cdf') === '0')>USD</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Créer le poste et continuer
                </button>
            </form>

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
