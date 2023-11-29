<?php

namespace Database\Seeders;

use App\SmClass;
use App\SmSection;
use App\SmClassSection;
use Illuminate\Database\Seeder;

class sm_class_sectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = SmClass::where('school_id', 1)->get();
        $sections = SmSection::where('school_id', 1)->get();
        foreach ($classes as $class) {           
            foreach ($sections as $section) {
                $s = new SmClassSection();
                $s->class_id = $class->id;
                $s->section_id = $section->id;
                $s->school_id = 1;              
                $s->save();
            }
        }
    }
}
