<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\SmVisitor;

class SmVisitorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $count = 10)
    {
        SmVisitor::factory()->times($count)->create([
            'school_id' => $school_id,
        ]);       
    }
}
