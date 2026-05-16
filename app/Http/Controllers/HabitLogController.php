<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Task;

class HabitLogController extends Controller
{
    /**
     * Mengubah status centang (Toggle)
     */
    public function toggle(Request $request)
    {
        // Validasi input
        $request->validate([
            'habit_id' => 'required|exists:habits,id',
            'log_date' => 'required|date',
        ]);

        $log = HabitLog::where('habit_id', $request->habit_id)
            ->where('log_date', $request->log_date)
            ->first();

        if ($log) {
            $log->delete();
            $status = 'unmarked';
        } else {
            HabitLog::create([
                'habit_id' => $request->habit_id,
                'log_date' => $request->log_date,
                'is_completed' => true
            ]);
            $status = 'marked';
        }

        // --- BERSIHKAN & SAMAKAN LOGIKA DI SINI ---
        // Gunakan Habit::all() sama seperti yang ada di fungsi index() kamu
        $habits = Habit::all();
        $totalHabits = $habits->count();

        $completedCount = HabitLog::whereIn('habit_id', $habits->pluck('id'))
            ->whereBetween('log_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $maxTarget = $totalHabits * 7;
        $newPercentage = $maxTarget > 0 ? round(($completedCount / $maxTarget) * 100) : 0;

        return response()->json([
            'status' => $status,
            'newPercentage' => $newPercentage
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
                'date' => $date->format('Y-m-d')   // Format untuk database
            ];
        }

        // 2. AMBIL DATA HABITS & TASKS
        $habits = Habit::all();
        $tasks = Task::all();

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
            'weekDays' // Variabel ini yang sebelumnya hilang
        ));
    }
}