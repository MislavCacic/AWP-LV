<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            // Nastavnik vidi svoje radove i prijave
            $tasks = $user->tasks()->with(['applications.user'])->get();
            return view('teacher.tasks', compact('tasks'));
        }

        if ($user->isStudent()) {
            // Student vidi svoje prijave i status svakog
            $applications = $user->applications()->with('task')->get();
            return view('student.applications', compact('applications'));
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.users');
        }

        abort(403);
    }

    public function accept(Application $application)
    {
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        $task = $application->task;

        if ($task->user_id !== $user->id) {
            abort(403);
        }

        // Odbij sve ostale prijave
        Application::where('task_id', $task->id)->update(['accepted' => false]);

        // Prihvati ovog studenta
        $application->accepted = true;
        $application->save();

        return back()->with('success', 'Student je prihvaćen.');
    }
}
