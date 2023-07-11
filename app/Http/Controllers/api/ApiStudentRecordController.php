<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiStudentRecordController extends Controller
{
    //
    public function getRecord($student_id)
    {
        $records = studentRecords(null, $student_id)->get()->map(function ($record) {
            return[
                'id'=>$record->id,
                'student_id'=>$record->student_id,
                'full_name'=>$record->student->full_name,
                'class'=>$record->class->class_name,
                'section'=>$record->section->section_name,
                'class_id'=>$record->class_id,
                'section_id'=>$record->section_id,
                'is_default'=>$record->is_default,
                'is_promote'=>$record->is_promote,
                'roll_no'=>$record->roll_no,
                'session_id'=>$record->session_id,
                'academic_id'=>$record->academic_id,
                'school_id'=>$record->school_id,
            ];
        });
        return response()->json(compact('records'));
    }
    public function getRecordSaas($school_id, $student_id)
    {
        $records = studentRecords(null, $student_id, $school_id)->get()->map(function ($record) {
            return[
                'id'=>$record->id,
                'student_id'=>$record->student_id,
                'full_name'=>$record->student->full_name,
                'class'=>$record->class->class_name,
                'section'=>$record->section->section_name,
                'is_default'=>$record->is_default,
                'is_promote'=>$record->is_promote,
                'roll_no'=>$record->roll_no,
                'session_id'=>$record->session_id,
                'academic_id'=>$record->academic_id,
                'school_id'=>$record->school_id,
            ];
        });
        return response()->json(compact('records'));
    }
}
