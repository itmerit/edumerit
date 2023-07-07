<?php

namespace Database\Seeders;

use App\SmClass;
use Illuminate\Database\Seeder;

class sm_classesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        SmClass::factory()->times(10)->create(); 
    }
}
