<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Popis dostupnih radova</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        @foreach ($tasks as $task)
            <div class="border rounded p-4 mb-4">
                <h3 class="text-lg font-bold">{{ $task->naziv_hr }} / {{ $task->naziv_en }}</h3>
                <p><strong>Nastavnik:</strong> {{ $task->user->name }}</p>
                <p>{{ $task->zadatak }}</p>
                <p><em>{{ $task->tip_studija }}</em></p>

                @if (in_array($task->id, $myApplications))
                    <p class="text-green-600 mt-2">Prijavljeni</p>
                @else
                    <form method="POST" action="{{ route('tasks.apply', $task) }}" class="mt-2">
                        @csrf
                        <button class="bg-blue-500 text-white px-3 py-1 rounded">Prijavi se</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
