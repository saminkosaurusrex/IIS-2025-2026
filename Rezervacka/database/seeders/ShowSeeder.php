<?php

namespace Database\Seeders;


use App\Models\Show;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        Show::insert([
            [
                'name' => 'Inception',
                'show_type_id' => 1,
                'image' => '/images/inception.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Oppenheimer',
                'show_type_id' => 1,
                'image' => '/images/oppenheimer.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Eternal Sunshine of the Spotless Mind',
                'show_type_id' => 1,
                'image' => '/images/ethernal.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Fight Club',
                'show_type_id' => 1,
                'image' => '/images/fight_club.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Gone Girl',
                'show_type_id' => 1,
                'image' => '/images/gone_girl.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Chinatown',
                'show_type_id' => 1,
                'image' => '/images/chinatown.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'No Country For Old Man',
                'show_type_id' => 1,
                'image' => '/images/no_country.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Seven',
                'show_type_id' => 1,
                'image' => '/images/seven.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'The Game',
                'show_type_id' => 1,
                'image' => '/images/the_game.webp',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'AI Conference 2025',
                'show_type_id' => 2,
                'image' => '/images/conference.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Hamlet',
                'show_type_id' => 3,
                'image' => '/images/hamlet.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Swan Lake Ballet',
                'show_type_id' => 3,
                'image' => '/images/swan_lake.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Don Quixote',
                'show_type_id' => 3,
                'image' => '/images/don.jpg',
                'description' => $faker->sentence(30),
            ],
            [
                'name' => 'Romeo and Juliet',
                'show_type_id' => 3,
                'image' => '/images/romeo.jpg',
                'description' => $faker->sentence(30),
            ]
        ]);
    }
}
