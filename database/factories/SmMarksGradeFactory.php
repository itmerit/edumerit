<?php

namespace Database\Factories;

use App\SmMarksGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmMarksGradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmMarksGrade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $grade_name = ['A+','A','A-','B','C', 'D','F'];
    public $i=0;
    public $gpa = ['5.00', '4.00','3.50','3.00','2.00','1.00','0.00'];
    public $h=0;
    public $gpa_from = ['5.00', '4.00','3.50','3.00','2.00','1.00','0.00'];
    public $j=0;    
    public $gpa_up = ['5.99', '4.99','3.99','3.49','2.99','1.99','0.99'];
    public $k=0;
    public $percent_from=['80','70','60','50','40','33','0'];
    public $l=0;
    public $percent_upto=['100','79','69','59','49','39','32'];
    public $m=0;
    public $description=['Outstanding !','Very Good !','Good !','Outstanding !','Bad !','Very Bad !','Failed !'];
    public $n=0;
  

    public function definition()
    {
        return [
             
                'grade_name'          => $this->grade_name[$this->i++],
                'gpa'                 => $this->gpa[$this->h++],
                'from'                => $this->gpa_from[$this->j++],
                'up'                  => $this->gpa_up[$this->k++],
                'percent_from'        => $this->percent_from[$this->l++],
                'percent_upto'        => $this->percent_upto[$this->m++],
                'description'         => $this->description[$this->n++],
            
        ];
    }
}
