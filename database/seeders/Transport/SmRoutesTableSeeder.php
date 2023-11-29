<?php

namespace Database\Seeders\Transport;

use App\SmRoute;
use Illuminate\Database\Seeder;

class SmRoutesTableSeeder extends Seeder
{
    public function run($school_id = 1, $academic_id = 1, $count = 5){
        SmRoute::factory()->times($count)->create([
           'school_id' => $school_id,
           'academic_id' => $academic_id,
        ]);
    }

}