<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmParent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmParentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmParent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $i =1;
    public function definition()
    {
        return [
                'fathers_name'       => $this->faker->firstNameMale,
                'fathers_mobile'     => rand(1000, 9999) . rand(1000, 9999),
                'fathers_occupation' => 'Teacher',              
                'mothers_name'       => $this->faker->firstNameFemale,
                'mothers_mobile'     => rand(1000, 9999) . rand(1000, 9999),
                'mothers_occupation' => 'Housewife',               
                'guardians_name'       => $this->faker->firstNameMale,
                'guardians_mobile'     => rand(1000, 9999) . rand(1000, 9999),
                'guardians_email'      => 'guardian_' . $this->i++ . '@infixedu.com',
                'guardians_occupation' => 'Businessman',
                'guardians_relation'   => 'Brother',
                'relation'             => 'Son',
                'guardians_address' => 'Dhaka-1219, Bangladesh',
                'is_guardian'       => 1,
        ];
    }
}
