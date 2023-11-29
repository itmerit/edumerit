<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmContactMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmContactMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                  
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'subject' => $this->faker->title,
            'message' => $this->faker->text(200),
        ];
    }
}
