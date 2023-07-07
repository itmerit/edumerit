<?php

namespace Database\Seeders\OnlineExam;

use App\SmOnlineExam;
use App\SmAssignSubject;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SmOnlineExamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        $faker = Faker::create();
        $i = 1;

        $question_details = SmAssignSubject::where('school_id', $school_id)->where('academic_id', $academic_id)->get();
        foreach ($question_details as $question_detail) {
            $store = new SmOnlineExam();
            $store->subject_id = $question_detail->subject_id;
            $store->class_id = $question_detail->class_id;
            $store->section_id = $question_detail->section_id;
            $store->title = $faker->realText($maxNbChars = 30, $indexSize = 1);
            $store->date = date('Y-m-d');
            $store->start_time = '10:00 AM';
            $store->end_time = '11:00 AM';
            $store->end_date_time = date('Y-m-d') . " 11:00 AM";
            $store->percentage = 50;
            $store->instruction = $faker->realText($maxNbChars = 100, $indexSize = 1);
            $store->status = 1;
            $store->created_at = date('Y-m-d h:i:s');
            $store->school_id = $school_id;
            $store->academic_id = $academic_id;
            $store->save();
        }
    }
}
