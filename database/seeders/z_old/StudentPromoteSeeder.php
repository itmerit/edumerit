<?php

namespace Database\Seeders;

use App\SmAcademicYear;
use Illuminate\Database\Seeder;

class StudentPromoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academic_year = SmAcademicYear::find(1);
        $new_year = $academic_year->year + 1;
        $new_academic_year = new SmAcademicYear();
        $new_academic_year->year = $new_year;
        $new_academic_year->title = "Academic Year  " . $new_year;
        $new_academic_year->starting_date =  $new_year . "-01-01";
        $new_academic_year->ending_date =  $new_year . "-12-31";
        $new_academic_year->created_at =  $new_year . "-01-01";
        $new_academic_year->save();

        

    }
}
