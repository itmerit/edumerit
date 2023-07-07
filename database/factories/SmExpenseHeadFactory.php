<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmExpenseHead;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmExpenseHeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmExpenseHead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->realText($maxNbChars = 100, $indexSize = 1),
        ];
    }
}
