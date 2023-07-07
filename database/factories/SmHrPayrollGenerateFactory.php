<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmHrPayrollGenerate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmHrPayrollGenerateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmHrPayrollGenerate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'basic_salary' => 300,
            'total_earning' => 500,
            'total_deduction' => 300,
            'gross_salary' => 400,
            'tax' => 10,
            'net_salary' => 1510,
            'payroll_month' => $this->faker->monthName($max = 'now'),
            'payroll_year' => $this->faker->year($max = 'now'),
            'payroll_status' => 'G',
            'payment_mode' => 1,
            'created_at' => date('Y-m-d h:i:s'),
            'note' => $this->faker->realText($maxNbChars = 100, $indexSize = 1),
        ];
    }
}
