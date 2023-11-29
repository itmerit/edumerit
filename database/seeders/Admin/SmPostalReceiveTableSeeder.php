<?php

namespace Database\Seeders\Admin;

use App\SmPostalReceive;
use Illuminate\Database\Seeder;

class SmPostalReceiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=10)
    {
        SmPostalReceive::factory()->times($count)->create([
            'school_id'=>$school_id,
            'academic_id'=>$academic_id,
        ]);
    }
}
