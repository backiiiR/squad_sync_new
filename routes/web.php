<?php

use App\Http\Controllers\CredentialController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('teams.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::get('teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::post('teams/users', [TeamController::class, 'createUser'])->name('teams.createUser');

    Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
    Route::put('teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    Route::post('teams/{team}/invite', [TeamController::class, 'inviteUser'])->name('teams.invite');
    Route::delete('teams/{team}/user/{user}', [TeamController::class, 'removeUser'])->name('teams.removeUser');

    Route::get('teams/{team}/credentials', [CredentialController::class, 'index'])->name('teams.credentials.index');
    Route::get('teams/{team}/credentials/create', [CredentialController::class, 'create'])->name('teams.credentials.create');
    Route::get('teams/{team}/credentials/{credential}/edit', [CredentialController::class, 'edit'])->name('teams.credentials.edit');
    Route::get('teams/{team}/credentials/{credential}', [CredentialController::class, 'show'])->name('teams.credentials.show');
    Route::post('teams/{team}/credentials', [CredentialController::class, 'store'])->name('teams.credentials.store');
    Route::put('teams/{team}/credentials/{credential}', [CredentialController::class, 'update'])->name('teams.credentials.update');
    Route::delete('teams/{team}/credentials/{credential}', [CredentialController::class, 'destroy'])->name('teams.credentials.destroy');

    Route::get('teams/{team}/tags', [TagController::class, 'index'])->name('teams.tags.index');
    Route::post('teams/{team}/tags', [TagController::class, 'store'])->name('teams.tags.store');
    Route::delete('teams/{team}/tags/{tag}', [TagController::class, 'destroy'])->name('teams.tags.destroy');

    Route::get('teams/{team}/statuses', [TaskStatusController::class, 'index'])->name('teams.statuses.index');
    Route::post('teams/{team}/statuses', [TaskStatusController::class, 'store'])->name('teams.statuses.store');
    Route::delete('teams/{team}/statuses/{taskStatus}', [TaskStatusController::class, 'destroy'])->name('teams.statuses.destroy');

    Route::get('teams/{team}/tasks', [TaskController::class, 'index'])->name('teams.tasks.index');
    Route::get('teams/{team}/tasks/create', [TaskController::class, 'create'])->name('teams.tasks.create');
    Route::get('teams/{team}/tasks/{task}', [TaskController::class, 'show'])->name('teams.tasks.show');
    Route::post('teams/{team}/tasks', [TaskController::class, 'store'])->name('teams.tasks.store');
    Route::get('teams/{team}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('teams.tasks.edit');
    Route::put('teams/{team}/tasks/{task}', [TaskController::class, 'update'])->name('teams.tasks.update');
    Route::delete('teams/{team}/tasks/{task}', [TaskController::class, 'destroy'])->name('teams.tasks.destroy');
    Route::post('teams/{team}/tasks/{task}/addTime', [TaskController::class, 'addTimeEntry'])->name('teams.tasks.addTime');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
