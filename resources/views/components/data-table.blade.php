@props(['rows', 'placeholder' => 'Rechercher…'])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <form method="GET" action="{{ url()->current() }}" class="flex flex-col gap-3 border-b border-gray-200 p-4 sm:flex-row sm:items-end">
        <div class="min-w-0 flex-1">
            <label for="table-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Recherche</label>
            <input id="table-search" name="search" type="search" value="{{ request('search') }}"
                   placeholder="{{ $placeholder }}"
                   class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-200">
        </div>
        {{ $filters ?? '' }}
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Filtrer</button>
            @if(request()->query())
                <a href="{{ url()->current() }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Réinitialiser</a>
            @endif
        </div>
    </form>

    <div class="flex items-center justify-between px-5 py-3 text-xs text-gray-500">
        <span>{{ $rows->total() }} résultat(s)</span>
        <span>Page {{ $rows->currentPage() }} sur {{ max(1, $rows->lastPage()) }}</span>
    </div>

    <div class="overflow-x-auto">{{ $slot }}</div>

    @if($rows->hasPages())
        <div class="border-t border-gray-200 p-4">{{ $rows->links() }}</div>
    @endif
</div>
