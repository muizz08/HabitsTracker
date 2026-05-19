<?php

use App\Http\Controllers\HabitLogController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/habits', [SidebarController::class, 'index']);
Route::get('/habits', [HabitLogController::class, 'index'])->name('habits.index');
Route::post('/habit-logs/toggle', [HabitLogController::class, 'toggle'])->name('habit.toggle');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::patch(
    '/tasks/{task}/toggle-status',
    [TaskController::class, 'toggleStatus']
);
