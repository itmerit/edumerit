<?php

namespace Database\Seeders\Fees;

use App\SmClassSection;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use App\SmFeesDiscount;
use Illuminate\Database\Seeder;

class SmFeesAssignDiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        $classSection = SmClassSection::where('school_id',$school_id)->where('academic_id', $academic_id)->first();
        $students = StudentRecord::where('class_id', $classSection->class_id)->where('section_id', $classSection->section_id)->where('school_id',$school_id)->where('academic_id', $academic_id)->get();
        $feesDisCountId= SmFeesDiscount::where('school_id',$school_id)->where('academic_id', $academic_id)->value('id');
        foreach ($students as $record) {
            $store = new SmFeesAssignDiscount();
            $store->fees_discount_id = $feesDisCountId;
            $store->student_id = $record->student_id;
            $store->school_id = $school_id;
            $store->academic_id = $academic_id;
            $store->save();
        }
    }
}
