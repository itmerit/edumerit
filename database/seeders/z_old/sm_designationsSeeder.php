<?php

namespace Database\Seeders;

use App\SmDesignation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_designationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       SmDesignation::factory()->times(10)->create();
    }
}
