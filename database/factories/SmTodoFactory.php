<?php

namespace Database\Factories;

use App\SmToDo;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmTodoFactory extends Factory
{
    protected $model = SmToDo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'todo_title' => $this->faker->realText($maxNbChars = 30, $indexSize = 1),
            'date' => $this->faker->dateTime()->format('Y-m-d'),
            'created_by' => 1,
            'created_at' => date('Y-m-d h:i:s'),
        ];
    }
}
