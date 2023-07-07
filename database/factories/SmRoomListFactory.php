<?php

namespace Database\Factories;

use App\SmRoomList;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmRoomListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmRoomList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(10),
            'number_of_bed' => rand(40,100),
            'cost_per_bed' => rand(5000,7000),
            'description' => $this->faker->text(200),
        ];
    }
}
