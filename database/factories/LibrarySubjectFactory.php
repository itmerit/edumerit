<?php

namespace Database\Factories;

use App\LibrarySubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibrarySubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LibrarySubject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject_name' =>$this->faker->word(10),
        ];
    }
}
