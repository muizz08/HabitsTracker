<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
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

        // 3. Panggil HabitSeeder
        $this->call([
            HabitSeeder::class,
        ]);

        // 4. Seed Categories agar foreign key tasks terpenuhi
        $this->call([
            CategorySeeder::class,
        ]);

        // 5. Seed Tags
        $this->call([
            tags::class,
        ]);

        // 6. Seed Tasks agar tabel Task tidak kosong
        Task::create([
            'user_id' => $user->id,
            'category_id' => 1, // Penting
            'title' => 'Selesaikan Project Laravel',
            'priority' => 'Tinggi',
            'due_date' => now()->addDays(1),
        ]);

    }
}
