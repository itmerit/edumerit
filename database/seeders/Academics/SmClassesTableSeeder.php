<?php

namespace Database\Seeders\Academics;

use App\Models\StudentRecord;
use App\SmAdmissionQuery;
use App\SmAdmissionQueryFollowup;
use App\SmAssignClassTeacher;
use App\SmClass;
use App\SmParent;
use App\SmSection;
use App\SmStaff;
use App\SmStudent;
use App\SmSubject;
use App\User;
use Illuminate\Database\Seeder;

class SmClassesTableSeeder extends Seeder
{
    public $sections;
    public $subjects;

    public function __construct()
    {
        $this->sections = SmSection::all();
        $this->subjects = SmSubject::all();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $academic_id = 1, $count = 10)
    {
        $sections = SmSection::where('school_id', $school_id)->where('academic_id', $academic_id)->get();
        $subjects = SmSubject::where('school_id', $school_id)->where('academic_id', $academic_id)->get();

        SmClass::factory()->times($count)->create([
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ])->each(function ($class) use ($sections) {
            $class_sections = [];
            foreach ($sections as $section) {
                $class_sections[] = [
                    'section_id' => $section->id,
                    'school_id' => $class->school_id,
                    'academic_id' => $class->academic_id,
                ];
                $i = 0;
                SmStudent::factory()->times(5)->create()->each(function ($student) use ($class, $section) {

                    User::factory()->times(1)->create([
                        'role_id' => 2,
                        'email' => $student->email,
                        'username' => $student->email,
                        'school_id' => $class->school_id,
                    ])->each(function ($user) use ($student) {
                        $student->user_id = $user->id;
                        $student->save();
                    });

                    SmParent::factory()->times(1)->create([
                        'school_id' => $class->school_id,
                        'guardians_email' => 'guardian_' . $student->id . '@infixedu.com',
                    ])->each(function ($parent) use ($student) {
                        $student->parent_id = $parent->id;
                        $student->save();
                        User::factory()->times(1)->create([
                            'role_id' => 3,
                            'email' => $parent->guardians_email,
                            'username' => $parent->guardians_email,
                            'school_id' => $parent->school_id,
                        ])->each(function ($user) use ($parent) {
                            $parent->user_id = $user->id;
                            $parent->save();
                        });
                    });

                    StudentRecord::create([
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'school_id' => $class->school_id,
                        'academic_id' => $class->academic_id,
                        'roll_no' => $student->id,
                        'session_id' => $class->academic_id,
                        'is_default' => 1,
                        'student_id' => $student->id,
                    ]);
                });
            }
            $class_sections = $class->classSection()->createMany($class_sections);
            $assign_class_teachers = [];
            foreach ($class_sections as $class_section) {
                $assign_class_teachers[] = [
                    'class_id' => $class_section->class_id,
                    'section_id' => $class_section->section_id,
                    'academic_id' => $class_section->academic_id,
                    'school_id' => $class_section->school_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                SmStaff::factory()->times(1)->create([
                    'email' => 'staff_'.$class_section->id.'@infixedu.com',
                    'school_id' => $class_section->school_id,
                ])->each(function($staff){

                });
            }

            SmAdmissionQuery::factory()->times(10)->create([
                'class' => $class->id,
                'school_id' => $class->school_id,
                'academic_id' => $class->academic_id,
            ])->each(function ($admission_query) {
                SmAdmissionQueryFollowup::factory()->times(random_int(5, 10))->create([
                    'admission_query_id' => $admission_query->id,
                    'school_id' => $admission_query->school_id,
                    'academic_id' => $admission_query->academic_id,
                ]);
            });
        });
    }
}
