<?php

namespace Database\Seeders;

use App\SmAssignSubject;
use Illuminate\Database\Seeder;
use Modules\Lesson\Entities\SmLesson;

class sm_lessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sections=SmAssignSubject::where('class_id',1)
                        ->where('subject_id',1)
                        ->get();
        $lessons=['Chapter 01','Chapter 02','Chapter 03','Chapter 04','Chapter 05','Chapter 06','Chapter 07','Chapter 08','Chapter 09','Chapter 10','Chapter 11','Chapter 12'];
        foreach($sections as $section){
            foreach($lessons as $lesson){
                $smLesson=new SmLesson;
                $smLesson->lesson_title=$lesson;
                $smLesson->class_id=1;	
                $smLesson->subject_id=1;
                $smLesson->section_id=$section->section_id;
                $smLesson->save();
                
            }
        } 
    }
}
