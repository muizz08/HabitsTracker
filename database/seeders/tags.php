<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class tags extends Seeder
{
    public function run(): void
    {
        $dataTags = [
            ['name' => 'Penting'],
            ['name' => 'Deadline'],
            ['name' => 'Fokus'],
            ['name' => 'Kebiasaan'],
            ['name' => 'Cepat'],
            ['name' => 'Mingguan'],
        ];

        foreach ($dataTags as $tag) {

            Tag::updateOrCreate(
                ['name' => $tag['name']],
                ['name' => $tag['name']]
            );

        }
    }
}