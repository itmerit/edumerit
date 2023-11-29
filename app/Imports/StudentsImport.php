<?php

namespace App\Imports;

use App\StudentBulkTemporary;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithStartRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $dob = null;
        $admission_date = date('Y-m-d');

        if(gv($row, 'date_of_birth')){
            $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
        }
        

        if(gv($row, 'admission_date')){
            $admission_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['admission_date'])->format('Y-m-d');
        }
        
        return new StudentBulkTemporary([
          "admission_number" =>(string) @$row['admission_number'],
          "roll_no" => (string) @$row['roll_no'],
          "first_name" => @$row['first_name'],
          "last_name" => @$row['last_name'],
          "date_of_birth" => $dob,
          "religion" => @$row['religion'],
          "gender" => @$row['gender'],
          "caste" => @$row['caste'],
          "mobile" => (string) @$row['mobile'],
          "email" => @$row['email'],
          "admission_date" => $admission_date,
          "blood_group" => @$row['blood_group'],
          "height" => @$row['height'],
          "weight" => @$row['weight'],
          "father_name" => @$row['father_name'],
          "father_phone" => (string) @$row['father_phone'],
          "father_occupation" => @$row['father_occupation'],
          "mother_name" => @$row['mother_name'],
          "mother_phone" => (string) @$row['mother_phone'],
          "mother_occupation" => @$row['mother_occupation'],
          "guardian_name" => @$row['guardian_name'],
          "guardian_relation" => @$row['guardian_relation'],
          "guardian_email" => @$row['guardian_email'],
          "guardian_phone" => (string) @$row['guardian_phone'],
          "guardian_occupation" => @$row['guardian_occupation'],
          "guardian_address" => @$row['guardian_address'],
          "current_address" => @$row['current_address'],
          "permanent_address" => @$row['permanent_address'],
          "bank_account_no" => (string) @$row['bank_account_no'],
          "bank_name" => @$row['bank_name'],
          "national_identification_no" => (string) @$row['national_identification_no'],
          "local_identification_no" => (string) @$row['local_identification_no'],
          "previous_school_details" => (string) @$row['previous_school_details'],
          "note" => @$row['note'],
          "user_id" => Auth::user()->id
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