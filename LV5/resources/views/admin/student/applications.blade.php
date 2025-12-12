<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Moje prijave</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        @forelse ($applications as $app)
            <div class="border p-4 mb-4 rounded">
                <h3 class="font-bold">{{ $app->task->naziv_hr }}</h3>
                <p>Nastavnik: {{ $app->task->user->name }}</p>
                <p>Prioritet: {{ $app->prioritet }}</p>
                <p>Status:
                    @if ($app->accepted === true)
                        <span class="text-green-600 font-bold">Prihvaćen</span>
                    @elseif ($app->accepted === false)
                        <span class="text-red-600">Odbijen</span>
                    @else
                        <span class="text-gray-500">Na čekanju</span>
                    @endif
                </p>
            </div>
        @empty
            <p class="text-gray-500">Nema prijava.</p>
        @endforelse
    </div>
</x-app-layout>
