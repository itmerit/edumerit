<?php

namespace Database\Factories;

use App\SmEmailSmsLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmEmailSmsLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmEmailSmsLog::class;

    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->text(),
            'send_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'send_through' => 'E',
            'send_to' => 'G',
        ];
    }
}