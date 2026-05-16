<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Habit;
use App\Models\User;
use App\Models\HabitLog; 
use Carbon\Carbon;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $dataHabits = [
            ['title' => 'Minum 2L Air', 'icon' => 'fa-tint', 'color' => '#3b82f6'],
            ['title' => 'Olahraga 30 Menit', 'icon' => 'fa-running', 'color' => '#f59e0b'],
            ['title' => 'Membaca Buku', 'icon' => 'fa-book-open', 'color' => '#8b5cf6'],
            ['title' => 'Meditasi 10 Menit', 'icon' => 'fa-peace', 'color' => '#ec4899'],
            ['title' => 'Tidur Sebelum 22:00', 'icon' => 'fa-moon', 'color' => '#1e293b'],
        ];

        foreach ($dataHabits as $item) {
            // Simpan ke database sebagai Object Model Eloquent murni
            $insertedHabit = Habit::create([
                'user_id' => $user->id,
                'title'   => $item['title'],
                'icon'    => $item['icon'],
                'color'   => $item['color'],
            ]);

            // Gunakan ->id dari Object $insertedHabit murni
            HabitLog::create([
                'habit_id'     => $insertedHabit->id, 
                'log_date'     => Carbon::now()->format('Y-m-d'),
                'is_completed' => true
            ]);
        }
    }
}