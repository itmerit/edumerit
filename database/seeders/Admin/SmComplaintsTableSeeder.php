<?php

namespace Database\Seeders\Admin;

use App\SmComplaint;
use App\SmSetupAdmin;
use Database\Factories\SmSetupAdminFactory;
use Illuminate\Database\Seeder;

class SmComplaintsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 5)
    {
        SmSetupAdmin::factory()->times($count)->create([
            'type' => 2,
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ])->each(function ($complaint_type) use ($count){
            SmComplaint::factory()->times($count)->create([
                'school_id' => $complaint_type->school_id,
                'academic_id' => $complaint_type->academic_id
            ]);
        });


    }
}
