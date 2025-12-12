@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Novi projekt
        </h1>

        <form method="POST" action="{{ route('projects.store') }}"
              class="space-y-4 bg-white shadow-sm rounded-lg p-6 border border-gray-100">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Naziv projekta</label>
                <input type="text" name="naziv" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Opis</label>
                <textarea name="opis" required rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cijena</label>
                    <input type="number" name="cijena" step="0.01"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Datum početka</label>
                    <input type="date" name="datum_pocetka" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Datum završetka</label>
                    <input type="date" name="datum_zavrsetka" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Obavljeni poslovi</label>
                <textarea name="obavljeni_poslovi" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Članovi tima</label>
                <div class="space-y-1">
                    @foreach($users as $user)
                        <label class="inline-flex items-center text-sm text-gray-700 mr-4">
                            <input type="checkbox" name="clanovi[]" value="{{ $user->id }}"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2">{{ $user->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Spremi projekt
                </button>
            </div>
        </form>
    </div>
@endsection