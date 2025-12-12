@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Uredi projekt
        </h1>

        @if(auth()->id() === $project->voditelj_id)
            {{-- Voditelj projekta: uređuje sve --}}
            <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4 bg-white shadow-sm rounded-lg p-6 border border-gray-100">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naziv projekta</label>
                    <input type="text" name="naziv" value="{{ $project->naziv }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opis</label>
                    <textarea name="opis" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              rows="3">{{ $project->opis }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cijena</label>
                        <input type="number" name="cijena" step="0.01" value="{{ $project->cijena }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Datum početka</label>
                        <input type="date" name="datum_pocetka" value="{{ $project->datum_pocetka }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Datum završetka</label>
                        <input type="date" name="datum_zavrsetka" value="{{ $project->datum_zavrsetka }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obavljeni poslovi</label>
                    <textarea name="obavljeni_poslovi"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              rows="3">{{ $project->obavljeni_poslovi }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Članovi tima</label>
                    <div class="space-y-1">
                        @foreach($users as $user)
                            <label class="inline-flex items-center text-sm text-gray-700 mr-4">
                                <input
                                    type="checkbox"
                                    name="clanovi[]"
                                    value="{{ $user->id }}"
                                    @if($project->clanovi->contains($user->id)) checked @endif
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                >
                                <span class="ml-2">{{ $user->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Spremi izmjene
                    </button>
                </div>
            </form>

        @elseif($project->clanovi->contains(auth()->user()))
            {{-- Član projekta: samo "obavljeni poslovi" --}}
            <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4 bg-white shadow-sm rounded-lg p-6 border border-gray-100">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obavljeni poslovi</label>
                    <textarea name="obavljeni_poslovi"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              rows="4">{{ $project->obavljeni_poslovi }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Spremi izmjene
                    </button>
                </div>
            </form>
        @else
            {{-- Nije član projekta ni voditelj --}}
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-100">
                <p class="text-sm text-gray-700">
                    Nemaš pravo uređivati ovaj projekt.
                </p>
            </div>
        @endif
    </div>
@endsection