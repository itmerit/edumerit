<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmDormitoryList;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmDormitoryListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmDormitoryList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $dormitory = [
        'Sir Isaac Newton Hostel',
        'Louis Pasteur Hostel',
        'Galileo Hostel',
        'Marie Curie Hostel',
        'Albert Einstein Hostel',
        'Charles Darwin Hostel',
        'Nikola Tesla Hostel',
    ];
    public $i =0;

    public function definition()
    {
        return [
                    'dormitory_name' => $this->dormitory[$this->i++] ?? $this->faker->word,
                    'type' => 'B',
                    'address' => '25/13, Sukrabad Rd, Tallahbag, Dhaka 1215',
                    'intake' => 120,
                    'created_at' => date('Y-m-d h:i:s'),
                    'description' => $this->faker->text(),
        ];
    }
}
