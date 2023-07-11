<?php

namespace Database\Seeders\Student;

use App\SmStudentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmStudentCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $count = 6){
        SmStudentCategory::factory()->times($count)->create([
            'school_id' => $school_id,
        ]);
    }
}
