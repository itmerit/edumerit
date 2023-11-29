<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\StaffImportBulkTemporary;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffsImport implements ToModel, WithStartRow, WithHeadingRow
{

    public function model(array $row)
    {
        $dob = null;
        if(gv($row, 'date_of_birth')){
            $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
        }        
        $date_of_joining = null;
        if(gv($row, 'date_of_joining')){
            $date_of_joining = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_joining'])->format('Y-m-d');
        }
        if(gv($row, 'driving_license_ex_date')){
            $driving_license_ex_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['driving_license_ex_date'])->format('Y-m-d');
        }
       
        return new StaffImportBulkTemporary([
          "staff_no" => @$row['staff_no'],
          "role" => @$row['role'],
          "department" => @$row['department'],
          "designation" => @$row['designation'],
          "first_name" =>  @$row['first_name'],
          "last_name" => @$row['last_name'],
          "fathers_name" => @$row['fathers_name'],
          "mothers_name" => @$row['mothers_name'],
          "date_of_birth" => $dob,
          "date_of_joining" => $date_of_joining,
          "email" => @$row['email'],
          "gender_id" => @$row['gender_id'],
          "mobile" => @$row['mobile'],
          "emergency_mobile" => @$row['emergency_mobile'],
          "marital_status" => @$row['marital_status'],
          "current_address" => @$row['current_address'],
          "permanent_address" => @$row['permanent_address'],
          "qualification" => @$row['qualification'],
          "experience" => @$row['experience'],
          "epf_no" =>  @$row['epf_no'],
          "basic_salary" => @$row['basic_salary'],
          "contract_type" => @$row['contract_type'],
          "location" =>  @$row['location'],
          "bank_account_name" => @$row['bank_account_name'],
          "bank_account_no" =>  @$row['bank_account_no'],
          "bank_name" => @$row['bank_name'],
          "bank_brach" => @$row['bank_brach'],
          "facebook_url" => @$row['facebook_url'],
          "twitter_url" => @$row['twitter_url'],
          "instagram_url" =>  @$row['instagram_url'],
          "driving_license" =>  @$row['driving_license'],
          "user_id"=>auth()->user()->id,
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
