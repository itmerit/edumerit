<?php

namespace Database\Seeders;

use App\SmClassTeacher;
use App\SmStaff;
use Illuminate\Database\Seeder;

class sm_class_teachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $staff_id = SmStaff::where('role_id', 4)->first()->id ?? null;
        $store = new SmClassTeacher();
        $store->assign_class_teacher_id = 1;
        $store->teacher_id = $staff_id;
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

    }
}
