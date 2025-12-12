<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

public function index()
{
    abort_unless(auth()->user()->isStudent(), 403);

    $tasks = Task::with('user')->get();
    $myApplications = auth()->user()->applications->pluck('task_id')->toArray();

    return view('applications.index', compact('tasks', 'myApplications'));
}

public function apply(Task $task)
{
    abort_unless(auth()->user()->isStudent(), 403);

    // Ograniči na max 5 prijava
    if (auth()->user()->applications()->count() >= 5) {
        return back()->withErrors(['msg' => 'Možete prijaviti najviše 5 radova.']);
    }

    // Ne dozvoli duplu prijavu
    if (auth()->user()->applications()->where('task_id', $task->id)->exists()) {
        return back()->withErrors(['msg' => 'Već ste se prijavili na ovaj rad.']);
    }

    auth()->user()->applications()->create([
        'task_id' => $task->id,
        'prioritet' => auth()->user()->applications()->count() + 1
    ]);

    return back()->with('success', 'Uspješno ste se prijavili na rad.');
}

}
