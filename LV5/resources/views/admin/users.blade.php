<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upravljanje korisnicima</h2>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow rounded">
            @foreach ($users as $user)
                <div class="border-b pb-4 mb-4">
                    <p><strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
                    <p>Trenutna uloga: <strong>{{ $user->role }}</strong></p>

                    <form method="POST" action="{{ route('admin.updateRole', $user) }}">
                        @csrf
                        <select name="role" class="border rounded p-1">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="nastavnik" {{ $user->role == 'nastavnik' ? 'selected' : '' }}>Nastavnik</option>
                            <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                        <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded">Spremi</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
