<?php

namespace Database\Seeders\HumanResources;

use Database\Factories\SmHourRateFactory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class SmHourRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        SmHourRateFactory::factory()->times($count)->create([
            'school_id'=> $school_id,
            'academic_id'=> $academic_id,
        ]);

    }
}
