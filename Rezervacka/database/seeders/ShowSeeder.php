<?php

namespace Database\Seeders;


use App\Models\Show;
use Illuminate\Database\Seeder;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Show::insert([
            [
                'name' => 'AI Conference 2025',
                'show_type_id' => 2,
                'image' => 'images/conference.jpg',
            ],
            [
                'name' => 'Hamlet',
                'show_type_id' => 3,
                'image' => 'images/hamlet.jpg',
            ],
            [
                'name' => 'Inception',
                'show_type_id' => 1,
                'image' => 'images/inception.jpg',
            ],
            [
                'name' => 'Oppenheimer',
                'show_type_id' => 1,
                'image' => 'images/oppenheimer.jpg',
            ],
            [
                'name' => 'Swan Lake Ballet',
                'show_type_id' => 3,
                'image' => 'images/swan_lake.jpg',
            ]
        ]);
    }
}
