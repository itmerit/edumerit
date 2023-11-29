<?php

namespace Database\Seeders\Fees;

use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmClassSection;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;

class SmFeesAssignTableSeeder extends Seeder
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
            $val = 1 + rand() % 5;
            $fees_masters = SmFeesMaster::where('active_status', 1)
            ->where('school_id',$school_id)
            ->where('academic_id', $academic_id)
            ->take($val)->get();
            foreach ($fees_masters as $fees_master) {
                $store = new SmFeesAssign();
                $store->student_id = $record->student_id;
                $store->record_id = $record->id;
                $store->fees_master_id = $fees_master->id;
                $store->school_id = $school_id;
                $store->academic_id = $academic_id;
                $store->save();
            }
        }
    }
}
