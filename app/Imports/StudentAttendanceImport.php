<?php

namespace App\Imports;

use DB;
use App\SmStudent;
use App\Models\StudentRecord;
use App\StudentAttendanceBulk;
use App\SmStudentAttendanceImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentAttendanceImport implements ToModel, WithStartRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $class;
    public $section;
    public function __construct($class,$section)
    {
        $this->class = $class;
        $this->section = $section;
    }
    public function model(array $row)
    {
        $student = SmStudent::select('id')->where('admission_no', $row['admission_no'])->where('school_id', Auth::user()->school_id)->first();
        if ($student != "") {
            $record = StudentRecord::where('student_id',$student->id )->where('class_id',$this->class)->where('section_id',$this->section)->first();
            return new StudentAttendanceBulk([
            "attendance_date" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['attendance_date'])->format('Y-m-d'),
            "attendance_type" => $row['attendance_type'],
            "note" => $row['note'],
            "student_id" => $student->id,
            "class_id" => $this->class,
            "section_id" => $this->section,
            "student_record_id" => @$record->id,
            "school_id" => Auth::user()->school_id,
        ]);
        }
        
    }

    public function startRow(): int
    {
        return 2;
    }

    public function headingRow(): int
    {
        return 1;
    }

}