<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Changer le mot de passe | VCarte</title>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    @include('composant.navlogin')

    <main class="mx-auto flex min-h-[85vh] max-w-xl flex-col justify-center px-4 py-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="mb-2 text-2xl font-bold text-gray-900">Changer mon mot de passe</h1>
            <p class="mb-6 text-sm text-gray-500">Saisissez votre mot de passe actuel, puis choisissez-en un nouveau.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium">Mot de passe actuel</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">Nouveau mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirmer le nouveau mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm">
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('users.profile') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700">
                        Annuler
                    </a>
                    <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
