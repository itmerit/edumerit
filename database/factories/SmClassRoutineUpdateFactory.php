<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmClassRoutineUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmClassRoutineUpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmClassRoutineUpdate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $start_time=['09:00:00', '10:30:00', '12:00:00', '14:00:00', '15:39:00'];
    public $end_time=['09:45:00', '11:15:00', '12:45:00', '14:45:00', '16:39:00'];
    public $i=0;
    public $j=0;
    public function definition()
    {
        return [
            'start_time' =>$this->start_time[$this->i++],
            'end_time' =>$this->end_time[$this->j++],
        ];
    }
}
