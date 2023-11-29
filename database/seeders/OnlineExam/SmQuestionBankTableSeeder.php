<?php

namespace Database\Seeders\OnlineExam;

use App\SmQuestionBank;
use App\SmAssignSubject;
use App\SmQuestionGroup;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SmQuestionBankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        {
            $faker = Faker::create();
            $i = 1;
            $group_id = SmQuestionGroup::where('school_id', $school_id)->where('academic_id', $academic_id)->value('id');
            $question_details = SmAssignSubject::all();
            foreach ($question_details as $question_detail) {
    
                $store = new SmQuestionBank();
                $store->q_group_id = $group_id;
                $store->class_id = $question_detail->class_id;
                $store->section_id = $question_detail->section_id;
                $store->type = 'M';
                $store->question = $faker->realText($maxNbChars = 80, $indexSize = 1);
                $store->marks = 100;
                $store->trueFalse = 'T';
                $store->suitable_words = $faker->realText($maxNbChars = 50, $indexSize = 1);
                $store->number_of_option = 4;
                $store->created_at = date('Y-m-d h:i:s');
                $store->school_id = $school_id;
                $store->academic_id = $academic_id;
                $store->save();
            }
        }
    }
}
