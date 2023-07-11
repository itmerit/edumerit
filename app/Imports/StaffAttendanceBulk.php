<?php

namespace App\Imports;

use App\SmStaff;
use App\SmStaffAttendanceImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffAttendanceBulk implements ToModel, WithStartRow, WithHeadingRow
{
    public function model(array $row)
    {
        // $student = SmStaff::select('staff_no')->where('staff_no', $row['staff_no'])->where('school_id', Auth::user()->school_id)->first();

        return new SmStaffAttendanceImport([
            // "attendence_date" =>$row['attendence_date'],
            "attendence_date" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['attendence_date'])->format('Y-m-d'),
            "in_time" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['in_time'])->format('h:i A'),
            "out_time" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['out_time'])->format('h:i A'),
            "attendance_type" => $row['attendance_type'],
            "notes" => $row['notes'],
            "staff_id" => $row['staff_no'],
            "school_id" => Auth::user()->school_id,
            "academic_id" => getAcademicId(),
        ]);
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