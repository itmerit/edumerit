<?php

namespace Database\Seeders\Communicate;

use App\SmEmailSmsLog;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SmEmailSmsLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 5)
    {

        SmEmailSmsLog::factory()->times($count)->create([
            'school_id' => $school_id,
            'academic_id' => $academic_id,
        ]);

    }
}
