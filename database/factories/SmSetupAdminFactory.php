<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmSetupAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmSetupAdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmSetupAdmin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(10),
            'type' =>rand(1,4),
        ];
    }
}
