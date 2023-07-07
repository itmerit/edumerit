<?php

namespace Database\Seeders\Admin;

use App\SmAdmissionQuery;
use Illuminate\Database\Seeder;

class SmAdmissionQueriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $academic_id = 1, $count = 10)
    {
        SmAdmissionQuery::factory()->times($count)->create([
            'class' => 1,
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ]);

    }
}
