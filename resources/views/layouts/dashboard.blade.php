<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Administration') - Carte Rose</title>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="min-h-screen">

        <!-- Sidebar -->
        <aside
            id="sidebar"
            class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full border-r border-gray-200 bg-white transition-transform md:translate-x-0"
            aria-label="Menu principal">

            <!-- Logo -->
            <div class="flex h-16 items-center gap-3 border-b border-gray-200 px-6">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-white">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2M8 18h.01M16 18h.01M8 14h.01M16 14h.01" />
                    </svg>

                </div>

                <div>
                    <h1 class="text-lg font-bold text-gray-900">
                        Carte Rose
                    </h1>

                    <p class="text-xs text-gray-500">
                        Gestion des véhicules
                    </p>
                </div>

                <button
                    type="button"
                    data-drawer-hide="sidebar"
                    class="ml-auto rounded-lg p-1 text-gray-500 hover:bg-gray-100 md:hidden"
                    aria-label="Fermer le menu">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>

            <!-- Navigation -->
            <div class="h-[calc(100vh-64px)] overflow-y-auto px-4 py-5">

                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Menu principal
                </p>

                <ul class="space-y-1">

                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 rounded-lg bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-700">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 13h8V3H3v10zm0 8h8v-5H3v5zm12 0h6V11h-6v10zm0-18v5h6V3h-6z" />
                            </svg>

                            Tableau de bord
                        </a>
                    </li>

                    <li>
                        <a href="#demandes"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                            </svg>

                            Demandes de cartes
                            <span class="ml-auto rounded-full bg-orange-100 px-2 py-0.5 text-xs text-orange-700">
                                18
                            </span>

                        </a>
                    </li>

                    <li>
                        <a href="{{route('vehicules.index')}}"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0l-1 2v5h16v-5l-1-2" />
                            </svg>

                            Véhicules
                        </a>
                    </li>

                    <li>
                        <a href="#proprietaires"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 017.75-1.33M15 7a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>

                            Propriétaires
                        </a>
                    </li>

                </ul>

                <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Administration
                </p>

                <ul class="space-y-1">

                    <li>
                        <a href="#paiements"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10h18M7 15h2m4 0h2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>

                            Paiements
                        </a>
                    </li>

                    <li>
                        <a href="#rapports"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-6m3 6V7m3 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>

                            Rapports
                        </a>
                    </li>

                    <li>
                        <a href="#parametres"
                            class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8a4 4 0 100 8 4 4 0 000-8zm8 4a8 8 0 01-.1 1.3l2 1.5-2 3.5-2.3-.9a8 8 0 01-2.2 1.3L15 21h-4l-.4-2.3a8 8 0 01-2.2-1.3l-2.3.9-2-3.5 2-1.5a8 8 0 010-2.6l-2-1.5 2-3.5 2.3.9a8 8 0 012.2-1.3L11 3h4l.4 2.3a8 8 0 012.2 1.3l2.3-.9 2 3.5-2 1.5A8 8 0 0120 12z" />
                            </svg>

                            Paramètres
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100">

                                <!-- Icône Déconnexion -->
                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 15l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </li>



                </ul>

                <!-- Carte d'aide -->
                <div class="mt-8 rounded-xl bg-blue-50 p-4">

                    <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                        </svg>

                    </div>

                    <h3 class="text-sm font-semibold text-gray-900">
                        Besoin d'aide ?
                    </h3>

                    <p class="mb-3 mt-1 text-xs leading-5 text-gray-500">
                        Consultez la documentation de votre plateforme.
                    </p>

                    <a href="#aide"
                        class="text-xs font-semibold text-blue-700 hover:underline">
                        Centre d'assistance →
                    </a>

                </div>

            </div>
        </aside>

        <!-- Contenu principal -->
        <div class="min-h-screen md:ml-64">

            <!-- Topbar -->
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">

                <div class="flex items-center gap-3">

                    <button
                        type="button"
                        data-drawer-target="sidebar"
                        data-drawer-show="sidebar"
                        aria-controls="sidebar"
                        class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 md:hidden"
                        aria-label="Ouvrir le menu">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>

                    <div>
                        <p class="text-xs text-gray-500">
                            Administration / <span class="text-gray-900">@yield('breadcrumb', 'Tableau de bord')</span>
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-3 sm:gap-5">

                    <!-- Notifications -->
                    <button type="button"
                        class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                        aria-label="Notifications">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0" />
                        </svg>

                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>

                    </button>

                    <!-- Profil -->
                    <div class="flex items-center gap-3 border-l border-gray-200 pl-4">

                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-gray-900">
                                Administrateur
                            </p>

                            <p class="text-xs text-gray-500">
                                Super administrateur
                            </p>
                        </div>

                        <button type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"
                            aria-label="Profil administrateur">
                            AD
                        </button>

                    </div>

                </div>

            </header>

            <!-- Page -->
            <main class="p-4 sm:p-6 lg:p-8">

                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>