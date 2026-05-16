<?php

use App\Http\Controllers\HabitsController;
use App\Http\Controllers\HabitLogController;
use App\Http\Controllers\SidebarController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidePanelController;
use App\Http\Controllers\TaskController;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/habits', [SidebarController::class, 'index']);
// Route::post('/tasks', [SidebarController::class, 'store']); // dinonaktifkan: konflik dengan route tasks.store
Route::get('/habits', [HabitLogController::class, 'index'])->name('habits.index');
Route::post('/habit-logs/toggle', [HabitLogController::class, 'toggle'])->name('habit.toggle');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
