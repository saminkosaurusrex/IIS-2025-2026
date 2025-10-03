<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Hall;
use App\Models\Performer;
use App\Models\Reservation;
use App\Models\Show;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    private const TAGS_PER_SHOW = 5;
    private const TAG_COUNT = 20;

    private const PERFORMER_PER_SHOW = 8;
    private const PERFORMERS_COUNT = 50;
    private const HALLS_COUNT = 7;
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $faker = Faker::create();
        $users = User::factory(30)->create();




        $tags = Tag::factory(self::TAG_COUNT)->create();
        $performers = Performer::factory(self::PERFORMERS_COUNT)->create();
        $halls = Hall::factory(self::HALLS_COUNT)->create();

        $this->call([
            ShowTypeSeeder::class,
            ShowSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
        ]);



        $shows = Show::all();
        foreach ($shows as $show) {
            $show->tags()->attach(
                $tags->random(self::TAGS_PER_SHOW)
                    ->pluck('id')
                    ->toArray()
            );

            $show->performers()->attach(
                $performers->random(self::PERFORMER_PER_SHOW)
                    ->pluck('id')
                    ->toArray()
            );

            foreach ($halls as $hall) {
                $price = rand(500, 2000) / 10;

                Event::create([
                        'hall_id' => $hall->id,
                        'show_id' => $show->id,
                        'starting_at' => now()->addDay()->setHour(14)->setMinute(0)->setSecond(0),
                        'ending_at' => now()->addDay()->setHour(16)->setMinute(0)->setSecond(0),
                        'price' => $price]
                );

            }
        }
        $reservations = [];
        $events = Event::all();
        foreach ($events as $event) {
            $rows = $event->hall->rows;
            $columns = $event->hall->columns;
            for ($i = 1; $i <= $rows; $i++) {
                for ($j = 1; $j <= $columns; $j++) {
                    $shoud_insert = rand(0, 2);

                    // non registered reservation
                    if($shoud_insert == 1) {
                        $reservations[] =[
                            'access_code' => Str::uuid(),
                            'event_id' => $event->id,
                            'row' => $i,
                            'column' => $j,
                            'name' => $faker->name(),
                            'email' => $faker->email(),
                            'user_id' => null,
                            'selected_at' => now(),
                            'confirmed_at' => now()->addMinutes(rand(0, 120))->addSeconds(rand(0, 60)),
                        ];
                    }
                    //registered reservation
                    else if($shoud_insert == 2) {
                        $reservations[] =[
                            'access_code' => Str::uuid(),
                            'event_id' => $event->id,
                            'row' => $i,
                            'column' => $j,
                            'name' => null,
                            'email' => null,
                            'user_id' => $users->random()->id,
                            'selected_at' => now(),
                            'confirmed_at' => now()->addMinutes(rand(0, 120))->addSeconds(rand(0, 60)),
                        ];
                    }
                }
            }
        }

        Reservation::insert($reservations);

        // rating shows by user
        foreach ($users as $user) {
            $selectedShows = $shows->random(2);
            foreach ($selectedShows as $show) {
                $rating = rand(2, 10) / 2;
                $user->rated_shows()->attach($show->id, ['rating' => $rating]);
            }
        }

    }
}
