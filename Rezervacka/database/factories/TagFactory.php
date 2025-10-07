<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{

    protected static $tags = [
        'akčné', 'dráma', 'komédia', 'romantické', 'sci-fi', 'fantasy',
        'balet', 'opera', 'muzikál', 'thriller', 'mystery', 'krimi',
        'dobrodružné', 'historické', 'dokumentárne', 'hudobné', 'tanečné',
        'technologické', 'umelecké', 'moderné', 'klasické', 'experimentálne',
        'psychologické', 'filozofické', 'vzdelávacie', 'populárne', 'vedecké',
        'profesionálne', 'divadelné', 'pohybové', 'čiernobiele', 'farebné',
        'animované','dlhometrážne', 'študentské', 'amatérske',
        'premietanie', 'diskusia', 'prednáška', 'workshop', 'seminár',
        'interaktívne', 'online', 'premiéra', 'repríza', 'festivalové',
        'konferencia', 'panel', 'masterclass', 'literárne', 'poetické',

    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => $this->faker->unique()->randomElement(static::$tags)
        ];
    }
}
