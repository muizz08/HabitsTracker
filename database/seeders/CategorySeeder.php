<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::insert([
            ['name' => 'Penting', 'color' => 'red'],
            ['name' => 'Kerja', 'color' => 'orange'],
            ['name' => 'Belajar', 'color' => 'blue'],
            ['name' => 'Kesehatan', 'color' => 'green'],
            ['name' => 'Kebiasaan', 'color' => 'teal'],
            ['name' => 'Hiburan', 'color' => 'purple'],
        ]);
    }
}
