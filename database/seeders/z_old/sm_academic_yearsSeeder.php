<?php

namespace Database\Seeders;

use App\SmAcademicYear;
use App\SmGeneralSettings;
use Illuminate\Database\Seeder;

class sm_academic_yearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SmAcademicYear::query()->truncate();

        // $year = date('Y');
        // $starting_date = $year . '-01-01';
        // $ending_date = $year . '-12-31';
        // $s = new SmAcademicYear();
        // $s->year = $year;
        // $s->title = 'Academic Year ' . $year;
        // $s->starting_date = $starting_date;
        // $s->ending_date = $ending_date;
        // $s->created_at = date('Y-m-d h:i:s');
        // $s->save();
        // $academic_year = SmAcademicYear::first();
        // $sm_general_setting = SmGeneralSettings::first();
        // $sm_general_setting->session_id = $academic_year->id;
        // $sm_general_setting->save();



        // for ($i = 1; $i <= 4; $i++) {
        //     for ($year = date('Y'); $year <= date('Y') + 2; $year++) {
        //         $starting_date = $year . '-01-01';
        //         $ending_date = $year . '-12-31';
        //         $s = new SmAcademicYear();
        //         $s->year = $year;
        //         $s->title = 'Academic Year ' . $year;
        //         $s->starting_date = $starting_date;
        //         $s->ending_date = $ending_date;
        //         $s->created_at = date('Y-m-d h:i:s');
        //         $s->school_id = $i;
        //         $s->save();
        //     }

        //     $academic_year = SmAcademicYear::where('school_id', $i)->first();
        //     $sm_general_setting = SmGeneralSettings::where('school_id', $i)->first();
        //     $sm_general_setting->session_id = $academic_year->id;
        //     $sm_general_setting->save();
        // }
    }
}