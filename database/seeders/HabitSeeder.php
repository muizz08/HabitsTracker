<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Habit;
use App\Models\User;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user dengan ID 1 (atau buat user baru)
        $user = User::first() ?? User::factory()->create();

        $habits = [
            [
                'title' => 'Minum 2L Air',
                'icon' => 'fa-tint',
                'color' => '#3b82f6', // Biru
            ],
            [
                'title' => 'Olahraga 30 Menit',
                'icon' => 'fa-running',
                'color' => '#f59e0b', // Oranye
            ],
            [
                'title' => 'Membaca Buku',
                'icon' => 'fa-book-open',
                'color' => '#8b5cf6', // Ungu
            ],
            [
                'title' => 'Meditasi 10 Menit',
                'icon' => 'fa-peace',
                'color' => '#ec4899', // Pink
            ],
            [
                'title' => 'Tidur Sebelum 22:00',
                'icon' => 'fa-moon',
                'color' => '#1e293b', // Gelap
            ],
        ];

        foreach ($habits as $habit) {
            Habit::create([
                'user_id' => $user->id,
                'title'   => $habit['title'],
                'icon'    => $habit['icon'],
                'color'   => $habit['color'],
                'completed' => false,
            ]);
        }
    }
}