<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\SmVisitor;

class sm_visitorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1)
    {
        SmVisitor::factory()->times(10)->create([
            'school_id' => $school_id,
        ]);       
    }
}
