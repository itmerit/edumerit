<?php

namespace Database\Seeders;

use App\SmClass;
use App\SmSection;
use App\SmAssignClassTeacher;
use Illuminate\Database\Seeder;

class sm_assign_class_teachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = SmClass::where('school_id', 1)->get(['id','school_id']);
        $sections = SmSection::where('school_id', 1)->get(['id','school_id']);
        foreach ($classes as $class) {
            foreach ($sections as $section) {
                $store = new SmAssignClassTeacher();
                $store->class_id = $class->id;
                $store->section_id = $section->id;
                $store->created_at = date('Y-m-d h:i:s');
                $store->save();
            }
        }
    }
}
