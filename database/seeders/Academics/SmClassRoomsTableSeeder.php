<?php

namespace Database\Seeders\Academics;

use App\SmClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmClassRoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 5)
    {
        SmClassRoom::factory()->times($count)->create([
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ]);

    }
}
