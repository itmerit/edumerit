<?php

namespace Database\Seeders;

use App\User;
use App\SmBook;
use App\SmItem;
use App\SmNews;
use App\SmPage;
use App\SmClass;
use App\SmRoute;
use App\SmStaff;
use App\SmStyle;
use App\SmCourse;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmVehicle;
use App\SmVisitor;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmExamType;
use App\SmFeesType;
use App\SmHomework;
use App\SmNewsPage;
use App\SmRoomList;
use App\SmRoomType;
use App\SmSupplier;
use App\SmAboutPage;
use App\SmAddIncome;
use App\SmBaseSetup;
use App\SmBookIssue;
use App\SmClassRoom;
use App\SmComplaint;
use App\SmExamSetup;
use App\SmFeesGroup;
use App\SmItemStore;
use App\SmLeaveType;
use App\Models\Theme;
use App\SmAddExpense;
use App\SmCoursePage;
use App\SmCustomLink;
use App\SmFeesMaster;
use App\SmMarksGrade;
use App\SmSetupAdmin;
use App\SmBankAccount;
use App\SmDesignation;
use App\SmLeaveDefine;
use App\SmTestimonial;
use App\LibrarySubject;
use App\SmAcademicYear;
use App\SmBookCategory;
use App\SmClassTeacher;
use App\SmFeesDiscount;
use App\SmItemCategory;
use App\SmLeaveRequest;
use App\SmNewsCategory;
use App\SmStudentGroup;
use App\SmAssignSubject;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use App\SmLibraryMember;
use App\SmPostalReceive;
use App\SmAdmissionQuery;
use App\SmChartOfAccount;
use App\SmContactMessage;
use App\SmCourseCategory;
use App\SmExamAttendance;
use App\SmPaymentMethhod;
use App\SmPostalDispatch;
use App\SmGeneralSettings;
use App\SmHomePageSetting;
use App\SmHomeworkStudent;
use App\SmHumanDepartment;
use App\SmStaffAttendence;
use App\SmStudentCategory;
use App\SmBackgroundSetting;
use App\SmHeaderMenuManager;
use App\SmHrPayrollGenerate;
use App\SmStudentAttendance;
use App\SmAssignClassTeacher;
use App\SmClassRoutineUpdate;
use App\SmStudentCertificate;
use App\SmTeacherUploadContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Lesson\Entities\SmLesson;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Saas\Events\InstituteRegistration;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;

class SmSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmSchool::factory()->times(2)->create()->each(
            function ($school) {
                $role_id = User::where('school_id', $school->id)->where('role_id', 1)->first() ? 5 :1;
                //school admin user
                User::factory()->times(1)->create([
                    'school_id' => $school->id,
                    'username' => $school->email,
                    'email' => $school->email,
                    'role_id' => $role_id,
                ])->each(function ($user) use($role_id){
                    SmStaff::factory()->times(1)->create([
                        'role_id' => $role_id,
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'full_name' => $user->full_name,
                    ]);
                });
                event(new InstituteRegistration($school));
            });
        // SmSchool::factory()->times(1)->create()->each(
        //     function ($school) {
        //         $role_id = User::where('school_id', $school->id)->where('role_id', 1)->first() ? 5 :1;
        //         //school admin user
        //         User::factory()->times(1)->create([
        //             'school_id' => $school->id,
        //             'username' => $school->email,
        //             'email' => $school->email,
        //             'role_id' => $role_id,
        //         ])->each(function ($user) use($role_id){
        //             SmStaff::factory()->times(1)->create([
        //                 'role_id' => $role_id,
        //                 'user_id' => $user->id,
        //                 'email' => $user->email,
        //                 'full_name' => $user->full_name,
        //             ]);
        //         });
        //         SmDesignation::factory()->times(10)->create([
        //             'school_id' => $school->id,
        //         ]);
        //         SmHumanDepartment::factory()->times(10)->create([
        //             'school_id' => $school->id,
        //         ]);
        //         SmBaseSetup::factory()->times(10)->create([
        //             'school_id' => $school->id,
        //         ]);

        //         SmAcademicYear::factory()->times(1)->create([
        //             'school_id' => $school->id,
        //         ])->each(function ($academic_year) use ($school) {
        //             $school_academic = [
        //                 'school_id' => $school->id,
        //                 'academic_id' => $academic_year->id,
        //             ];
        //             SmGeneralSettings::factory()->times(1)->create(array_merge([
        //                 'session_id' => $academic_year->id,
        //                 'email' => $school->email,
        //                 'school_name' => $school->school_name,
        //             ],$school_academic));
        //             $smRoute=SmRoute::factory()->times(5)->create($school_academic);
        //             SmSetupAdmin::factory()->times(20)->create($school_academic);
        //             SmStudentCategory::factory()->times(10)->create($school_academic);
        //             SmStudentGroup::factory()->times(10)->create($school_academic);
        //             //staff user
        //             User::factory()->times(10)->create([
        //                 'school_id' => $school->id,
        //             ])->each(function ($userStaff) use ($school,$school_academic,$smRoute) {
        //                 $staffs=SmStaff::factory()->times(1)->create([
        //                     'user_id' => $userStaff->id,
        //                     'email' => $userStaff->email,
        //                     'full_name' => $userStaff->full_name,
        //                     'school_id' => $school->id,
        //                     'role_id'=>$userStaff->role_id,
        //                 ])->each(function ($staffs) use ($school_academic,$smRoute) {
        //                     if ($staffs->role_id == 9) {
        //                         SmVehicle::factory()->times(1)->create(array_merge([
        //                            'driver_id' =>$staffs->id, 
        //                         ],$school_academic))->each(function ($smVehicle) use ($school_academic,$smRoute){
        //                             SmAssignVehicle::factory()->times(1)->create(array_merge([
        //                                 'vehicle_id' =>$smVehicle->id,
        //                                 'route_id' =>null,
        //                             ],$school_academic));
        //                         });
        //                     }
        //                     SmHrPayrollGenerate::factory()->times(1)->create(array_merge([
        //                         'staff_id'=>$staffs->id,
        //                     ],$school_academic));
        //                 });
        //             });
        //             $teacher_id = SmStaff::where('role_id', 4)->where('school_id', $school->id)->first()->id;
        //             //subject seeder
        //             $sections = SmSection::factory()->times(5)->create($school_academic);
        //             $subjects = SmSubject::factory()->times(2)->create($school_academic);
        //             //Class room Seeder
        //             $rooms = SmClassRoom::factory()->times(10)->create($school_academic);
        //             //class seeder
        //             $classes=SmClass::factory()->times(2)->create($school_academic)->each(function ($class) use ($sections, $school_academic, $school, $subjects, $teacher_id, $rooms, $academic_year) {
        //                 $class_sections = [];
        //                 foreach ($sections as $section) {
        //                     $class_sections[] = array_merge(['class_id' => $class->id, 'section_id' => $section->id], $school_academic);
        //                 }

        //                 $class_sections = $class->classSection()->createMany($class_sections)->each(function ($classSections) use ($school_academic, $school, $teacher_id, $rooms, $subjects, $academic_year) {
                           
        //                     SmAssignClassTeacher::factory()->times(1)->create($school_academic)->each(function ($assignClassTeacher) use ($school_academic, $teacher_id) {
        //                         SmClassTeacher::factory()->times(1)->create(array_merge([
        //                             'assign_class_teacher_id' => $assignClassTeacher->id,
        //                             'teacher_id' => $teacher_id,
        //                         ], $school_academic));
        //                     });

        //                     SmTeacherUploadContent::factory()->times(10)->create(array_merge([
        //                         'class' => $classSections->class_id,
        //                         'section' => $classSections->section_id,
        //                     ], $school_academic));

        //                 }); /* data insert into sm_class_section_table */

        //                 //assign subject
        //                 foreach ($class_sections as $data) {
        //                     foreach ($subjects as $subject) {
        //                         DB::table('sm_assign_subjects')->insert(array_merge([
        //                             'class_id' => $data->class_id,
        //                             'section_id' => $data->section_id,
        //                             'teacher_id' => $teacher_id,
        //                             'subject_id' => $subject->id,
        //                         ], $school_academic));
        //                     }

        //                     //student  & parent insert

        //                     for ($i = 1; $i <= 5; $i++) {                                
        //                         User::factory()->times(1)->create([
        //                             'role_id' =>2,
        //                             'email'=>'student_'.$data->class_id.'_'.$data->section_id.'_'.$i.'@infixedu.com', 
        //                             'username'=>'student_'.$data->class_id.'_'.$data->section_id.'_'.$i.'@infixedu.com',
        //                             'school_id' =>$school->id,
        //                         ]);
        //                         User::factory()->times(1)->create([
        //                             'role_id' =>3,
        //                             'email'=>'guardian_'.$data->class_id.'_'.$data->section_id.'_'.$i.'@infixedu.com', 
        //                             'username'=>'guardian_'.$data->class_id.'_'.$data->section_id.'_'.$i.'@infixedu.com',
        //                             'school_id' =>$school->id,
        //                         ]);
        //                         $studentUser=User::where('school_id',$school->id)->where('role_id',2)->latest('id')->first();
        //                         $parentUser=User::where('school_id',$school->id)->where('role_id',3)->latest('id')->first();

        //                         SmParent::factory()->times(1)->create(array_merge([
        //                             'user_id' => $parentUser->id,
        //                             'guardians_email' => $parentUser->email,                                 
        //                         ], $school_academic));

        //                         $parent=SmParent::where('school_id',$school->id)->where('academic_id',$academic_year->id)->latest('id')->first();

        //                        SmStudent::factory()->times(1)->create(array_merge([
                                   
        //                             'session_id' => $academic_year->id,
        //                             'user_id' => $studentUser->id,
        //                             'parent_id' => $parent->id,
        //                             'class_id' => $data->class_id,
        //                             'section_id' => $data->section_id,
        //                             'email' => 'student_'.$data->class_id.'_'.$data->section_id.'_'.$i.'@infixedu.com',                                
        //                         ], $school_academic));                                       
                    
                                
        //                     }
        //                 }
        //                 //end assign subject
        //                 SmAdmissionQuery::factory()->times(10)->create(array_merge(['class' => $class->id], $school_academic));

        //                 //end class id
        //             });
        //             $classes = SmClass::where('school_id', $school->id)->get(['id', 'class_name']);
        //             //class routine
        //             $classSectionSubjects=SmAssignSubject::where('school_id',$school->id)->where('academic_id',$academic_year->id)->get();
        //             SmWeekend::factory()->times(7)->create($school_academic)->each(function ($day) use ($school_academic,$classSectionSubjects) {
                        
        //                 foreach($classSectionSubjects as  $classSectionSubject){                  
                                                     
        //                  SmClassRoutineUpdate::factory()->times(1)->create(array_merge([
        //                     'day' => $day->id,
        //                     'class_id' => $classSectionSubject->class_id,
        //                     'section_id' => $classSectionSubject->section_id,
        //                     'subject_id' => $classSectionSubject->subject_id,
        //                 ], $school_academic));  
                
                        
        //             }

 
        //             });
        //             $classSectionSubjects=SmAssignSubject::where('school_id',$school->id)->where('academic_id',$academic_year->id)->get();
        //             foreach($classSectionSubjects as  $classSectionSubject){ 
        //                 $s = new SmHomework();
        //                 $s->class_id =  $classSectionSubject->class_id;
        //                 $s->section_id = $classSectionSubject->section_id;
        //                 $s->subject_id = $classSectionSubject->subject_id;
        //                 $s->homework_date = date('Y-m-d');
        //                 $s->submission_date = date('Y-m-d');
        //                 $s->evaluation_date = date('Y-m-d');
        //                 $s->evaluated_by = 1;
        //                 $s->marks = rand(10, 15);
        //                 $s->description = 'Test';
        //                 $s->created_at = date('Y-m-d h:i:s');
        //                 $s->school_id = $school->id;
        //                 $s->academic_id = $academic_year->id;
        //                 $s->save();
        //              }
        //             //end class routine
        //             $homeworks = SmHomework::where('school_id',$school->id)->first();
        //             $students = SmStudent::where('school_id',$school->id)->get(['id','user_id']);
        //             foreach ($students as $student) {                      
                      
        //                     $s = new SmHomeworkStudent();
        //                     $s->student_id = $student->id;
        //                     $s->homework_id = $homeworks->id;
        //                     $s->marks = rand(5, 10);
        //                     $s->teacher_comments = 'faker';
        //                     $s->complete_status = 'C';
        //                     $s->school_id = $school->id;
        //                     $s->academic_id = $academic_year->id;
        //                     $s->created_at = date('Y-m-d h:i:s');
        //                     $s->save();
                      
        //                 $SmLibraryMember = new SmLibraryMember();
        //                 $SmLibraryMember->member_ud_id = rand(10,100000000000000); 
        //                 $SmLibraryMember->member_type = rand(1,8);     
        //                 $SmLibraryMember->student_staff_id = $student->id;
        //                 $SmLibraryMember->active_status = 1;                      
        //                 $SmLibraryMember->school_id = $school->id;
        //                 $SmLibraryMember->academic_id = $academic_year->id;
        //                 $SmLibraryMember->created_at = date('Y-m-d h:i:s');
        //                 $SmLibraryMember->save();
        //             }
        //             //admission Query
        //             SmAdmissionQuery::factory()->times(10)->create($school_academic);
        //             //visitor
        //             SmVisitor::factory()->times(10)->create($school_academic);
        //             SmComplaint::factory()->times(10)->create($school_academic);
        //             SmPostalReceive::factory()->times(10)->create($school_academic);
        //             SmPostalDispatch::factory()->times(10)->create($school_academic);
        //             //phonelog
        //             SmPostalDispatch::factory()->times(10)->create($school_academic);
        //             // SmStudentCertificate::factory()->times(1)->create($school_academic);
        //             //student id card


        //             //fees Collection
        //             $discount = SmFeesDiscount::factory()->times(10)->create($school_academic);
        //             SmFeesGroup::factory()->times(5)->create($school_academic)->each(function ($feesGroup) use ($school_academic) {
        //                 SmFeesType::factory()->times(5)->create(array_merge([
        //                     'fees_group_id' => $feesGroup->id,
        //                 ], $school_academic))->each(function ($feesTypes) use ($school_academic) {
        //                     SmFeesMaster::factory()->times(1)->create(array_merge([
        //                         'fees_group_id' => $feesTypes->fees_group_id,
        //                         'fees_type_id' => $feesTypes->id,
        //                     ], $school_academic));
        //                 });
        //             });

        //             //end fess collection

        //             //
        //             //Examination
        //             $assignSubjects = SmAssignSubject::where('school_id', $school->id)->where('academic_id', $academic_year->id)->get();
        //             SmMarksGrade::factory()->times(7)->create($school_academic);
        //             SmExamType::factory()->times(3)->create($school_academic)->each(function ($examTerm) use ($assignSubjects, $school, $academic_year) {
        //                 foreach ($assignSubjects as $classSectionSubject) {
        //                     $s = new SmExamSetup();
        //                     $s->class_id = $classSectionSubject->class_id;
        //                     $s->section_id = $classSectionSubject->section_id;
        //                     $s->subject_id = $classSectionSubject->subject_id;
        //                     $s->exam_term_id = $examTerm->id;
        //                     $s->exam_title = 'Exam';
        //                     $s->exam_mark = 100;
        //                     $s->school_id = $school->id;
        //                     $s->academic_id = $academic_year->id;
        //                     $s->save();

        //                     $store= new SmExamAttendance();
        //                     $store->exam_id=$examTerm->id;
        //                     $store->subject_id=$classSectionSubject->subject_id;
        //                     $store->class_id=$classSectionSubject->class_id;
        //                     $store->section_id=$classSectionSubject->section_id;                           
        //                     $store->created_at = date('Y-m-d h:i:s');
        //                     $store->school_id = $school->id;
        //                     $store->academic_id = $academic_year->id;
        //                     $store->save();
        //                 }

        //             });

        //             $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        //             $students = SmStudent::where('school_id', $school->id)->where('academic_id', $academic_year->id)->get(['id', 'user_id']);
        //             $staffs = SmStaff::whereIn('role_id', [4, 5, 6, 7, 8, 9])->where('school_id', $school->id)->get(['id', 'user_id']);

        //             for ($i = 1; $i <= $days; $i++) {
        //                 foreach ($students as $student) {
        //                     if ($i <= 9) {
        //                         $d = '0' . $i;
        //                     }
        //                     $date = date('Y') . '-' . date('m') . '-' . $d;
        //                     $sa = new SmStudentAttendance();
        //                     $sa->student_id = $student->id;
        //                     $sa->attendance_type = 'P';
        //                     $sa->notes = 'Sample Attendance for Student';
        //                     $sa->attendance_date = $date;
        //                     $sa->school_id = $school->id;
        //                     $sa->academic_id = $academic_year->id;
        //                     $sa->save();
        //                 }
        //                 foreach ($staffs as $staff) {
        //                     if ($i <= 9) {
        //                         $d = '0' . $i;
        //                     }
        //                     $date = date('Y') . '-' . date('m') . '-' . $d;

        //                     $sa = new SmStaffAttendence();
        //                     $sa->staff_id = $staff->id;
        //                     $sa->attendence_type = 'P';
        //                     $sa->notes = 'Sample Attendance for Staff';
        //                     $sa->attendence_date = $date;
        //                     $sa->school_id = $school->id;
        //                     $sa->academic_id = $academic_year->id;
        //                     $sa->save();
        //                 }
        //             }
        //             $rules = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1) /* ->where('id', '!=', 2) */->where('id', '!=', 3)->where('id', '!=', 10)->get();
        //             $staffs = SmStaff::where('role_id', 4)->where('school_id', $school->id)->get();
        //             SmLeaveType::factory()->times(5)->create($school_academic)->each(function ($leaveTypes) use ($rules, $school, $academic_year, $staffs) {
        //                 foreach ($rules as $key => $value) {
        //                     $users = User::where('role_id', $value->id)->get();
        //                     foreach ($users as $user) {
        //                         $store = new SmLeaveDefine();
        //                         $store->role_id = $value->id;
        //                         $store->user_id = $user->id;
        //                         $store->type_id = $leaveTypes->id;
        //                         $store->days = $leaveTypes->total_days;
        //                         $store->school_id = $school->id;
        //                         $store->academic_id = $academic_year->id;
        //                         $store->save();
        //                     }
        //                 }
        //                 foreach ($staffs as $staff) {

        //                     $store = new SmLeaveRequest();
        //                     $store->type_id = $leaveTypes->id;
        //                     $store->leave_define_id = 1;
        //                     $store->staff_id = $staff->id;
        //                     $store->role_id = 4;
        //                     $store->apply_date = Carbon::now()->format('Y-m-d');
        //                     $store->leave_from = Carbon::now()->format('Y-m-d');
        //                     $store->leave_to = Carbon::now()->addDays(2)->format('Y-m-d');
        //                     $store->reason = 'Seeder Leave';
        //                     $store->note = 'Seeder Leave';
        //                     $store->file = "public/uploads/leave_request/sample.pdf";
        //                     $store->approve_status = "P";
        //                     $store->school_id = $school->id;
        //                     $store->academic_id = $academic_year->id;
        //                     $store->save();
        //                 }

        //             });

        //             //Accounts
        //             SmBankAccount::factory()->times(10)->create($school_academic);
        //             SmChartOfAccount::factory()->times(5)->create($school_academic)->each(function ($smChartOfAccount) use($school_academic){
        //                 if($smChartOfAccount=='I'){
        //                     SmAddIncome::factory()->times(5)->create(array_merge([
        //                         'income_head_id'=>$smChartOfAccount->id,
        //                     ],$school_academic));
        //                 }
        //                 if($smChartOfAccount=='E'){
        //                     SmAddExpense::factory()->times(5)->create(array_merge([
        //                         'income_head_id'=>$smChartOfAccount->id,
        //                     ],$school_academic));
        //                 }
        //             });


        //             SmRoomType::factory()->times(5)->create($school_academic);
        //             $smRoomTypes=SmRoomType::where('school_id',$school->id)->where('academic_id',$academic_year->id)->get();
        //             SmDormitoryList::factory()->times(5)->create($school_academic)->each(function ($SmDormitoryLists) use ($smRoomTypes,$school_academic){
        //                 foreach($smRoomTypes as $room) {
        //                     SmRoomList::factory()->times(1)->create(array_merge([
        //                         'dormitory_id'=>$SmDormitoryLists->id,
        //                         'room_type_id'=>$room->id,
        //                     ],$school_academic));
        //                 }
        //             });
        //             SmItemCategory::factory()->times(10)->create($school_academic)->each(function ($itemCategory) use($school_academic){
        //                 SmItem::factory()->times(5)->create(array_merge([
        //                     'item_category_id' =>$itemCategory->id,
        //                 ],$school_academic));
        //             });
        //             SmItemStore::factory()->times(10)->create($school_academic);
        //             SmSupplier::factory()->times(10)->create($school_academic);
        //             SmBookCategory::factory()->times(10)->create($school_academic)->each(function($bookCategory) use ($school_academic,$students,$school,$academic_year){
        //                 LibrarySubject::factory()->times(10)->create(array_merge([
        //                     'sb_category_id' => $bookCategory->id,
        //                 ],$school_academic));
        //                 SmBook::factory()->times(1)->create(array_merge([
        //                         'book_category_id' => $bookCategory->id,
        //                     ],$school_academic))->each(function ($smBooks) use ($students,$school,$academic_year){
        //                         foreach ($students as $student) {
        //                             $store = new SmBookIssue();
        //                             $store->member_id = $student->id;
        //                             $store->book_id = $smBooks->id;
        //                             $store->quantity = rand(1, 5);
        //                             $store->given_date = Carbon::now()->format('Y-m-d');                                   
        //                             $store->issue_status = "I"; 
        //                             $store->school_id = $school->id;
        //                             $store->academic_id = $academic_year->id;
        //                             $store->save();
        //                         }
        //                     });
                        
        //             });


        //         });
        //         //end academic id

        //         SmHomePageSetting::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmNewsPage::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmNewsCategory::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ])->each(function($newsCategory) use ($school){
        //             SmNews::factory()->times(1)->create([
        //                 'category_id' =>$newsCategory->id,
        //                 'school_id' =>$school->id,
        //             ]);
        //         });
        //         SmCoursePage::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmCourseCategory::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ])->each(function ($courseCategory) use ($school){
        //             SmCourse::factory()->times(1)->create([
        //                 'category_id' =>$courseCategory->id,
        //                 'school_id' =>$school->id,
        //             ]);
        //         });

        //         SmTestimonial::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmBackgroundSetting::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmContactMessage::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
        //         SmAboutPage::factory()->times(1)->create([
        //             'school_id' =>$school->id,
        //         ]);
                


        //         if (moduleStatusCheck('RazorPay') == true) {
        //             $payment_methods = ['Cash', 'Cheque', 'Bank', 'Stripe', 'Paystack', 'PayPal', 'RazorPay'];
        //             DB::table('sm_payment_gateway_settings')->insert([
        //                 [
        //                     'gateway_name' => 'RazorPay',
        //                     'gateway_username' => 'demo@gmail.com',
        //                     'gateway_password' => '12334589',
        //                     'gateway_client_id' => '',
        //                     'gateway_secret_key' => '',
        //                     'gateway_publisher_key' => '',
        //                     'school_id' => $school->id,
        //                 ],
        
        //             ]);
        //         } else {
        //             $payment_methods = ['Cash', 'Cheque', 'Bank', 'Stripe', 'Paystack', 'PayPal'];
        //         }
        
        //         foreach ($payment_methods as $payment_method) {
        //             $method = new SmPaymentMethhod();
        //             $method->method = $payment_method;
        //             $method->type = 'System';
        //             $method->school_id = $school->id;
        //             $method->save();
        //         }
        
        //         // End if razorpay enable
        
        //         DB::table('sm_payment_gateway_settings')->insert([
        //             [
        //                 'gateway_name' => 'Stripe',
        //                 'gateway_username' => 'demo@strip.com',
        //                 'gateway_password' => '12334589',
        //                 'gateway_client_id' => '',
        //                 'gateway_secret_key' => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j',
        //                 'gateway_secret_word' => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1',
        //                 'school_id' => $school->id,
        //             ],
        //         ]);
        
        //         DB::table('sm_payment_gateway_settings')->insert([
        //             [
        //                 'gateway_name' => 'Paystack',
        //                 'gateway_username' => 'demo@gmail.com',
        //                 'gateway_password' => '12334589',
        //                 'gateway_client_id' => '',
        //                 'gateway_secret_key' => 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1',
        //                 'gateway_publisher_key' => 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71',
        //                 'school_id' => $school->id,
        //             ],
        
        //         ]);
        
        //         DB::table('sm_payment_gateway_settings')->insert([
        //             [
        //                 'gateway_name' => 'PayPal',
        //                 'gateway_username' => 'demo@paypal.com',
        //                 'gateway_password' => '12334589',
        //                 'gateway_client_id' => 'AaCPtpoUHZEXCa3v006nbYhYfD0HIX-dlgYWlsb0fdoFqpVToATuUbT43VuUE6pAxgvSbPTspKBqAF0x69',
        //                 'gateway_secret_key' => 'EJ6q4h8w0OanYO1WKtNbo9o8suDg6PKUkHNKv-T6F4APDiq2e19OZf7DfpL5uOlEzJ_AMgeE0L2PtTEj69',
        //                 'gateway_publisher_key' => '',
        //                 'school_id' => $school->id,
        //             ],
        
        //         ]);
        
        //         DB::table('sm_payment_gateway_settings')->insert([
        //             [
        //                 'gateway_name' => 'Bank',
        //                 'school_id' => $school->id,
        //             ],
        
        //         ]);
        
        //         DB::table('sm_payment_gateway_settings')->insert([
        //             [
        //                 'gateway_name' => 'Cheque',
        //                 'school_id' => $school->id,
        //             ],
        
        //         ]);
        
        //         DB::table('sm_sms_gateways')->insert([
        //             [
        //                 'gateway_name' => 'Clickatell',
        //                 'clickatell_username' => 'demo1',
        //                 'clickatell_password' => '122334',
        //                 'school_id' => $school->id,
        //             ],
        //             [
        //                 'gateway_name' => 'Twilio',
        //                 'clickatell_username' => 'demo2',
        //                 'clickatell_password' => '12336',
        //                 'school_id' => $school->id,
        //             ],
        //             [
        //                 'gateway_name' => 'Msg91',
        //                 'clickatell_username' => 'demo3',
        //                 'clickatell_password' => '23445',
        //                 'school_id' => $school->id,
        //             ],
        //         ]);
                
        //         for ($i = 2; $i <= 21; ++$i) {

        //             $assign = new SaasSchoolModulePermissionAssign();
        //             $assign->module_id = $i;
        //             $assign->created_by = 1;
        //             $assign->updated_by = 1;
        //             $assign->school_id = $school->id;
        //             $assign->save();
        //         }
        
        //         for ($j = 1; $j <= 541; $j++) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $j;
        //             $permission->role_id = 5;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         $admins = [800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 810, 811, 812, 813, 814, 815, 900, 901, 902, 903, 904];
        
        //         foreach ($admins as $key => $value) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 5;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         $ids = [399, 400, 401, 402, 403, 404, 428, 429, 430, 431, 456, 457, 458, 459, 460, 461, 462, 463, 478, 482, 483, 484, 549];
        //         foreach ($ids as $id) {
        //             $permission = InfixPermissionAssign::where('school_id', $school->id)->where('role_id', 5)->where('module_id', $id)->first();
        //             if ($permission) {
        //                 $permission->delete();
        //             }
        //         }
        
        //         // for teacher
        //         $teachers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 277, 278, 279, 280, 281, 282, 283, 284, 285, 800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 833, 834, 900, 901, 902, 903, 904];
        
        //         foreach ($teachers as $key => $value) {
        
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 4;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // for receiptionists
        //         $receiptionists = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 376, 377, 378, 379, 380, 900, 901, 902, 903, 904];
        
        //         foreach ($receiptionists as $key => $value) {
        
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 7;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // for librarians
        //         $librarians = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 376, 377, 378, 379, 380, 900, 901, 902, 903, 904];
        
        //         foreach ($librarians as $key => $value) {
        
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 8;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // for drivers
        //         $drivers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 188, 193, 194, 19, 900, 901, 902, 903, 904];
        
        //         foreach ($drivers as $key => $value) {
        
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 9;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // for accountants
        //         $accountants = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 68, 69, 70, 83, 84, 85, 86, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 188, 193, 194, 195, 376, 377, 378, 379, 380, 381, 382, 383, 900, 901, 902, 903, 904];
        
        //         foreach ($accountants as $key => $value) {
        
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 6;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // student
        //         for ($j = 1; $j <= 55; $j++) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $j;
        //             $permission->role_id = 2;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         $students = [800, 810, 815, 900, 901, 902, 903, 904];
        //         foreach ($students as $key => $value) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 2;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        
        //         // parent
        //         for ($j = 56; $j <= 99; $j++) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $j;
        //             $permission->role_id = 3;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        //         // chat module
        //         $parents = [910, 911, 912, 913, 914];
        //         foreach ($parents as $key => $value) {
        //             $permission = new InfixPermissionAssign();
        //             $permission->module_id = $value;
        //             $permission->role_id = 3;
        //             $permission->school_id = $school->id;
        //             $permission->save();
        //         }
        //         // theme
        //         $default_themes = ['Default', 'Lawn Green'];
        //         foreach($default_themes as $key=>$item) {
        //             $theme = Theme::updateOrCreate([
        //                 'title'=>$item,
        //                 'school_id'=>$school->id
        //             ]);
        //             if($item == 'Lawn Green') {
        //                 $theme->path_main_style = 'lawngreen_version/style.css';
        //                 $theme->path_infix_style = 'lawngreen_version/infix.css';
        //                 $theme->path_infix_style = false;                   
        //                 $theme->box_shadow = true;                   
        //             }else {
        //                 $theme->path_main_style = 'style.css';
        //                 $theme->path_infix_style = 'infix.css';
        //                 $theme->is_default = $key == 0 ? 1: 0;
        //             }
        //             $theme->color_mode = "gradient";
        //             $theme->background_type = "image";
        //             $theme->background_image = asset('/public/backEnd/img/body-bg.jpg');
        //             $theme->is_system = true;
        //             $theme->created_by = 1;
        //             $theme->school_id = $school->id;
        //             $theme->save(); 
                    
        //         }
        //         $themes = \App\Models\Theme::withOutGlobalScopes()->where('school_id', $school->id)->get();
        //         $sql = [];
        //         foreach($themes as $theme){
        //             if($theme->title == 'Default') { 
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 1, 'value'   => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 2, 'value'  => "#7c32ff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 3, 'value'  => "#c738d8"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 4, 'value'  => "#7c32ff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 5, 'value'  => "#828bb2"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 6, 'value'  => "#828bb2"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 7, 'value'  => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 8, 'value'  => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 9, 'value'  => "#000000"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 10, 'value' => "#000000"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 11, 'value' => "#c738d8"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 16, 'value' => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 17, 'value'  => "#222222"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 18, 'value'  => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 19, 'value'  => "transparent"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 20, 'value'  => "#4c5c9b"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 21, 'value'  => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 29, 'value'  => "#415094"];
                
        //             }
        
        //              if($theme->title != 'Default') {
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 1, 'value'   => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 2, 'value'  => "#03e396"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 3, 'value'  => "#03e396"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 4, 'value'  => "#03e396"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 5, 'value'  => "#7e7172"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 6, 'value'  => "#828bb2"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 7, 'value'  => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 8, 'value'  => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 9, 'value'  => "#000000"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 10, 'value' => "#000000"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 11, 'value' => "#03e396"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 16, 'value' => "#415094"];                
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 17, 'value'  => "#222222"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 18, 'value'  => "#415094"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 19, 'value'  => "#ffffff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 20, 'value'  => "#e7ecff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 21, 'value'  => "#e7ecff"];
        //                 $sql[] = ['theme_id'  => $theme->id, 'color_id' => 29, 'value'  => "#e7ecff"];            
        //              }
        //         }
        //         //end theme 
        //         DB::table('color_theme')->insert($sql);
        //         $store = new SmHeaderMenuManager();
               
        //         $store->type = 'sPages';
        //         $store->element_id = 1;
        //         $store->title = 'Home';
        //         $store->link = '/';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $store = new SmHeaderMenuManager();
              
        //         $store->type = 'sPages';
        //         $store->element_id = 2;
        //         $store->title = 'About';
        //         $store->link = '/about';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $store = new SmHeaderMenuManager();
               
        //         $store->type = 'sPages';
        //         $store->element_id = 3;
        //         $store->title = 'Course';
        //         $store->link = '/course';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $store = new SmHeaderMenuManager();
             
        //         $store->type = 'sPages';
        //         $store->element_id = 4;
        //         $store->title = 'News';
        //         $store->link = '/news-page';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $store = new SmHeaderMenuManager();
              
        //         $store->type = 'sPages';
        //         $store->element_id = 5;
        //         $store->title = 'Contact';
        //         $store->link = '/contact';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $store = new SmHeaderMenuManager();
              
        //         $store->type = 'sPages';
        //         $store->element_id = 6;
        //         $store->title = 'Login';
        //         $store->link = '/login';
        //         $store->school_id = $school->id;
        //         $store->save();

        //         DB::table('sm_social_media_icons')->insert([
        //             [
        //                 'url' => 'https://www.facebook.com/Spondonit',
        //                 'icon' => 'fa fa-facebook',
        //                 'status' => 1,
        //                 'school_id' => $school->id,
        //             ],
        //             [
        //                 'url' => 'https://www.facebook.com/Spondonit',
        //                 'icon' => 'fa fa-twitter',
        //                 'status' => 1,
        //                 'school_id' => $school->id,
        //             ],
        //             [
        //                 'url' => 'https://www.facebook.com/Spondonit',
        //                 'icon' => 'fa fa-dribbble',
        //                 'status' => 1,
        //                 'school_id' => $school->id,
        //             ],
        //             [
        //                 'url' => 'https://www.facebook.com/Spondonit',
        //                 'icon' => 'fa fa-linkedin',
        //                 'status' => 1,
        //                 'school_id' => $school->id,
        //             ],
        //         ]);

        //         $store = new SmPage();
               
        //         $store->title = 'Home';
        //         $store->slug = '/';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();
        
        //         $store = new SmPage();
               
        //         $store->title = 'About';
        //         $store->slug = '/about';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();
        
        //         $store = new SmPage();
              
        //         $store->title = 'Course';
        //         $store->slug = '/course';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();
        
        //         $store = new SmPage();
             
        //         $store->title = 'News';
        //         $store->slug = '/news-page';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();
        
        //         $store = new SmPage();
              
        //         $store->title = 'Contact';
        //         $store->slug = '/contact';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();
        
        //         $store = new SmPage();
              
        //         $store->title = 'Login';
        //         $store->slug = '/login';
        //         $store->active_status = 1;
        //         $store->is_dynamic = 0;
        //         $store->school_id = $school->id;
        //         $store->save();

        //         $s         = new SmCustomLink();
        //         $s->title1 = 'Departments';
        //         $s->title2 = 'Health Care';
        //         $s->title3 = 'About Our System';
        //         $s->title4 = 'Resources';
        
        //         $s->link_label1 = 'About Infix';
        //         $s->link_href1  = 'http://infixedu.com';
        
        //         $s->link_label2 = 'Infix Home';
        //         $s->link_href2  = 'http://infixedu.com/home';
        
        //         $s->link_label3 = 'Business';
        //         $s->link_href3  = 'http://infixedu.com';
        
        //         $s->link_label4 = 'link_label4';
        //         $s->link_href4  = 'http://infixedu.com';
        
        //         $s->link_label5 = 'link_label5';
        //         $s->link_href5  = 'http://infixedu.com';
        
        //         $s->link_label6 = 'link_label6';
        //         $s->link_href6  = 'http://infixedu.com';
        
        //         $s->link_label7 = 'link_label7';
        //         $s->link_href7  = 'http://infixedu.com';
        
        //         $s->link_label8 = 'link_label8';
        //         $s->link_href8  = 'http://infixedu.com';
        
        //         $s->link_label9  = 'Home';
        //         $s->link_href9   = 'http://infixedu.com/home';
        
        //         $s->link_label10 = 'About';
        //         $s->link_href10  = 'http://infixedu.com/about';
        
        
        //         $s->link_label11 = 'Contact';
        //         $s->link_href11  = 'http://infixedu.com/contact';
        
        //         $s->link_label12 = 'link_label12';
        //         $s->link_href12  = 'http://infixedu.com';
        
        //         $s->link_label13 = 'link_label13';
        //         $s->link_href13  = 'http://infixedu.com';
        
        //         $s->link_label14 = 'link_label14';
        //         $s->link_href14  = 'http://infixedu.com';
        
        //         $s->link_label15 = 'link_label15';
        //         $s->link_href15  = 'http://infixedu.com';
        
        //         $s->link_label16 = 'link_label16';
        //         $s->link_href16  = 'http://infixedu.com';
        //         $s->school_id = $school->id; 
        //         $s->save();

        //     }
        // );
    }
}
