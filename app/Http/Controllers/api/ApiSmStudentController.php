<?php

namespace App\Http\Controllers\api;

use App\SmStudent;
use App\ApiBaseMethod;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiSmStudentController extends Controller
{
    public function searchStudent(Request $request)
    {
        $class_id = $request->class;
        $section_id = $request->section;
        $name = $request->name;
        $roll_no = $request->roll_no;

        $student_ids = StudentRecord::when($request->academic_year, function ($query) use ($request) {
            $query->where('academic_id', $request->academic_year);
        })
            ->when($request->class, function ($query) use ($request) {
                $query->where('class_id', $request->class);
            })
            ->when($request->section, function ($query) use ($request) {
                $query->where('section_id', $request->section);
            })
            ->when($request->roll_no, function ($query) use ($request) {
                $query->where('roll_no', $request->roll_no);
            })
            ->when(!$request->academic_year, function ($query) use ($request) {
                $query->where('academic_id', getAcademicId());
            })
            ->where('school_id', auth()->user()->school_id)
            ->distinct('student_id')->pluck('student_id')->toArray();

        $studentDetails = SmStudent::whereIn('id', $student_ids)
            ->when($request->name, function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->name . '%');
            })->get();
        // ->select('sm_students.id', 'student_photo', 'full_name', 'roll_no', 'user_id');

        $students = [];
        foreach ($studentDetails as $student) {

            $class_sec = [];
            foreach ($student->studentRecords as $classSec) {
                $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
            }
            if ($request->class) {
                $sections = [];
                $class = $student->recordClass ? $student->recordClass->class->class_name : '';
                if ($request->section) {
                    $sections = $student->recordSection != "" ? $student->recordSection->section->section_name : "";
                } else {
                    foreach ($student->recordClasses as $section) {
                        $sections[] = $section->section->section_name;
                    }

                }
                $class_sec = $class . '(' . $sections . '), ';
            }

            $data['id'] = $student->id;
            $data['photo'] = $student->student_photo;
            $data['full_name'] = $student->full_name;
            $data['user_id'] = $student->user_id;
            $data['class_section'] = $class_sec;

            $students[] = $data;
        }

        if (count($studentDetails)) {
            $msg = "Student Found";
        } else {
            $msg = "Student Not Found";
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['students'] = $students;

            return ApiBaseMethod::sendResponse($data, $msg);
        }
    }
    public function saas_searchStudent(Request $request, $school_id)
    {
        $student_ids = StudentRecord::when($request->academic_year, function ($query) use ($request) {
            $query->where('academic_id', $request->academic_year);
        })
            ->when($request->class, function ($query) use ($request) {
                $query->where('class_id', $request->class);
            })
            ->when($request->section, function ($query) use ($request) {
                $query->where('section_id', $request->section);
            })
            ->when($request->roll_no, function ($query) use ($request) {
                $query->where('roll_no', $request->roll_no);
            })
            ->when(!$request->academic_year, function ($query) use ($request) {
                $query->where('academic_id', getAcademicId());
            })
            ->where('school_id', $school_id)
            ->distinct('student_id')->pluck('student_id')->toArray();

        $studentDetails = SmStudent::whereIn('id', $student_ids)
            ->when($request->name, function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->name . '%');
            })->withOutGlobalScope(SchoolScope::class)->get();

        $students = [];
        foreach ($studentDetails as $student) {
            $class_sec = [];
            foreach ($student->studentRecords as $classSec) {
                $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
            }
            if ($request->class) {
                $sections = [];
                $class = $student->recordClass ? $student->recordClass->class->class_name : '';
                if ($request->section) {
                    $sections = $student->recordSection != "" ? $student->recordSection->section->section_name : "";
                } else {
                    foreach ($student->recordClasses as $section) {
                        $sections[] = $section->section->section_name;
                    }

                }
                $class_sec = $class . '(' . $sections . '), ';
            }

            $data['id'] = $student->id;
            $data['photo'] = $student->student_photo;
            $data['full_name'] = $student->full_name;
            $data['user_id'] = $student->user_id;
            $data['class_section'] = $class_sec;

            $students[] = $data;
        }
    
        if (count($studentDetails)) {
            $msg = "Student Found";
        } else {
            $msg = "Student Not Found";
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['students'] = $students;

            return ApiBaseMethod::sendResponse($data, $msg);
        }
    }
}
