<?php

use App\Scopes\AcademicSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmStudentTakeOnlineExam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MultipleCourseDataMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Promoted data
        $promotes = \App\SmStudentPromotion::all();
        foreach ($promotes as $promote) {
            $class = \App\SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->where(['id' => $promote->class_id, 'school_id' => $promote->school_id])->first();
            $studentRecords = \App\Models\StudentRecord::firstOrCreate([
                'student_id' => $promote->student_id,
                'school_id' => $promote->school_id,
                'class_id' => $promote->previous_class_id,
                'section_id' => $promote->previous_section_id,
                'session_id' => $promote->previous_session_id,
                'academic_id' => $promote->previous_session_id,
                'roll_no' => $promote->previous_roll_number,
                'is_promote' => 1,
            ]);
        }
        // Student data migration

        $students = \App\SmStudent::all();

        foreach ($students as $student) {
            if ($student->class_id && $student->section_id && $student->session_id && $student->academic_id) {
                \App\Models\StudentRecord::firstOrCreate([
                    'student_id' => $student->id,
                    'school_id' => $student->school_id,
                    'class_id' => $student->class_id,
                    'section_id' => $student->section_id,
                    'session_id' => $student->session_id,
                    'academic_id' => $student->academic_id,
                    'roll_no' => $student->roll_no,
                    'is_default' => 1,
                ]);
            }

            $student->class_id = null;
            $student->section_id = null;
            $student->session_id = null;
            $student->academic_id = null;
            $student->roll_no = null;
            $student->save();

            // Fees data migration

        }


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
