<?php

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $request->validate([
            'role' => 'required|in:admin,nastavnik,student',
        ]);

        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Uloga korisnika je ažurirana.');
    }
}

