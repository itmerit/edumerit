<?php

namespace Database\Seeders\Fees;

use App\SmFeesType;
use App\SmFeesPayment;
use App\SmClassSection;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;

class SmFeesPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        $classSection = SmClassSection::where('school_id',$school_id)->where('academic_id', $academic_id)->first();
        $students = StudentRecord::where('class_id', $classSection->class_id)
        ->where('section_id', $classSection->section_id)
        ->where('school_id',$school_id)
        ->where('academic_id', $academic_id)
        ->get();
        foreach ($students as $record) {
            $fees_types = SmFeesType::where('school_id',$school_id)
            ->where('academic_id', $academic_id)
            ->where('active_status', 1)
            ->get();
            foreach ($fees_types as $fees_type) {
                $store = new SmFeesPayment();
                $store->student_id = $record->student_id;
                $store->record_id = $record->id;
                $store->fees_type_id = $fees_type->id;
                $store->fees_discount_id = 1;
                $store->discount_month = date('m');
                $store->discount_amount = 100;
                $store->fine = 50;
                $store->amount = 250;
                $store->payment_mode = "C";
                $store->school_id = $school_id;
                $store->academic_id = $academic_id;
                $store->save();

            }
        }
    }
}
