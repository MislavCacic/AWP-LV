<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dodaj završni ili diplomski rad</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="mb-4">
                <label>Naziv rada (HR)</label>
                <input type="text" name="naziv_hr" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Naziv rada (EN)</label>
                <input type="text" name="naziv_en" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Zadatak rada</label>
                <textarea name="zadatak" class="w-full border p-2" required></textarea>
            </div>

            <div class="mb-4">
                <label>Tip studija</label>
                <select name="tip_studija" class="w-full border p-2">
                    <option value="stručni">Stručni</option>
                    <option value="preddiplomski">Preddiplomski</option>
                    <option value="diplomski">Diplomski</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Spremi</button>
        </form>
    </div>
</x-app-layout>
