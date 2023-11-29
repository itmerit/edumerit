<?php

namespace Database\Factories;


use App\SmStaff;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmStaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmStaff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $roles = [4, 5, 6, 7, 8, 9];
    public $i     = 0;

    public function definition()
    {
        return [
            'full_name' => $this->faker->firstNameMale,
            'basic_salary' =>30000,       
        ];
    }
}
