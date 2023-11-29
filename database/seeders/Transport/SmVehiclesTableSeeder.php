<?php

namespace Database\Seeders\Transport;

use App\SmVehicle;
use Illuminate\Database\Seeder;

class SmVehiclesTableSeeder extends Seeder
{
    public function run($school_id = 1, $count = 5){

        SmVehicle::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }

}