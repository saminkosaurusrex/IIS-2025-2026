<?php

namespace Database\Seeders;

use App\Models\Hall;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $halls = [
            'Hall A',
            'Hall B',
            'Hall C',
            'Big Hall',
            'Main Hall',
            'Conference Room 1',
            'Conference Room 2',
        ];

        foreach ($halls as $name) {
            Hall::create([
                'name' => $name,
                'description' => $faker->sentence(30),
                'address' => $faker->address(),
                'rows' => $faker->numberBetween(6, 10),
                'columns' => $faker->numberBetween(6, 10),
            ]);
        }

    }
}
