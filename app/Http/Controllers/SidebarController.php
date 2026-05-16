<?php

namespace App\Http\Controllers;


use App\Models\Task;
use App\Models\Habit;
use App\Models\Category;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
     public function index()
    {
        // 1. Ambil semua kategori untuk dropdown di form "Tambah Tugas"
        $categories = Category::all();

        // 2. Ambil Todo List yang belum selesai (is_completed = false)
        // Kita gunakan eager loading 'with' agar lebih ringan saat ambil data kategori
        $tasks = Task::with('category')->where('is_completed', false)->get();

        // 3. Pastikan habit default tersedia dan tidak bisa ditambah lewat UI
        $defaultHabits = [
            ['title' => 'Minum 2L Air', 'icon' => 'fa-tint', 'color' => '#38bdf8'],
            ['title' => 'Olahraga 30 Menit', 'icon' => 'fa-person-running', 'color' => '#f97316'],
            ['title' => 'Membaca Buku', 'icon' => 'fa-book-open', 'color' => '#8b5cf6'],
            ['title' => 'Meditasi 10 Menit', 'icon' => 'fa-moon', 'color' => '#22c55e'],
            ['title' => 'Tidur Sebelum 22:00', 'icon' => 'fa-bed', 'color' => '#0ea5e9'],
        ];

        foreach ($defaultHabits as $habitData) {
            Habit::updateOrCreate(
                ['title' => $habitData['title']],
                ['icon' => $habitData['icon'], 'color' => $habitData['color']]
            );
        }

        $habits = Habit::all();

        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $weekDays[] = [
                'label' => $dayLabels[$day->dayOfWeekIso - 1],
                'date' => $day->toDateString(),
                'number' => $day->translatedFormat('d'),
            ];
        }

        $habitLogs = \App\Models\HabitLog::whereBetween('log_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->groupBy('habit_id')
            ->map(function ($logs) {
                return $logs->keyBy('log_date');
            });

        // 4. Hitung Statistik Sederhana untuk Box di atas UI
        $totalTasksToday = Task::whereDate('task_date', now())->count();
        $completedTasksToday = Task::whereDate('task_date', now())->where('is_completed', true)->count();

        // 5. Kirim semua data ke view 'habits.dashboard'
        return view('habits.dashboard', compact(
            'categories',
            'tasks',
            'habits',
            'totalTasksToday',
            'completedTasksToday',
            'habitLogs',
            'today',
            'weekDays',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        Task::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'priority' => 'Sedang',
            'task_date' => now()->toDateString(),
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil disimpan.');
    }
}

