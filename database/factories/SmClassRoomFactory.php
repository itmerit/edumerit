<?php

namespace Database\Factories;

use App\SmClassRoom;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmClassRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmClassRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'room_no' => $this->faker->unique()->numberBetween(100, 100000000000000) ?? rand(100, 100000000000000),
            'capacity' => $this->faker->unique()->numberBetween(10, 500000000000000) ?? rand(100, 100000000000000),
        ];
    }
}
