<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TeacherPanelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [ApplicationController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/apply', [ApplicationController::class, 'apply'])->name('tasks.apply');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/moji-radovi', [TeacherPanelController::class, 'index'])->name('teacher.tasks');
    Route::post('/prihvati-prijavu/{application}', [TeacherPanelController::class, 'accept'])->name('applications.accept');
});


require __DIR__.'/auth.php';
