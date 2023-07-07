<?php

namespace Database\Seeders\Dormitory;

use App\SmRoomList;
use Illuminate\Database\Seeder;

class SmRoomListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $count = 5)
    {
        SmRoomList::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
