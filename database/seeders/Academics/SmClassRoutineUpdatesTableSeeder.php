<?php

namespace Database\Seeders\Academics;

use App\SmWeekend;
use App\SmAssignSubject;
use App\SmClassRoutineUpdate;
use Illuminate\Database\Seeder;

class SmClassRoutineUpdatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=1)
    {
        $school_academic = [
            'school_id' => $school_id,
            'academic_id' => $academic_id,
        ];
        $classSectionSubjects=SmAssignSubject::where('school_id',$school_id)
        ->where('academic_id',$academic_id)
        ->get();
        $weekends = SmWeekend::where('school_id', $school_id)->get();
        foreach ($weekends as $day){
            foreach($classSectionSubjects as  $classSectionSubject){
                SmClassRoutineUpdate::factory()->times($count)->create(array_merge([
                    'day' => $day->id,
                    'class_id' => $classSectionSubject->class_id,
                    'section_id' => $classSectionSubject->section_id,
                    'subject_id' => $classSectionSubject->subject_id,
                ], $school_academic));
            }
        }

    }
}
