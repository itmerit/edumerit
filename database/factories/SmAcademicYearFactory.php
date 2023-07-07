<?php

namespace Database\Factories;

use App\SmAcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmAcademicYearFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmAcademicYear::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $i;
        $i = $i ?? 0;
        $year = date('Y') + $i +1;
        $i++;
        $starting_date = $year . '-01-01';
        $ending_date = $year . '-12-31';
        return [
            'year' => $year,
            'title' => 'Jan-Dec',
            'starting_date' => $starting_date,
            'ending_date' => $ending_date,
        ];
    }
}
