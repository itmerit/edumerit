<?php

namespace Database\Seeders\Dormitory;

use App\SmDormitoryList;
use Illuminate\Database\Seeder;

class SmDormitoryListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $count = 5)
    {
        SmDormitoryList::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
