<?php

namespace Database\Factories;

use App\SmClass;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
class SmClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $class = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
    public $i = 0;
    public function definition(): array
    {
        return [
            'class_name' => $this->class[$this->i++] ?? $this->faker->word,
            'school_id' => 1
        ];
    }
}
