<?php

namespace Database\Seeders\Exam;

use App\SmAssignSubject;
use App\SmExam;
use App\SmExamSetup;
use App\SmExamType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmExamTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 3)
    {
        SmExamType::factory()->times($count)->create([
            'school_id' => $school_id,
            'academic_id' => $academic_id,
        ])->each(function($exam_type){
            $data = SmAssignSubject::withOutGlobalScopes()->where([
                'school_id' => $exam_type->school_id, 
                'academic_id' => $exam_type->academic_id])->get();
            foreach ($data as $row) {
                $s = new SmExamSetup();
                $s->class_id = $row->class_id;
                $s->section_id = $row->section_id;
                $s->subject_id = $row->subject_id;
                $s->exam_term_id = $exam_type->id;
                $s->school_id = $exam_type->school_id;
                $s->academic_id = $exam_type->academic_id;
                $s->exam_title = 'Exam';
                $s->exam_mark = 100;
                $s->created_at = date('Y-m-d h:i:s');
                $s->save();

                SmExam::create([
                    'exam_type_id' => $exam_type->id,
                    'school_id' => $exam_type->school_id,
                    'class_id' => $row->class_id,
                    'section_id' => $row->section_id,
                    'subject_id' => $row->subject_id,
                    'exam_mark' => 100,
                    'academic_id' =>$exam_type->academic_id,
                    'active_status' => 1,
                ]);
            }


        });
    }
}
