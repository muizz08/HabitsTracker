<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Tag;

class HabitLogController extends Controller
{
    /**
     * Mengubah status centang (Toggle)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'habit_id' => 'required',
            'log_date' => 'required|date',
        ]);

        $habitId = $request->habit_id;
        $date = $request->log_date;

        $existing = HabitLog::where('habit_id', $habitId)
            ->where('log_date', $date)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            HabitLog::create([
                'habit_id' => $habitId,
                'log_date' => $date,
            ]);
        }

        // =========================
        // HITUNG PERSENTASE BARU
        // =========================

        $startWeek = now()->startOfWeek()->toDateString();
        $endWeek = now()->endOfWeek()->toDateString();

        $totalHabits = Habit::count();

        $totalDays = 7;

        $totalTarget = $totalHabits * $totalDays;

        $completed = HabitLog::whereBetween('log_date', [
            $startWeek,
            $endWeek
        ])->count();

        $percentage = $totalTarget > 0
            ? round(($completed / $totalTarget) * 100)
            : 0;

        return response()->json([
            'success' => true,
            'newPercentage' => $percentage,
        ]);
    }
    public function index()
    {

        $userId = auth()->id();

        // 1. GENERATE DATA HARI (Agar $weekDays tidak undefined)
        $weekDays = [];
        $startOfWeek = now()->startOfWeek(); // Mulai dari hari Senin

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'label' => $date->format('D'),      // Contoh: Mon, Tue
                'number' => $date->format('d'),      // Contoh: 12, 13
                'date' => $date->format('Y-m-d'),   // Format untuk database
            ];
        }

        // 2. AMBIL DATA HABITS & TASKS
        $habits = Habit::all();
        $tasks = Task::all();
        $categories = Category::all();
        $tags = Tag::all();

        // 3. LOGIKA PERSENTASE & LOG
        $completedCount = HabitLog::whereIn('habit_id', $habits->pluck('id'))
            ->whereBetween('log_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $maxTarget = $habits->count() * 7;
        $averagePercentage = $maxTarget > 0 ? round(($completedCount / $maxTarget) * 100) : 0;

        $habitLogs = HabitLog::whereIn('habit_id', $habits->pluck('id'))->get()->groupBy('habit_id');

        // 4. KIRIM SEMUA VARIABEL KE BLADE (Pastikan weekDays disertakan)
        return view('habits.dashboard', compact(
            'habits',
            'averagePercentage',
            'habitLogs',
            'tasks',
            'weekDays',
            'categories',
            'tags'
        ));

    }
}
