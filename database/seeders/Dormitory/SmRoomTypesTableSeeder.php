<?php

namespace Database\Seeders\Dormitory;

use App\SmRoomType;
use Illuminate\Database\Seeder;

class SmRoomTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $count = 5)
    {
        SmRoomType::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
