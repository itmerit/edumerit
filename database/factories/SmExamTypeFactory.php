<?php

namespace Database\Factories;

use App\SmExamType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmExamTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmExamType::class;

    public $exam_type=['First Term','Second Term','Third Term'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $i;
        $i = $i ?? 0;
        return [
            'title' => $this->exam_type[$i++] ?? $this->faker->word,
        ];
    }
}
