<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmVisitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmVisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmVisitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [            
            'name' => $this->faker->name,
            'phone' => $this->faker->tollFreePhoneNumber,
            'visitor_id' => rand(1,3),
            'no_of_person' => $this->faker->numberBetween(1, 5),
            'purpose' => $this->faker->word,
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'in_time' => $this->faker->time($format = 'H:i A', $max = 'now'),
            'out_time' => $this->faker->time($format = 'H:i A', $max = 'now'),
            'school_id' => 1
          
        ];
    }
}
