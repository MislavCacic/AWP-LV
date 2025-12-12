@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Svi projekti
            </h1>

            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                Novi projekt
            </a>
        </div>

        @if($projects->isEmpty())
            <p class="text-gray-500">
                Trenutno nema projekata.
            </p>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($projects as $project)
                    <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ $project->naziv }}
                        </h2>
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $project->opis }}
                        </p>

                        <dl class="text-sm text-gray-700 space-y-1">
                            <div>
                                <dt class="font-medium inline">Cijena:</dt>
                                <dd class="inline"> {{ $project->cijena }}€</dd>
                            </div>
                            <div>
                                <dt class="font-medium inline">Datum početka:</dt>
                                <dd class="inline"> {{ \Carbon\Carbon::parse($project->datum_pocetka)->format('d.m.Y.') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium inline">Datum završetka:</dt>
                                <dd class="inline"> {{ \Carbon\Carbon::parse($project->datum_zavrsetka)->format('d.m.Y.') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium block mt-2">Obavljeni poslovi:</dt>
                                <dd>{{ $project->obavljeni_poslovi }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium inline">Voditelj:</dt>
                                <dd class="inline"> {{ $project->voditelj->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium block mt-2">Članovi tima:</dt>
                                <dd>
                                    @foreach($project->clanovi as $clan)
                                        {{ $clan->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-4">
                            <a href="{{ route('projects.edit', $project) }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Uredi
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection