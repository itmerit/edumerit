<?php

namespace Database\Seeders\Academics;

use App\SmAcademicYear;
use App\SmSection;
use Illuminate\Database\Seeder;

class SmAcademicYearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $count = 10)
    {
        SmAcademicYear::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
