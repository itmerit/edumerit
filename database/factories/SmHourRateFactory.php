<?php

namespace Database\Factories;

use App\SmHourlyRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmHourRateFactory extends Factory
{

    protected $model = SmHourlyRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grade'=>'A+',
            'rate'=>20,
        ];
    }
}
