  @php
    $registerRoute = Route::current()->getName() == "register";
    $loginRoute = Route::current()->getName() == "login";
    $registrationClosed = $registrationClosed ?? false;
  @endphp
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
                VCarte
            </a>

            <div class="flex items-center gap-3">

                <a href="{{ route('login') }}"
                   class="{{$registerRoute ?: 'hidden'}} rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Connexion
                </a>

                @if (! $registrationClosed)
                    <a href="{{ route('register') }}"
                       class="{{$loginRoute ?: 'hidden'}} rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                        S'inscrire
                    </a>
                @endif
            </div>

        </div>
    </nav>
