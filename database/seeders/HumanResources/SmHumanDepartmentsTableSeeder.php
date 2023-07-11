<?php

namespace Database\Seeders\HumanResources;

use App\SmHumanDepartment;
use Illuminate\Database\Seeder;

class SmHumanDepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $count = 10)
    {
        SmHumanDepartment::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
