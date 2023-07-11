<?php

namespace Database\Seeders;

use App\SmExamType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_exam_typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        SmExamType::query()->truncate();
        DB::table('sm_exam_types')->insert([

            [
                'school_id' => 1,
                'active_status' => 1,
                'title' => 'First Term',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'school_id' => 1,
                'active_status' => 1,
                'title' => 'Second Term',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'school_id' => 1,
                'active_status' => 1,
                'title' => 'Third Term',
                'created_at' => date('Y-m-d h:i:s')
            ],

        ]);
    }
}
