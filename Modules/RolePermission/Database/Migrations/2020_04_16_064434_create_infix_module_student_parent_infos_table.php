<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class CreateInfixModuleStudentParentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infix_module_student_parent_infos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('module_id')->nullable();

            $table->integer('parent_id')->nullable()->default(0);

            $table->string('name')->nullable();
            $table->string('route')->nullable()->comment('url');
            $table->string('lang_name')->nullable();
            $table->string('icon_class')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('type')->nullable()->comment('1 for module, 2 for module link, 3 for module options');
            $table->integer('user_type')->nullable()->comment('1 for student, 2 for parent');

            $table->timestamps();
        });

        // DB::statement('SET foreign_key_checks=0');
        // DB::table('infix_module_student_parent_infos')->truncate();
        // DB::statement('SET foreign_key_checks=1');

       //`id`, `module_id`, `parent_id`, `type`, `user_type`, `name`, `route`, `lang_name`, `icon_class`, `active_status`, `created_by`, `updated_by`, `school_id`, `created_at`, `updated_at`


     

       $datas = [
        [1, 1, 0, '1', 1, 'Dashboard Menu','student-dashboard','dashboard','flaticon-resume', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2, 1, 1, '3', 1, 'Subject','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [3, 1, 1, '3', 1, 'Notice','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [4, 1, 1, '3', 1, 'Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [5, 1, 1, '3', 1, 'Online Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [6, 1, 1, '3', 1, 'Teachers','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [7, 1, 1, '3', 1, 'Issued books','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [8, 1, 1, '3', 1, 'Pending homeworks','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [9, 1, 1, '3', 1, 'attendance in current month','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [10, 1, 1, '3', 1, 'Calendar','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- student Profile
        [11, 2, 0, '1', 1, 'My Profile','student-profile','my_profile','flaticon-resume', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [12, 2, 11, '2', 1, 'Profile','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [13, 2, 11, '2', 1, 'Fees','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [14, 2, 11, '2', 1, 'Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [15, 2, 11, '2', 1, 'Document','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [16, 2, 15, '3', 1, 'Upload','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [17, 2, 15, '3', 1, 'download','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [18, 2, 15, '3', 1, 'delete','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [19, 2, 11, '2', 1, 'Timeline','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Fees
        [20, 3, 0, '1', 1, 'Fees','','fees','flaticon-wallet', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [21, 3, 20, '2', 1, 'Pay Fees','student-fees','pay_fees','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        [1156, 800, 0, '1', 1, 'Fees','','fees','flaticon-wallet', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Student Class Routine
        [22, 4, 0, '1', 1, 'Class Routine','student-class-routine','class_routine','flaticon-calendar-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25'],

       // -- Student Homework List
        [23, 5, 0, '1', 1, 'Homework List','student-homework','home_work','flaticon-book', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [24, 5, 23, '2', 1, 'View','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [25, 5, 23, '2', 1, 'Add Content','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Student Download Center
        [26, 6, 0, '1', 1, 'Download Center','','download_center','flaticon-data-storage', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        [27, 6, 26, '2', 1, 'Assignment','student-assignment','assignment','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [28, 6, 27, '3', 1, 'Download','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],


        [31, 6, 26, '2', 1, 'Syllabus','student-syllabus','syllabus','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [32, 6, 31, '3', 1, 'Download','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [33, 6, 26, '2', 1, 'Other Downloads','student-others-download','other_download','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [34, 6, 33, '3', 1, 'Download','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Student Attendance
        [35, 7, 0, '1', 1, 'Attendance','student-my-attendance','attendance','flaticon-authentication', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22' ],

        //-- Student Examination
        [36, 8, 0, '1', 1, 'Examination','','examinations','flaticon-test', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [37, 8, 36, '2', 1, 'Result','student-result','result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [38, 8, 36, '2', 1, 'Exam Schedule','student-exam-schedule','exam_schedule','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Leave
        [39, 9, 0, '1', 1, 'Leave','','leave','flaticon-slumber', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [40, 9, 39, '2', 1, 'Apply Leave','student-apply-leave','apply_leave','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [41, 9, 40, '3', 1, 'Save','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [42, 9, 40, '3', 1, 'Edit','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [44, 9, 39, '2', 1, 'Pending Leave','student-pending-leave','pending_leave_request','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Student Online Exam
        [45, 100, 0, '1', 1, 'Online Exam','','online_exam','flaticon-test-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [46, 100, 45, '2', 1, 'Active Exams','student-online-exam','active_exams','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [47, 100, 45, '2', 1, 'View Results','student_view_result','view_result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Online Exam Module
        [2045, 101, 0, '1', 1, 'Online Exam','','online_exam','flaticon-test-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2046, 101, 2045, '2', 1, 'Active Exams','onlineexam/student-online-exam','active_exams','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2047, 101, 2045, '2', 1, 'View Results','onlineexam/student-view-result','view_result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2048, 101, 2045, '2', 1, 'PDF Exam','onlineexam/student-pdf-exam','pdf_exam','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2049, 101, 2045, '2', 1, 'PDF Exam Result','onlineexam/student-view-exam-result','pdf_exam_result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25'],

      //  -- Student Notice Board
        [48, 11, 0, '1', 1, 'Notice Board','student-noticeboard','notice_board','flaticon-poster', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Subject
        [49, 12, 0, '1', 1, 'Subject','student-subject','subjects','flaticon-reading-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Teachers List
        [50, 13, 0, '1', 1, 'Teachers List','student-teacher','student_teacher','flaticon-professor', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Library
        [51, 14, 0, '1', 1, 'Library','','library','flaticon-book-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [52, 14, 51, '2', 1, 'Book List','student-library','book_list','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [53, 14, 51, '2', 1, 'Book Issued','student-book-issue','book_issue','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Transport
        [54, 15, 0, '1', 1, 'Transport','student-transport','student_transport','flaticon-bus', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Student Dormitory
        [55, 16, 0, '1', 1, 'Dormitory','student-dormitory','dormitory','flaticon-hotel', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
       // -- lesson
        [800, 29, 0, '1', 1,'Lesson','','lesson','flaticon-calendar-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [810, 29, 800, '1', 1,'Lesson Plan','lesson/student/lessonPlan','lesson_plan','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [815, 29, 800, '1', 1,'Lesson Plan Overview','lesson/student/lessonPlan-overview','lesson_plan_overview','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-1'],


        // -- bbb
        [850, 2033, 0, '1', 1,'BigBlueButton','','bbb','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [851, 2033, 850, '2', 1,'Virtual Class','bbb/virtual-class','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],   
        [855, 2033, 851, '3', 1,'Start Class','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [868, 2033, 850, '2', 1,'Class Recorded List','bbb/class-recording-list','class_recorded_list','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24'],

       // -- zoom

        [554, 2022, 0, '1', 1,'Zoom','','zoom','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [555, 2022, 554, '2', 1,'Virtual Class','zoom/virtual-class','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],    
        [559, 2022, 555, '3', 1,'Start Class','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        
      //-- jitsi
         [816, 2030, 0, '1', 1,'Jitsi','','jitsi','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [817, 2030, 816, '2', 1,'Virtual Class','jitsi/virtual-class','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],  
         [821, 2030, 817, '3', 1,'Start Class','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

      //  -- Wallet
        [1124, 1130, 0, '1', 1,'Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [1125, 1130, 1124, '2', 1,'Add Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [1126, 1130, 1124, '2', 1,'Refund Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Chat
        [900, 31, 0, '1', 1,'Chat','','chat','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
      
        [901, 31, 900, '2', 1,'Chat Box','chat/open','chat_box','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [902, 31, 901, '3', 1,'New Chat','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [903, 31, 900, '2', 1,'Invitation','chat/invitation/index','invitation','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [904, 31, 900, '2', 1,'Blocked User','chat/users/blocked','blocked_user','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent Dashboard
        [56, 1, 0, '1', 2, 'Dashboard Menu','parent-dashboard','dashboard','flaticon-resume', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [57, 1, 56, '3', 2, 'Subject','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [58, 1, 56, '3', 2, 'Notice','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [59, 1, 56, '3', 2, 'Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [60, 1, 56, '3', 2, 'Online Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [61, 1, 56, '3', 2, 'Teachers','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [62, 1, 56, '3', 2, 'Issued books','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [63, 1, 56, '3', 2, 'Pending homeworks','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [64, 1, 56, '3', 2, 'attendance in current month','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [65, 1, 56, '3', 2, 'Calendar','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        //-- Parent Profile
        [66, 2, 0, '1', 2, 'My Children','my-children','my_children','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [67, 2, 66, '2', 2, 'Profile','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [68, 2, 66, '2', 2, 'Fees','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [69, 2, 66, '2', 2, 'Exam','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [70, 2, 66, '2', 2, 'Timeline','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        


       // -- Parent Fees
        [71, 3, 0, '1', 2, 'Fees','parent-fees','fees','flaticon-wallet', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        [1157, 800, 0, '1', 2, 'Fees','','fees','flaticon-wallet', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent Class Routine
        [72, 4, 0, '1', 2, 'Class Routine','parent-class-routine','class_routine','flaticon-calendar-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent HomeWork
        [73, 5, 0, '1', 2, 'HomeWork ','parent-homework','home_work','flaticon-book', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [74, 5, 73, '3', 2, 'View','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Parent Attendance
        [75, 6, 0, '1', 2, 'Attendance ','parent-attendance','attendance','flaticon-authentication', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Parent Exam
        [76, 7, 0, '1', 2, 'Exam ','','exam','flaticon-test', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [78, 7, 76, '2', 2, 'Exam Schedule','parent-examination-schedule/{id}','exam_schedule','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        
        
         //-- Parent Online Exam
         [2016, 10, 0, '1', 2, 'Online Exam ','','online_exam','flaticon-test', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2017, 10, 2016, '2', 2, 'Exam Result','parent-online-examination-result/{id}','online_exam_result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25'],
        [2018, 10, 2016, '2', 2, 'Online Exam','parent-online-examination/{id}','online_exam','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        
            //  -- Parent Online Exam Module
        [2101, 101, 0, '1', 2, 'Online Exam ','','online_exam','flaticon-test', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2001, 101, 2101, '2', 2, 'Exam Result','onlineexam/parent-online-examination-result/{id}','online_exam_result','', 1, 1, 1, 1, '2019-07-25 02:21:21'],
        [2002, 101, 2101, '2', 2, 'Active Online Exam','onlineexam/parent-online-examination/{id}','online_exam','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-1'],
        [2103, 101, 2101, '2', 2, 'Pdf Exam','onlineexam/parent-pdf-exam/{id}','pdf_exam','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [2104, 101, 2101, '2', 2, 'Pdf Exam Result','onlineexam/parent-view-exam-result/{id}','pdf_exam_result','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-1'],
       

       // -- Parent Leave
        [80, 8, 0, '1', 2, 'Leave','parent-leave','leave','flaticon-test', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [81, 8, 80, '2', 2, 'Apply Leave','parent-apply-leave','apply_leave','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [82, 8, 81, '3', 2, 'Save','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [83, 8, 81, '3', 2, 'Edit','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [84, 8, 80, '2', 2, 'Pending Leave','parent-pending-leave','pending_leave_request','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent Notice Board
        [85, 9, 0, '1', 2, 'Notice Board','parent-noticeboard','notice_board','flaticon-poster', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Parent Subject
        [86, 10, 0, '1', 2, 'Subject','parent-subjects','subject','flaticon-reading-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Parent Teachers List
        [87, 11, 0, '1', 2, 'Teachers List','parent-teacher-list','teacher_list','flaticon-professor', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'] ,

       // -- Parent Library
        [88, 12, 0, '1', 2, 'Library','','library','flaticon-book-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [89, 12, 88, '2', 2, 'Book List','parent-library','book_list','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [90, 12, 88, '2', 2, 'Book Issued','parent-book-issue','book_issue','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

       // -- Parent Transport
        [91, 13, 0, '1', 2, 'Transport','parent-transport','transport','flaticon-bus', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent Dormitory
        [92, 14, 0, '1', 2, 'Dormitory','parent-dormitory','dormitory_list','flaticon-hotel', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],


        //-- Student Leave Missing
        [93, 9, 40, '3', 1, 'View','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [94, 9, 40, '3', 1, 'Delete','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

        //-- Parent Leave Missing
        [95, 8, 81, '3', 2, 'View','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [96, 8, 81, '3', 2, 'Delete','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        //-- parent lesson
        [97, 29, 0, '1', 2,'Lesson','','lesson','flaticon-calendar-1', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [98, 29, 97, '1', 2,'Lesson Plan','lesson/parent/lessonPlan/{id}','lesson_plan','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [99, 29, 97, '1', 2,'Lesson Plan Overview','lesson/parent/lessonPlan-overview/{id}','lesson_plan_overview','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-01-02'],
   
        // -- parent zoom

        [100, 2022, 0, '1',2,'Zoom','','zoom','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [101, 2022, 100, '2', 2,'Virtual Class','zoom/virtual-class/child/{id}','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [103, 2022, 100, '2', 2,'Virtual Meeting','zoom/meetings/parent','virtual_meeting','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
    
       
        //-- parent bbb
        [105, 2033, 0, '1', 2,'BigBlueButton','','bbb','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [106, 2033, 105, '2', 2,'Virtual Class','bbb/virtual-class/child/{id}','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [107, 2033, 105, '2', 2,'Virtual Meeting','bbb/meetings/parent','virtual_meeting','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [115, 2033, 105, '2', 2,'Class Recorded List','bbb/class-recording-list/child/{id}','class_recorded_list','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-0-1'],
        [116, 2033, 105, '2', 2,'Meeting Recorded List','bbb/meeting-recording-list/parent','meeting_recorded_list','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-01-2'],
      
        
        //-- parent jitsi
        
        [108, 2030, 0, '1', 2,'Jitsi','','jitsi','flaticon-reading', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [109, 2030, 108, '2', 2,'Virtual Class','jitsi/virtual-class/child/{id}','virtual_class','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [110, 2030, 108, '2', 2,'Virtual Meeting','jitsi/meetings/parent','virtual_meeting','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],  

        //-- Wallet
        [1127, 1130, 0, '1', 2,'Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [1128, 1130, 1127, '2', 2,'Add Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [1129, 1130, 1127, '2', 2,'Refund Wallet','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
      
        //-- Chat

        [910, 31, 0, '1', 2,'Chat','','chat','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],      
        [911, 31, 910, '2', 2,'Chat box','chat/open','chat_box','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [912, 31, 911, '3', 2,'New Chat','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [913, 31, 910, '2', 2,'Invitation','chat/invitation/index','invitation','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        [914, 31, 910, '2', 2,'Blocked User','chat/users/blocked','blocked_user','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22']

       ];

       foreach($datas as $data){
            $new = new InfixModuleStudentParentInfo();
            $new->id = $data[0];
            $new->module_id = $data[1];
            $new->parent_id = $data[2];
            $new->type = $data[3];
            $new->user_type = $data[4];
            $new->name = $data[5];
            $new->route = $data[6];
            $new->lang_name = $data[7];
            $new->icon_class = $data[8];
            $new->active_status = $data[9];
            $new->created_by = $data[10];
            $new->updated_by = $data[11];
            $new->school_id = $data[12];
            $new->save();
       }
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infix_module_student_parent_infos');
    }
}
