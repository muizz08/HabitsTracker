<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Penting', 'color' => 'red'],
            ['name' => 'Kerja', 'color' => 'orange'],
            ['name' => 'Belajar', 'color' => 'blue'],
            ['name' => 'Kesehatan', 'color' => 'green'],
            ['name' => 'Kebiasaan', 'color' => 'teal'],
            ['name' => 'Hiburan', 'color' => 'purple'],
        ]);
    }
}
