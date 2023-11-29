<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmComplaint;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmComplaintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmComplaint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'complaint_by' => $this->faker->name,
            'complaint_type' => rand(1,3),
            'complaint_source' => rand(1,3),
            'phone' => $this->faker->tollFreePhoneNumber,
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'description' => $this->faker->realText($maxNbChars = 100, $indexSize = 1),
            'action_taken' => $this->faker->word,
            'assigned' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
          
        ];
    }
}
