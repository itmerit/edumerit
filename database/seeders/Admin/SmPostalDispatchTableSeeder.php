<?php

namespace Database\Seeders\Admin;

use App\SmPostalDispatch;
use Illuminate\Database\Seeder;

class SmPostalDispatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=10)
    {
        SmPostalDispatch::factory()->times($count)->create([
            'school_id'=>$school_id,
            'academic_id'=>$academic_id,
        ]);
    }
}
