<?php

namespace Database\Seeders;

use App\SmAdmissionQuery;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class sm_admission_queriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      SmAdmissionQuery::factory()->times(5)->create(['class' => 1]);
        
    }
}
