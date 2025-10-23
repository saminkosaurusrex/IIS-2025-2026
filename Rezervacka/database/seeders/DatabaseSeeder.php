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


        $this->call([
            HallSeeder::class,
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
        }



        $shows_movie = $shows->filter(fn(Show $show) => $show->show_type_id == 1);
        $prices = [12.0, 25.0, 30.0, 49.9, 99.98];

        // Definovaný harmonogram podľa predchádzajúcej tabuľky
        $daily_schedule = [
            ['hall' => 1, 'show' => 0, 'start' => 8, 'end' => 10],
            ['hall' => 2, 'show' => 1, 'start' => 8, 'end' => 10],
            ['hall' => 3, 'show' => 2, 'start' => 8, 'end' => 10],
            ['hall' => 4, 'show' => 3, 'start' => 8, 'end' => 10],
            ['hall' => 5, 'show' => 4, 'start' => 8, 'end' => 10],

            ['hall' => 1, 'show' => 5, 'start' => 11, 'end' => 13],
            ['hall' => 2, 'show' => 6, 'start' => 11, 'end' => 13],
            ['hall' => 3, 'show' => 7, 'start' => 11, 'end' => 13],
            ['hall' => 4, 'show' => 8, 'start' => 11, 'end' => 13],
            ['hall' => 5, 'show' => 0, 'start' => 11, 'end' => 13],

            ['hall' => 1, 'show' => 1, 'start' => 14, 'end' => 16],
            ['hall' => 2, 'show' => 2, 'start' => 14, 'end' => 16],
            ['hall' => 3, 'show' => 3, 'start' => 14, 'end' => 16],
            ['hall' => 4, 'show' => 4, 'start' => 14, 'end' => 16],
            ['hall' => 5, 'show' => 5, 'start' => 14, 'end' => 16],

            ['hall' => 1, 'show' => 6, 'start' => 17, 'end' => 19],
            ['hall' => 2, 'show' => 7, 'start' => 17, 'end' => 19],
            ['hall' => 3, 'show' => 8, 'start' => 17, 'end' => 19],
            ['hall' => 4, 'show' => 0, 'start' => 17, 'end' => 19],
            ['hall' => 5, 'show' => 1, 'start' => 17, 'end' => 19],

            ['hall' => 1, 'show' => 2, 'start' => 20, 'end' => 22],
            ['hall' => 2, 'show' => 3, 'start' => 20, 'end' => 22],
            ['hall' => 3, 'show' => 4, 'start' => 20, 'end' => 22],
            ['hall' => 4, 'show' => 5, 'start' => 20, 'end' => 22],
            ['hall' => 5, 'show' => 6, 'start' => 20, 'end' => 22],

        ];



        for ($day = 1; $day <= 7; $day++) {
            foreach ($daily_schedule as $slot) {
                $hall_id = $slot['hall'];
                $show = $shows_movie[$slot['show']];
                Event::create([
                    'hall_id' => $hall_id,
                    'show_id' => $show->id,
                    'starting_at' => now()->addDays($day)->setHour($slot['start'])->setMinute(0)->setSecond(0),
                    'ending_at' => now()->addDays($day)->setHour($slot['end'])->setMinute(0)->setSecond(0),
                    'price' => $prices[$show->id % count($prices)],
                ]);
            }
        }

        $daily_schedule_others = [
            ['hall' => 6, 'show' => 10, 'start' => 8, 'end' => 10],
            ['hall' => 6, 'show' => 11, 'start' => 11, 'end' => 13],
            ['hall' => 6, 'show' => 12, 'start' => 14, 'end' => 16],
            ['hall' => 6, 'show' => 13, 'start' => 17, 'end' => 19],
            ['hall' => 6, 'show' => 14, 'start' => 20, 'end' => 22],
            ['hall' => 7, 'show' => 10, 'start' => 8, 'end' => 10],
            ['hall' => 7, 'show' => 11, 'start' => 11, 'end' => 13],
            ['hall' => 7, 'show' => 12, 'start' => 14, 'end' => 16],
            ['hall' => 7, 'show' => 13, 'start' => 17, 'end' => 19],
            ['hall' => 7, 'show' => 14, 'start' => 20, 'end' => 22],
        ];

        foreach ($daily_schedule_others as $slot) {
            $hall_id = $slot['hall'];
            $show_id = $slot['show'];
            Event::create([
                'hall_id' => $hall_id,
                'show_id' => $show_id,
                'starting_at' => now()->addDay()->setHour($slot['start'])->setMinute(0)->setSecond(0),
                'ending_at' => now()->addDay()->setHour($slot['end'])->setMinute(0)->setSecond(0),
                'price' => $prices[$show_id % count($prices)],
            ]);
        }

        $reservations = [];
        $events = Event::all();
        foreach ($events as $event) {
            $rows = $event->hall->rows;
            $columns = $event->hall->columns;
            for ($i = 1; $i <= $rows; $i++) {
                for ($j = 1; $j <= $columns; $j++) {
                    $shoud_insert = rand(0, 6);

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
                            'reserved_at' => now(),
                            'confirmed_at' => now()->addMinutes(rand(0, 120))->addSeconds(rand(0, 60)),
                            'paid_at' => null,
                            'canceled_at' => null,
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
                            'reserved_at' => now(),
                            'confirmed_at' => now()->addMinutes(rand(0, 120))->addSeconds(rand(0, 60)),
                            'paid_at' => null,
                            'canceled_at' => null,
                        ];
                    }
                    //registered reservation non-confirmed
                    else if($shoud_insert == 3) {
                        $reservations[] =[
                            'access_code' => Str::uuid(),
                            'event_id' => $event->id,
                            'row' => $i,
                            'column' => $j,
                            'name' => null,
                            'email' => null,
                            'user_id' => $users->random()->id,
                            'reserved_at' => now(),
                            'confirmed_at' => null,
                            'paid_at' => null,
                            'canceled_at' => null,
                        ];
                    }
                }
            }
        }
        foreach (array_chunk($reservations, 400) as $chunk) {
            Reservation::insert($chunk);
        }


        // rating shows by user
        foreach ($users as $user) {
            $selectedShows = $shows->random(2);
            foreach ($selectedShows as $show) {
                $rating = rand(1, 20) / 2;
                $user->rated_shows()->attach($show->id, ['rating' => $rating]);
            }
        }

    }
}
