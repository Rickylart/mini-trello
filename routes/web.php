<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('projects', 'projects')->name('projects');
    Route::get('projects/{project}/tasks', function (App\Models\Project $project) {
        return view('tasks', ['project' => $project]);
    })->name('projects.tasks');
});

require __DIR__.'/settings.php';
