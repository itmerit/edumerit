<?php

namespace Database\Factories;


use App\SmSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmSubject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $subjects=['Bangla', 'English', 'Math','Computer Science' ,'Algorithms' , 'Data Structure' , 'Networking' , 'General Knowledge', 'chemistry'];
    public $subject_codes=['BAN-123', 'ENG-123', 'Math-123','CS-123' ,'Al-123' , 'Dt-123' , 'Net-123' , 'GK-123', 'CH-123'];
    public $i=0;
    public function definition()

    {

        return [               
                'subject_name'=> $this->subjects[$this->i++] ?? $this->faker->word,
                'subject_code'=> $this->subject_codes[$this->i++] ?? $this->faker->word,
                'subject_type'=> 'T',            
        ];
    }
}
