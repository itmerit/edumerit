<?php

namespace Database\Factories;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $roles = [4, 5, 6, 7, 8, 9];
    public $i     = 0;
    public function definition()
    {
        $i=$this->i++;
        return [ 
            'full_name' => $this->faker->firstNameMale ?? $this->faker->firstNameFemale, 
            'email'=>'user_'.$i.'@infixedu.com', 
            'username'=>'user_'.$i.'@infixedu.com', 
            'role_id'   => $this->faker->numberBetween(4, 9) ?? 4,         
            'is_administrator'     => 'no',            
            'password' => Hash::make('123456'),      
        ];
    }
}
