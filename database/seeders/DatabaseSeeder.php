<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User dummy agar auth()->id() tidak null saat testing
        $user = User::factory()->create([
            'name' => 'User Test',
            'email' => 'test@example.com',
        ]);

        // 2. Seed Categories
        \App\Models\Category::insert([
            ['name' => 'Penting', 'color' => 'red'],
            ['name' => 'Kerja', 'color' => 'orange'],
            ['name' => 'Belajar', 'color' => 'blue'],
            ['name' => 'Kesehatan', 'color' => 'green'],
            ['name' => 'Kebiasaan', 'color' => 'emerald'],
        ]);

        // 3. Panggil HabitSeeder
        $this->call([
            HabitSeeder::class,
        ]);

        // 4. Seed Tasks agar tabel Task tidak kosong
        \App\Models\Task::create([
            'user_id' => $user->id,
            'category_id' => 1, // Penting
            'title' => 'Selesaikan Project Laravel',
            'priority' => 'Tinggi',
            'due_date' => now()->addDays(1),
        ]);
    }
}
