<?php

namespace Database\Seeders\Communicate;

use App\SmNoticeBoard;
use Illuminate\Database\Seeder;
use Database\Factories\SmNoticeBoardFactory;

class SmNoticeBoardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count)
    {
        SmNoticeBoard::factory()->times($count)->create([
            'school_id'=>$school_id,
            'academic_id'=>$academic_id,
        ]);
    }
}
