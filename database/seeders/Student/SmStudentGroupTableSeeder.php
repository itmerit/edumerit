<?php

namespace Database\Seeders\Student;

use App\SmStudentGroup;
use Illuminate\Database\Seeder;

class SmStudentGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        $school_academic = [
            'school_id'=>$school_id,
            'academic_id'=>$academic_id,
        ];
        SmStudentGroup::factory()->times($count)->create($school_academic);
    }
}
