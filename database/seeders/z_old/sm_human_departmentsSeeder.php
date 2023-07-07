<?php

namespace Database\Seeders;

use App\SmHumanDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_human_departmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      SmHumanDepartment::factory()->times(10)->create();
    }
}
