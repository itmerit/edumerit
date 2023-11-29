<?php

namespace Database\Factories;

use App\SmRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmRouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmRoute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(20),
            'far' => rand(100, 500),
        ];
    }
}
