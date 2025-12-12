<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create()
{
    abort_unless(auth()->user()->isTeacher(), 403);
    return view('tasks.create');
}

public function store(Request $request)
{
    abort_unless(auth()->user()->isTeacher(), 403);

    $request->validate([
        'naziv_hr' => 'required|string',
        'naziv_en' => 'required|string',
        'zadatak' => 'required|string',
        'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
    ]);

    auth()->user()->tasks()->create($request->all());

    return redirect()->route('dashboard')->with('success', 'Rad je uspješno dodan.');
}

}
