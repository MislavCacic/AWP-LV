<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Moji radovi i prijave</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        @foreach ($tasks as $task)
            <div class="border p-4 mb-6 rounded shadow">
                <h3 class="text-lg font-bold mb-2">{{ $task->naziv_hr }}</h3>
                <p><strong>Tip studija:</strong> {{ $task->tip_studija }}</p>
                <p class="mt-2 font-semibold">Prijave:</p>

                @forelse ($task->applications as $app)
                    <div class="border-t pt-2 mt-2">
                        <p><strong>{{ $app->user->name }}</strong> ({{ $app->user->email }})</p>
                        <p>Prioritet: {{ $app->prioritet }}</p>
                        <p>Status:
                            @if ($app->accepted === true)
                                <span class="text-green-600 font-bold">Prihvaćen</span>
                            @elseif ($app->accepted === false)
                                <span class="text-red-500">Odbijen</span>
                            @else
                                <span class="text-gray-500">Na čekanju</span>
                            @endif
                        </p>

                        @if (is_null($app->accepted))
                            <form method="POST" action="{{ route('applications.accept', $app) }}">
                                @csrf
                                <button class="mt-2 bg-blue-600 text-white px-3 py-1 rounded">
                                    Prihvati ovog studenta
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Nema prijava za ovaj rad.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</x-app-layout>
