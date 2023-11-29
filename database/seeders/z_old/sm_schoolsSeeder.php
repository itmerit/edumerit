<?php

namespace Database\Seeders;

use App\User;
use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmVisitor;
use App\SmExamType;
use App\SmFeesType;
use App\SmLanguage;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmExamSetup;
use App\SmFeesGroup;
use App\SmMarkStore;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmMarksGrade;
use App\SmResultStore;
use App\GlobalVariable;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmQuestionGroup;
use App\SmExamAttendance;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Faker\Factory as Faker;
use App\SmExamAttendanceChild;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;
use Modules\Saas\Events\InstituteRegistration;
use Modules\Saas\Listeners\InstituteRegisterdListener;
use Modules\Saas\Entities\SmSubscriptionPayment;

class sm_schoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFile = "modules_statuses.json";
        
        $allData = json_decode(file_get_contents($jsonFile), true); 

        if($allData['Saas'] == TRUE){
                
            $faker = Faker::create();
            $prefix = "school";
            for($i=2; $i<=5; $i++){
                $class_id = SmClass::where('school_id',$i)->where('academic_id',$i)->first('id')->id;
                $section_id = SmClassSection::where('school_id',$i)->where('class_id',$class_id)->where('academic_id',$i)->first('section_id')->section_id;

                $email= $prefix.'_'.$i.'@infixedu.com';
                $school= $faker->company. ' School';

                $store= new SmSchool();
                $store->school_name= $school;
                $store->email= $email;
                $store->domain= 'school'.$i;
                $store->created_at = date('Y-m-d h:i:s');
                $store->starting_date = date('Y-m-d');
                $store->is_email_verified = 1;
                $store->save();

                event(new InstituteRegistration($store));
                
                $user            = new User();
                $user->role_id   = 1;
                $user->full_name = $faker->name;
                $user->email     = $email;
                $user->username  = $email;
                $user->password  = Hash::make('123456');
                $user->school_id = $i;
                $user->save();
                $user->toArray();

                //user details
                $staff                  = new SmStaff();
                $staff->user_id         = $user->id;
                $staff->role_id         = 1;
                $staff->staff_no        = 1;
                $staff->designation_id  = 1;
                $staff->department_id   = 1;
                $staff->first_name      = 'School';
                $staff->last_name       = 'Admin';
                $staff->full_name       = 'School Admin';
                $staff->date_of_birth   = '1980-12-26';
                $staff->date_of_joining = '2019-05-26';
                $staff->gender_id       = 1;
                $staff->school_id       = $i;
                $staff->email           = $email;
                $staff->staff_photo     = 'public/uploads/staff/1_infix_edu.jpg';
                $staff->casual_leave    = '12';
                $staff->medical_leave   = '15';
                $staff->metarnity_leave = '15';
                $staff->save();

                DB::table('sm_testimonials')->insert([
                    [
                        'name' => 'Tristique euhen',
                        'designation' => 'CEO',
                        'school_id' => $store->id,
                        'institution_name' => 'Google',
                        'image' => 'public/uploads/testimonial/testimonial_1.jpg',
                        'description' => 'its vast! Infix has more additional feature that will expect in a complete solution.',
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'name' => 'Malala euhen',
                        'designation' => 'Chairman',
                        'school_id' => $store->id,
                        'institution_name' => 'Linkdin',
                        'image' => 'public/uploads/testimonial/testimonial_2.jpg',
                        'description' => 'its vast! Infix has more additional feature that will expect in a complete solution.',
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                ]);

                DB::table('sm_events')->insert([
                    [
                        'event_title' => 'Biggest Robotics Competition in Campus',    //      1
                        'event_location' => 'Main Campus',
                        'event_des' => 'Amet enim curabitur urna. Faucibus tincidunt pellentesque varius blandit fermentum tristique vulputate sodales tempus est hendrerit est tincidunt ligula lorem tellus eu malesuada tortor, lacinia posuere. Conubia Egestas sed senectus.',
                        'from_date' => '2019-06-12',
                        'to_date' => '2019-06-21',
                        'school_id' => $store->id,
                        'uplad_image_file' => 'public/uploads/events/event1.jpg',
                    ],
                    [
                        'event_title' => 'Great Science Fair in main campus',    //      1
                        'event_location' => 'Main Campus',
                        'event_des' => 'Magna odio in. Facilisi arcu nec augue lacus augue maecenas hendrerit euismod cras vulputate dignissim pellentesque sociis est. Ut congue Leo dignissim. Fermentum curabitur pede bibendum aptent, quam, ultrices Nam convallis sed condimentum. Adipiscing mollis lorem integer eget neque, vel.',
                        'from_date' => '2019-06-12',
                        'to_date' => '2019-06-21',
                        'school_id' => $store->id,
                        'uplad_image_file' => 'public/uploads/events/event2.jpg',
                    ],
                    [
                        'event_title' => 'Seminar on Internet of Thing in Campus',    //      1
                        'event_location' => 'Main Campus',
                        'event_des' => 'Libero erat porta ridiculus semper mi eleifend. Nisl nulla. Tempus, rhoncus per. Varius. Pharetra nisi potenti ut ultrices sociosqu adipiscing at. Suscipit vulputate senectus. Nostra. Aliquam fringilla eleifend accumsan dui.',
                        'from_date' => '2019-06-12',
                        'to_date' => '2019-06-21',
                        'school_id' => $store->id,
                        'uplad_image_file' => 'public/uploads/events/event3.jpg',
                    ],
                    [
                        'event_title' => 'Art Competition in Campus',    //      1
                        'event_location' => 'Main Campus',
                        'event_des' => 'Dui nunc faucibus Feugiat penatibus molestie taciti nibh nulla pellentesque convallis praesent. Fusce. Vivamus egestas Rutrum est eu dictum volutpat morbi et. Placerat justo elementum dictumst magna nisl ut mollis varius velit facilisi. Duis tellus ullamcorper aenean massa nibh mi.',
                        'from_date' => '2019-06-12',
                        'to_date' => '2019-06-21',
                        'school_id' => $store->id,
                        'uplad_image_file' => 'public/uploads/events/event4.jpg',
                    ],
                ]);

                $faker = Faker::create();

                for ($t = 1; $t <= 5; $t++) {
                    DB::table('sm_courses')->insert([
                        'title' => $faker->text(50),
                        'image' => 'public/uploads/course/academic' . $t++ . '.jpg',
                        'overview' => $faker->text(2000),
                        'outline' => $faker->text(2000),
                        'prerequisites' => $faker->text(2000),
                        'resources' => $faker->text(2000),
                        'stats' => $faker->text(2000),
                        'active_status' => 1,
                        'school_id' => $store->id,
                        'created_at' => date('Y-m-d h:i:s')
                    ]);
                }

                DB::table('sm_notice_boards')->insert([
                    [
                        'notice_title' => 'Inter school football tournament',
                        'notice_message' => 'Sit eget Vivamus pede etiam purus. A arcu Consequat feugiat etiam egestas, quis amet nec dictumst sociosqu integer mattis euismod.',
                        'notice_date' => '2019-06-11',
                        'publish_on' => '2019-06-12',
                        'inform_to' => '1,2,3,5,6',
                        'is_published' => 1,
                        'school_id' => $store->id
                    ],
                    [
                        'notice_title' => 'Seminar On ICT',
                        'notice_message' => 'Tellus luctus. Mattis phasellus venenatis ante porttitor purus. Scelerisque justo aenean lectus, adipiscing. Hymenaeos nulla metus eu auctor pharetra, risus lacus amet posuere quisque et Vehicula posuere nibh diam sociis accumsan varius vehicula inceptos duis,',
                        'notice_date' => '2019-06-10',
                        'publish_on' => '2019-06-11',
                        'inform_to' => '1,2,3,5,6',
                        'is_published' => 1,
                        'school_id' => $store->id
                    ],
                    [
                        'notice_title' => 'Internet of Things Competition',
                        'notice_message' => 'Adipiscing sociosqu quis pede diam natoque aenean, sociosqu lacinia vel magna. Nostra ornare, velit ultrices venenatis. Tellus est velit laoreet lectus dui nibh lorem erat aptent a porttitor torquent urna varius class aenean sapien.',
                        'notice_date' => '2019-06-10',
                        'publish_on' => '2019-06-11',
                        'inform_to' => '1,2,3,5,6',
                        'is_published' => 1,
                        'school_id' => $store->id
                    ],
                    [
                        'notice_title' => 'Cricket Match Between Class Ten with Nine',
                        'notice_message' => 'Dignissim sodales praesent gravida eros facilisi nec. Lacinia habitasse accumsan suspendisse. Porta praesent eu natoque, nibh scelerisque per urna torquent nisl praesent. Cum Accumsan nibh platea donec tempus.',
                        'notice_date' => '2019-06-10',
                        'publish_on' => '2019-06-11',
                        'inform_to' => '1,2,3,5,6',
                        'is_published' => 1,
                        'school_id' => $store->id
                    ],
                ]);

            

                $sectionData=['A','B','C'];
                foreach ($sectionData as $row) {
                    for ($j = 2; $j <= 2; $j++) {
                        $s= new SmSection();
                        $s->section_name=$row.' '.$j;
                        $s->created_at = date('Y-m-d h:i:s');
                        $s->school_id=$i;
                        $s->academic_id=$i;
                        $s->save();
                    }
                } 

                $classData = ['One', 'Two', 'Three', 'Four', 'Five'];
                foreach ($classData as $row) {
                    for ($j = 2; $j <= 2; $j++) {
                        $s = new SmClass();
                        $s->class_name = $row . ' ' . $j;
                        $s->created_at = date('Y-m-d h:i:s');
                        $s->school_id = $i;
                        $s->academic_id = $i;
                        $s->save();
                    }
                } 

                for ($j = 2; $j <= 2; $j++) {
                    $classes= SmClass::where('school_id', $i)->get();
                    foreach ($classes as  $class) {
                        $sections=SmSection::where('school_id', $i)->get();
                        foreach ($sections as $section) {
                            $s = new SmClassSection();
                            $s->class_id = $class->id;
                            $s->section_id = $section->id;
                            $s->school_id = $i;
                            $s->academic_id = $i;
                            $s->created_at = date('Y-m-d h:i:s');
                            $s->save();
                        }
                    } 
                }

                DB::table('sm_subjects')->insert([

                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Bangla',
                        'subject_code'=> 'BAN-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'English For Today',
                        'subject_code'=> 'ENG-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Mathematics',
                        'subject_code'=> 'MATH-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Agricultural Education',
                        'subject_code'=> 'AG-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Information and Communication Technology',
                        'subject_code'=> 'ICT-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Science',
                        'subject_code'=> 'SI-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                    [
                        'school_id'=> $i,
                        'academic_id'=> $i,
                        'subject_name'=> 'Islam & Ethical Education',
                        'subject_code'=> 'IEE-01',
                        'subject_type'=> 'T',
                        'active_status'=> 1,
                        'created_at' => date('Y-m-d h:i:s')
                    ],
                ]);

                for ($j = 2; $j <= 4; $j++) {
                    $store = new SmVisitor();
                    $store->name = $faker->name;
                    $store->phone = $faker->tollFreePhoneNumber;
                    $store->visitor_id = $j;
                    $store->no_of_person = $faker->numberBetween(1, 5);
                    $store->purpose = $faker->word;
                    $store->date = $faker->dateTime()->format('Y-m-d');
                    $store->in_time = $faker->time($format = 'H:i A', $max = 'now');
                    $store->out_time = $faker->time($format = 'H:i A', $max = 'now');
                    $store->file = '';
                    $store->created_at = date('Y-m-d h:i:s');
                    $store->school_id = $i;
                    $store->save();
                }

                $obj = new GlobalVariable();
                $Names = $obj->Names;
                $basic_salary = 30000;

                for ($role_id = 4; $role_id <10; $role_id++) {
                    for ($j = 1; $j < 2; $j++) {
                    $gender_id = 1;

                    $name_index = array_rand($Names, 8);
                    $First_Name = $UserName = $faker->firstName($gender =  'male');
                    $Last_Name  =              $faker->lastName($gender =  'male');
                    $Full_name  = $First_Name . ' ' . $Last_Name;

                    //parents name genarator
                    $Father_First_Name  =   $faker->firstName($gender =  'male');
                    $Father_Last_Name   =   $faker->firstName($gender =  'male');
                    $Father_full_name   =   $Father_First_Name . ' ' . $Father_Last_Name;



                    $Mother_First_Name  =   $faker->firstName($gender =  'female');
                    $Mother_Last_Name   =   $faker->firstName($gender =  'female');
                    $Mother_full_name = $Mother_First_Name . ' ' . $Mother_Last_Name;



                    //insert staff user & pass
                    $newUser            = new User();
                    $newUser->role_id   = $role_id;
                    $newUser->full_name = $Full_name;
                    $newUser->email     = $First_Name . $j . '@infixedu.com';
                    $newUser->username  = $First_Name . $j . '@infixedu.com';
                    $newUser->password  = Hash::make(123456);
                    $newUser->created_at = date('Y-m-d h:i:s');
                    $newUser->school_id = $i;
                    $newUser->save();
                    $newUser->toArray();
                    $staff_id_number = $newUser->id;

                    //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                    DB::table('sm_staffs')->insert([

                        [
                            'user_id'          => $staff_id_number,
                            'role_id'          => $role_id,
                            'staff_no'         => count(\App\SmStaff::all()) + 1,
                            'designation_id'   => 1,
                            'department_id'    => 1,
                            'first_name'       => $First_Name,
                            'last_name'        => $Last_Name,
                            'full_name'        => $Full_name,
                            'fathers_name'     => $Father_full_name,
                            'mothers_name'     => $Mother_full_name,

                            'date_of_birth'    => $faker->date($format = 'Y-m-d', $max = 'now'),
                            'date_of_joining'  => $faker->date($format = 'Y-m-d', $max = 'now'),

                            'gender_id'        => $gender_id,
                            'email'            => $First_Name . $j . '@infixedu.com',
                            'mobile'           => '123456789',
                            'emergency_mobile' => '1234567890',
                            'marital_status'   => 'Married',
                            'staff_photo'      => '',
                            'current_address'  => $faker->address,
                            'permanent_address' => $faker->streetAddress,
                            'qualification'    => 'B.Sc in Computer Science',
                            'experience'       => '4 Years',
                            'basic_salary'     => $basic_salary + $j,
                            'casual_leave'     => '12',
                            'medical_leave'    => '15',
                            'metarnity_leave'  => '45',

                            'driving_license'  => '56776987453',
                            'driving_license_ex_date' => '2019-02-23',
                            'school_id' => $i,
                            'created_at' => date('Y-m-d h:i:s')
                        ]


                    ]);
                    }
                }



                for ($j = 1; $j <= 20; $j++) {
                                $gender_id = 1 + $j % 2;
            
                                $student_First_Name = $student_User_Name = $faker->firstName($gender = 'male');
                                $student_Last_Name  = $faker->lastName($gender = 'male');
                                $student_full_name  = $student_First_Name . ' ' . $student_Last_Name;
            
                                //parents name genarator
                                $Father_First_Name = $Father_User_Name = $faker->firstName($gender = 'male');
                                $Father_Last_Name  = $faker->lastName($gender = 'male');
                                $Father_full_name  = $Father_First_Name . ' ' . $Father_Last_Name;
            
                                $Mother_First_Name = $faker->firstName($gender = 'female');
                                $Mother_Last_Name  = $faker->lastName($gender = 'female');
                                $Mother_full_name  = $Mother_First_Name . ' ' . $Mother_Last_Name;
            
                                //guardians name gebarator
                                $Guardian_First_Name = $faker->firstName($gender = 'male');
                                $Guardian_Last_Name  = $faker->lastName($gender = 'male');
                                $Guardian_full_name  = $Guardian_First_Name . ' ' . $Guardian_Last_Name;
            
                                $studentEmail = strtolower($student_User_Name) . $j . '@infixedu.com';
            
                                //insert student user & pass
                                $newUser            = new User();
                                $newUser->role_id   = 2;
                                $newUser->full_name = $student_full_name;
                                $newUser->email     = $studentEmail;
                                $newUser->username  = $studentEmail;
                                $newUser->password  = Hash::make(123456);
            
                                $newUser->created_at = date('Y-m-d h:i:s');
                                $newUser->school_id = $i;
                                $newUser->save();
                                $newUser->toArray();
                                $student_id = $newUser->id;
            
                                //insert student user & pass
                                $newUser            = new User();
                                $newUser->role_id   = 3;
                                $newUser->full_name = $Father_full_name;
                                $newUser->email     = strtolower($Father_User_Name).'_pa' . $j . '@infixedu.com';
                                $newUser->username  = strtolower($Father_User_Name).'_pa' . $j . '@infixedu.com';
                                $newUser->password  = Hash::make(123456);
            
                                $newUser->created_at = date('Y-m-d h:i:s');
                                $newUser->school_id = $i;
                                $newUser->school_id = $i;
                                $newUser->save();
                                $newUser->toArray();
                                $parents_id = $newUser->id;
            
                                $parent          = new SmParent();
                                $parent->user_id = $parents_id;
            
                                $parent->fathers_name       = $Father_full_name;
                                $parent->fathers_mobile     = rand(1000, 9999) . rand(1000, 9999);
                                $parent->fathers_occupation = 'Teacher';
                                $parent->fathers_photo      = '';
            
                                $parent->mothers_name       = $Mother_full_name;
                                $parent->mothers_mobile     = rand(1000, 9999) . rand(1000, 9999);
                                $parent->mothers_occupation = 'Housewife';
                                $parent->mothers_photo      = '';
            
                                $parent->guardians_name       = $Guardian_full_name;
                                $parent->guardians_mobile     = rand(1000, 9999) . rand(1000, 9999);
                                $parent->guardians_email      = $Guardian_First_Name . $j . '@infixedu.com';
                                $parent->guardians_occupation = 'Businessman';
                                $parent->guardians_relation   = 'Brother';
                                $parent->relation             = 'Son';
                                $parent->guardians_photo      = '';
            
                                $parent->guardians_address = 'Dhaka-1219, Bangladesh';
                                $parent->is_guardian       = 1;
            
                                $parent->created_at = date('Y-m-d h:i:s');
                                $parent->school_id = $i;
                                $parent->academic_id = $i;
                                $parent->save();
                                $parent->toArray();
                                $parents_id = $parent->id;
            
            
            
                                DB::table('sm_students')->insert([
                                    [
                                        'user_id'                 => $student_id,
                                        'parent_id'               => $parents_id,
                                        'admission_no'            => $faker->numberBetween($min = 10000, $max = 90000),
                                        'roll_no'                 => $faker->numberBetween($min = 10000, $max = 90000),
                                        'class_id'                => $class_id,
                                        'student_category_id'     => 1,
                                        'role_id'     => 2,
                                        'section_id'              => $section_id,
                                        'session_id'              => 1,
                                        'caste'                   => 'Asian',
                                        'bloodgroup_id'           => 8 + $j % 8,
            
                                        //transport section
            
            
                                        'national_id_no'          => '237864238764' . $j * $j,
                                        'local_id_no'             => '237864238764' . $j * $j,
            
                                        'religion_id'             => 3 + $j % 5,
                                        'height'                  => 56,
                                        'weight'                  => 45,
            
                                        'first_name'              => $student_First_Name,
                                        'last_name'               => $student_Last_Name,
                                        'full_name'               => $student_full_name,
            
                                        'date_of_birth'           => $faker->date($format = 'Y-m-d', $max = 'now'),
                                        'admission_date'          => $faker->date($format = 'Y-m-d', $max = 'now'),
            
                                        'gender_id'               => $gender_id,
                                        'email'                   => $studentEmail,
                                        'mobile'                  => '+8801234567' . $j,
                                        'bank_account_no'         => '+8801234567' . $j,
            
                                        'bank_name'               => 'DBBL',
                                        'student_photo'           => '',
            
                                        'current_address'         => 'Bangladesh',
                                        'previous_school_details' => 'Bangladesh',
                                        'aditional_notes'         => 'Bangladesh',
            
                                        'permanent_address'       => 'Bangladesh',
                                        'school_id' => $i,
                                        'academic_id' => $i,
                                        'created_at' => date('Y-m-d h:i:s')
                                    ],
            
                                ]);
                }

                for ($j = 1; $j <= 3; $j++) {
                    $exam_time = new SmClassTime();
                    $exam_time->type = "exam";
                    $exam_time->period = $j."st period";
                    $exam_time->start_time = '09:00:00';
                    $exam_time->end_time = '12:00:00';
                    $exam_time->is_break = 0;
                    $exam_time->school_id = $i;
                    $exam_time->academic_id = $i;
                    $exam_time->save();
                }
                for ($j = 1; $j <= 3; $j++) {
                    $exam_type = new SmExamType();
                    $exam_type->title = $j."Term";
                    $exam_type->active_status = 1;
                    $exam_type->created_at = date('Y-m-d h:i:s');
                    $exam_type->school_id = $i;
                    $exam_type->academic_id = $i;
                    $exam_type->save();
                }


                $teacher = SmStaff::where('role_id', 4)->where('school_id',$i)->first();
                $class_ids = SmClass::where('school_id', $i)->first();
                $data = SmClassSection::where('class_id', $class_ids->id)->where('school_id',$i)->get();
                $subject_id = SmSubject::where('school_id', $i)->get();

                foreach ($data as $datum) {
                    $class_id = $datum->class_id;
                    $section_id = $datum->section_id;
                    foreach ($subject_id as $subject) {

                        DB::table('sm_assign_subjects')->insert([
                            [
                                'class_id' => $class_id,
                                'section_id' => $section_id,
                                'teacher_id' => $teacher->id,
                                'subject_id' => $subject->id,
                                'school_id' => $i,
                                'academic_id' => $i,
                                'created_at' => date('Y-m-d h:i:s')
                            ]
                        ]);
                    }
                }


                $exams_types= SmExamType::where('school_id',$i)->get();
                $class_id = SmClass::where('school_id', $i)->where('academic_id',$i)->first('id')->id;
                $sections = SmClassSection::where('class_id', $class_id)->get();
                $subjects_ids = SmSubject::where('school_id', $i)->get();
                foreach ($exams_types as $exam_type_id) {
                    foreach ($sections as $section) {
                        $subject_for_sections = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section->section_id)->get();
                        $eligible_subjects = [];
                        foreach ($subject_for_sections as $subject_for_section) {
                            $eligible_subjects[] = $subject_for_section->subject_id;
                        }
                        foreach ($subjects_ids as $subject_id) {
                            if (in_array($subject_id->id, $eligible_subjects)) {
                                $exam = new SmExam();
                                $exam->exam_type_id = $exam_type_id->id;
                                $exam->class_id = $class_id;
                                $exam->section_id = $section->section_id;
                                $exam->subject_id = $subject_id->id;
                                $exam->exam_mark = 100;
                                $exam->school_id = $i;
                                $exam->academic_id = $i;
                                $exam->save();
                                // $exam->toArray();
                                $ex_title = "Written";
                                $ex_mark = 100;
                                $newSetupExam = new SmExamSetup();
                                $newSetupExam->exam_id = $exam->id;
                                $newSetupExam->class_id = $class_id;
                                $newSetupExam->section_id = $section->section_id;
                                $newSetupExam->subject_id = $subject_id->id;
                                $newSetupExam->exam_term_id = $exam_type_id->id;
                                $newSetupExam->exam_title = $ex_title;
                                $newSetupExam->exam_mark = $ex_mark;
                                $newSetupExam->school_id = $i;
                                $newSetupExam->academic_id = $i;
                                $newSetupExam->save();
                            }
                        }
                    }
                }


                for($j=301; $j <= 304; $j++){
                    $class_room = new SmClassRoom();
                    $class_room->room_no = "Room".$j;
                    $class_room->capacity = 50;
                    $class_room->school_id = $i;
                    $class_room->academic_id = $i;
                    $class_room->save();
                }
                
                
                
                $examTime = SmClassTime::where('school_id',$i)->where('academic_id',$i)->first();
                $room = SmClassRoom::where('school_id',$i)->where('academic_id',$i)->first();
                    foreach($exams_types as $exam_type_id){
                        foreach($sections as $section){
                            foreach ($subjects_ids as $subject_id) {
                                $exam_routine = new SmExamSchedule();
                                $exam_routine->class_id = $class_id;
                                $exam_routine->section_id = $section->section_id;
                                $exam_routine->subject_id = $subject_id->id;
                                $exam_routine->exam_period_id = $examTime->id;
                                $exam_routine->exam_term_id = $exam_type_id->id;
                                $exam_routine->room_id = $room->id;
                                $exam_routine->school_id = $i;
                                $exam_routine->academic_id = $i;
                                $exam_routine->save();
                            }
                        }
                    }

                

                $examType= SmExamType::where('school_id',$i)->where('academic_id',$i)->first('id')->id;

                    foreach($sections as $section){
                        foreach ($subjects_ids as $subject_id) {
                            $exam_attendance = new SmExamAttendance();
                            $exam_attendance->exam_id = $examType;
                            $exam_attendance->subject_id = $subject_id->id;
                            $exam_attendance->class_id = $class_id;
                            $exam_attendance->section_id = $section->section_id;
                            $exam_attendance->school_id = $i;
                            $exam_attendance->academic_id = $i;
                            $exam_attendance->save();
                            
                        }
                    }

                $students = SmStudent::where('school_id',$i)->where('academic_id',$i)->get();
                $infos = SmExamAttendance::where('school_id',$i)->where('academic_id',$i)->get();

                foreach($infos as $info){
                    foreach ($students as $student) {    
                        $exam_attendance_child = new SmExamAttendanceChild();
                        $exam_attendance_child->exam_attendance_id = $info->id;
                        $exam_attendance_child->student_id = $student->id;
                        $exam_attendance_child->attendance_type = "P";
                        $exam_attendance_child->school_id = $i;
                        $exam_attendance_child->academic_id = $i;
                        $exam_attendance_child->save();
                    }
                }


                $dataGrade = [
                    ['A+',  '5.00',  5.00,    5.99,   80, 100,     'Outstanding !'],
                    ['A',  '4.00',  4.00,    4.99,   70, 79,      'Very Good !'],
                    ['A-',  '3.50',  3.50,    3.99,   60, 69,      'Good !'],
                    ['B',  '3.00',  3.00,    3.49,   50, 59,     'Outstanding !'],
                    ['C',  '2.00',  2.00,    2.99,   40, 49,      'Bad !'],
                    ['D',  '1.00',  1.00,    1.99,   33, 39,      'Very Bad !'],
                    ['F',  '0.00',  0.00,    0.99,   0, 32,       'Failed !'],
                ];
                foreach ($dataGrade as $r) {
                    $store = new SmMarksGrade();
                    $store->academic_id         = $i;
                    $store->school_id           = $i;
                    $store->grade_name          = $r[0];
                    $store->gpa                 = $r[1];
                    $store->from                = $r[2];
                    $store->up                  = $r[3];
                    $store->percent_from        = $r[4];
                    $store->percent_upto        = $r[5];
                    $store->description         = $r[6];
                    $store->save();
                }





                // Mark Register
                foreach($sections as $section){
                    foreach ($subjects_ids as $subject_id) {
                        foreach ($students as $student) { 
                            $marks_register = new SmMarkStore();
                            $marks_register->exam_term_id           = $examType;
                            $marks_register->class_id               = $class_id;
                            $marks_register->section_id             = $section->section_id;
                            $marks_register->subject_id             = $subject_id->id;

                                $marks_register->student_id         = $student->id;
                                $marks_register->total_marks        = rand(25,100);
                            // $marks_register->exam_setup_id          = $exam_setup_id;
                            $marks_register->teacher_remarks        = "Good";
                            $marks_register->school_id = $i;
                            $marks_register->academic_id = $i;
                            $marks_register->save();

                            $mark_grade = SmMarksGrade::where([
                                        ['percent_from', '<=', $marks_register->total_marks], 
                                        ['percent_upto', '>=', $marks_register->total_marks]])
                                        ->where('academic_id',$i)
                                        ->where('school_id',$i)
                                        ->first();


                            $result_record = new SmResultStore();
                            $result_record->class_id               =   $class_id;
                            $result_record->section_id             =   $section->section_id;
                            $result_record->subject_id             =   $subject_id->id;
                            $result_record->exam_type_id           =   $examType;

                            
                                $result_record->student_id             = $student->id;
                                $result_record->total_marks            = $marks_register->total_marks;

                            $result_record->total_gpa_point        = @$mark_grade->gpa;
                            $result_record->total_gpa_grade        = @$mark_grade->grade_name;
                            $result_record->teacher_remarks        = "Good";
                            $result_record->school_id              = $i;
                            $result_record->academic_id            = $i;
                            $result_record->save();

                        }
                    }
                }


                for($j=1; $j<=5; $j++){
                    $store= new SmQuestionGroup();
                    $store->title = $faker->word;
                    $store->save();
                }


                // Fees

                DB::table('sm_fees_groups')->insert([          
                    [
                        'name' => 'Library Fee',
                        'type' => 'System',
                        'description' => 'System Automatic created this fee group',
                        'school_id' => $i,
                        'academic_id' => $i,
                    ],
                    [
                        'name' => 'Processing Fee',
                        'type' => 'System',
                        'description' => 'System Automatic created this fee group',
                        'school_id' => $i,
                        'academic_id' => $i,
                    ],
                    [
                        'name' => 'Tuition Fee',
                        'type' => 'System',
                        'description' => 'System Automatic created this fee group',
                        'school_id' => $i,
                        'academic_id' => $i,
                    ],
                    [
                        'name' => 'Development Fee',
                        'type' => 'System',
                        'description' => 'System Automatic created this fee group',
                        'school_id' => $i,
                        'academic_id' => $i,
                    ]
                ]);


                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $feeesGroups = SmFeesGroup::where('school_id',$i)->where('academic_id',$i)->get();

                $array = ['Library','Sports','Environment','E-learning'];
                foreach($feeesGroups as $key =>$feeesGroup){
                        $store                  = new SmFeesType();
                        $store->name            = $array[$key];
                        $store->fees_group_id   = $feeesGroup->id;
                        $store->description     = 'Sample Data genarated';
                        $store->school_id       = $i;
                        $store->academic_id     = $i;
                        $store->save(); 
                }

                $feesTypeData = SmFeesType::where('school_id',$i)->where('academic_id',$i)->get();
                foreach ($feesTypeData as $row) {
                    $store= new SmFeesMaster();
                    $store->fees_group_id= $row->fees_group_id;
                    $store->fees_type_id= $row->id;
                    $store->amount=500+rand()%500;
                    $store->school_id       = $i;
                    $store->academic_id     = $i;
                    $store->save(); 
                }


                $feesMaterDatas = SmFeesMaster::where('school_id',$i)->where('academic_id',$i)->get();
                foreach($students as $student){
                    foreach($feesMaterDatas as $feesMaterData){
                        $assign_fees = new SmFeesAssign();
                        $assign_fees->student_id = $student->id;
                        $assign_fees->fees_amount = $feesMaterData->amount;
                        $assign_fees->fees_master_id = $feesMaterData->id;
                        $assign_fees->school_id = $i;
                        $assign_fees->academic_id = $i;
                        $assign_fees->save();
                    }
                }

            

            } // End For

            $findSaas= InfixModuleManager::where('name','Saas')->first();
            $saasPurchase= InfixModuleManager::find($findSaas->id);
            $saasPurchase->purchase_code = 123456;
            $saasPurchase->is_default = 0;
            $saasPurchase->update();

            $act = SmGeneralSettings::first();
            $act->Saas= 1;
            $act->save();
        
        }
    }
}
