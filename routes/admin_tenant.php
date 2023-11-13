<?php

use App\SmStaff;
use App\SmSchool;
use App\Models\Theme;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\AssignPermission;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use App\Http\Controllers\Admin\FeesCollection\DueFeesLoginPermissionController;
use App\Http\Controllers\Admin\FeesCollection\SmFeesCarryForwardController;

Route::get('checkForeignKey', 'HomeController@checkForeignKey')->name('checkForeignKey');

//ADMIN
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('reg', function () {
    return view('auth.register');
});



Route::group(['middleware' => ['XSS','subscriptionAccessUrl']], function () {

    // User Auth Routes
    Route::group(['middleware' => ['CheckDashboardMiddleware']], function () {

        Route::get('staff-download-timeline-doc/{file_name}', function ($file_name = null) {
            // return "Timeline";
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            // echo $file;
            // exit();
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('staff-download-timeline-doc');

        Route::get('download-holiday-document/{file_name}', function ($file_name = null) {
            // return "Timeline";
            $file = public_path() . '/uploads/holidays/' . $file_name;

            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('download-holiday-document');

        Route::get('get-other-days-ajax', 'Admin\Academics\SmClassRoutineNewController@getOtherDaysAjax');
       

        /* ******************* Dashboard Setting ***************************** */
        Route::get('dashboard/display-setting', 'Admin\SystemSettings\SmSystemSettingController@displaySetting');
        Route::post('dashboard/display-setting-update', 'Admin\SystemSettings\SmSystemSettingController@displaySettingUpdate');


        /* ******************* Dashboard Setting ***************************** */
        Route::get('api/permission', 'Admin\SystemSettings\SmSystemSettingController@apiPermission')->name('api/permission')->middleware('userRolePermission:api/permission');
        Route::get('api-permission-update', 'Admin\SystemSettings\SmSystemSettingController@apiPermissionUpdate');
        Route::post('set-fcm_key', 'Admin\SystemSettings\SmSystemSettingController@setFCMkey')->name('set_fcm_key');
        /* ******************* Dashboard Setting ***************************** */

        Route::get('delete-student-document/{id}', ['as' => 'delete-student-document', 'uses' => 'SmStudentAdmissionController@deleteDocument']);


        Route::view('/admin-setup', 'frontEnd.admin_setup');
        Route::view('/general-setting', 'frontEnd.general_setting');
        Route::view('/student-id', 'frontEnd.student_id');
        Route::view('/add-homework', 'frontEnd.add_homework');
        // Route::view('/fees-collection-invoice', 'frontEnd.fees_collection_invoice');
        Route::view('/exam-promotion-naim', 'frontEnd.exam_promotion');
        Route::view('/front-cms-gallery', 'frontEnd.front_cms_gallery');
        Route::view('/front-cms-media-manager', 'frontEnd.front_cms_media_manager');
        Route::view('/reports-class', 'frontEnd.reports_class');
        Route::view('/human-resource-payroll-generate', 'frontEnd.human_resource_payroll_generate');
        // Route::view('/fees-collection-collect-fees', 'frontEnd.fees_collection_collect_fees');
        Route::view('/calendar', 'frontEnd.calendar');
        Route::view('/design', 'frontEnd.design');
        Route::view('/loginn', 'frontEnd.login');
        Route::view('/dash-board/super-admin', 'frontEnd.dashBoard.super_admin');
        Route::view('/admit-card-report', 'frontEnd.admit_card_report');
        Route::view('/reports-terminal-report2', 'frontEnd.reports_terminal_report');
        // Route::view('/reports-tabulation-sheet', 'frontEnd.reports_tabulation_sheet');
        Route::view('/system-settings-sms', 'frontEnd.system_settings_sms');
        Route::view('/front-cms-setting', 'frontEnd.front_cms_setting');
        Route::view('/base_setup_naim', 'frontEnd.base_setup');
        Route::view('/dark-home', 'frontEnd.home.dark_home');
        Route::view('/dark-about', 'frontEnd.home.dark_about');
        Route::view('/dark-news', 'frontEnd.home.dark_news');
        Route::view('/dark-news-details', 'frontEnd.home.dark_news_details');
        Route::view('/dark-course', 'frontEnd.home.dark_course');
        Route::view('/dark-course-details', 'frontEnd.home.dark_course_details');
        Route::view('/dark-department', 'frontEnd.home.dark_department');
        Route::view('/dark-contact', 'frontEnd.home.dark_contact');
        Route::view('/light-home', 'frontEnd.home.light_home');
        Route::view('/light-about', 'frontEnd.home.light_about');
        Route::view('/light-news', 'frontEnd.home.light_news');
        Route::view('/light-news-details', 'frontEnd.home.light_news_details');
        Route::view('/light-course', 'frontEnd.home.light_course');
        Route::view('/light-course-details', 'frontEnd.home.light_course_details');
        Route::view('/light-department', 'frontEnd.home.light_department');
        Route::view('/light-contact', 'frontEnd.home.light_contact');
        Route::view('/color-home', 'frontEnd.home.color_home');
        Route::view('/id-card', 'frontEnd.home.id_card');

        Route::get('/viewFile/{id}', 'HomeController@viewFile')->name('viewFile');

        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::get('add-toDo', 'HomeController@addToDo');
        Route::post('saveToDoData', 'HomeController@saveToDoData')->name('saveToDoData');
        Route::get('view-toDo/{id}', 'HomeController@viewToDo')->where('id', '[0-9]+');
        Route::get('edit-toDo/{id}', 'HomeController@editToDo')->where('id', '[0-9]+');
        Route::post('update-to-do', 'HomeController@updateToDo');
        Route::get('remove-to-do', 'HomeController@removeToDo');
        Route::get('get-to-do-list', 'HomeController@getToDoList');

        Route::get('admin-dashboard', 'HomeController@index')->name('admin-dashboard');
       

        //Role Setup
        Route::get('role', ['as' => 'role', 'uses' => 'Admin\RolePermission\RoleController@index']);
        Route::post('role-store', ['as' => 'role_store', 'uses' => 'Admin\RolePermission\RoleController@store']);
        Route::get('role-edit/{id}', ['as' => 'role_edit', 'uses' => 'Admin\RolePermission\RoleController@edit'])->where('id', '[0-9]+');
        Route::post('role-update', ['as' => 'role_update', 'uses' => 'Admin\RolePermission\RoleController@update']);
        Route::post('role-delete', ['as' => 'role_delete', 'uses' => 'Admin\RolePermission\RoleController@delete']);


        // Role Permission
        Route::get('assign-permission/{id}', ['as' => 'assign_permission', 'uses' => 'SmRolePermissionController@assignPermission']);
        Route::post('role-permission-store', ['as' => 'role_permission_store', 'uses' => 'SmRolePermissionController@rolePermissionStore']);


        // Module Permission

        Route::get('module-permission', 'Admin\RolePermission\RoleController@modulePermission')->name('module-permission');


        Route::get('assign-module-permission/{id}', 'Admin\RolePermission\RoleController@assignModulePermission')->name('assign-module-permission');
        Route::post('module-permission-store', 'Admin\RolePermission\RoleController@assignModulePermissionStore')->name('module-permission-store');


        //User Route
        Route::get('user', ['as' => 'user', 'uses' => 'UserController@index']);
        Route::get('user-create', ['as' => 'user_create', 'uses' => 'UserController@create']);

        // Base group
        Route::get('base-group', ['as' => 'base_group', 'uses' => 'SmBaseGroupController@index']);
        Route::post('base-group-store', ['as' => 'base_group_store', 'uses' => 'SmBaseGroupController@store']);
        Route::get('base-group-edit/{id}', ['as' => 'base_group_edit', 'uses' => 'SmBaseGroupController@edit']);
        Route::post('base-group-update', ['as' => 'base_group_update', 'uses' => 'SmBaseGroupController@update']);
        Route::get('base-group-delete/{id}', ['as' => 'base_group_delete', 'uses' => 'SmBaseGroupController@delete']);

        // Base setup
        Route::get('base-setup', ['as' => 'base_setup', 'uses' => 'Admin\SystemSettings\SmBaseSetupController@index'])->middleware('userRolePermission:base_setup');
        Route::post('base-setup-store', ['as' => 'base_setup_store', 'uses' => 'Admin\SystemSettings\SmBaseSetupController@store'])->middleware('userRolePermission:base_setup_store');
        Route::get('base-setup-edit/{id}', ['as' => 'base_setup_edit', 'uses' => 'Admin\SystemSettings\SmBaseSetupController@edit'])->middleware('userRolePermission:base_setup_edit');
        Route::post('base-setup-update', ['as' => 'base_setup_update', 'uses' => 'Admin\SystemSettings\SmBaseSetupController@update'])->middleware('userRolePermission:base_setup_edit');
        Route::post('base-setup-delete', ['as' => 'base_setup_delete', 'uses' => 'Admin\SystemSettings\SmBaseSetupController@delete'])->middleware('userRolePermission:base_setup_delete');

        //// Academics Routing

        // Class route
        Route::get('class', ['as' => 'class', 'uses' => 'Admin\Academics\SmClassController@index'])->middleware('userRolePermission:class');
        Route::post('class-store', ['as' => 'class_store', 'uses' => 'Admin\Academics\SmClassController@store'])->middleware('userRolePermission:class_store');
        Route::get('class-edit/{id}', ['as' => 'class_edit', 'uses' => 'Admin\Academics\SmClassController@edit'])->middleware('userRolePermission:class_edit');
        Route::post('class-update', ['as' => 'class_update', 'uses' => 'Admin\Academics\SmClassController@update'])->middleware('userRolePermission:class_edit');
        Route::get('class-delete/{id}', ['as' => 'class_delete', 'uses' => 'Admin\Academics\SmClassController@delete'])->middleware('userRolePermission:class_delete');


        //*********************************************** START SUBJECT WISE ATTENDANCE ****************************************************** */
        Route::get('subject-wise-attendance',  'Admin\StudentInfo\SmSubjectAttendanceController@index')->name('subject-wise-attendance')->middleware('userRolePermission:subject-wise-attendance');
        Route::post('subject-attendance-search',  'Admin\StudentInfo\SmSubjectAttendanceController@search')->name('subject-attendance-search');
        Route::post('subject-attendance-store',  'Admin\StudentInfo\SmSubjectAttendanceController@storeAttendance')->name('subject-attendance-store')->middleware('userRolePermission:student-attendance-store');
        Route::post('subject-attendance-store-second',  'Admin\StudentInfo\SmSubjectAttendanceController@storeAttendanceSecond')->name('subject-attendance-store-second')->middleware('userRolePermission:student-attendance-store');
        Route::post('student-subject-holiday-store',  'Admin\StudentInfo\SmSubjectAttendanceController@subjectHolidayStore')->name('student-subject-holiday-store')->middleware('userRolePermission:student-subject-holiday-store');


        // Student Attendance Report
        Route::get('subject-attendance-report', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReport')->name('subject-attendance-report')->middleware('userRolePermission:subject-attendance-report');
        Route::post('subject-attendance-report-search', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReportSearch')->name('subject-attendance-report-search');
        Route::get('subject-attendance-report-search', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReport');
       
        Route::get('subject-attendance-average-report', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceAverageReport');
        Route::post('subject-attendance-average-report', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceAverageReportSearch');

        // Route::get('subject-attendance-report/print/{class_id}/{section_id}/{month}/{year}', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReportPrint');
        Route::get('subject-attendance-average/print/{class_id}/{section_id}/{month}/{year}', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReportAveragePrint')->name('subject-average-attendance/print')->middleware('userRolePermission:subject-attendance/print');

        // for university module

        Route::get('un-subject-attendance-average/print/{semester_label_id}/{month}/{year}', 'Admin\StudentInfo\SmSubjectAttendanceController@unSubjectAttendanceReportAveragePrint')->name('un-subject-average-attendance/print')->middleware('userRolePermission:subject-attendance/print');

        Route::get('subject-attendance/print/{class_id}/{section_id}/{month}/{year}', 'Admin\StudentInfo\SmSubjectAttendanceController@subjectAttendanceReportPrint')->name('subject-attendance/print')->middleware('userRolePermission:subject-attendance/print');
        //*********************************************** END SUBJECT WISE ATTENDANCE ****************************************************** */



        // Student Attendance Report
        Route::get('student-attendance-report', ['as' => 'student_attendance_report', 'uses' => 'Admin\StudentInfo\SmStudentAttendanceReportController@index'])->middleware('userRolePermission:student_attendance_report');
        Route::post('student-attendance-report-search', ['as' => 'student_attendance_report_search', 'uses' => 'Admin\StudentInfo\SmStudentAttendanceReportController@search']);
        Route::get('student-attendance-report-search', 'Admin\StudentInfo\SmStudentAttendanceReportController@index');
        Route::get('student-attendance/print/{class_id}/{section_id}/{month}/{year}', 'Admin\StudentInfo\SmStudentAttendanceReportController@print')->name('student-attendance-print');


        // for university module
        Route::get('un-student-attendance/print/{semester_id}/{month}/{year}', 'Admin\StudentInfo\SmStudentAttendanceReportController@unPrint')->name('un-student-attendance-print');
        //Class Section routes
        Route::get('optional-subject',  'Admin\SystemSettings\SmOptionalSubjectAssignController@index')->name('optional-subject')->middleware('userRolePermission:optional-subject');

        Route::post('assign-optional-subject',  'Admin\SystemSettings\SmOptionalSubjectAssignController@assignOptionalSubjectSearch')->name('assign_optional_subject_search');
        Route::post('assign-optional-subject-search',  'Admin\SystemSettings\SmOptionalSubjectAssignController@assignOptionalSubject');
        Route::post('assign-optional-subject-store',  'Admin\SystemSettings\SmOptionalSubjectAssignController@assignOptionalSubjectStore')->name('assign-optional-subject-store')->middleware('userRolePermission:assign-optional-subject-store');


        Route::get('section', ['as' => 'section', 'uses' => 'Admin\Academics\SmSectionController@index'])->middleware('userRolePermission:section');

        Route::post('section-store', ['as' => 'section_store', 'uses' => 'Admin\Academics\SmSectionController@store'])->middleware('userRolePermission:section_store');
        Route::get('section-edit/{id}', ['as' => 'section_edit', 'uses' => 'Admin\Academics\SmSectionController@edit'])->middleware('userRolePermission:section_edit');
        Route::post('section-update', ['as' => 'section_update', 'uses' => 'Admin\Academics\SmSectionController@update'])->middleware('userRolePermission:section_edit');
        Route::get('section-delete/{id}', ['as' => 'section_delete', 'uses' => 'Admin\Academics\SmSectionController@delete'])->middleware('userRolePermission:section_delete');

        // Subject routes
        Route::get('subject', ['as' => 'subject', 'uses' => 'Admin\Academics\SmSubjectController@index'])->middleware('userRolePermission:subject');
        Route::post('subject-store', ['as' => 'subject_store', 'uses' => 'Admin\Academics\SmSubjectController@store'])->middleware('userRolePermission:subject_store');
        Route::get('subject-edit/{id}', ['as' => 'subject_edit', 'uses' => 'Admin\Academics\SmSubjectController@edit'])->middleware('userRolePermission:subject_edit');
        Route::post('subject-update', ['as' => 'subject_update', 'uses' => 'Admin\Academics\SmSubjectController@update'])->middleware('userRolePermission:subject_edit');
        Route::get('subject-delete/{id}', ['as' => 'subject_delete', 'uses' => 'Admin\Academics\SmSubjectController@delete'])->middleware('userRolePermission:subject_delete');

        //Class Routine
        // Route::get('class-routine', ['as' => 'class_routine', 'uses' => 'SmAcademicsController@classRoutine']);
        // Route::get('class-routine-create', ['as' => 'class_routine_create', 'uses' => 'SmAcademicsController@classRoutineCreate']);
        Route::get('ajaxSelectSubject', 'SmAcademicsController@ajaxSelectSubject');
        Route::get('ajaxSelectCurrency', 'Admin\SystemSettings\SmSystemSettingController@ajaxSelectCurrency');

        // Route::post('assign-routine-search', 'SmAcademicsController@assignRoutineSearch');
        // Route::get('assign-routine-search', 'SmAcademicsController@classRoutine');
        // Route::post('assign-routine-store', 'SmAcademicsController@assignRoutineStore');
        // Route::post('class-routine-report-search', 'SmAcademicsController@classRoutineReportSearch');
        // Route::get('class-routine-report-search', 'SmAcademicsController@classRoutineReportSearch');


        // class routine new

        Route::get('class-routine-new', ['as' => 'class_routine_new', 'uses' => 'Admin\Academics\SmClassRoutineNewController@classRoutine'])->middleware('userRolePermission:class_routine');




        Route::post('class-routine-new', 'Admin\Academics\SmClassRoutineNewController@classRoutineSearch')->name('class_routine_new');
        Route::get('add-new-routine/{class_time_id}/{day}/{class_id}/{section_id}', 'Admin\Academics\SmClassRoutineNewController@addNewClassRoutine')->name('add-new-routine')->middleware('userRolePermission:add-new-class-routine-store');

        Route::post('add-new-class-routine-store', 'Admin\Academics\SmClassRoutineNewController@addNewClassRoutineStore')->name('add-new-class-routine-store')->middleware('userRolePermission:add-new-class-routine-store');


        Route::get('get-class-teacher-ajax', 'Admin\Academics\SmClassRoutineNewController@getClassTeacherAjax');
        Route::get('delete-class-routine/{id}', 'Admin\Academics\SmClassRoutineNewController@deleteClassRoutine')->name('delete-class-routine')->middleware('userRolePermission:delete-class-routine');

        Route::get('class-routine-new/{class_id}/{section_id}', 'Admin\Academics\SmClassRoutineNewController@classRoutineRedirect');

        Route::post('delete-class-routine', 'Admin\Academics\SmClassRoutineNewController@destroyClassRoutine')->name('destroy-class-routine')->middleware('userRolePermission:delete-class-routine');
        //Student Panel

        Route::get('print-teacher-routine/{teacher_id}', 'Admin\Academics\SmClassRoutineNewController@printTeacherRoutine')->name('print-teacher-routine');
        Route::get('view-teacher-routine', 'teacher\SmAcademicsController@viewTeacherRoutine')->name('view-teacher-routine');

        //assign subject
        Route::get('assign-subject', ['as' => 'assign_subject', 'uses' => 'Admin\Academics\SmAssignSubjectController@index'])->middleware('userRolePermission:assign_subject');

        Route::get('assign-subject-create', ['as' => 'assign_subject_create', 'uses' => 'Admin\Academics\SmAssignSubjectController@create'])->middleware('userRolePermission:assign-subject-store');

        Route::post('assign-subject-search', ['as' => 'assign_subject_search', 'uses' => 'Admin\Academics\SmAssignSubjectController@search']);
        Route::get('assign-subject-search', 'Admin\Academics\SmAssignSubjectController@create');
        Route::post('assign-subject-store', 'Admin\Academics\SmAssignSubjectController@assignSubjectStore')->name('assign-subject-store')->middleware('userRolePermission:assign-subject-store');
        Route::get('assign-subject-store', 'Admin\Academics\SmAssignSubjectController@create');
        Route::post('assign-subject', 'Admin\Academics\SmAssignSubjectController@assignSubjectFind')->name('assign-subject');
        Route::get('assign-subject-get-by-ajax', 'Admin\Academics\SmAssignSubjectController@assignSubjectAjax');

        //Assign Class Teacher
        // Route::resource('assign-class-teacher', 'SmAssignClassTeacherControler')->middleware('userRolePermission:253');
        Route::get('assign-class-teacher', 'Admin\Academics\SmAssignClassTeacherController@index')->name('assign-class-teacher')->middleware('userRolePermission:assign-class-teacher');
        Route::post('assign-class-teacher', 'Admin\Academics\SmAssignClassTeacherController@store')->name('assign-class-teacher')->middleware('userRolePermission:assign-class-teacher-store');
        Route::get('assign-class-teacher/{id}', 'Admin\Academics\SmAssignClassTeacherController@edit')->name('assign-class-teacher-edit')->middleware('userRolePermission:assign-class-teacher-edit');
        Route::put('assign-class-teacher/{id}', 'Admin\Academics\SmAssignClassTeacherController@update')->name('assign-class-teacher-update')->middleware('userRolePermission:assign-class-teacher-edit');
        Route::delete('assign-class-teacher/{id}', 'Admin\Academics\SmAssignClassTeacherController@destroy')->name('assign-class-teacher-delete')->middleware('userRolePermission:assign-class-teacher-delete');
        // Class room
        // Route::resource('class-room', 'SmClassRoomController')->middleware('userRolePermission:269');
        Route::get('class-room', 'Admin\Academics\SmClassRoomController@index')->name('class-room')->middleware('userRolePermission:class-room');
        Route::post('class-room', 'Admin\Academics\SmClassRoomController@store')->name('class-room')->middleware('userRolePermission:class-room-store');
        Route::get('class-room/{id}', 'Admin\Academics\SmClassRoomController@edit')->name('class-room-edit')->middleware('userRolePermission:class-room-edit');
        Route::put('class-room/{id}', 'Admin\Academics\SmClassRoomController@update')->name('class-room-update')->middleware('userRolePermission:class-room-edit');
        Route::delete('class-room/{id}', 'Admin\Academics\SmClassRoomController@destroy')->name('class-room-delete')->middleware('userRolePermission:class-room-delete');

        // Route::resource('class-time', 'SmClassTimeController')->middleware('userRolePermission:273');
        // Route::get('class-time', 'Admin\Academics\SmClassTimeController@index')->name('class-time')->middleware('userRolePermission:273');
        // Route::post('class-time', 'Admin\Academics\SmClassTimeController@store')->name('class-time')->middleware('userRolePermission:274');
        // Route::get('class-time/{id}', 'Admin\Academics\SmClassTimeController@edit')->name('class-time-edit')->middleware('userRolePermission:275');
        // Route::put('class-time/{id}', 'Admin\Academics\SmClassTimeController@update')->name('class-time-update')->middleware('userRolePermission:275');
        // Route::delete('class-time/{id}', 'Admin\Academics\SmClassTimeController@destroy')->name('class-time-delete');
        



        //Admission Query
        Route::get('admission-query', ['as' => 'admission_query', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@index'])->middleware('userRolePermission:admission_query');

        Route::post('admission-query-store-a', ['as' => 'admission_query_store_a', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@store']);

        Route::get('admission-query-edit/{id}', ['as' => 'admission_query_edit', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@edit'])->middleware('userRolePermission:admission_query_edit');
        Route::post('admission-query-update', ['as' => 'admission_query_update', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@update']);
        Route::get('add-query/{id}', ['as' => 'add_query', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@addQuery'])->middleware('userRolePermission:add_query');
        Route::post('query-followup-store', ['as' => 'query_followup_store', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@queryFollowupStore']);
        Route::get('delete-follow-up/{id}', ['as' => 'delete_follow_up', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@deleteFollowUp']);
        Route::post('admission-query-delete', ['as' => 'admission_query_delete', 'uses' => 'Admin\AdminSection\SmAdmissionQueryController@delete'])->middleware('userRolePermission:15');

        Route::post('admission-query-search', 'Admin\AdminSection\SmAdmissionQueryController@admissionQuerySearch')->name('admission-query-search');
        Route::get('admission-query-search', 'Admin\AdminSection\SmAdmissionQueryController@index');

        Route::get('admission-query-datatable', 'Admin\AdminSection\SmAdmissionQueryController@admissionQueryDatatable')->name('admission-query-datatable');

        // Visitor routes

        Route::get('visitor', ['as' => 'visitor', 'uses' => 'Admin\AdminSection\SmVisitorController@index'])->middleware('userRolePermission:visitor');
        Route::post('visitor-store', ['as' => 'visitor_store', 'uses' => 'Admin\AdminSection\SmVisitorController@store'])->middleware('userRolePermission:visitor_store');
        Route::get('visitor-edit/{id}', ['as' => 'visitor_edit', 'uses' => 'Admin\AdminSection\SmVisitorController@edit'])->middleware('userRolePermission:visitor_edit');
        Route::post('visitor-update', ['as' => 'visitor_update', 'uses' => 'Admin\AdminSection\SmVisitorController@update'])->middleware('userRolePermission:visitor_edit');
        Route::post('visitor-delete', ['as' => 'visitor_delete', 'uses' => 'Admin\AdminSection\SmVisitorController@delete'])->middleware('userRolePermission:visitor_delete');
        Route::get('download-visitor-document/{file_name}', ['as' => 'visitor_download', 'uses' => 'Admin\AdminSection\SmVisitorController@download_files'])->middleware('userRolePermission:visitor_download');

        Route::get('visitor-datatable', ['as' => 'visitor_datatable', 'uses' => 'Admin\AdminSection\SmVisitorController@visitorDatatable'])->middleware('userRolePermission:visitor_datatable');

        // Route::get('download-visitor-document/{file_name}', function ($file_name = null) {

        //     $file = public_path() . '/uploads/visitor/' . $file_name;
        //     if (file_exists($file)) {
        //         return Response::download($file);
        //     }
        // });

        // Fees Group routes
        Route::get('fees-group', ['as' => 'fees_group', 'uses' => 'Admin\FeesCollection\SmFeesGroupController@index'])->middleware('userRolePermission:fees_group');
        Route::post('fees-group-store', ['as' => 'fees_group_store', 'uses' => 'Admin\FeesCollection\SmFeesGroupController@store'])->middleware('userRolePermission:fees_group_store');
        Route::get('fees-group-edit/{id}', ['as' => 'fees_group_edit', 'uses' => 'Admin\FeesCollection\SmFeesGroupController@edit'])->middleware('userRolePermission:fees_group_edit');
        Route::post('fees-group-update', ['as' => 'fees_group_update', 'uses' => 'Admin\FeesCollection\SmFeesGroupController@update'])->middleware('userRolePermission:fees_group_edit');
        Route::post('fees-group-delete', ['as' => 'fees_group_delete', 'uses' => 'Admin\FeesCollection\SmFeesGroupController@deleteGroup'])->middleware('userRolePermission:fees_group_delete');

        // Fees type routes
        Route::get('fees-type', ['as' => 'fees_type', 'uses' => 'Admin\FeesCollection\SmFeesTypeController@index'])->middleware('userRolePermission:fees_type');
        Route::post('fees-type-store', ['as' => 'fees_type_store', 'uses' => 'Admin\FeesCollection\SmFeesTypeController@store'])->middleware('userRolePermission:fees_type_store');
        Route::get('fees-type-edit/{id}', ['as' => 'fees_type_edit', 'uses' => 'Admin\FeesCollection\SmFeesTypeController@edit'])->middleware('userRolePermission:fees_type_edit');
        Route::post('fees-type-update', ['as' => 'fees_type_update', 'uses' => 'Admin\FeesCollection\SmFeesTypeController@update'])->middleware('userRolePermission:fees_type_edit');
        Route::get('fees-type-delete/{id}', ['as' => 'fees_type_delete', 'uses' => 'Admin\FeesCollection\SmFeesTypeController@delete'])->middleware('userRolePermission:fees_type_delete');

        // Fees Discount routes
        Route::get('fees-discount', ['as' => 'fees_discount', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@index'])->middleware('userRolePermission:fees_discount');
        Route::post('fees-discount-store', ['as' => 'fees_discount_store', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@store'])->middleware('userRolePermission:fees_discount_store');
        Route::get('fees-discount-edit/{id}', ['as' => 'fees_discount_edit', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@edit'])->middleware('userRolePermission:fees_discount_edit');
        Route::post('fees-discount-update', ['as' => 'fees_discount_update', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@update'])->middleware('userRolePermission:fees_discount_edit');
        Route::get('fees-discount-delete/{id}', ['as' => 'fees_discount_delete', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@delete'])->middleware('userRolePermission:fees_discount_delete');
        Route::get('fees-discount-assign/{id}', ['as' => 'fees_discount_assign', 'uses' => 'Admin\FeesCollection\SmFeesDiscountController@feesDiscountAssign'])->middleware('userRolePermission:fees_discount_assign');
        Route::post('fees-discount-assign-search', 'Admin\FeesCollection\SmFeesDiscountController@feesDiscountAssignSearch')->name('fees-discount-assign-search');
        Route::post('fees-discount-assign-store', 'Admin\FeesCollection\SmFeesDiscountController@feesDiscountAssignStore');
        Route::post('directfees/fees-discount-assign-store', 'Admin\FeesCollection\SmFeesDiscountController@directFeesDiscountAssignStore')->name('directFees.fees-discount-assign-store');

        Route::get('fees-generate-modal/{amount}/{student_id}/{type}/{master}/{assign_id}/{record_id}', 'Admin\FeesCollection\SmFeesController@feesGenerateModal')->name('fees-generate-modal')->middleware('userRolePermission:fees-generate-modal');
        Route::get('fees-discount-amount-search', 'Admin\FeesCollection\SmFeesDiscountController@feesDiscountAmountSearch');
        //delete fees payment
        Route::post('fees-payment-delete', 'Admin\FeesCollection\SmFeesController@feesPaymentDelete')->name('fees-payment-delete');

        Route::get('direct-fees-generate-modal/{amount}/{installment_id}/{record_id}', 'Admin\FeesCollection\SmFeesController@directFeesGenerateModal')->name('direct-fees-generate-modal')->middleware('userRolePermission:fees-generate-modal');
        Route::post('directFeesInstallmentUpdate', 'Admin\FeesCollection\SmFeesController@directFeesInstallmentUpdate')->name('directFeesInstallmentUpdate')->middleware('userRolePermission:fees-generate-modal');

        Route::get('direct-fees-total-payment/{record_id}', 'Admin\FeesCollection\SmFeesController@directFeesTotalPayment')->name('direct-fees-total-payment')->middleware('userRolePermission:fees-generate-modal');
        Route::post('direct-fees-total-payment', 'Admin\FeesCollection\SmFeesController@directFeesTotalPaymentSubmit')->name('direct-fees-total-payment-submit')->middleware('userRolePermission:fees-generate-modal');


        Route::get('directFees/editSubPaymentModal/{payment_id}/{paid_amount}', 'Admin\FeesCollection\SmFeesController@editSubPaymentModal')->name('directFees.editSubPaymentModal')->middleware('userRolePermission:fees-generate-modal');
        Route::post('directFees/deleteSubPayment', 'Admin\FeesCollection\SmFeesController@deleteSubPayment')->name('directFees.deleteSubPayment');
        Route::post('directFees/updateSubPaymentModal', 'Admin\FeesCollection\SmFeesController@updateSubPaymentModal')->name('directFees.updateSubPaymentModal');
        Route::get('directFees/viewPaymentReceipt/{id}', 'Admin\FeesCollection\SmFeesController@viewPaymentReceipt')->name('directFees.viewPaymentReceipt');
        Route::get('directFees/setting', 'Admin\FeesCollection\SmFeesController@directFeesSetting')->name('directFees.setting');
        Route::post('directFees/feesInvoiceUpdate', 'Admin\FeesCollection\SmFeesController@feesInvoiceUpdate')->name('directFees.feesInvoiceUpdate');
        Route::post('directFees/paymentReminder', 'Admin\FeesCollection\SmFeesController@paymentReminder')->name('directFees.paymentReminder');

        // Fees carry forward
        Route::get('fees-forward', ['as' => 'fees_forward', 'uses' => 'Admin\FeesCollection\SmFeesCarryForwardController@feesForward'])->middleware('userRolePermission:fees_forward');
        Route::post('fees-forward-search', 'Admin\FeesCollection\SmFeesCarryForwardController@feesForwardSearch')->name('fees-forward-search')->middleware('userRolePermission:fees_forward');
        Route::get('fees-forward-search', 'Admin\FeesCollection\SmFeesCarryForwardController@feesForward')->middleware('userRolePermission:fees_forward');

        Route::post('fees-forward-store', 'Admin\FeesCollection\SmFeesCarryForwardController@feesForwardStore')->name('fees-forward-store')->middleware('userRolePermission:fees_forward');
        Route::get('fees-forward-store', 'Admin\FeesCollection\SmFeesCarryForwardController@feesForward')->middleware('userRolePermission:fees_forward');;

        //fees payment store
        Route::post('fees-payment-store', 'Admin\FeesCollection\SmFeesController@feesPaymentStore')->name('fees-payment-store');

         Route::get('bank-slip-view/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/bankSlip/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('bank-slip-view');

        // Collect Fees
        Route::get('collect-fees', ['as' => 'collect_fees', 'uses' => 'Admin\FeesCollection\SmFeesCollectController@index'])->middleware('userRolePermission:collect_fees');
        Route::get('fees-collect-student-wise/{id}', ['as' => 'fees_collect_student_wise', 'uses' => 'Admin\FeesCollection\SmFeesCollectController@collectFeesStudent'])->where('id', '[0-9]+')->middleware('userRolePermission:110');

        Route::post('collect-fees', ['as' => 'collect_fees', 'uses' => 'Admin\FeesCollection\SmFeesCollectController@search']);


        // fees print
        Route::get('fees-payment-print/{id}/{group}', ['as' => 'fees_payment_print', 'uses' => 'Admin\FeesCollection\SmFeesController@feesPaymentPrint']);

        Route::get('fees-payment-invoice-print/{id}/{group}', ['as' => 'fees_payment_invoice_print', 'uses' => 'Admin\FeesCollection\SmFeesController@feesPaymentInvoicePrint']);

        Route::get('fees-group-print/{id}', ['as' => 'fees_group_print', 'uses' => 'Admin\FeesCollection\SmFeesController@feesGroupPrint'])->where('id', '[0-9]+');

        Route::get('fees-groups-print/{id}/{s_id}', 'Admin\FeesCollection\SmFeesController@feesGroupsPrint');

        //Search Fees Payment
        Route::get('search-fees-payment', ['as' => 'search_fees_payment', 'uses' => 'Admin\FeesCollection\SmSearchFeesPaymentController@index'])->middleware('userRolePermission:search_fees_payment');
        Route::post('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'Admin\FeesCollection\SmSearchFeesPaymentController@search']);
        Route::get('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'Admin\FeesCollection\SmSearchFeesPaymentController@index']);
        Route::get('edit-fees-payment/{id}',['as' => 'edit-fees-payment', 'uses' => 'Admin\FeesCollection\SmSearchFeesPaymentController@editFeesPayment']);
        Route::post('fees-payment-update',['as' =>'fees-payment-update','uses' => 'Admin\FeesCollection\SmSearchFeesPaymentController@updateFeesPayment']);
        //Fees Search due
        Route::get('search-fees-due', ['as' => 'search_fees_due', 'uses' => 'Admin\FeesCollection\SmFeesController@searchFeesDue'])->middleware('userRolePermission:search_fees_due');
        Route::post('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'Admin\FeesCollection\SmFeesController@feesDueSearch']);
        Route::get('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'Admin\FeesCollection\SmFeesController@searchFeesDue']);


        Route::post('send-dues-fees-email', 'Admin\FeesCollection\SmFeesController@sendDuesFeesEmail')->name('send-dues-fees-email');

        // fees bank slip approve
        Route::get('bank-payment-slip', 'Admin\FeesCollection\SmFeesBankPaymentController@bankPaymentSlip')->name('bank-payment-slip');
        Route::post('bank-payment-slip', 'Admin\FeesCollection\SmFeesBankPaymentController@bankPaymentSlipSearch')->name('bank-payment-slip');
        Route::post('approve-fees-payment', 'Admin\FeesCollection\SmFeesBankPaymentController@approveFeesPayment')->name('approve-fees-payment');
        Route::post('reject-fees-payment', 'Admin\FeesCollection\SmFeesBankPaymentController@rejectFeesPayment')->name('reject-fees-payment');
        Route::get('bank-payment-slip-ajax', 'DatatableQueryController@bankPaymentSlipAjax')->name('bank-payment-slip-ajax');

        //Fees Statement
        Route::get('fees-statement', ['as' => 'fees_statement', 'uses' =>'Admin\FeesCollection\SmFeesController@feesStatemnt'])->middleware('userRolePermission:fees_statement');
        Route::post('fees-statement-search', ['as' => 'fees_statement_search', 'uses' => 'Admin\FeesCollection\SmFeesController@feesStatementSearch']);

        // Balance fees report
        Route::get('balance-fees-report', ['as' => 'balance_fees_report', 'uses' => 'Admin\FeesCollection\SmFeesReportController@balanceFeesReport'])->middleware('userRolePermission:balance_fees_report');
        Route::post('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'Admin\FeesCollection\SmFeesReportController@balanceFeesSearch']);
        Route::get('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'Admin\FeesCollection\SmFeesReportController@balanceFeesReport']);

        // Transaction Report
        Route::get('transaction-report', ['as' => 'transaction_report', 'uses' => 'Admin\FeesCollection\SmCollectionReportController@transactionReport'])->middleware('userRolePermission:transaction_report');
        Route::post('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'Admin\FeesCollection\SmCollectionReportController@transactionReportSearch']);
        Route::get('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'Admin\FeesCollection\SmCollectionReportController@transactionReport']);

       
        //Fine Report
        Route::get('fine-report', ['as' => 'fine-report', 'uses' => 'Admin\FeesCollection\SmFeesController@fineReport'])->middleware('userRolePermission:fine-report');
        Route::post('fine-report-search', ['as' => 'fine-report-search', 'uses' => 'Admin\FeesCollection\SmFeesController@fineReportSearch']);
        

        // Class Report
        Route::get('class-report', ['as' => 'class_report', 'uses' => 'SmAcademicsController@classReport'])->middleware('userRolePermission:class_report');
        Route::post('class-report', ['as' => 'class_report', 'uses' => 'SmAcademicsController@classReportSearch']);


        // merit list Report
        Route::get('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'Admin\Examination\SmExaminationController@meritListReport'])->middleware('userRolePermission:merit_list_report');
        Route::post('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'Admin\Examination\SmExaminationController@meritListReportSearch']);
        Route::get('merit-list/print/{exam_id}/{class_id}/{section_id}',  'Admin\Examination\SmExaminationController@meritListPrint')->name('merit-list/print');


        //tabulation sheet report
        Route::get('reports-tabulation-sheet', ['as' => 'reports_tabulation_sheet', 'uses' => 'Admin\Examination\SmExaminationController@reportsTabulationSheet']);
        Route::post('reports-tabulation-sheet', ['as' => 'reports_tabulation_sheet', 'uses' => 'Admin\Examination\SmExaminationController@reportsTabulationSheetSearch']);


        //results-archive report resultsArchive
        Route::get('results-archive', 'Admin\Examination\SmExaminationController@resultsArchiveView')->name('results-archive');
        Route::get('get-archive-class', 'Admin\Examination\SmExaminationController@getArchiveClass');
        Route::post('results-archive',  'Admin\Examination\SmExaminationController@resultsArchiveSearch');

        //Previous Record
        Route::get('previous-record', 'SmStudentAdmissionController@previousRecord')->name('previous-record')->middleware('userRolePermission:previous-record');
        Route::post('previous-record',  'SmStudentAdmissionController@previousRecordSearch')->name('previous-record');

        //previous-class-results
        Route::get('previous-class-results', 'Admin\Examination\SmExaminationController@previousClassResults')->name('previous-class-results')->middleware('userRolePermission:previous-class-results');
        Route::post('previous-class-results-view', 'Admin\Examination\SmExaminationController@previousClassResultsViewPost')->name('previous-class-results-view');
        Route::post('previous-student-record', 'Admin\Examination\SmExaminationController@previousStudentRecord')->name('previous-student-record');

        Route::post('session-student', 'Admin\Examination\SmExaminationController@sessionStudentGet')->name('session_student');

        Route::post('previous-class-results', 'Admin\Examination\SmExaminationController@previousClassResultsViewPrint')->name('previous-class-result-print');
        // merit list Report
        Route::get('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamReport'])->middleware('userRolePermission:online_exam_report');
        Route::post('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamReportSearch']);

        // class routine report
        Route::get('class-routine-report', ['as' => 'class_routine_report', 'uses' => 'Admin\Academics\SmClassRoutineNewController@classRoutineReport'])->middleware('userRolePermission:class_routine_report');
        Route::post('class-routine-report', 'Admin\Academics\SmClassRoutineNewController@classRoutineReportSearch')->name('class_routine_report');


        // exam routine report
        Route::get('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'Admin\Examination\SmExamRoutineController@examRoutineReport'])->middleware('userRolePermission:exam_routine_report');
        Route::post('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'Admin\Examination\SmExamRoutineController@examRoutineReportSearch']);


        Route::get('exam-routine/print/{exam_id}', 'Admin\Examination\SmExamRoutineController@examRoutineReportSearchPrint')->name('exam-routine/print');

        Route::get('teacher-class-routine-report', ['as' => 'teacher_class_routine_report', 'uses' => 'Admin\Academics\SmClassRoutineNewController@teacherClassRoutineReport'])->middleware('userRolePermission:teacher_class_routine_report');
        Route::post('teacher-class-routine-report', 'Admin\Academics\SmClassRoutineNewController@teacherClassRoutineReportSearch')->name('teacher-class-routine-report');


        // mark sheet Report
        Route::get('mark-sheet-report', ['as' => 'mark_sheet_report', 'uses' => 'Admin\Examination\SmExaminationController@markSheetReport']);
        Route::post('mark-sheet-report', ['as' => 'mark_sheet_report', 'uses' => 'Admin\Examination\SmExaminationController@markSheetReportSearch']);
        Route::get('mark-sheet-report/print/{exam_id}/{class_id}/{section_id}/{student_id}', ['as' => 'mark_sheet_report_print', 'uses' => 'Admin\Examination\SmExaminationController@markSheetReportStudentPrint']);


        //mark sheet report student
        Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'Admin\Examination\SmExaminationController@markSheetReportStudent'])->middleware('userRolePermission:mark_sheet_report_student');
        Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'Admin\Examination\SmExaminationController@markSheetReportStudentSearch']);
        
        //100 Percent mark sheet report student
        Route::post('percent-marksheet-report', ['as' => 'percent-marksheet-report', 'uses' => 'Admin\Examination\SmExaminationController@percentMarkSheetReport']);


        //user log
        Route::get('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'Admin\FeesCollection\SmFeesController@studentFineReport'])->middleware('userRolePermission:student_fine_report');
        Route::post('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'Admin\FeesCollection\SmFeesController@studentFineReportSearch']);
        Route::get('user-log-ajax', ['as' => 'user_log_ajax', 'uses' => 'DatatableQueryController@userLogAjax'])->middleware('userRolePermission:user_log');

        //user log
        Route::get('user-log', ['as' => 'user_log', 'uses' => 'UserController@userLog'])->middleware('userRolePermission:user_log');

        Route::get('income-list-datatable', ['as' => 'incom_list_datatable', 'uses' => 'DatatableQueryController@incomeList']);

        // income head routes
        Route::get('income-head', ['as' => 'income_head', 'uses' => 'SmIncomeHeadController@index']);
        Route::post('income-head-store', ['as' => 'income_head_store', 'uses' => 'SmIncomeHeadController@store']);
        Route::get('income-head-edit/{id}', ['as' => 'income_head_edit', 'uses' => 'SmIncomeHeadController@edit']);
        Route::post('income-head-update', ['as' => 'income_head_update', 'uses' => 'SmIncomeHeadController@update']);
        Route::get('income-head-delete/{id}', ['as' => 'income_head_delete', 'uses' => 'SmIncomeHeadController@delete']);

        // Search account
        Route::get('search-account', ['as' => 'search_account', 'uses' => 'Admin\Accounts\SmAccountsController@searchAccount'])->middleware('userRolePermission:147');
        Route::post('search-account', ['as' => 'search_account', 'uses' => 'Admin\Accounts\SmAccountsController@searchAccountReportByDate']);
        Route::get('fund-transfer', ['as' => 'fund-transfer', 'uses' => 'Admin\Accounts\SmAccountsController@fundTransfer'])->middleware('userRolePermission:fund-transfer');
        Route::post('fund-transfer-store', ['as' => 'fund-transfer-store', 'uses' => 'Admin\Accounts\SmAccountsController@fundTransferStore']);
        Route::get('transaction', ['as' => 'transaction', 'uses' => 'Admin\Accounts\SmAccountsController@transaction'])->middleware('userRolePermission:transaction');
        Route::post('transaction-search', ['as' => 'transaction-search', 'uses' => 'Admin\Accounts\SmAccountsController@transactionSearch']);
        
        // Accounts Payroll Report
        Route::get('accounts-payroll-report', ['as' => 'accounts-payroll-report', 'uses' => 'Admin\Accounts\SmAccountsController@accountsPayrollReport'])->middleware('userRolePermission:accounts-payroll-report');
        Route::post('accounts-payroll-report-search', ['as' => 'accounts-payroll-report-search', 'uses' => 'Admin\Accounts\SmAccountsController@accountsPayrollReportSearch']);


        // add income routes
        Route::get('add-income', ['as' => 'add_income', 'uses' => 'Admin\Accounts\SmAddIncomeController@index'])->middleware('userRolePermission:add_income');
        Route::post('add-income-store', ['as' => 'add_income_store', 'uses' => 'Admin\Accounts\SmAddIncomeController@store'])->middleware('userRolePermission:add_income_store');
        Route::get('add-income-edit/{id}', ['as' => 'add_income_edit', 'uses' => 'Admin\Accounts\SmAddIncomeController@edit'])->middleware('userRolePermission:add_income_edit');
        Route::post('add-income-update', ['as' => 'add_income_update', 'uses' => 'Admin\Accounts\SmAddIncomeController@update'])->middleware('userRolePermission:add_income_edit');
        Route::post('add-income-delete', ['as' => 'add_income_delete', 'uses' => 'Admin\Accounts\SmAddIncomeController@delete'])->middleware('userRolePermission:add_income_delete');
        Route::get('download-income-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/add_income/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-income-document');


        // Profit of account
        Route::get('profit', ['as' => 'profit', 'uses' => 'Admin\Accounts\SmAccountsController@profit'])->middleware('userRolePermission:profit');
        Route::post('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'Admin\Accounts\SmAccountsController@searchProfitByDate']);
        Route::get('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'Admin\Accounts\SmAccountsController@profit']);

        // Student Type Routes
        Route::get('student-category', ['as' => 'student_category', 'uses' => 'Admin\StudentInfo\SmStudentCategoryController@index'])->middleware('userRolePermission:student_category');
        Route::post('student-category-store', ['as' => 'student_category_store', 'uses' => 'Admin\StudentInfo\SmStudentCategoryController@store'])->middleware('userRolePermission:student_category_store');
        Route::get('student-category-edit/{id}', ['as' => 'student_category_edit', 'uses' => 'Admin\StudentInfo\SmStudentCategoryController@edit'])->middleware('userRolePermission:student_category_edit');
        Route::post('student-category-update', ['as' => 'student_category_update', 'uses' => 'Admin\StudentInfo\SmStudentCategoryController@update'])->middleware('userRolePermission:student_category_edit');
        Route::get('student-category-delete/{id}', ['as' => 'student_category_delete', 'uses' => 'Admin\StudentInfo\SmStudentCategoryController@delete'])->middleware('userRolePermission:student_category_delete');

        // Student Group Routes
        Route::get('student-group', ['as' => 'student_group', 'uses' => 'Admin\StudentInfo\SmStudentGroupController@index'])->middleware('userRolePermission:student_group');
        Route::post('student-group-store', ['as' => 'student_group_store', 'uses' => 'Admin\StudentInfo\SmStudentGroupController@store'])->middleware('userRolePermission:student_group_store');
        Route::get('student-group-edit/{id}', ['as' => 'student_group_edit', 'uses' => 'Admin\StudentInfo\SmStudentGroupController@edit'])->middleware('userRolePermission:student_group_edit');
        Route::post('student-group-update', ['as' => 'student_group_update', 'uses' => 'Admin\StudentInfo\SmStudentGroupController@update'])->middleware('userRolePermission:student_group_edit');
        Route::get('student-group-delete/{id}', ['as' => 'student_group_delete', 'uses' => 'Admin\StudentInfo\SmStudentGroupController@delete'])->middleware('userRolePermission:student_group_delete');

        // Student Group Routes

        // Route::get('payment-method', ['as' => 'payment_method', 'uses' => 'SmPaymentMethodController@index'])->middleware('userRolePermission:payment_method');
        // Route::post('payment-method-store', ['as' => 'payment_method_store', 'uses' => 'SmPaymentMethodController@store'])->middleware('userRolePermission:153');
        // Route::get('payment-method-settings-edit/{id}', ['as' => 'payment_method_edit', 'uses' => 'SmPaymentMethodController@edit'])->middleware('userRolePermission:154');
        // Route::post('payment-method-update', ['as' => 'payment_method_update', 'uses' => 'SmPaymentMethodController@update'])->middleware('userRolePermission:154');
        // Route::get('delete-payment-method/{id}', ['as' => 'payment_method_delete', 'uses' => 'SmPaymentMethodController@delete'])->middleware('userRolePermission:155');


        //academic year
        // Route::resource('academic-year', 'Admin\SystemSettings\SmAcademicYearController')->middleware('userRolePermission:432');
        Route::get('academic-year', 'Admin\SystemSettings\SmAcademicYearController@index')->name('academic-year')->middleware('userRolePermission:academic-year');
        Route::post('academic-year', 'Admin\SystemSettings\SmAcademicYearController@store')->name('academic-year')->middleware('userRolePermission:academic-year-store');
        Route::get('academic-year/{id}', 'Admin\SystemSettings\SmAcademicYearController@show')->name('academic-year-edit')->middleware('userRolePermission:academic-year-edit');
        Route::put('academic-year/{id}', 'Admin\SystemSettings\SmAcademicYearController@update')->name('academic-year-update')->middleware('userRolePermission:academic-year-edit');
        Route::delete('academic-year/{id}', 'Admin\SystemSettings\SmAcademicYearController@destroy')->name('academic-year-delete')->middleware('userRolePermission:academic-year-delete');

        //Session
        Route::resource('session', 'SmSessionController');


        // exam
        Route::get('exam-reset', 'Admin\Examination\SmExamController@exam_reset');

        // Route::resource('exam', 'Admin\Examination\SmExamController')->middleware('userRolePermission:214');
        Route::get('exam', 'Admin\Examination\SmExamController@index')->name('exam')->middleware('userRolePermission:exam');
        Route::post('exam', 'Admin\Examination\SmExamController@store')->name('exam')->middleware('userRolePermission:exam-setup-store');
        Route::get('exam/{id}', 'Admin\Examination\SmExamController@show')->name('exam-edit')->middleware('userRolePermission:exam-edit');
        Route::put('exam/{id}', 'Admin\Examination\SmExamController@update')->name('exam-update')->middleware('userRolePermission:exam-edit');
        Route::delete('exam/{id}', 'Admin\Examination\SmExamController@destroy')->name('exam-delete')->middleware('userRolePermission:exam-delete');
        
        Route::get('exam-marks-setup/{id}', 'Admin\Examination\SmExamController@exam_setup')->name('exam-marks-setup')->where('id', '[0-9]+');
        Route::get('get-class-subjects', 'Admin\Examination\SmExamController@getClassSubjects');
        Route::get('subject-assign-check', 'Admin\Examination\SmExamController@subjectAssignCheck');

        // If 100% Mark Option is Enable
        Route::get('custom-marksheet-report', 'Admin\Examination\SmExamController@customMarksheetReport')->name('custom-marksheet-report')->middleware('userRolePermission:custom-marksheet-report');
        Route::post('percent-marksheet-print', 'Admin\Examination\SmExaminationController@percentMarksheetPrint')->name('percent-marksheet-print')->middleware('userRolePermission:percent-marksheet-print');

        // Dormitory Module
        //Dormitory List
        // Route::resource('dormitory-list', 'Admin\Dormitory\SmDormitoryListController')->middleware('userRolePermission:367');
        Route::get('dormitory-list', 'Admin\Dormitory\SmDormitoryListController@index')->name('dormitory-list')->middleware('userRolePermission:dormitory-list');
        Route::post('dormitory-list', 'Admin\Dormitory\SmDormitoryListController@store')->name('dormitory-list')->middleware('userRolePermission:dormitory-list-store');
        Route::get('dormitory-list/{id}', 'Admin\Dormitory\SmDormitoryListController@show')->name('dormitory-list-edit')->middleware('userRolePermission:dormitory-list-edit');
        Route::put('dormitory-list/{id}', 'Admin\Dormitory\SmDormitoryListController@update')->name('dormitory-list-update')->middleware('userRolePermission:dormitory-list-edit');
        Route::delete('dormitory-list/{id}', 'Admin\Dormitory\SmDormitoryListController@destroy')->name('dormitory-list-delete')->middleware('userRolePermission:dormitory-list-delete');

        //Room Type
        // Route::resource('room-type', 'Admin\Dormitory\SmRoomTypeController@')->middleware('userRolePermission:371');
        Route::get('room-type', 'Admin\Dormitory\SmRoomTypeController@index')->name('room-type')->middleware('userRolePermission:room-type');
        Route::post('room-type', 'Admin\Dormitory\SmRoomTypeController@store')->name('room-type')->middleware('userRolePermission:room-type-store');
        Route::get('room-type/{id}', 'Admin\Dormitory\SmRoomTypeController@show')->name('room-type-edit')->middleware('userRolePermission:room-type-edit');
        Route::put('room-type/{id}', 'Admin\Dormitory\SmRoomTypeController@update')->name('room-type-update')->middleware('userRolePermission:room-type-edit');
        Route::delete('room-type/{id}', 'Admin\Dormitory\SmRoomTypeController@destroy')->name('room-type-delete')->middleware('userRolePermission:room-type-delete');

        //Room Type
        // Route::resource('room-list', 'Admin\Dormitory\SmRoomListController')->middleware('userRolePermission:363');
        Route::get('room-list', 'Admin\Dormitory\SmRoomListController@index')->name('room-list')->middleware('userRolePermission:room-list');
        Route::post('room-list', 'Admin\Dormitory\SmRoomListController@store')->name('room-list')->middleware('userRolePermission:room-list-store');
        Route::get('room-list/{id}', 'Admin\Dormitory\SmRoomListController@show')->name('room-list-edit')->middleware('userRolePermission:room-list-edit');
        Route::put('room-list/{id}', 'Admin\Dormitory\SmRoomListController@update')->name('room-list-update')->middleware('userRolePermission:room-list-edit');
        Route::delete('room-list/{id}', 'Admin\Dormitory\SmRoomListController@destroy')->name('room-list-delete')->middleware('userRolePermission:room-list-delete');
        // Student Dormitory Report
        Route::get('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'Admin\Dormitory\SmDormitoryController@studentDormitoryReport'])->middleware('userRolePermission:student_dormitory_report');

        Route::post('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'Admin\Dormitory\SmDormitoryController@studentDormitoryReportSearch']);


        // Transport Module Start
        //Vehicle
        // Route::resource('vehicle', 'Admin\Transport\SmVehicleController')->middleware('userRolePermission:353');
        Route::get('vehicle', 'Admin\Transport\SmVehicleController@index')->name('vehicle')->middleware('userRolePermission:vehicle');
        Route::post('vehicle', 'Admin\Transport\SmVehicleController@store')->name('vehicle')->middleware('userRolePermission:vehicle-store');
        Route::get('vehicle/{id}', 'Admin\Transport\SmVehicleController@show')->name('vehicle-edit')->middleware('userRolePermission:vehicle-edit');
        Route::put('vehicle/{id}', 'Admin\Transport\SmVehicleController@update')->name('vehicle-update')->middleware('userRolePermission:vehicle-edit');
        Route::delete('vehicle/{id}', 'Admin\Transport\SmVehicleController@destroy')->name('vehicle-delete')->middleware('userRolePermission:vehicle-delete');

        //Assign Vehicle
        // Route::resource('assign-vehicle', 'Admin\Transport\SmAssignVehicleController')->middleware('userRolePermission:357');
        Route::get('assign-vehicle', 'Admin\Transport\SmAssignVehicleController@index')->name('assign-vehicle')->middleware('userRolePermission:assign-vehicle');
        Route::post('assign-vehicle', 'Admin\Transport\SmAssignVehicleController@store')->name('assign-vehicle')->middleware('userRolePermission:assign-vehicle-store');
        Route::get('assign-vehicle/{id}/edit', 'Admin\Transport\SmAssignVehicleController@edit')->name('assign-vehicle-edit')->middleware('userRolePermission:assign-vehicle-edit');
        Route::put('assign-vehicle/{id}', 'Admin\Transport\SmAssignVehicleController@update')->name('assign-vehicle-update')->middleware('userRolePermission:assign-vehicle-edit');
        // Route::delete('assign-vehicle/{id}', 'Admin\Transport\SmAssignVehicleController@delete')->name('assign-vehicle-delete')->middleware('userRolePermission:360');
        
        Route::post('assign-vehicle-delete', 'Admin\Transport\SmAssignVehicleController@delete')->name('assign-vehicle-delete')->middleware('userRolePermission:assign-vehicle-delete');

        // student transport report
        Route::get('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'Admin\Transport\SmTransportController@studentTransportReport'])->middleware('userRolePermission:student_transport_report');
        Route::post('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'Admin\Transport\SmTransportController@studentTransportReportSearch']);

        // Route transport
        // Route::resource('transport-route', 'Admin\Transport\SmRouteController')->middleware('userRolePermission:349');
        Route::get('transport-route', 'Admin\Transport\SmRouteController@index')->name('transport-route')->middleware('userRolePermission:transport-route');
        Route::post('transport-route', 'Admin\Transport\SmRouteController@store')->name('transport-route')->middleware('userRolePermission:transport-route-store');
        Route::get('transport-route/{id}', 'Admin\Transport\SmRouteController@show')->name('transport-route-edit')->middleware('userRolePermission:transport-route-edit');
        Route::put('transport-route/{id}', 'Admin\Transport\SmRouteController@update')->name('transport-route-update')->middleware('userRolePermission:transport-route-edit');
        Route::delete('transport-route/{id}', 'Admin\Transport\SmRouteController@destroy')->name('transport-route-delete')->middleware('userRolePermission:transport-route-delete');

        //// Examination
        // instruction Routes
        Route::get('instruction', 'SmInstructionController@index')->name('instruction');
        Route::post('instruction', 'SmInstructionController@store')->name('instruction');
        Route::get('instruction/{id}', 'SmInstructionController@show')->name('instruction-edit');
        Route::put('instruction/{id}', 'SmInstructionController@update')->name('instruction-update');
        Route::delete('instruction/{id}', 'SmInstructionController@destroy')->name('instruction-delete');

        // Question Level
        Route::get('question-level', 'SmQuestionLevelController@index')->name('question-level');
        Route::post('question-level', 'SmQuestionLevelController@store')->name('question-level');
        Route::get('question-level/{id}', 'SmQuestionLevelController@show')->name('question-level-edit');
        Route::put('question-level/{id}', 'SmQuestionLevelController@update')->name('question-level-update');
        Route::delete('question-level/{id}', 'SmQuestionLevelController@destroy')->name('question-level-delete');

        // Question group
        // Route::resource('question-group', 'Admin\OnlineExam\SmQuestionGroupController')->middleware('userRolePermission:230');
        Route::get('question-group', 'Admin\OnlineExam\SmQuestionGroupController@index')->name('question-group')->middleware('userRolePermission:question-group');
        Route::post('question-group', 'Admin\OnlineExam\SmQuestionGroupController@store')->name('question-group')->middleware('userRolePermission:question-group-store');
        Route::get('question-group/{id}', 'Admin\OnlineExam\SmQuestionGroupController@show')->name('question-group-edit')->middleware('userRolePermission:question-group-edit');
        Route::put('question-group/{id}', 'Admin\OnlineExam\SmQuestionGroupController@update')->name('question-group-update')->middleware('userRolePermission:question-group-edit');
        Route::delete('question-group/{id}', 'Admin\OnlineExam\SmQuestionGroupController@destroy')->name('question-group-delete')->middleware('userRolePermission:question-group-delete');

        // Question bank
        // Route::resource('question-bank', 'SmQuestionBankController')->middleware('userRolePermission:234');
        Route::get('question-bank', 'Admin\OnlineExam\SmQuestionBankController@index')->name('question-bank')->middleware('userRolePermission:question-bank');
        Route::post('question-bank', 'Admin\OnlineExam\SmQuestionBankController@store')->name('question-bank')->middleware('userRolePermission:question-bank-store');
        Route::get('question-bank/{id}', 'Admin\OnlineExam\SmQuestionBankController@show')->name('question-bank-edit')->middleware('userRolePermission:question-bank-edit');
        Route::put('question-bank/{id}', 'Admin\OnlineExam\SmQuestionBankController@update')->name('question-bank-update')->middleware('userRolePermission:question-bank-edit');
        Route::delete('question-bank/{id}', 'Admin\OnlineExam\SmQuestionBankController@destroy')->name('question-bank-delete')->middleware('userRolePermission:question-bank-delete');


        // Marks Grade
        // Route::resource('marks-grade', 'Admin\Examination\SmMarksGradeController')->middleware('userRolePermission:225');
        Route::get('marks-grade', 'Admin\Examination\SmMarksGradeController@index')->name('marks-grade')->middleware('userRolePermission:marks-grade');
        Route::post('marks-grade', 'Admin\Examination\SmMarksGradeController@store')->name('marks-grade')->middleware('userRolePermission:marks-grade-store');
        Route::get('marks-grade/{id}', 'Admin\Examination\SmMarksGradeController@show')->name('marks-grade-edit')->middleware('userRolePermission:marks-grade-edit');
        Route::put('marks-grade/{id}', 'Admin\Examination\SmMarksGradeController@update')->name('marks-grade-update')->middleware('userRolePermission:marks-grade-edit');
        Route::delete('marks-grade/{id}', 'Admin\Examination\SmMarksGradeController@destroy')->name('marks-grade-delete')->middleware('userRolePermission:marks-grade-delete');


        // exam
        // Route::resource('exam', 'Admin\Examination\SmExamController');

        Route::get('exam-type', 'Admin\Examination\SmExaminationController@exam_type')->name('exam-type')->middleware('userRolePermission:exam-type');
        Route::get('exam-type-edit/{id}', ['as' => 'exam_type_edit', 'uses' => 'Admin\Examination\SmExaminationController@exam_type_edit'])->middleware('userRolePermission:exam_type_edit');
        Route::post('exam-type-store', ['as' => 'exam_type_store', 'uses' => 'Admin\Examination\SmExaminationController@exam_type_store'])->middleware('userRolePermission:exam_type_store');
        Route::post('exam-type-update', ['as' => 'exam_type_update', 'uses' => 'Admin\Examination\SmExaminationController@exam_type_update'])->middleware('userRolePermission:exam_type_edit');
        Route::get('exam-type-delete/{id}', ['as' => 'exam_type_delete', 'uses' => 'Admin\Examination\SmExaminationController@exam_type_delete'])->middleware('userRolePermission:exam_type_delete');


        Route::get('exam-setup/{id}', 'Admin\Examination\SmExamController@examSetup');
        Route::post('exam-setup-store', 'Admin\Examination\SmExamController@examSetupStore')->name('exam-setup-store');


        // exam
        // Route::resource('department', 'SmHumanDepartmentController')->middleware('userRolePermission:184');
        Route::get('department', 'Admin\Hr\SmHumanDepartmentController@index')->name('department')->middleware('userRolePermission:department');
        Route::post('department', 'Admin\Hr\SmHumanDepartmentController@store')->name('department')->middleware('userRolePermission:department-store');
        Route::get('department/{id}', 'Admin\Hr\SmHumanDepartmentController@show')->name('department-edit')->middleware('userRolePermission:department-edit');
        Route::put('department/{id}', 'Admin\Hr\SmHumanDepartmentController@update')->name('department-update')->middleware('userRolePermission:department-edit');
        Route::delete('department/{id}', 'Admin\Hr\SmHumanDepartmentController@destroy')->name('department-delete')->middleware('userRolePermission:department-delete');

        // Route::post('exam-schedule-store', ['as' => 'exam_schedule_store', 'uses' => 'Admin\Examination\SmExaminationController@examScheduleStore']);
        // Route::get('exam-schedule-store', ['as' => 'exam_schedule_store', 'uses' => 'Admin\Examination\SmExaminationController@examScheduleCreate']);

        //Exam Schedule
        Route::get('exam-schedule', ['as' => 'exam_schedule', 'uses' => 'Admin\Examination\SmExamRoutineController@examSchedule'])->middleware('userRolePermission:exam_schedule');

        Route::post('exam-schedule-report-search', ['as' => 'exam_schedule_report_search', 'uses' => 'Admin\Examination\SmExamRoutineController@examScheduleReportSearch']);

        Route::get('exam-schedule-report-search', ['as' => 'exam_schedule_report_search', 'uses' => 'Admin\Examination\SmExamRoutineController@examSchedule']);
        Route::get('exam-schedule/print/{exam_id}/{class_id}/{section_id}', ['as' => 'exam_schedule_print', 'uses' => 'Admin\Examination\SmExamRoutineController@examSchedulePrint']);
        Route::get('view-exam-schedule/{class_id}/{section_id}/{exam_id}', ['as' => 'view_exam_schedule', 'uses' => 'Admin\Examination\SmExaminationController@viewExamSchedule']);


        //Exam Schedule create
        Route::get('exam-schedule-create', ['as' => 'exam_schedule_create', 'uses' => 'Admin\Examination\SmExamRoutineController@examScheduleCreate'])->middleware('userRolePermission:exam_schedule_store');

        Route::post('exam-schedule-create', ['as' => 'exam_schedule_create', 'uses' => 'Admin\Examination\SmExamRoutineController@examScheduleSearch'])->middleware('userRolePermission:exam_schedule_store');


        Route::post('delete-exam-routine', 'SmExamRoutineController@deleteExamRoutine')->name('delete-exam-routine');/* delete exam rouitne for update =abunayem */





        Route::get('exam-routine-view/{class_id}/{section_id}/{exam_period_id}', 'Admin\Examination\SmExamRoutineController@examRoutineView');
        Route::get('exam-routine-print/{class_id}/{section_id}/{exam_period_id}', 'Admin\Examination\SmExamRoutineController@examRoutinePrint')->name('exam-routine-print');

        //view exam status
        Route::get('view-exam-status/{exam_id}', ['as' => 'view_exam_status', 'uses' => 'Admin\Examination\SmExaminationController@viewExamStatus']);

        // marks register
        Route::get('marks-register', ['as' => 'marks_register', 'uses' => 'Admin\Examination\SmExamMarkRegisterController@index']);
        Route::post('marks-register', ['as' => 'marks_register', 'uses' => 'Admin\Examination\SmExamMarkRegisterController@reportSearch']);

        Route::get('marks-register-create', ['as' => 'marks_register_create', 'uses' => 'Admin\Examination\SmExamMarkRegisterController@create']);

        Route::post('add-exam-routine-store', 'Admin\Examination\SmExamRoutineController@addExamRoutineStore')->name('add-exam-routine-store');
        
        Route::post('marks-register-create', ['as' => 'marks_register_create', 'uses' => 'Admin\Examination\SmExamMarkRegisterController@search']);

        Route::post('marks_register_store', ['as' => 'marks_register_store', 'uses' => 'Admin\Examination\SmExamMarkRegisterController@store']);
        
        Route::get('exam-settings', ['as' => 'exam-settings', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@index'])->middleware('userRolePermission:exam-settings');
        Route::post('save-exam-content', ['as' => 'save-exam-content', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@store'])->middleware('userRolePermission:save-exam-content');
        Route::get('edit-exam-settings/{id}', ['as' => 'edit-exam-settings', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@edit']);
        Route::post('update-exam-content', ['as' => 'update-exam-content', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@update'])->middleware('userRolePermission:708');

        Route::get('delete-content/{id}', ['as' => 'delete-content', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@delete'])->middleware('userRolePermission:delete-content');
        
        Route::get('exam-report-position', ['as' => 'exam-report-position', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@examReportPosition']);
        Route::post('exam-report-position-store', ['as' => 'exam-report-position-store', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@examReportPositionStore']);
        
        Route::get('all-exam-report-position', ['as' => 'all-exam-report-position', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@allExamReportPosition']);
        Route::post('all-exam-report-position-store', ['as' => 'all-exam-report-position-store', 'uses' => 'Admin\Examination\SmExamFormatSettingsController@allExamReportPositionStore']);


        //Seat Plan
        Route::get('seat-plan', ['as' => 'seat_plan', 'uses' => 'Admin\Examination\SmExaminationController@seatPlan']);
        Route::post('seat-plan-report-search', ['as' => 'seat_plan_report_search', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanReportSearch']);
        Route::get('seat-plan-report-search', ['as' => 'seat_plan_report_search', 'uses' => 'Admin\Examination\SmExaminationController@seatPlan']);

        Route::get('seat-plan-create', ['as' => 'seat_plan_create', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanCreate']);

        Route::post('seat-plan-store', ['as' => 'seat_plan_store', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanStore']);
        Route::get('seat-plan-store', ['as' => 'seat_plan_store', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanCreate']);

        Route::post('seat-plan-search', ['as' => 'seat_plan_search', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanSearch']);
        Route::get('seat-plan-search', ['as' => 'seat_plan_search', 'uses' => 'Admin\Examination\SmExaminationController@seatPlanCreate']);
        Route::get('assign-exam-room-get-by-ajax', ['as' => 'assign-exam-room-get-by-ajax', 'uses' => 'Admin\Examination\SmExaminationController@getExamRoomByAjax']);
        Route::get('get-room-capacity', ['as' => 'get-room-capacity', 'uses' => 'Admin\Examination\SmExaminationController@getRoomCapacity']);


        // Exam Attendance
        Route::get('exam-attendance', ['as' => 'exam_attendance', 'uses' => 'Admin\Examination\SmExaminationController@examAttendance']);
        Route::post('exam-attendance', ['as' => 'exam_attendance', 'uses' => 'Admin\Examination\SmExaminationController@examAttendanceAeportSearch']);


        Route::get('exam-attendance-create', ['as' => 'exam_attendance_create', 'uses' => 'Admin\Examination\SmExamAttendanceController@examAttendanceCreate']);
        Route::post('exam-attendance-create', ['as' => 'exam_attendance_create', 'uses' => 'Admin\Examination\SmExamAttendanceController@examAttendanceSearch']);

        Route::post('exam-attendance-store', 'Admin\Examination\SmExamAttendanceController@examAttendanceStore')->name('exam-attendance-store');
        // Send Marks By SmS
        Route::get('send-marks-by-sms', ['as' => 'send_marks_by_sms', 'uses' => 'Admin\Examination\SmExaminationController@sendMarksBySms'])->middleware('userRolePermission:send_marks_by_sms');
        Route::post('send-marks-by-sms-store', ['as' => 'send_marks_by_sms_store', 'uses' => 'Admin\Examination\SmExaminationController@sendMarksBySmsStore'])->middleware('userRolePermission:marks-grade-edit');


        // Online Exam
        // Route::resource('online-exam', 'Admin\OnlineExam\SmOnlineExamController')->middleware('userRolePermission:238');
        Route::get('online-exam', 'Admin\OnlineExam\SmOnlineExamController@index')->name('online-exam')->middleware('userRolePermission:online-exam');
        Route::post('online-exam', 'Admin\OnlineExam\SmOnlineExamController@store')->name('online-exam')->middleware('userRolePermission:online-exam-store');
        Route::get('online-exam/{id}', 'Admin\OnlineExam\SmOnlineExamController@edit')->name('online-exam-edit')->middleware('userRolePermission:online-exam-edit');
        Route::get('view-online-exam-question/{id}', 'Admin\OnlineExam\SmOnlineExamController@viewOnlineExam')->name('online-exam-question-view')->middleware('userRolePermission:online-exam');
        Route::put('online-exam/{id}', 'Admin\OnlineExam\SmOnlineExamController@update')->name('online-exam-update')->middleware('userRolePermission:online-exam-edit');
        // Route::delete('online-exam/{id}', 'Admin\OnlineExam\SmOnlineExamController@delete')->name('online-exam-delete')->middleware('userRolePermission:241');

        Route::post('online-exam-delete', 'Admin\OnlineExam\SmOnlineExamController@delete')->name('online-exam-delete')->middleware('userRolePermission:online-exam-delete');
        Route::get('manage-online-exam-question/{id}', ['as' => 'manage_online_exam_question', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@manageOnlineExamQuestion'])->middleware('userRolePermission:manage_online_exam_question');
        Route::post('online_exam_question_store', ['as' => 'online_exam_question_store', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@manageOnlineExamQuestionStore']);

        Route::get('online-exam-publish/{id}', ['as' => 'online_exam_publish', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamPublish']);
        Route::get('online-exam-publish-cancel/{id}', ['as' => 'online_exam_publish_cancel', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamPublishCancel']);

        Route::get('online-question-edit/{id}/{type}/{examId}', 'Admin\OnlineExam\SmOnlineExamController@onlineQuestionEdit');
        Route::post('online-exam-question-edit', ['as' => 'online_exam_question_edit', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamQuestionEdit']);
        Route::post('online-exam-question-delete', 'Admin\OnlineExam\SmOnlineExamController@onlineExamQuestionDelete')->name('online-exam-question-delete');

        // store online exam question
        Route::post('online-exam-question-assign', ['as' => 'online_exam_question_assign', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamQuestionAssign']);

        Route::get('view_online_question_modal/{id}', ['as' => 'view_online_question_modal', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@viewOnlineQuestionModal']);


        // Online exam marks
        Route::get('online-exam-marks-register/{id}', ['as' => 'online_exam_marks_register', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamMarksRegister'])->middleware('userRolePermission:243');

        // Route::post('online-exam-marks-store', ['as' => 'online_exam_marks_store', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamMarksStore']);
        Route::get('online-exam-result/{id}', ['as' => 'online_exam_result', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamResult'])->middleware('userRolePermission:online_exam_result');

        Route::get('online-exam-marking/{exam_id}/{s_id}', ['as' => 'online_exam_marking', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamMarking']);
        Route::post('online-exam-marks-store', ['as' => 'online_exam_marks_store', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamMarkingStore']);

        Route::get('online-exam-datatable', ['as' => 'online_exam_datatable', 'uses' => 'Admin\OnlineExam\SmOnlineExamController@onlineExamDatatable'])->middleware('userRolePermission:online-exam-datatable');

        // Staff Hourly rate
        Route::get('hourly-rate', 'SmHourlyRateController@index')->name('hourly-rate');
        Route::post('hourly-rate', 'SmHourlyRateController@store')->name('hourly-rate');
        Route::get('hourly-rate', 'SmHourlyRateController@show')->name('hourly-rate');
        Route::put('hourly-rate', 'SmHourlyRateController@update')->name('hourly-rate');
        Route::delete('hourly-rate', 'SmHourlyRateController@destroy')->name('hourly-rate');

        // Staff leave type
        // Route::resource('leave-type', 'SmLeaveTypeController')->middleware('userRolePermission:203');
        Route::get('leave-type', 'Admin\Leave\SmLeaveTypeController@index')->name('leave-type')->middleware('userRolePermission:leave-type');
        Route::post('leave-type', 'Admin\Leave\SmLeaveTypeController@store')->name('leave-type')->middleware('userRolePermission:leave-type-store');
        Route::get('leave-type/{id}', 'Admin\Leave\SmLeaveTypeController@show')->name('leave-type-edit')->middleware('userRolePermission:leave-type-edit');
        Route::put('leave-type/{id}', 'Admin\Leave\SmLeaveTypeController@update')->name('leave-type-update')->middleware('userRolePermission:leave-type-edit');
        Route::delete('leave-type/{id}', 'Admin\Leave\SmLeaveTypeController@destroy')->name('leave-type-delete')->middleware('userRolePermission:leave-type-delete');

        // Staff leave define
        // Route::resource('leave-define', 'Admin\Leave\SmLeaveDefineController')->middleware('userRolePermission:199');
        Route::get('leave-define', 'Admin\Leave\SmLeaveDefineController@index')->name('leave-define')->middleware('userRolePermission:leave-define');
        Route::post('leave-define', 'Admin\Leave\SmLeaveDefineController@store')->name('leave-define')->middleware('userRolePermission:leave-define-define');
        Route::get('leave-define/{id}', 'Admin\Leave\SmLeaveDefineController@show')->name('leave-define-edit')->middleware('userRolePermission:leave-define-edit');
        Route::put('leave-define/{id}', 'Admin\Leave\SmLeaveDefineController@update')->name('leave-define-update')->middleware('userRolePermission:leave-define-edit');
        Route::delete('leave-define', 'Admin\Leave\SmLeaveDefineController@destroy')->name('leave-define-delete')->middleware('userRolePermission:leave-define-delete');
        Route::post('leave-define-updateLeave', 'Admin\Leave\SmLeaveDefineController@updateLeave')->name('leave-define-updateLeave')->middleware('userRolePermission:leave-define-edit');

        Route::get('leave-define-ajax', 'DatatableQueryController@leaveDefineList')->name('leave-define-ajax')->middleware('userRolePermission:leave-define');

        // Staff leave define
        // Route::resource('apply-leave', 'SmLeaveRequestController')->middleware('userRolePermission:193');
        Route::get('apply-leave', 'Admin\Leave\SmLeaveRequestController@index')->name('apply-leave')->middleware('userRolePermission:apply-leave');
        Route::post('apply-leave', 'Admin\Leave\SmLeaveRequestController@store')->name('apply-leave')->middleware('userRolePermission:apply-leave-store');
        Route::get('apply-leave/{id}', 'Admin\Leave\SmLeaveRequestController@show')->name('apply-leave-edit')->middleware('userRolePermission:apply-leave-edit');
        Route::put('apply-leave/{id}', 'Admin\Leave\SmLeaveRequestController@update')->name('apply-leave-update')->middleware('userRolePermission:apply-leave-edit');
        Route::delete('apply-leave/{id}', 'Admin\Leave\SmLeaveRequestController@destroy')->name('apply-leave-delete')->middleware('userRolePermission:apply-leave-delete');
        Route::post('apply-leave-delte', 'Admin\Leave\SmLeaveRequestController@deleteLeave')->name('delete-apply-leave')->middleware('userRolePermission:apply-leave-delete');


          // Route::resource('approve-leave', 'Admin\Leave\SmApproveLeaveController')->middleware('userRolePermission:189');
          Route::get('approve-leave', 'Admin\Leave\SmApproveLeaveController@index')->name('approve-leave')->middleware('userRolePermission:approve-leave');
          Route::post('approve-leave', 'Admin\Leave\SmApproveLeaveController@store')->name('approve-leave');
          Route::get('approve-leave/{id}', 'Admin\Leave\SmApproveLeaveController@show')->name('approve-leave-edit');
          Route::put('approve-leave/{id}', 'Admin\Leave\SmApproveLeaveController@update')->name('approve-leave-update');
          Route::delete('approve-leave/{id}','Admin\Leave\SmApproveLeaveController@destroy')->name('approve-leave-delete')->middleware('userRolePermission:approve-leave-delete');
  
          Route::get('pending-leave', 'Admin\Leave\SmApproveLeaveController@pendingLeave')->name('pending-leave')->middleware('userRolePermission:pending-leave');
  
          Route::post('update-approve-leave', 'Admin\Leave\SmApproveLeaveController@updateApproveLeave')->name('update-approve-leave');
  
          Route::get('/staffNameByRole', 'Admin\Leave\SmApproveLeaveController@staffNameByRole');
  
          Route::get('view-leave-details-approve/{id}', 'Admin\Leave\SmApproveLeaveController@viewLeaveDetails')->name('view-leave-details-approve')->middleware('userRolePermission:approve-leave-edit');
          
  
        // Staff designation
        // Route::resource('designation', 'SmDesignationController')->middleware('userRolePermission:180');
        Route::get('designation', 'Admin\Hr\SmDesignationController@index')->name('designation')->middleware('userRolePermission:designation');
        Route::post('designation', 'Admin\Hr\SmDesignationController@store')->name('designation')->middleware('userRolePermission:designation-store');
        Route::get('designation/{id}', 'Admin\Hr\SmDesignationController@show')->name('designation-edit')->middleware('userRolePermission:designation-edit');
        Route::put('designation/{id}', 'Admin\Hr\SmDesignationController@update')->name('designation-update')->middleware('userRolePermission:designation-edit');
        Route::delete('designation/{id}', 'Admin\Hr\SmDesignationController@destroy')->name('designation-delete')->middleware('userRolePermission:designation-delete');

      
        // Bank Account
        // Route::resource('bank-account', 'Admin\Accounts\SmBankAccountController')->middleware('userRolePermission:156');
        Route::get('bank-account', 'Admin\Accounts\SmBankAccountController@index')->name('bank-account')->middleware('userRolePermission:bank-account');
        Route::post('bank-account', 'Admin\Accounts\SmBankAccountController@store')->name('bank-account')->middleware('userRolePermission:bank-account-store');
        Route::get('bank-account/{id}', 'Admin\Accounts\SmBankAccountController@show')->name('bank-account-edit');
        Route::put('bank-account/{id}', 'Admin\Accounts\SmBankAccountController@update')->name('bank-account-update');
        Route::get('bank-transaction/{id}', 'Admin\Accounts\SmBankAccountController@bankTransaction')->name('bank-transaction')->middleware('userRolePermission:bank-transaction');
        Route::delete('bank-account-delete', 'Admin\Accounts\SmBankAccountController@destroy')->name('bank-account-delete')->middleware('userRolePermission:bank-account-delete');
        Route::get('bank-account-datatable', 'Admin\Accounts\SmBankAccountController@bankAccountDatatable')->name('bank-account-datatable')->middleware('userRolePermission:bank-account-datatable');

        // Expense head
        // Route::resource('expense-head', 'SmExpenseHeadController');   //not used 

        // Chart Of Account
        // Route::resource('chart-of-account', 'SmChartOfAccountController')->middleware('userRolePermission:148');
        Route::get('chart-of-account', 'Admin\Accounts\SmChartOfAccountController@index')->name('chart-of-account')->middleware('userRolePermission:chart-of-account');
        Route::post('chart-of-account', 'Admin\Accounts\SmChartOfAccountController@store')->name('chart-of-account')->middleware('userRolePermission:chart-of-account-store');
        Route::get('chart-of-account/{id}', 'Admin\Accounts\SmChartOfAccountController@show')->name('chart-of-account-edit')->middleware('userRolePermission:chart-of-account-edit');
        Route::put('chart-of-account/{id}', 'Admin\Accounts\SmChartOfAccountController@update')->name('chart-of-account-update')->middleware('userRolePermission:chart-of-account-edit');
        Route::delete('chart-of-account/{id}', 'Admin\Accounts\SmChartOfAccountController@destroy')->name('chart-of-account-delete')->middleware('userRolePermission:chart-of-account-delete');

        // Add Expense
        // Route::resource('add-expense', 'Admin\Accounts\SmAddExpenseController')->middleware('userRolePermission:143');
        Route::get('add-expense', 'Admin\Accounts\SmAddExpenseController@index')->name('add-expense')->middleware('userRolePermission:add-expense');
        Route::post('add-expense', 'Admin\Accounts\SmAddExpenseController@store')->name('add-expense')->middleware('userRolePermission:add-expense-store');
        Route::get('add-expense/{id}', 'Admin\Accounts\SmAddExpenseController@show')->name('add-expense-edit')->middleware('userRolePermission:add-expense-edit');
        Route::put('add-expense/{id}', 'Admin\Accounts\SmAddExpenseController@update')->name('add-expense-update')->middleware('userRolePermission:add-expense-edit');
        Route::post('add-expense-delete', 'Admin\Accounts\SmAddExpenseController@destroy')->name('add-expense-delete')->middleware('userRolePermission:add-expense-delete');
        Route::get('download-expense-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/addExpense/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-expense-document');
        // Fees Master
        // Route::resource('fees-master', 'Admin\FeesCollection\SmFeesMasterController')->middleware('userRolePermission:118');
        Route::get('fees-master', 'Admin\FeesCollection\SmFeesMasterController@index')->name('fees-master')->middleware('userRolePermission:fees-master');
        Route::post('fees-master', 'Admin\FeesCollection\SmFeesMasterController@store')->name('fees-master')->middleware('userRolePermission:fees-master-store');
        Route::get('fees-master/{id}', 'Admin\FeesCollection\SmFeesMasterController@show')->name('fees-master-edit')->middleware('userRolePermission:fees-master-edit');
        Route::put('fees-master/{id}', 'Admin\FeesCollection\SmFeesMasterController@update')->name('fees-master-update')->middleware('userRolePermission:fees-master-edit');
        Route::delete('fees-master/{id}', 'Admin\FeesCollection\SmFeesMasterController@destroy')->name('fees-master-delete')->middleware('userRolePermission:fees-master-delete');

        Route::post('fees-master-single-delete', 'Admin\FeesCollection\SmFeesMasterController@deleteSingle')->name('fees-master-single-delete')->middleware('userRolePermission:fees-master-delete');
        Route::post('fees-master-group-delete', 'Admin\FeesCollection\SmFeesMasterController@deleteGroup')->name('fees-master-group-delete');
        Route::get('fees-assign/{id}', ['as' => 'fees_assign', 'uses' => 'Admin\FeesCollection\SmFeesMasterController@feesAssign']);

        Route::post('fees-assign-search', 'Admin\FeesCollection\SmFeesMasterController@feesAssignSearch')->name('fees-assign-search');

        Route::post('btn-assign-fees-group', 'Admin\FeesCollection\SmFeesMasterController@feesAssignStore');
        Route::post('unssign-all-fees-group', 'Admin\FeesCollection\SmFeesMasterController@feesUnassignAll');

        Route::get('fees-assign-datatable', 'Admin\FeesCollection\SmFeesMasterController@feesAssignDatatable')->name('fees-assign-datatable')->middleware('userRolePermission:fees-assign-datatable');

        //installment
        Route::post('fees-installment-update', 'Admin\FeesCollection\SmFeesMasterController@feesInstallmentUpdate')->name('feesInstallmentUpdate');

        // Complaint
        // Route::resource('complaint', 'SmComplaintController')->middleware('userRolePermission:21'); 
        Route::get('complaint', 'Admin\AdminSection\SmComplaintController@index')->name('complaint')->middleware('userRolePermission:complaint'); 
        Route::post('complaint', 'Admin\AdminSection\SmComplaintController@store')->name('complaint_store')->middleware('userRolePermission:complaint_store'); 
        Route::get('complaint/{id}', 'Admin\AdminSection\SmComplaintController@show')->name('complaint_show')->middleware('userRolePermission:complaint_show'); 
        Route::get('complaint/{id}/edit', 'Admin\AdminSection\SmComplaintController@edit')->name('complaint_edit')->middleware('userRolePermission:complaint_edit'); 
        Route::put('complaint/{id}', 'Admin\AdminSection\SmComplaintController@update')->name('complaint_update')->middleware('userRolePermission:complaint_edit'); 
        Route::post('delete-complaint', 'Admin\AdminSection\SmComplaintController@destroy')->name('complaint_delete')->middleware('userRolePermission:complaint_delete'); 

        Route::get('download-complaint-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/complaint/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-complaint-document')->middleware('userRolePermission:25');


        // Complaint
  
        Route::get('setup-admin', 'Admin\AdminSection\SmSetupAdminController@index')->name('setup-admin')->middleware('userRolePermission:setup-admin');
        Route::post('setup-admin', 'Admin\AdminSection\SmSetupAdminController@store')->name('setup-admin')->middleware('userRolePermission:setup-admin-store');
        Route::get('setup-admin/{id}', 'Admin\AdminSection\SmSetupAdminController@show')->name('setup-admin-edit')->middleware('userRolePermission:setup-admin-edit');
        Route::put('setup-admin/{id}', 'Admin\AdminSection\SmSetupAdminController@update')->name('setup-admin-update')->middleware('userRolePermission:setup-admin-edit');
        Route::get('setup-admin-delete/{id}', 'Admin\AdminSection\SmSetupAdminController@destroy')->name('setup-admin-delete')->middleware('userRolePermission:setup-admin-delete');


        // Postal Receive
        // Route::resource('postal-receive', 'SmPostalReceiveController');
        Route::get('postal-receive', 'Admin\AdminSection\SmPostalReceiveController@index')->name('postal-receive')->middleware('userRolePermission:postal-receive');
        Route::post('postal-receive', 'Admin\AdminSection\SmPostalReceiveController@store')->name('postal-receive')->middleware('userRolePermission:postal-receive-store');
        Route::get('postal-receive/{id}', 'Admin\AdminSection\SmPostalReceiveController@show')->name('postal-receive_edit')->middleware('userRolePermission:postal-receive-edit');
        Route::put('postal-receive/{id}', 'Admin\AdminSection\SmPostalReceiveController@update')->name('postal-receive_update')->middleware('userRolePermission:postal-receive-edit');
        Route::post('postal-receive-delete', 'Admin\AdminSection\SmPostalReceiveController@destroy')->name('postal-receive_delete')->middleware('userRolePermission:postal-receive-delete');

        Route::get('postal-receive-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/postal/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('postal-receive-document')->middleware('userRolePermission:postal-receive-document');

        
        Route::get('postal-receive-datatable', 'Admin\AdminSection\SmPostalReceiveController@postalReceiveDatatable')->name('postal-receive-datatable')->middleware('userRolePermission:postal-receive-datatable');

        // Postal Dispatch
        // Route::resource('postal-dispatch', 'SmPostalDispatchController');
        Route::get('postal-dispatch', 'Admin\AdminSection\SmPostalDispatchController@index')->name('postal-dispatch')->middleware('userRolePermission:postal-dispatch');
        Route::post('postal-dispatch', 'Admin\AdminSection\SmPostalDispatchController@store')->name('postal-dispatch')->middleware('userRolePermission:postal-dispatch-store');
        Route::get('postal-dispatch/{id}', 'Admin\AdminSection\SmPostalDispatchController@show')->name('postal-dispatch_edit')->middleware('userRolePermission:postal-dispatch_edit');
        Route::put('postal-dispatch/{id}', 'Admin\AdminSection\SmPostalDispatchController@update')->name('postal-dispatch_update')->middleware('userRolePermission:postal-dispatch_edit');
        Route::post('postal-dispatch-delete', 'Admin\AdminSection\SmPostalDispatchController@destroy')->name('postal-dispatch_delete')->middleware('userRolePermission:postal-dispatch_delete');

        Route::get('postal-dispatch-document/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/postal/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            } else {
                redirect()->back();
            }
        })->name('postal-dispatch-document')->middleware('userRolePermission:postal-dispatch-document');
        
        Route::get('postal-dispatch-datatable', 'Admin\AdminSection\SmPostalDispatchController@postalDispatchDatatable')->name('postal_dispatch_datatable')->middleware('userRolePermission:postal-dispatch-datatable');

        // Phone Call Log
        // Route::resource('phone-call', 'SmPhoneCallLogController');
        Route::get('phone-call', 'Admin\AdminSection\SmPhoneCallLogController@index')->name('phone-call')->middleware('userRolePermission:phone-call');
        Route::post('phone-call', 'Admin\AdminSection\SmPhoneCallLogController@store')->name('phone-call')->middleware('userRolePermission:phone-call-store');
        Route::get('phone-call/{id}', 'Admin\AdminSection\SmPhoneCallLogController@show')->name('phone-call_edit')->middleware('userRolePermission:phone-call_edit');
        Route::put('phone-call/{id}', 'Admin\AdminSection\SmPhoneCallLogController@update')->name('phone-call_update')->middleware('userRolePermission:phone-call_edit');
        Route::delete('phone-call-delete', 'Admin\AdminSection\SmPhoneCallLogController@destroy')->name('phone-call_delete')->middleware('userRolePermission:phone-call_delete');
        Route::get('phone-call-datatable', 'Admin\AdminSection\SmPhoneCallLogController@phoneCallDatatable')->name('phone-call-datatable')->middleware('userRolePermission:phone-call-datatable');

        // Student Certificate
        // Route::resource('student-certificate', 'SmStudentCertificateController');
        Route::get('student-certificate', 'Admin\AdminSection\SmStudentCertificateController@index')->name('student-certificate')->middleware('userRolePermission:student-certificate');
        Route::post('student-certificate', 'Admin\AdminSection\SmStudentCertificateController@store')->name('student-certificate')->middleware('userRolePermission:student-certificate-store');
        Route::get('student-certificate/{id}', 'Admin\AdminSection\SmStudentCertificateController@edit')->name('student-certificate-edit')->middleware('userRolePermission:student-certificate-edit');
        Route::put('student-certificate/{id}', 'Admin\AdminSection\SmStudentCertificateController@update')->name('student-certificate-update')->middleware('userRolePermission:student-certificate-edit');
        Route::delete('student-certificate/{id}', 'Admin\AdminSection\SmStudentCertificateController@destroy')->name('student-certificate-delete')->middleware('userRolePermission:student-certificate-delete');

        // Generate certificate
        Route::get('generate-certificate', ['as' => 'generate_certificate', 'uses' => 'Admin\AdminSection\SmStudentCertificateController@generateCertificate'])->middleware('userRolePermission:generate_certificate');
        Route::post('generate-certificate', ['as' => 'generate_certificate', 'uses' => 'Admin\AdminSection\SmStudentCertificateController@generateCertificateSearch'])->middleware('userRolePermission:generate_certificate');
        // print certificate
        Route::get('generate-certificate-print/{s_id}/{c_id}', ['as' => 'student_certificate_generate', 'uses' => 'Admin\AdminSection\SmStudentCertificateController@generateCertificateGenerate']);

        Route::get('class-routine', ['as' => 'class_routine', 'uses' => 'Admin\Academics\SmClassRoutineNewController@classRoutine'])->middleware('userRolePermission:class_routine');


        // Student Certificate
        //Route::get('certificate', 'Admin\AdminSection\SmStudentCertificateController@index')->name('certificate')->middleware('userRolePermission:49');
       //Route::get('create-certificate', 'Admin\AdminSection\SmStudentCertificateController@createCertificate')->name('create-certificate');
        //Route::post('student-certificate-store', 'Admin\AdminSection\SmStudentCertificateController@store')->name('student-certificate-store')->middleware('userRolePermission:50');
        //Route::get('student-certificate-edit/{id}', 'Admin\AdminSection\SmStudentCertificateController@edit')->name('student-certificate-edit')->middleware('userRolePermission:51');
        //Route::post('student-certificate-update', 'Admin\AdminSection\SmStudentCertificateController@update')->name('student-certificate-update')->middleware('userRolePermission:51');
        //Route::post('student-certificate-delete', 'Admin\AdminSection\SmStudentCertificateController@destroy')->name('student-certificate-delete')->middleware('userRolePermission:52');
        //Route::get('view-certificate/{id}', 'Admin\AdminSection\SmStudentCertificateController@viewCertificate')->name('view-certificate');
        

        // print certificate
        // Route::get('generate-certificate-print/{s_id}/{c_id}', ['as' => 'student_certificate_generate', 'uses' => 'Admin\AdminSection\SmStudentCertificateController@generateCertificateGenerate']);





        Route::get('class-routine-new', 'Admin\Academics\SmClassRoutineNewController@classRoutineSearch')->name('class_routine_new')->middleware('userRolePermission:add-new-class-routine-store');/* change method for class routine update ->abunayem */
        Route::post('day-wise-class-routine', 'Admin\Academics\SmClassRoutineNewController@dayWiseClassRoutine')->name('dayWise_class_routine');

        Route::get('print-teacher-routine/{teacher_id}', 'Admin\Academics\SmClassRoutineNewController@printTeacherRoutine')->name('print-teacher-routine');

          // Student ID Card
        // Route::resource('student-id-card', 'Admin\AdminSection\SmStudentIdCardController');

        Route::get('student-id-card', 'Admin\AdminSection\SmStudentIdCardController@index')->name('student-id-card')->middleware('userRolePermission:student-id-card');
        Route::get('create-id-card', 'Admin\AdminSection\SmStudentIdCardController@create_id_card')->name('create-id-card');
        Route::post('genaret-id-card-bulk', 'Admin\AdminSection\SmStudentIdCardController@generateIdCardBulk')->name('genaret-id-card-bulk');
        Route::post('store-id-card', 'Admin\AdminSection\SmStudentIdCardController@store')->name('store-id-card')->middleware('userRolePermission:create-id-card');
        Route::get('student-id-card/{id}', 'Admin\AdminSection\SmStudentIdCardController@edit')->name('student-id-card-edit')->middleware('userRolePermission:student-id-card-edit');
        Route::put('student-id-card/{id}', 'Admin\AdminSection\SmStudentIdCardController@update')->name('student-id-card-update')->middleware('userRolePermission:student-id-card-edit');
        Route::post('student-id-card', 'Admin\AdminSection\SmStudentIdCardController@destroy')->name('student-id-card-delete')->middleware('userRolePermission:student-id-card-delete');

        Route::get('generate-id-card', ['as' => 'generate_id_card', 'uses' => 'Admin\AdminSection\SmStudentIdCardController@generateIdCard'])->middleware('userRolePermission:generate_id_card');
        Route::post('generate-id-card-search', ['as' => 'generate_id_card_search', 'uses' => 'Admin\AdminSection\SmStudentIdCardController@generateIdCardBulk']);


        // Route::post('generate-id-card-search', ['as' => 'generate_id_card_search', 'uses' => 'Admin\AdminSection\SmStudentIdCardController@generateIdCardSearch']);
        Route::get('generate-id-card-search', ['as' => 'generate_id_card_search', 'uses' => 'Admin\AdminSection\SmStudentIdCardController@generateIdCard']);
        Route::get('generate-id-card-print/{s_id}/{c_id}', 'Admin\AdminSection\SmStudentIdCardController@generateIdCardPrint');



        // Student Module /Student Admission
        Route::get('student-admission', ['as' => 'student_admission', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@index'])->middleware('userRolePermission:student_admission');
        Route::get('student-admission-check/{id}', ['as' => 'student_admission_check', 'uses' => 'SmStudentAdmissionController@admissionCheck']);
        Route::get('student-admission-update-check/{val}/{id}', ['as' => 'student_admission_check_update', 'uses' => 'SmStudentAdmissionController@admissionCheckUpdate']);
        Route::post('student-admission-pic', ['as' => 'student_admission_pic', 'uses' => 'SmStudentAdmissionController@admissionPic']);

        // Ajax get vehicle
        Route::get('/academic-year-get-class', 'SmStudentAdmissionController@academicYearGetClass');

        // Ajax get vehicle


        // Ajax Section
        Route::get('/ajaxVehicleInfo', 'Admin\StudentInfo\SmStudentAjaxController@ajaxVehicleInfo');

        // Ajax Roll No
        Route::get('/ajax-get-roll-id', 'Admin\StudentInfo\SmStudentAjaxController@ajaxGetRollId');

        // Ajax Roll exist check
        Route::get('/ajax-get-roll-id-check', 'Admin\StudentInfo\SmStudentAjaxController@ajaxGetRollIdCheck');

        // Ajax Section
        Route::get('/ajaxSectionStudent', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSectionStudent');

         // Ajax Subject
         Route::get('/ajaxSubjectFromClass', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSubjectClass');

         Route::get('/ajaxSubjectFromExamType', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSubjectFromExamType');

        // Ajax room details


        //ajax id card type
        
         Route::get('/ajaxIdCard', 'Admin\AdminSection\SmStudentIdCardController@ajaxIdCard');

        //student store
        Route::post('student-store', ['as' => 'student_store', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@store'])->middleware('userRolePermission:student_store');

        //Student details document

        Route::get('delete-document/{id}', ['as' => 'delete_document', 'uses' => 'SmStudentAdmissionController@deleteDocument'])->middleware('userRolePermission:delete_document');
        Route::post('upload-document', ['as' => 'upload_document', 'uses' => 'SmStudentAdmissionController@uploadDocument']);



        Route::get('download-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/student/document/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-document');





        // Student timeline upload
        Route::post('student-timeline-store', ['as' => 'student_timeline_store', 'uses' => 'SmStudentAdmissionController@studentTimelineStore']);
        //parent
        Route::get('parent-download-timeline-doc/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('parent-download-timeline-doc');

        Route::get('delete-timeline/{id}', ['as' => 'delete_timeline', 'uses' => 'SmStudentAdmissionController@deleteTimeline']);


        //student import
        Route::get('import-student', ['as' => 'import_student', 'uses' => 'SmStudentAdmissionController@importStudent'])->middleware('userRolePermission:import_student');
        Route::get('download_student_file', ['as' => 'download_student_file', 'uses' => 'SmStudentAdmissionController@downloadStudentFile']);
        Route::post('student-bulk-store', ['as' => 'student_bulk_store', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@studentBulkStore']);

        //Ajax Sibling section
        Route::get('ajaxSectionSibling', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSectionSibling');

        //Ajax Sibling info
        Route::get('ajaxSiblingInfo', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSiblingInfo');

        //Ajax Sibling info detail
        Route::get('ajaxSiblingInfoDetail', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSiblingInfoDetail');


        //Datatables
        Route::get('student-list-datatable', ['as' => 'student_list_datatable', 'uses' => 'DatatableQueryController@studentDetailsDatatable'])->middleware('userRolePermission:student_list');
       
        
        // student list
        Route::get('student-list', ['as' => 'student_list', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@studentDetails'])->middleware('userRolePermission:student_list');
        Route::get('student-settings', ['as' => 'student_settings', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@settings'])->middleware('userRolePermission:student_settings');
        Route::post('student/field/switch',['as' => 'student_switch', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@statusUpdate']);
        Route::post('student/field/show',['as' => 'student_show', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@studentFieldShow']);
        // student search

        Route::post('student-list-search', 'DatatableQueryController@studentDetailsDatatable')->name('student-list-search');
        Route::post('ajax-student-list-search', 'DatatableQueryController@searchStudentList')->name('ajax-student-list-search');

        Route::get('student-list-search', 'SmStudentAdmissionController@studentDetails');

        // student list

        Route::get('student-view/{id}/{type?}', ['as' => 'student_view', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@view']);

        // student delete
        Route::post('student-delete', 'SmStudentAdmissionController@studentDelete')->name('student-delete')->middleware('userRolePermission:67');


        // student edit
        Route::get('student-edit/{id}', ['as' => 'student_edit', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@edit'])->middleware('userRolePermission:student_edit');
        // Student Update
        Route::post('student-update', ['as' => 'student_update', 'uses' => 'Admin\StudentInfo\SmStudentAdmissionController@update']);
        // Route::post('student-update-pic/{id}', ['as' => 'student_update_pic', 'uses' => 'SmStudentAdmissionController@studentUpdatePic']);

        // Student Promote search
        // Route::get('student-promote', ['as' => 'student_promote', 'uses' => 'SmStudentAdmissionController@studentPromote'])->middleware('userRolePermission:81');

        // Route::get('student-current-search', 'SmStudentAdmissionController@studentPromote');
        // Route::post('student-current-search', 'SmStudentAdmissionController@studentCurrentSearch')->name('student-current-search');

        // Route::get('student-current-search-custom', 'SmStudentAdmissionController@studentPromoteCustom');
        // Route::post('student-current-search-custom', 'SmStudentAdmissionController@studentCurrentSearchCustom')->name('student-current-search-custom');

        Route::get('view-academic-performance/{id}', 'SmStudentAdmissionController@view_academic_performance');


        // // Student Promote Store
        // Route::get('student-promote-store', 'SmStudentAdmissionController@studentPromote');
        // Route::post('student-proadminmote-store', 'SmStudentAdmissionController@studentPromoteStore')->name('student-promote-store')->middleware('userRolePermission:82');

        Route::get('student-promote', ['as' => 'student_promote', 'uses' => 'SmStudentPromoteController@index'])->middleware('userRolePermission:student_promote');
        Route::get('student-current-search', 'SmStudentPromoteController@studentCurrentSearch')->name('student-current-search');
        Route::post('student-current-search', 'SmStudentPromoteController@studentCurrentSearch');
        Route::get('ajaxStudentRollCheck', 'SmStudentPromoteController@rollCheck');
        Route::post('student-promote-store', 'SmStudentPromoteController@promote')->name('student-promote-store')->middleware('userRolePermission:student-promote-store');
        Route::get('student-current-search-with-exam', 'SmStudentPromoteController@studentSearchWithExam')->name('student-current-search-with-exam');


        // // Student Promote Store Custom
        Route::get('student-promote-store-custom', 'SmStudentAdmissionController@studentPromoteCustom');
        Route::post('student-promote-store-custom', 'SmStudentAdmissionController@studentPromoteCustomStore')->name('student-promote-store-custom')->middleware('userRolePermission:student-promote-store');

        // Student Export
        Route::get('all-student-export','SmStudentAdmissionController@allStudentExport')->name('all-student-export')->middleware('userRolePermission:all-student-export');
        Route::get('all-student-export-excel','SmStudentAdmissionController@allStudentExportExcel')->name('all-student-export-excel')->middleware('userRolePermission:all-student-export-excel');
        Route::get('all-student-export-pdf','SmStudentAdmissionController@allStudentExportPdf')->name('all-student-export-pdf')->middleware('userRolePermission:all-student-export-pdf');


        //Ajax Student Promote Section
        Route::get('ajaxStudentPromoteSection', 'Admin\StudentInfo\SmStudentAjaxController@ajaxStudentPromoteSection');
        Route::get('ajaxSubjectSection', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSubjectSection');
        Route::get('ajax-get-class', 'Admin\StudentInfo\SmStudentAjaxController@ajaxGetClass');
        Route::get('SearchMultipleSection', 'SmStudentAdmissionController@SearchMultipleSection');
        //Ajax Student Select
        Route::get('ajaxSelectStudent', 'Admin\StudentInfo\SmStudentAjaxController@ajaxSelectStudent');

        Route::get('promote-year/{id?}', 'Admin\StudentInfo\SmStudentAjaxController@ajaxPromoteYear');

        // Student Attendance
        Route::get('student-attendance', ['as' => 'student_attendance', 'uses' => 'Admin\StudentInfo\SmStudentAttendanceController@index'])->middleware('userRolePermission:student_attendance');
        Route::post('student-search', 'Admin\StudentInfo\SmStudentAttendanceController@studentSearch')->name('student-search');
        Route::any('ajax-student-attendance-search/{class_id}/{section}/{date}', 'DatatableQueryController@AjaxStudentSearch');
        Route::get('student-search', 'Admin\StudentInfo\SmStudentAttendanceController@index');

        Route::post('student-attendance-store', 'Admin\StudentInfo\SmStudentAttendanceController@studentAttendanceStore')->name('student-attendance-store')->middleware('userRolePermission:student-attendance-store');
        Route::post('student-attendance-holiday', 'Admin\StudentInfo\SmStudentAttendanceController@studentAttendanceHoliday')->name('student-attendance-holiday');


        Route::get('student-attendance-import', 'Admin\StudentInfo\SmStudentAttendanceController@studentAttendanceImport')->name('student-attendance-import');
        Route::get('download-student-attendance-file', 'Admin\StudentInfo\SmStudentAttendanceController@downloadStudentAtendanceFile');
        Route::post('student-attendance-bulk-store', 'Admin\StudentInfo\SmStudentAttendanceController@studentAttendanceBulkStore')->name('student-attendance-bulk-store');

        //Student Report
        Route::get('student-report', ['as' => 'student_report', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentReport'])->middleware('userRolePermission:student_report');
        Route::post('student-report', ['as' => 'student_report', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentReportSearch']);


        //guardian report
        Route::get('guardian-report', ['as' => 'guardian_report', 'uses' => 'Admin\StudentInfo\SmStudentReportController@guardianReport'])->middleware('userRolePermission:guardian_report');
        Route::post('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@guardianReportSearch']);
        Route::get('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@guardianReport']);

        Route::get('student-history', ['as' => 'student_history', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentHistory'])->middleware('userRolePermission:student_history');
        Route::post('student-history-search', ['as' => 'student_history_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentHistorySearch']);
        Route::get('student-history-search', ['as' => 'student_history_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentHistory']);


        // student login report
        Route::get('student-login-report', ['as' => 'student_login_report', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentLoginReport'])->middleware('userRolePermission:student_login_report');
        Route::post('student-login-search', ['as' => 'student_login_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentLoginSearch']);
        Route::get('student-login-search', ['as' => 'student_login_search', 'uses' => 'Admin\StudentInfo\SmStudentReportController@studentLoginReport']);

        // student & parent reset password
        Route::post('reset-student-password', 'Admin\RolePermission\SmResetPasswordController@resetStudentPassword')->name('reset-student-password');

  
        // Disabled Student
        Route::get('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmStudentAdmissionController@disabledStudent'])->middleware('userRolePermission:disabled_student');

        Route::post('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmStudentAdmissionController@disabledStudentSearch']);
        Route::post('disabled-student-delete', ['as' => 'disable_student_delete', 'uses' => 'SmStudentAdmissionController@disabledStudentDelete'])->middleware('userRolePermission:disable_student_delete');
        Route::post('enable-student', ['as' => 'enable_student', 'uses' => 'SmStudentAdmissionController@enableStudent'])->middleware('userRolePermission:enable_student');


        Route::get('student-report-search', 'SmStudentAdmissionController@studentReport');

        Route::get('language-list', 'Admin\SystemSettings\LanguageController@index')->name('language-list')->middleware('userRolePermission:language-list');
        Route::get('language-list/{id}', 'Admin\SystemSettings\LanguageController@show')->name('language_edit')->middleware('userRolePermission:language_edit');
        Route::post('language-list/update', 'Admin\SystemSettings\LanguageController@update')->name('language_update')->middleware('userRolePermission:language_edit');
        Route::post('language-list/store', 'Admin\SystemSettings\LanguageController@store')->name('language_store')->middleware('userRolePermission:language_store');
        Route::get('language-delete/{id}', 'Admin\SystemSettings\LanguageController@destroy')->name('language_delete')->middleware('userRolePermission:language_delete');


        // Tabulation Sheet Report
        Route::get('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'Admin\Report\SmReportController@tabulationSheetReport'])->middleware('userRolePermission:tabulation_sheet_report');
        Route::post('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'Admin\Report\SmReportController@tabulationSheetReportSearch']);
        Route::post('tabulation-sheet/print', 'Admin\Report\SmReportController@tabulationSheetReportPrint')->name('tabulation-sheet/print');

        Route::get('optional-subject-setup/delete/{id}', 'Admin\SystemSettings\SmOptionalSubjectAssignController@optionalSetupDelete')->name('delete_optional_subject')->middleware('userRolePermission:delete_optional_subject');
        Route::get('optional-subject-setup/edit/{id}', 'Admin\SystemSettings\SmOptionalSubjectAssignController@optionalSetupEdit')->name('class_optional_edit')->middleware('userRolePermission:class_optional_edit');
        Route::get('optional-subject-setup', 'Admin\SystemSettings\SmOptionalSubjectAssignController@optionalSetup')->name('class_optional')->middleware('userRolePermission:class_optional');
        Route::post('optional-subject-setup', 'Admin\SystemSettings\SmOptionalSubjectAssignController@optionalSetupStore')->name('optional_subject_setup_post')->middleware('userRolePermission:optional_subject_setup_post');

        // progress card report
        Route::get('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'Admin\Report\SmReportController@progressCardReport'])->middleware('userRolePermission:progress_card_report');
        Route::post('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'Admin\Report\SmReportController@progressCardReportSearch']);
        
        Route::get('custom-progress-card-report-percent', ['as' => 'custom_progress_card_report_percent', 'uses' => 'Admin\Report\SmReportController@customProgressCardReport']);


        Route::post('progress-card/print', 'Admin\Report\SmReportController@progressCardPrint')->name('progress-card/print');


        // staff directory
        Route::get('staff-directory', ['as' => 'staff_directory', 'uses' => 'Admin\Hr\SmStaffController@staffList'])->middleware('userRolePermission:staff_directory');
        Route::get('staff-directory-ajax', ['as' => 'staff_directory_ajax', 'uses' => 'DatatableQueryController@getStaffList'])->middleware('userRolePermission:staff_directory');


        Route::post('search-staff', ['as' => 'searchStaff', 'uses' => 'Admin\Hr\SmStaffController@searchStaff']);
        Route::post('search-staff-ajax', ['as' => 'AjaxSearchStaff', 'uses' => 'DatatableQueryController@getStaffList']);

        Route::get('add-staff', ['as' => 'addStaff', 'uses' => 'Admin\Hr\SmStaffController@addStaff'])->middleware('userRolePermission:addStaff');
        Route::post('staff-store', ['as' => 'staffStore', 'uses' => 'Admin\Hr\SmStaffController@staffStore']);
        Route::post('staff-pic-store', ['as' => 'staffPicStore', 'uses' => 'Admin\Hr\SmStaffController@staffPicStore']);


        Route::get('edit-staff/{id}', ['as' => 'editStaff', 'uses' => 'Admin\Hr\SmStaffController@editStaff']);
        Route::post('update-staff', ['as' => 'staffUpdate', 'uses' => 'Admin\Hr\SmStaffController@staffUpdate']);
        Route::post('staff-profile-update/{id}', ['as' => 'staffProfileUpdate', 'uses' => 'Admin\Hr\SmStaffController@staffProfileUpdate']);

        // Route::get('staff-roles', ['as' => 'viewStaff', 'uses' => 'Admin\Hr\SmStaffController@staffRoles']);
        Route::get('view-staff/{id}', ['as' => 'viewStaff', 'uses' => 'Admin\Hr\SmStaffController@viewStaff']);
        Route::get('delete-staff-view/{id}', ['as' => 'deleteStaffView', 'uses' => 'Admin\Hr\SmStaffController@deleteStaffView']);

        Route::get('deleteStaff/{id}', 'Admin\Hr\SmStaffController@deleteStaff')->name('deleteStaff')->middleware('userRolePermission:deleteStaff');
        Route::post('delete-staff', 'Admin\Hr\SmStaffController@delete_staff')->name('delete_staff');
        Route::get('staff-settings', 'Admin\Hr\SmStaffController@settings')->name('staff_settings')->middleware('userRolePermission:staff_settings');
        Route::post('staff/field/switch',['as' => 'staff_switch', 'uses' => 'Admin\Hr\SmStaffController@statusUpdate']);
        Route::post('teacher/field_view',['as' => 'teacher_field_view', 'uses' => 'Admin\Hr\SmStaffController@teacherFieldView']);
        Route::get('staff-disable-enable', 'Admin\Hr\SmStaffController@staffDisableEnable')->name('staff-disable-enable');

        Route::get('upload-staff-documents/{id}', 'Admin\Hr\SmStaffController@uploadStaffDocuments');
        Route::post('save_upload_document', 'Admin\Hr\SmStaffController@saveUploadDocument')->name('save_upload_document');
        Route::get('download-staff-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff/document/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-document');

        Route::get('download-staff-joining-letter/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff_joining_letter/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-joining-letter');

        Route::get('download-resume/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/resume/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-resume');

        Route::get('download-other-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/others_documents/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-other-document');

        Route::get('download-staff-timeline-doc/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-timeline-doc');

        Route::get('delete-staff-document-view/{id}', 'Admin\Hr\SmStaffController@deleteStaffDocumentView')->name('delete-staff-document-view');
        Route::get('delete-staff-document/{id}', 'Admin\Hr\SmStaffController@deleteStaffDocument')->name('delete-staff-document');

        // staff timeline
        Route::get('add-staff-timeline/{id}', 'Admin\Hr\SmStaffController@addStaffTimeline');
        Route::post('staff_timeline_store', 'Admin\Hr\SmStaffController@storeStaffTimeline')->name('staff_timeline_store');
        Route::get('delete-staff-timeline-view/{id}', 'Admin\Hr\SmStaffController@deleteStaffTimelineView')->name('delete-staff-timeline-view');
        Route::get('delete-staff-timeline/{id}', 'Admin\Hr\SmStaffController@deleteStaffTimeline')->name('delete-staff-timeline');


        //Staff Attendance
        Route::get('staff-attendance', ['as' => 'staff_attendance', 'uses' => 'Admin\Hr\SmStaffAttendanceController@staffAttendance'])->middleware('userRolePermission:staff_attendance');
        Route::post('staff-attendance', 'Admin\Hr\SmStaffAttendanceController@staffAttendanceSearch')->name('staff-attendance-search');
        Route::post('staff-attendance-store', 'Admin\Hr\SmStaffAttendanceController@staffAttendanceStore')->name('staff-attendance-store')->middleware('userRolePermission:staff-attendance-store');
        Route::post('staff-holiday-store', 'Admin\Hr\SmStaffAttendanceController@staffHolidayStore')->name('staff-holiday-store')->middleware('userRolePermission:staff-holiday-store');

        Route::get('staff-attendance-report', ['as' => 'staff_attendance_report', 'uses' => 'Admin\Hr\SmStaffAttendanceController@staffAttendanceReport'])->middleware('userRolePermission:staff_attendance_report');
        Route::post('staff-attendance-report', ['as' => 'staff_attendance_report_search', 'uses' => 'Admin\Hr\SmStaffAttendanceController@staffAttendanceReportSearch']);

        Route::get('staff-attendance/print/{role_id}/{month}/{year}/', 'Admin\Hr\SmStaffAttendanceController@staffAttendancePrint')->name('staff-attendance/print');


        // Biometric attendance
        Route::post('attendance', 'Admin\Hr\SmStaffAttendanceController@attendanceData')->name('attendanceData');



        Route::get('staff-attendance-import', 'Admin\Hr\SmStaffAttendanceController@staffAttendanceImport')->name('staff-attendance-import');
        Route::get('download-staff-attendance-file', 'Admin\Hr\SmStaffAttendanceController@downloadStaffAttendanceFile');
        Route::post('staff-attendance-bulk-store', 'Admin\Hr\SmStaffAttendanceController@staffAttendanceBulkStore')->name('staff-attendance-bulk-store');

        //payroll
        Route::get('payroll', ['as' => 'payroll', 'uses' => 'Admin\Hr\SmPayrollController@index'])->middleware('userRolePermission:payroll');

        // Route::post('payroll', ['as' => 'payroll', 'uses' => 'Admin\Hr\SmPayrollController@searchStaffPayr'])->middleware('userRolePermission:payroll');

        Route::get('generate-Payroll/{id}/{month}/{year}', 'Admin\Hr\SmPayrollController@generatePayroll')->name('generate-Payroll')->middleware('userRolePermission:generate-Payroll');
        Route::post('save-payroll-data', ['as' => 'savePayrollData', 'uses' => 'Admin\Hr\SmPayrollController@savePayrollData'])->middleware('userRolePermission:savePayrollData');

        Route::get('pay-payroll/{id}/{role_id}', 'Admin\Hr\SmPayrollController@paymentPayroll')->name('pay-payroll')->middleware('userRolePermission:pay-payroll');
        Route::post('savePayrollPaymentData', ['as' => 'savePayrollPaymentData', 'uses' => 'Admin\Hr\SmPayrollController@savePayrollPaymentData']);
        Route::get('view-payslip/{id}', 'Admin\Hr\SmPayrollController@viewPayslip')->name('view-payslip')->middleware('userRolePermission:view-payslip');
        Route::get('print-payslip/{id}', 'Admin\Hr\SmPayrollController@printPayslip')->name('print-payslip');
        Route::get('view-payroll-payment/{id}', 'Admin\Hr\SmPayrollController@viewPayrollPayment')->name('view-payroll-payment');
        Route::post('delete-payroll-payment', 'Admin\Hr\SmPayrollController@deletePayrollPayment')->name('delete-payroll-payment');
        Route::get('print-payroll-payment/{id}', 'Admin\Hr\SmPayrollController@printPayrollPayment')->name('print-payroll-payment');

        //payroll Report
        Route::get('payroll-report', 'Admin\Hr\SmPayrollController@payrollReport')->name('payroll-report')->middleware('userRolePermission:payroll-report');
        // Route::post('search-payroll-report', ['as' => 'searchPayrollReport', 'uses' => 'Admin\Hr\SmPayrollController@searchPayrollReport']);
        Route::post('payroll-report', 'Admin\Hr\SmPayrollController@searchPayrollReport')->name('searchPayrollReport'); 

        //Homework
        Route::get('homework-list', ['as' => 'homework-list', 'uses' => 'Admin\Homework\SmHomeworkController@homeworkList'])->middleware('userRolePermission:homework-list');

        Route::post('homework-list', ['as' => 'homework-list', 'uses' => 'Admin\Homework\SmHomeworkController@searchHomework'])->middleware('userRolePermission:homework-list');
        Route::get('homework-edit/{id}', ['as' => 'homework_edit', 'uses' => 'Admin\Homework\SmHomeworkController@homeworkEdit'])->middleware('userRolePermission:homework_edit');
        Route::post('homework-update', ['as' => 'homework_update', 'uses' => 'Admin\Homework\SmHomeworkController@homeworkUpdate'])->middleware('userRolePermission:homework_edit');
        Route::get('homework-delete/{id}', ['as' => 'homework_delete', 'uses' => 'Admin\Homework\SmHomeworkController@homeworkDelete'])->middleware('userRolePermission:homework_delete');

        Route::post('homework-delete', ['as' => 'homework-delete', 'uses' => 'Admin\Homework\SmHomeworkController@deleteHomework'])->middleware('userRolePermission:homework_delete');
        Route::get('add-homeworks', ['as' => 'add-homeworks', 'uses' => 'Admin\Homework\SmHomeworkController@addHomework'])->middleware('userRolePermission:add-homeworks');
        Route::post('save-homework-data', ['as' => 'saveHomeworkData', 'uses' => 'Admin\Homework\SmHomeworkController@saveHomeworkData'])->middleware('userRolePermission:saveHomeworkData');
        Route::get('download-uploaded-content-admin/{id}/{student_id}', 'Admin\Homework\SmHomeworkController@downloadHomeworkData')->name('download-uploaded-content-admin');
        //Route::get('evaluation-homework/{class_id}/{section_id}', 'Admin\Homework\SmHomeworkController@evaluationHomework');
        Route::get('evaluation-homework/{class_id}/{section_id}/{homework_id}', 'Admin\Homework\SmHomeworkController@evaluationHomework')->name('evaluation-homework')->middleware('userRolePermission:evaluation-homework');
        Route::get('university/evaluation-homework/{sem_label_id}/{homework_id}', 'Admin\Homework\SmHomeworkController@unEvaluationHomework')->name('university.unevaluation-homework')->middleware('userRolePermission:evaluation-homework');
        Route::post('save-homework-evaluation-data', ['as' => 'save-homework-evaluation-data', 'uses' => 'Admin\Homework\SmHomeworkController@saveHomeworkEvaluationData']);
        Route::get('evaluation-report', 'Admin\Homework\SmHomeworkController@EvaluationReport')->name('evaluation-report')->middleware('userRolePermission:evaluation-report');
        Route::get('evaluation-document-download/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/homework/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('evaluation-document-download');

        Route::post('evaluation-report', ['as' => 'search-evaluation', 'uses' => 'Admin\Homework\SmHomeworkController@searchEvaluation']);
        // Route::get('search-evaluation', 'Admin\Homework\SmHomeworkController@EvaluationReport');
        Route::get('view-evaluation-report/{homework_id}', 'Admin\Homework\SmHomeworkController@viewEvaluationReport')->name('view-evaluation-report')->middleware('userRolePermission:view-evaluation-report');

        //Study Material
        Route::get('upload-content', 'Admin\StudyMaterial\SmUploadContentController@index')->name('upload-content')->middleware('userRolePermission:upload-content');
        Route::post('save-upload-content', 'Admin\StudyMaterial\SmUploadContentController@store')->name('save-upload-content')->middleware('userRolePermission:save-upload-content');
        
        //
        Route::get('upload-content-edit/{id}', 'Admin\StudyMaterial\SmUploadContentController@uploadContentEdit')->name('upload-content-edit')->middleware('userRolePermission:upload-content-edit');
        Route::get('upload-content-view/{id}', 'Admin\StudyMaterial\SmUploadContentController@uploadContentView')->name('upload-content-view');
        //
        Route::post('update-upload-content', 'Admin\StudyMaterial\SmUploadContentController@updateUploadContent')->name('update-upload-content');
        Route::post('delete-upload-content', 'Admin\StudyMaterial\SmUploadContentController@deleteUploadContent')->name('delete-upload-content')->middleware('userRolePermission:95');

        Route::get('download-content-document/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/upload_contents/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-content-document');

        Route::get('assignment-list', 'Admin\StudyMaterial\SmUploadContentController@assignmentList')->name('assignment-list')->middleware('userRolePermission:assignment-list');
        Route::get('study-metarial-list', 'Admin\StudyMaterial\SmUploadContentController@studyMetarialList')->name('study-metarial-list');
        Route::get('syllabus-list', 'Admin\StudyMaterial\SmUploadContentController@syllabusList')->name('syllabus-list')->middleware('userRolePermission:syllabus-list');
        Route::get('other-download-list', 'Admin\StudyMaterial\SmUploadContentController@otherDownloadList')->name('other-download-list')->middleware('userRolePermission:other-download-list');

        Route::get('assignment-list-ajax', 'DatatableQueryController@assignmentList')->name('assignment-list-ajax')->middleware('userRolePermission:assignment-list');
        Route::get('syllabus-list-ajax', 'DatatableQueryController@syllabusList')->name('syllabus-list-ajax')->middleware('userRolePermission:syllabus-list');
        // Communicate
        Route::get('notice-list', 'Admin\Communicate\SmNoticeController@noticeList')->name('notice-list')->middleware('userRolePermission:notice-list');
        Route::get('administrator-notice', 'Admin\Communicate\SmNoticeController@administratorNotice')->name('administrator-notice');
        Route::get('add-notice', 'Admin\Communicate\SmNoticeController@sendMessage')->name('add-notice');
        Route::post('save-notice-data', 'Admin\Communicate\SmNoticeController@saveNoticeData')->name('save-notice-data');
        Route::get('edit-notice/{id}', 'Admin\Communicate\SmNoticeController@editNotice')->name('edit-notice');
        Route::post('update-notice-data', 'Admin\Communicate\SmNoticeController@updateNoticeData')->name('update-notice-data');
        Route::get('delete-notice-view/{id}', 'Admin\Communicate\SmNoticeController@deleteNoticeView')->name('delete-notice-view')->middleware('userRolePermission:delete-notice-view');
        Route::get('send-email-sms-view', 'Admin\Communicate\SmCommunicateController@sendEmailSmsView')->name('send-email-sms-view')->middleware('userRolePermission:send-email-sms-view');
        Route::post('send-email-sms', 'Admin\Communicate\SmCommunicateController@sendEmailSms')->name('send-email-sms')->middleware('userRolePermission:send-email-sms');
        Route::get('email-sms-log', 'Admin\Communicate\SmCommunicateController@emailSmsLog')->name('email-sms-log')->middleware('userRolePermission:email-sms-log');
        Route::get('delete-notice/{id}', 'Admin\Communicate\SmNoticeController@deleteNotice')->name('delete-notice');

        Route::get('studStaffByRole', 'Admin\Communicate\SmCommunicateController@studStaffByRole');

        Route::get('email-sms-log-ajax', 'DatatableQueryController@emailSmsLogAjax')->name('emailSmsLogAjax')->middleware('userRolePermission:email-sms-log');


        //Event
        // Route::resource('event', 'Admin\Communicate\SmEventController');
        Route::get('event', 'Admin\Communicate\SmEventController@index')->name('event')->middleware('userRolePermission:event');
        Route::post('event', 'Admin\Communicate\SmEventController@store')->name('event')->middleware('userRolePermission:event-store');
        Route::get('event/{id}', 'Admin\Communicate\SmEventController@edit')->name('event-edit')->middleware('userRolePermission:event-edit');
        Route::put('event/{id}', 'Admin\Communicate\SmEventController@update')->name('event-update')->middleware('userRolePermission:event-edit');
        Route::get('delete-event-view/{id}', 'Admin\Communicate\SmEventController@deleteEventView')->name('delete-event-view')->middleware('userRolePermission:delete-event-view');
        Route::get('delete-event/{id}', 'Admin\Communicate\SmEventController@deleteEvent')->name('delete-event')->middleware('userRolePermission:delete-event-view');
        Route::get('download-event-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/events/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-event-document');

        //Holiday
        // Route::resource('holiday', 'Admin\SystemSettings\SmHolidayController');
        Route::get('holiday', 'Admin\SystemSettings\SmHolidayController@index')->name('holiday')->middleware('userRolePermission:holiday');
        Route::post('holiday', 'Admin\SystemSettings\SmHolidayController@store')->name('holiday-store')->middleware('userRolePermission:holiday-store');
        Route::get('holiday/{id}/edit', 'Admin\SystemSettings\SmHolidayController@edit')->name('holiday-edit')->middleware('userRolePermission:holiday-edit');
        Route::put('holiday/{id}', 'Admin\SystemSettings\SmHolidayController@update')->name('holiday-update')->middleware('userRolePermission:holiday-edit');
        Route::get('delete-holiday-data-view/{id}', 'Admin\SystemSettings\SmHolidayController@deleteHolidayView')->name('delete-holiday-data-view')->middleware('userRolePermission:delete-holiday-data-view');
        Route::get('delete-holiday-data/{id}', 'Admin\SystemSettings\SmHolidayController@deleteHoliday')->name('delete-holiday-data')->middleware('userRolePermission:delete-holiday-data');

        //Notification Settings
        Route::get('notification_settings', 'Admin\SystemSettings\SmNotificationController@index')->name('notification_settings')->middleware('userRolePermission:notification_settings');
        Route::get('notification_event_modal/{id}/{key}', 'Admin\SystemSettings\SmNotificationController@notificationEventModal')->name('notification_event_modal');
        Route::post('notification-settings-update', 'Admin\SystemSettings\SmNotificationController@notificationSettingsUpdate')->name('notification_settings_update')->middleware('userRolePermission:notification_settings_update');

        // Route::resource('weekend', 'Admin\SystemSettings\SmWeekendController');
        Route::get('weekend', 'Admin\SystemSettings\SmWeekendController@index')->name('weekend')->middleware('userRolePermission:weekend');
        Route::post('weekend/switch', 'Admin\SystemSettings\SmWeekendController@store')
        ->name('weekend.store')->middleware('userRolePermission:weekend.store');
        Route::get('weekend/{id}', 'Admin\SystemSettings\SmWeekendController@edit')->name('weekend-edit');
        Route::put('weekend/{id}', 'Admin\SystemSettings\SmWeekendController@update')->name('weekend-update');

        //Book Category
        // Route::resource('book-category-list', 'Admin\Library\SmBookCategoryController');
        Route::get('book-category-list', 'Admin\Library\SmBookCategoryController@index')->name('book-category-list')->middleware('userRolePermission:book-category-list');
        Route::post('book-category-list', 'Admin\Library\SmBookCategoryController@store')->name('book-category-list')->middleware('userRolePermission:book-category-list-store');
        Route::get('book-category-list/{id}', 'Admin\Library\SmBookCategoryController@edit')->name('book-category-list-edit')->middleware('userRolePermission:book-category-list-edit');
        Route::put('book-category-list/{id}', 'Admin\Library\SmBookCategoryController@update')->name('book-category-list-update')->middleware('userRolePermission:book-category-list-edit');
        Route::delete('book-category-list/{id}', 'Admin\Library\SmBookCategoryController@destroy')->name('book-category-list-delete')->middleware('userRolePermission:book-category-list-delete');
        
        Route::get('delete-book-category-view/{id}', 'Admin\Library\SmBookCategoryController@deleteBookCategoryView');
        Route::get('delete-book-category/{id}', 'Admin\Library\SmBookCategoryController@deleteBookCategory')->name('delete-book-category');

        // Book
        Route::get('book-list', 'Admin\Library\SmBookController@index')->name('book-list')->middleware('userRolePermission:book-list');
        Route::get('add-book', 'Admin\Library\SmBookController@addBook')->name('add-book')->middleware('userRolePermission:add-book');
        Route::post('save-book-data', 'Admin\Library\SmBookController@saveBookData')->name('save-book-data')->middleware('userRolePermission:save-book-data');
        Route::get('edit-book/{id}', 'Admin\Library\SmBookController@editBook')->name('edit-book');
        Route::post('update-book-data/{id}', 'Admin\Library\SmBookController@updateBookData')->name('update-book-data');
        Route::get('delete-book-view/{id}', 'Admin\Library\SmBookController@deleteBookView')->name('delete-book-view')->middleware('userRolePermission:delete-book-view');
        Route::get('delete-book/{id}', 'Admin\Library\SmBookController@deleteBook');
        Route::get('member-list', 'Admin\Library\SmBookController@memberList')->name('member-list')->middleware('userRolePermission:member-list');
        Route::get('issue-books/{member_type}/{id}', 'Admin\Library\SmBookController@issueBooks')->name('issue-books');
        Route::post('save-issue-book-data', 'Admin\Library\SmBookController@saveIssueBookData')->name('save-issue-book-data');
        Route::get('return-book-view/{id}', 'Admin\Library\SmBookController@returnBookView')->name('return-book-view')->middleware('userRolePermission:return-book-view');
        Route::get('return-book/{id}', 'Admin\Library\SmBookController@returnBook')->name('return-book');
        Route::get('all-issed-book', 'Admin\Library\SmBookController@allIssuedBook')->name('all-issed-book')->middleware('userRolePermission:all-issed-book');
        Route::post('all-issed-book', 'Admin\Library\SmBookController@searchIssuedBook')->name('search-issued-book');
        Route::get('search-issued-book', 'p@allIssuedBook');


          // Library Subject routes
          Route::get('library-subject', ['as' => 'library_subject', 'uses' => 'Admin\Library\SmBookController@subjectList'])->middleware('userRolePermission:library_subject');
          Route::post('library-subject-store', ['as' => 'library_subject_store', 'uses' => 'Admin\Library\SmBookController@store'])->middleware('userRolePermission:library_subject_store');
          Route::get('library-subject-edit/{id}', ['as' => 'library_subject_edit', 'uses' => 'Admin\Library\SmBookController@edit'])->middleware('userRolePermission:library_subject_edit');
          Route::post('library-subject-update', ['as' => 'library_subject_update', 'uses' => 'Admin\Library\SmBookController@update'])->middleware('userRolePermission:library_subject_edit');
          Route::get('library-subject-delete/{id}', ['as' => 'library_subject_delete', 'uses' => 'Admin\Library\SmBookController@delete'])->middleware('userRolePermission:library_subject_delete');
        //library member
        // Route::resource('library-member', 'Admin\Library\SmLibraryMemberController');
        Route::get('library-member', 'Admin\Library\SmLibraryMemberController@index')->name('library-member')->middleware('userRolePermission:library-member');
        Route::post('library-member', 'Admin\Library\SmLibraryMemberController@store')->name('library-member')->middleware('userRolePermission:library-member-store');

        Route::get('cancel-membership/{id}', 'Admin\Library\SmLibraryMemberController@cancelMembership')->name('cancel-membership')->middleware('userRolePermission:cancel-membership');


        // Ajax Subject in dropdown by section change
        Route::get('ajaxSubjectDropdown', 'Admin\Academics\AcademicController@ajaxSubjectDropdown');
        Route::post('/language-change', 'Admin\SystemSettings\SmSystemSettingController@ajaxLanguageChange');

        // Route::get('localization/{locale}','SmLocalizationController@index');


        //inventory
        // Route::resource('item-category', 'Admin\Inventory\SmItemCategoryController');
        Route::get('item-category', 'Admin\Inventory\SmItemCategoryController@index')->name('item-category')->middleware('userRolePermission:item-category');
        Route::post('item-category', 'Admin\Inventory\SmItemCategoryController@store')->name('item-category')->middleware('userRolePermission:item-category-store');
        Route::get('item-category/{id}', 'Admin\Inventory\SmItemCategoryController@edit')->name('item-category-edit')->middleware('userRolePermission:item-category-edit');
        Route::put('item-category/{id}', 'Admin\Inventory\SmItemCategoryController@update')->name('item-category-update')->middleware('userRolePermission:item-category-edit');
        
        Route::get('delete-item-category-view/{id}', 'Admin\Inventory\SmItemCategoryController@deleteItemCategoryView')->name('delete-item-category-view')->middleware('userRolePermission:delete-item-category-view');
        Route::get('delete-item-category/{id}', 'Admin\Inventory\SmItemCategoryController@deleteItemCategory')->name('delete-item-category')->middleware('userRolePermission:delete-item-category-view');
        
        // Route::resource('item-list', 'Admin\Inventory\SmItemController');
        Route::get('item-list', 'Admin\Inventory\SmItemController@index')->name('item-list')->middleware('userRolePermission:item-list');
        Route::post('item-list', 'Admin\Inventory\SmItemController@store')->name('item-list')->middleware('userRolePermission:item-list-store');
        Route::get('item-list/{id}', 'Admin\Inventory\SmItemController@edit')->name('item-list-edit')->middleware('userRolePermission:item-list-edit');
        Route::put('item-list/{id}', 'Admin\Inventory\SmItemController@update')->name('item-list-update')->middleware('userRolePermission:item-list-edit');

        Route::get('delete-item-view/{id}', 'Admin\Inventory\SmItemController@deleteItemView')->name('delete-item-view')->middleware('userRolePermission:delete-item-view');
        Route::get('delete-item/{id}', 'Admin\Inventory\SmItemController@deleteItem')->name('delete-item')->middleware('userRolePermission:delete-item-view');

        // Route::resource('item-store', 'Admin\Inventory\SmItemStoreController');
        Route::get('item-store', 'Admin\Inventory\SmItemStoreController@index')->name('item-store')->middleware('userRolePermission:item-store');
        Route::post('item-store', 'Admin\Inventory\SmItemStoreController@store')->name('item-store')->middleware('userRolePermission:item-store-store');
        Route::get('item-store/{id}', 'Admin\Inventory\SmItemStoreController@edit')->name('item-store-edit')->middleware('userRolePermission:item-store-edit');
        Route::put('item-store/{id}', 'Admin\Inventory\SmItemStoreController@update')->name('item-store-update')->middleware('userRolePermission:item-store-edit');

        Route::get('delete-store-view/{id}', 'Admin\Inventory\SmItemStoreController@deleteStoreView')->name('delete-store-view')->middleware('userRolePermission:delete-store-view');
        Route::get('delete-store/{id}', 'Admin\Inventory\SmItemStoreController@deleteStore')->name('delete-store')->middleware('userRolePermission:delete-store-view');
        
        Route::get('item-receive', 'Admin\Inventory\SmItemReceiveController@itemReceive')->name('item-receive')->middleware('userRolePermission:item-receive');
        Route::post('get-receive-item', 'Admin\Inventory\SmItemReceiveController@getReceiveItem');
        Route::post('save-item-receive-data', 'Admin\Inventory\SmItemReceiveController@saveItemReceiveData')->name('save-item-receive-data')->middleware('userRolePermission:save-item-receive-data');
        Route::get('item-receive-list', 'Admin\Inventory\SmItemReceiveController@itemReceiveList')->name('item-receive-list')->middleware('userRolePermission:item-receive-list');
        Route::get('edit-item-receive/{id}', 'Admin\Inventory\SmItemReceiveController@editItemReceive')->name('edit-item-receive')->middleware('userRolePermission:edit-item-receive');
        Route::post('update-edit-item-receive-data/{id}', 'Admin\Inventory\SmItemReceiveController@updateItemReceiveData')->name('update-edit-item-receive-data')->middleware('userRolePermission:edit-item-receive');
        Route::post('delete-receive-item', 'Admin\Inventory\SmItemReceiveController@deleteReceiveItem');
        Route::get('view-item-receive/{id}', 'Admin\Inventory\SmItemReceiveController@viewItemReceive')->name('view-item-receive');
        Route::get('add-payment/{id}', 'Admin\Inventory\SmItemReceiveController@itemReceivePayment')->name('add-payment');
        Route::post('save-item-receive-payment', 'Admin\Inventory\SmItemReceiveController@saveItemReceivePayment')->name('save-item-receive-payment');
        Route::get('view-receive-payments/{id}', 'Admin\Inventory\SmItemReceiveController@viewReceivePayments')->name('view-receive-payments')->middleware('userRolePermission:view-receive-payments');
        Route::post('delete-receive-payment', 'Admin\Inventory\SmItemReceiveController@deleteReceivePayment');
        Route::get('delete-item-receive-view/{id}', 'Admin\Inventory\SmItemReceiveController@deleteItemReceiveView')->name('delete-item-receive-view')->middleware('userRolePermission:delete-item-receive-view');
        Route::get('delete-item-receive/{id}', 'Admin\Inventory\SmItemReceiveController@deleteItemReceive')->name('delete-item-receive');
        Route::get('delete-item-sale-view/{id}', 'Admin\Inventory\SmItemReceiveController@deleteItemSaleView')->name('delete-item-sale-view')->middleware('userRolePermission:delete-item-sale-view');
        Route::get('delete-item-sale/{id}', 'Admin\Inventory\SmItemReceiveController@deleteItemSale');
        Route::get('cancel-item-receive-view/{id}', 'Admin\Inventory\SmItemReceiveController@cancelItemReceiveView')->name('cancel-item-receive-view');
        Route::get('cancel-item-receive/{id}', 'Admin\Inventory\SmItemReceiveController@cancelItemReceive')->name('cancel-item-receive');

        // Item Sell in inventory
        Route::get('item-sell-list', 'Admin\Inventory\SmItemSellController@itemSellList')->name('item-sell-list')->middleware('userRolePermission:item-sell-list');
        Route::get('item-sell', 'Admin\Inventory\SmItemSellController@itemSell')->name('item-sell')->middleware('userRolePermission:save-item-sell-data');
        Route::post('save-item-sell-data', 'Admin\Inventory\SmItemSellController@saveItemSellData')->name('save-item-sell-data');

        Route::post('check-product-quantity', 'Admin\Inventory\SmItemSellController@checkProductQuantity');
        Route::get('edit-item-sell/{id}', 'Admin\Inventory\SmItemSellController@editItemSell')->name('edit-item-sell')->middleware('userRolePermission:edit-item-sell');

        Route::post('update-item-sell-data', 'Admin\Inventory\SmItemSellController@UpdateItemSellData')->name('update-item-sell-data');


        

        Route::get('item-issue', 'Admin\Inventory\SmItemSellController@itemIssueList')->name('item-issue')->middleware('userRolePermission:item-issue');
        Route::post('save-item-issue-data', 'Admin\Inventory\SmItemSellController@saveItemIssueData')->name('save-item-issue-data')->middleware('userRolePermission:save-item-issue-data');
        Route::get('getItemByCategory', 'Admin\Inventory\SmItemSellController@getItemByCategory');
        Route::get('return-item-view/{id}', 'Admin\Inventory\SmItemSellController@returnItemView')->name('return-item-view')->middleware('userRolePermission:return-item-view');
        Route::get('return-item/{id}', 'Admin\Inventory\SmItemSellController@returnItem')->name('return-item');

        Route::get('view-item-sell/{id}', 'Admin\Inventory\SmItemSellController@viewItemSell')->name('view-item-sell');
        Route::get('view-item-sell-print/{id}', 'Admin\Inventory\SmItemSellController@viewItemSellPrint')->name('view-item-sell-print');

        Route::get('add-payment-sell/{id}', 'Admin\Inventory\SmItemSellController@itemSellPayment')->name('add-payment-sell')->middleware('userRolePermission:add-payment-sell');
        Route::post('save-item-sell-payment', 'Admin\Inventory\SmItemSellController@saveItemSellPayment')->name('save-item-sell-payment');


        //Supplier
        // Route::resource('suppliers', 'Admin\Inventory\SmSupplierController');
        Route::get('suppliers', 'Admin\Inventory\SmSupplierController@index')->name('suppliers')->middleware('userRolePermission:suppliers');
        Route::post('suppliers', 'Admin\Inventory\SmSupplierController@store')->name('suppliers')->middleware('userRolePermission:suppliers-store');
        Route::get('suppliers/{id}', 'Admin\Inventory\SmSupplierController@edit')->name('suppliers-edit')->middleware('userRolePermission:suppliers-edit');
        Route::put('suppliers/{id}', 'Admin\Inventory\SmSupplierController@update')->name('suppliers-update')->middleware('userRolePermission:suppliers-edit');
        Route::get('delete-supplier-view/{id}', 'Admin\Inventory\SmSupplierController@deleteSupplierView')->name('delete-supplier-view')->middleware('userRolePermission:suppliers-delete');
        Route::get('delete-supplier/{id}', 'Admin\Inventory\SmSupplierController@deleteSupplier')->name('delete-supplier')->middleware('userRolePermission:delete-supplier-view');


        Route::get('view-sell-payments/{id}', 'Admin\Inventory\SmItemSellController@viewSellPayments')->name('view-sell-payments')->middleware('userRolePermission:view-sell-payments');


        Route::post('delete-sell-payment', 'Admin\Inventory\SmItemSellController@deleteSellPayment');
        Route::get('cancel-item-sell-view/{id}', 'Admin\Inventory\SmItemSellController@cancelItemSellView')->name('cancel-item-sell-view');
        Route::get('cancel-item-sell/{id}', 'Admin\Inventory\SmItemSellController@cancelItemSell')->name('cancel-item-sell');


        //library member
        // Route::resource('library-member', 'Admin\Library\SmLibraryMemberController');
        // Route::get('cancel-membership/{id}', 'Admin\Library\SmLibraryMemberController@cancelMembership');


        //ajax theme change
        // Route::get('theme-style-active', 'Admin\SystemSettings\SmSystemSettingController@themeStyleActive');
        // Route::get('theme-style-rtl', 'Admin\SystemSettings\SmSystemSettingController@themeStyleRTL');
        // Route::get('change-academic-year', 'Admin\SystemSettings\SmSystemSettingController@sessionChange');

        // Sms Settings
        Route::get('sms-settings', 'Admin\SystemSettings\SmSystemSettingController@smsSettings')->name('sms-settings')->middleware('userRolePermission:sms-settings');
        Route::post('update-clickatell-data', 'Admin\SystemSettings\SmSystemSettingController@updateClickatellData')->name('update-clickatell-data');
        Route::post('update-twilio-data', 'Admin\SystemSettings\SmSystemSettingController@updateTwilioData')->name('update-twilio-data')->middleware('userRolePermission:update-twilio-data');
        Route::post('update-msg91-data', 'Admin\SystemSettings\SmSystemSettingController@updateMsg91Data')->name('update-msg91-data')->middleware('userRolePermission:update-msg91-data');
        Route::any('activeSmsService', 'Admin\SystemSettings\SmSystemSettingController@activeSmsService');

        Route::post('update-textlocal-data', 'Admin\SystemSettings\SmSystemSettingController@updateTextlocalData')->name('update-textlocal-data')->middleware('userRolePermission:update-textlocal-data');

        Route::post('update-africatalking-data', 'Admin\SystemSettings\SmSystemSettingController@updateAfricaTalkingData')->name('update-africatalking-data')->middleware('userRolePermission:update-textlocal-data');


        //Language Setting
        Route::get('language-setup/{id}', 'Admin\SystemSettings\SmSystemSettingController@languageSetup')->name('language-setup')->middleware('userRolePermission:language-setup');
        Route::get('language-settings', 'Admin\SystemSettings\SmSystemSettingController@languageSettings')->name('language-settings')->middleware('userRolePermission:language-settings');
        Route::post('language-add', 'Admin\SystemSettings\SmSystemSettingController@languageAdd')->name('language-add')->middleware('userRolePermission:language-add');

        Route::get('language-edit/{id}', 'Admin\SystemSettings\SmSystemSettingController@languageEdit');
        Route::post('language-update', 'Admin\SystemSettings\SmSystemSettingController@languageUpdate')->name('language-update');

        Route::post('language-delete', 'Admin\SystemSettings\SmSystemSettingController@languageDelete')->name('language-delete')->middleware('userRolePermission:language-delete');

        Route::get('get-translation-terms', 'Admin\SystemSettings\SmSystemSettingController@getTranslationTerms');
        Route::post('translation-term-update', 'Admin\SystemSettings\SmSystemSettingController@translationTermUpdate')->name('translation-term-update');
     
        //currency
        Route::get('manage-currency', 'Admin\GeneralSettings\SmManageCurrencyController@manageCurrency')->name('manage-currency')->middleware('userRolePermission:manage-currency');

        Route::get('create-currency', 'Admin\GeneralSettings\SmManageCurrencyController@create')->name('create-currency')->middleware('userRolePermission:manage-currency');
        
        Route::post('currency-store', 'Admin\GeneralSettings\SmManageCurrencyController@storeCurrency')->name('currency-store')->middleware('userRolePermission:currency-store');

        Route::post('currency-update', 'Admin\GeneralSettings\SmManageCurrencyController@storeCurrencyUpdate')->name('currency-update')->middleware('userRolePermission:currency_edit');
        Route::get('manage-currency/edit/{id}', 'Admin\GeneralSettings\SmManageCurrencyController@manageCurrencyEdit')->name('currency_edit')->middleware('userRolePermission:currency_edit');

        Route::get('manage-currency/delete/{id}', 'Admin\GeneralSettings\SmManageCurrencyController@manageCurrencyDelete')->name('currency_delete')->middleware('userRolePermission:currency_delete');

        Route::get('manage-currency/active/{id}', 'Admin\GeneralSettings\SmManageCurrencyController@manageCurrencyActive')->name('currency_active')->middleware('userRolePermission:currency_active');

        Route::get('system-destroyed-by-authorized', 'Admin\GeneralSettings\SmManageCurrencyController@systemDestroyedByAuthorized')->name('systemDestroyedByAuthorized');
      

        //Backup Setting
        Route::post('backup-store', 'Admin\SystemSettings\SmSystemSettingController@BackupStore')->name('backup-store')->middleware('userRolePermission:backup-store');
        Route::get('backup-settings', 'Admin\SystemSettings\SmSystemSettingController@backupSettings')->name('backup-settings')->middleware('userRolePermission:backup-settings');
        Route::get('get-backup-files/{id}', 'Admin\SystemSettings\SmSystemSettingController@getfilesBackup')->name('get-backup-files')->middleware('userRolePermission:get-backup-files');
        Route::get('get-backup-db', 'Admin\SystemSettings\SmSystemSettingController@getDatabaseBackup')->name('get-backup-db')->middleware('userRolePermission:get-backup-db');
        Route::get('download-database/{id}', 'Admin\SystemSettings\SmSystemSettingController@downloadDatabase');
        Route::get('download-files/{id}', 'Admin\SystemSettings\SmSystemSettingController@downloadFiles')->name('download-files')->middleware('userRolePermission:download-files');
        Route::get('restore-database/{id}', 'Admin\SystemSettings\SmSystemSettingController@restoreDatabase')->name('restore-database');
        Route::get('delete-database/{id}', 'Admin\SystemSettings\SmSystemSettingController@deleteDatabase')->name('delete_database')->middleware('userRolePermission:delete_database');

        //Update System
        Route::get('about-system', 'Admin\SystemSettings\SmSystemSettingController@AboutSystem')->name('about-system')->middleware('userRolePermission:about-system');


        Route::get('database-upgrade', 'Admin\SystemSettings\SmSystemSettingController@databaseUpgrade')->name('database-upgrade');
        Route::get('update-system', 'Admin\SystemSettings\SmSystemSettingController@UpdateSystem')->name('update-system')->middleware('userRolePermission:update-system');
        Route::post('admin/update-system', 'Admin\SystemSettings\SmSystemSettingController@admin_UpdateSystem')->name('admin/update-system')->middleware('userRolePermission:admin/update-system');
        Route::any('upgrade-settings', 'Admin\SystemSettings\SmSystemSettingController@UpgradeSettings');

       
        //Route::get('sendSms','SmSmsTestController@sendSms');
        //Route::get('sendSmsMsg91','SmSmsTestController@sendSmsMsg91');
        //Route::get('sendSmsClickatell','SmSmsTestController@sendSmsClickatell');

        //Settings
        Route::get('general-settings', 'Admin\SystemSettings\SmSystemSettingController@generalSettingsView')->name('general-settings')->middleware('userRolePermission:general-settings');
        Route::get('update-general-settings', 'Admin\SystemSettings\SmSystemSettingController@updateGeneralSettings')->name('update-general-settings')->middleware('userRolePermission:update-general-settings');
        Route::post('update-general-settings-data', 'Admin\SystemSettings\SmSystemSettingController@updateGeneralSettingsData')->name('update-general-settings-data')->middleware('userRolePermission:update-general-settings-data');
        Route::post('update-school-logo', 'Admin\SystemSettings\SmSystemSettingController@updateSchoolLogo')->name('update-school-logo')->middleware('userRolePermission:update-school-logo');

        //Custom Field Start
        Route::get('student-registration-custom-field','SmCustomFieldController@index')->name('student-reg-custom-field')->middleware('userRolePermission:student-reg-custom-field');
        Route::post('store-student-registration-custom-field','SmCustomFieldController@store')->name('store-student-registration-custom-field')->middleware('userRolePermission:store-student-registration-custom-field');
        Route::get('edit-custom-field/{id}','SmCustomFieldController@edit')->name('edit-custom-field')->middleware('userRolePermission:edit-custom-field');
        Route::post('update-student-registration-custom-field','SmCustomFieldController@update')->name('update-student-registration-custom-field');
        Route::post('delete-custom-field','SmCustomFieldController@destroy')->name('delete-custom-field')->middleware('userRolePermission:delete-custom-field');

        Route::get('staff-reg-custom-field', 'SmCustomFieldController@staff_reg_custom_field')->name('staff-reg-custom-field')->middleware('userRolePermission:staff-reg-custom-field');
        Route::post('store-staff-registration-custom-field', 'SmCustomFieldController@store_staff_registration_custom_field')->name('store-staff-registration-custom-field')->middleware('userRolePermission:store-staff-registration-custom-field');
        Route::get('edit-staff-custom-field/{id}', 'SmCustomFieldController@edit_staff_custom_field')->name('edit-staff-custom-field');
        Route::post('update-staff-custom-field', 'SmCustomFieldController@update_staff_custom_field')->name('update-staff-custom-field')->middleware('userRolePermission:edit-staff-custom-field');
        Route::post('delete-staff-custom-field', 'SmCustomFieldController@delete_staff_custom_field')->name('delete-staff-custom-field')->middleware('userRolePermission:delete-staff-custom-field');
        //Custom Field End



        // payment Method Settings
        Route::get('payment-method-settings', 'Admin\SystemSettings\SmSystemSettingController@paymentMethodSettings')->name('payment-method-settings')->middleware('userRolePermission:payment-method-settings');
        Route::post('update-paypal-data', 'Admin\SystemSettings\SmSystemSettingController@updatePaypalData')->name('updatePaypalData');
        Route::post('update-stripe-data', 'Admin\SystemSettings\SmSystemSettingController@updateStripeData');
        Route::post('update-payumoney-data', 'Admin\SystemSettings\SmSystemSettingController@updatePayumoneyData');
        Route::post('active-payment-gateway', 'Admin\SystemSettings\SmSystemSettingController@activePaymentGateway');
        Route::post('bank-status', 'Admin\SystemSettings\SmSystemSettingController@bankStatus')->name('bank-status');

        //Email Settings
        Route::get('email-settings', 'Admin\SystemSettings\SmSystemSettingController@emailSettings')->name('email-settings')->middleware('userRolePermission:email-settings');
        Route::post('update-email-settings-data', 'Admin\SystemSettings\SmSystemSettingController@updateEmailSettingsData')->name('update-email-settings-data')->middleware('userRolePermission:update-email-settings-data');


        Route::post('send-test-mail', 'Admin\SystemSettings\SmSystemSettingController@sendTestMail')->name('send-test-mail');

        // payment Method Settings
        // Route::get('payment-method-settings', 'Admin\SystemSettings\SmSystemSettingController@paymentMethodSettings');
       
        Route::post('is-active-payment', 'Admin\SystemSettings\SmSystemSettingController@isActivePayment')->name('is-active-payment')->middleware('userRolePermission:is-active-payment');
        //Route::get('stripeTest', 'SmSmsTestController@stripeTest');
        //Route::post('stripe_post', 'SmSmsTestController@stripePost');

        //Collect fees By Online Payment Gateway(Paypal)
        Route::get('collect-fees-gateway/{amount}/{student_id}/{type}', 'SmCollectFeesByPaymentGateway@collectFeesByGateway');
        Route::post('payByPaypal', 'SmCollectFeesByPaymentGateway@payByPaypal')->name('payByPaypal');

        //Collect fees By Online Payment Gateway(Stripe)
        Route::get('collect-fees-stripe/{amount}/{student_id}/{type}', 'SmCollectFeesByPaymentGateway@collectFeesStripe');
        Route::post('collect-fees-stripe-strore', 'SmCollectFeesByPaymentGateway@stripeStore')->name('collect-fees-stripe-strore');

        // To Do list

        //Route::get('stripeTest', 'SmSmsTestController@stripeTest');
        //Route::post('stripe_post', 'SmSmsTestController@stripePost');


        

        Route::get('custom-result-setting', 'Admin\Examination\CustomResultSettingController@index')->name('custom-result-setting')->middleware('userRolePermission:custom-result-setting');
        Route::get('custom-result-setting/edit/{id}', 'Admin\Examination\CustomResultSettingController@edit')->name('custom-result-setting-edit')->middleware('userRolePermission:custom-result-setting-edit');
        Route::DELETE('custom-result-setting/{id}', 'Admin\Examination\CustomResultSettingController@delete')->name('custom-result-setting-delete')->middleware('userRolePermission:custom-result-setting-delete');
        Route::put('custom-result-setting/update', 'Admin\Examination\CustomResultSettingController@update')->name('custom-result-setting/update')->middleware('userRolePermission:custom-result-setting-edit');
        Route::post('custom-result-setting/store', 'Admin\Examination\CustomResultSettingController@store')->name('custom-result-setting/store')->middleware('userRolePermission:437');
        Route::post('merit-list-settings', 'Admin\Examination\CustomResultSettingController@merit_list_settings')->name('merit-list-settings');

        //Custom Result
        Route::get('custom-merit-list', 'Admin\Examination\CustomResultSettingController@meritListReportIndex')->name('custom-merit-list')->middleware('userRolePermission:custom-merit-list');
        Route::get('custom-merit-list/print/{class}/{section}', 'Admin\Examination\CustomResultSettingController@meritListReportPrint')->name('custom-merit-list-print');
        Route::post('custom-merit-list', 'Admin\Examination\CustomResultSettingController@meritListReport')->name('custom-merit-list');

        Route::get('custom-progress-card', 'Admin\Examination\CustomResultSettingController@progressCardReportIndex')->name('custom-progress-card')->middleware('userRolePermission:custom-progress-card');
        Route::post('custom-progress-card', 'Admin\Examination\CustomResultSettingController@progressCardReport')->name('custom-progress-card')->middleware('userRolePermission:custom-progress-card');
        Route::post('custom-progress-card/print', 'Admin\Examination\CustomResultSettingController@progressCardReportPrint')->name('custom-progress-card-print');


        Route::post('exam-step-skip', 'Admin\Examination\CustomResultSettingController@stepSkipUpdate')->name('exam.step.skip.update');

        // login access control
        Route::get('login-access-control', 'SmLoginAccessControlController@loginAccessControl')->name('login-access-control')->middleware('userRolePermission:login-access-control');
        Route::post('login-access-control', 'SmLoginAccessControlController@searchUser')->name('login-access-control');
        Route::get('login-access-permission', 'SmLoginAccessControlController@loginAccessPermission');
        Route::get('login-password-reset', 'SmLoginAccessControlController@loginPasswordDefault');

        Route::get('button-disable-enable', 'Admin\SystemSettings\SmSystemSettingController@buttonDisableEnable')->name('button-disable-enable')->middleware('userRolePermission:button-disable-enable');

        Route::get('manage-adons', 'Admin\SystemSettings\SmAddOnsController@ManageAddOns')->name('manage-adons')->middleware('userRolePermission:manage-adons');
        Route::get('manage-adons-delete/{name}', 'Admin\SystemSettings\SmAddOnsController@ManageAddOns')->name('deleteModule');
        Route::get('manage-adons-enable/{name}', 'Admin\SystemSettings\SmAddOnsController@moduleAddOnsEnable')->name('moduleAddOnsEnable');
        Route::get('manage-adons-disable/{name}', 'Admin\SystemSettings\SmAddOnsController@moduleAddOnsDisable')->name('moduleAddOnsDisable');

        // Route::post('manage-adons-validation', 'Admin\SystemSettings\SmAddOnsController@ManageAddOnsValidation')->name('ManageAddOnsValidation')->middleware('userRolePermission:400');
        Route::get('ModuleRefresh', 'Admin\SystemSettings\SmAddOnsController@ModuleRefresh')->name('ModuleRefresh');
        Route::get('view-as-superadmin', 'Admin\SystemSettings\SmSystemSettingController@viewAsSuperadmin')->name('viewAsSuperadmin');



        Route::get('/sms-template', 'Admin\Communicate\SmsEmailTemplateController@SmsTemplate')->name('sms-template');
        Route::post('/sms-template/{id}', 'Admin\Communicate\SmsEmailTemplateController@SmsTemplateStore')->name('sms-template-store')->middleware('userRolePermission:sms-template-store');

        Route::get('/sms-template-new', 'Admin\Communicate\SmsEmailTemplateController@SmsTemplateNew')->name('sms-template-new')->middleware('userRolePermission:sms-template-new');
        Route::post('/sms-template-new', 'Admin\Communicate\SmsEmailTemplateController@SmsTemplateNewStore')->name('sms-template-new')->middleware('userRolePermission:sms-template-new-store');
    });


    Route::post('update-payment-gateway', 'Admin\SystemSettings\SmSystemSettingController@updatePaymentGateway')->name('update-payment-gateway')->middleware('userRolePermission:update-payment-gateway');
    Route::post('versionUpdateInstall', 'Admin\SystemSettings\SmSystemSettingController@versionUpdateInstall')->name('versionUpdateInstall');

    Route::post('moduleFileUpload', 'Admin\SystemSettings\SmSystemSettingController@moduleFileUpload')->name('moduleFileUpload');


    //systemsetting utilities 

    Route::get('utility', 'Admin\SystemSettings\UtilityController@index')->name('utility');
    Route::get('utilities/{action}', 'Admin\SystemSettings\UtilityController@action')->name('utilities');
    Route::get('testup', 'Admin\SystemSettings\UtilityController@testup')->name('testup');
    Route::post('maintenance_mode', 'Admin\SystemSettings\UtilityController@updateMaintenance')->name('updateMaintenance');

    // background setting
    Route::get('background-setting', 'Admin\Style\SmBackGroundSettingController@index')->name('background-setting')->middleware('userRolePermission:background-setting');
    Route::post('background-settings-update', 'Admin\Style\SmBackGroundSettingController@update')->name('background-settings-update');
    Route::post('background-settings-store', 'Admin\Style\SmBackGroundSettingController@store')->name('background-settings-store')->middleware('userRolePermission:background-settings-store');
    Route::get('background-setting-delete/{id}', 'Admin\Style\SmBackGroundSettingController@delete')->name('background-setting-delete')->middleware('userRolePermission:background-setting-delete');
    Route::get('background_setting-status/{id}', 'Admin\Style\SmBackGroundSettingController@status')->name('background_setting-status')->middleware('userRolePermission:background_setting-status');

    //color theme change
    Route::get('color-style', 'Admin\Style\ThemeController@index')->name('color-style')->middleware('userRolePermission:color-style');
    Route::get('make-default-theme/{id}', 'Admin\Style\SmBackGroundSettingController@colorThemeSet')->name('make-default-theme')->middleware('userRolePermission:make-default-theme');

    Route::get('theme-create', 'Admin\Style\ThemeController@create')->name('theme-create')->middleware('userRolePermission:theme-create');
    Route::post('theme-create-store', 'Admin\Style\ThemeController@store')->name('theme-store')->middleware('userRolePermission:theme-store');
    Route::get('themes/{theme}/copy', 'Admin\Style\ThemeController@copy')->name('themes.copy')->middleware('userRolePermission:themes.copy');
    Route::get('themes/{theme}/default', 'Admin\Style\ThemeController@default')->name('themes.default')->middleware('userRolePermission:themes.default');
    Route::resource('themes', 'Admin\Style\ThemeController');
    //Front Settings Route
  
    // Header Menu Manager
    Route::get('header-menu-manager', 'Admin\FrontSettings\SmHeaderMenuManagerController@index')->name('header-menu-manager')->middleware('userRolePermission:header-menu-manager');
    Route::post('add-element', 'Admin\FrontSettings\SmHeaderMenuManagerController@store')->name('add-element')->middleware('userRolePermission:add-element');
    Route::post('reordering', 'Admin\FrontSettings\SmHeaderMenuManagerController@reordering')->name('reordering');
    Route::post('element-update', 'Admin\FrontSettings\SmHeaderMenuManagerController@update')->name('element-update')->middleware('userRolePermission:element-update');
    Route::post('delete-element', 'Admin\FrontSettings\SmHeaderMenuManagerController@delete')->name('delete-element')->middleware('userRolePermission:delete-element');
    
     // admin-home-page
    Route::get('admin-home-page', 'Admin\FrontSettings\HomePageController@index')->name('admin-home-page')->middleware('userRolePermission:admin-home-page');
    Route::post('admin-home-page-update', 'Admin\FrontSettings\HomePageController@update')->name('admin-home-page-update')->middleware('userRolePermission:admin-home-page-update');
     // News route start
    Route::get('news-heading-update', 'Admin\FrontSettings\NewsHeadingController@index')->name('news-heading-update')->middleware('userRolePermission:news-heading-update');
    Route::post('news-heading-update', 'Admin\FrontSettings\NewsHeadingController@update')->name('news-heading-update')->middleware('userRolePermission:news-heading-update');
   
    //news categroy
    Route::get('news-category', 'Admin\FrontSettings\SmNewsCategoryController@index')->name('news-category')->middleware('userRolePermission:news-category');
    Route::post('/news-category-store', 'Admin\FrontSettings\SmNewsCategoryController@store')->name('store_news_category')->middleware('userRolePermission:store_news_category');
    Route::get('edit-news-category/{id}', 'Admin\FrontSettings\SmNewsCategoryController@edit')->name('edit-news-category')->middleware('userRolePermission:edit-news-category');    
    Route::post('/news-category-update', 'Admin\FrontSettings\SmNewsCategoryController@update')->name('update_news_category')->middleware('userRolePermission:edit-news-category');
    Route::get('for-delete-news-category/{id}', 'Admin\FrontSettings\SmNewsCategoryController@deleteModalOpen')->name('for-delete-news-category')->middleware('userRolePermission:for-delete-news-category');
    Route::get('delete-news-category/{id}', 'Admin\FrontSettings\SmNewsCategoryController@delete')->name('delete-news-category');

    // news 
    
    Route::get('/news', 'Admin\FrontSettings\SmNewsController@index')->name('news_index');
    Route::post('/news-store', 'Admin\FrontSettings\SmNewsController@store')->name('store_news')->middleware('userRolePermission:store_news');
    Route::post('/news-update', 'Admin\FrontSettings\SmNewsController@update')->name('update_news')->middleware('userRolePermission:edit-news');
    Route::get('newsDetails/{id}', 'Admin\FrontSettings\SmNewsController@newsDetails')->name('newsDetails')->middleware('userRolePermission:496');
    Route::get('for-delete-news/{id}', 'Admin\FrontSettings\SmNewsController@forDeleteNews')->name('for-delete-news')->middleware('userRolePermission:delete-news');
    Route::get('delete-news/{id}', 'Admin\FrontSettings\SmNewsController@delete')->name('delete-news');
    Route::get('edit-news/{id}', 'Admin\FrontSettings\SmNewsController@edit')->name('edit-news')->middleware('userRolePermission:edit-news');


        // Course route start
    Route::get('course-heading-update', 'Admin\FrontSettings\SmCourseHeadingController@index')->name('course-heading-update')->middleware('userRolePermission:course-heading-update');
    Route::post('course-heading-update', 'Admin\FrontSettings\SmCourseHeadingController@update')->name('course-heading-update')->middleware('userRolePermission:course-heading-update');

        // Course Details route start
    Route::get('course-details-heading', 'Admin\FrontSettings\SmCourseHeadingDetailsController@index')->name('course-details-heading')->middleware('userRolePermission:course-details-heading');
    Route::post('course-heading-details-update', 'Admin\FrontSettings\SmCourseHeadingDetailsController@update')->name('course-details-heading-update')->middleware('userRolePermission:course-details-heading');
    
    //For course module
    Route::get('course-category', 'Admin\FrontSettings\SmCourseCategoryController@index')->name('course-category')->middleware('userRolePermission:course-category');
    Route::post('store-course-category', 'Admin\FrontSettings\SmCourseCategoryController@store')->name('store-course-category')->middleware('userRolePermission:store-course-category');
    Route::get('edit-course-category/{id}', 'Admin\FrontSettings\SmCourseCategoryController@edit')->name('edit-course-category')->middleware('userRolePermission:edit-course-category');
    Route::post('update-course-category', 'Admin\FrontSettings\SmCourseCategoryController@update')->name('update-course-category')->middleware('userRolePermission:edit-course-category');
    Route::post('delete-course-category/{id}', 'Admin\FrontSettings\SmCourseCategoryController@delete')->name('delete-course-category')->middleware('userRolePermission:delete-course-category');
  
    //for frontend
    Route::get('view-course-category/{id}', 'Admin\FrontSettings\SmCourseCategoryController@view')->name('view-course-category');
    //course List
    Route::get('course-list', 'Admin\FrontSettings\SmCourseListController@index')->name('course-list')->middleware('userRolePermission:course-list');
    Route::post('/course-store', 'Admin\FrontSettings\SmCourseListController@store')->name('store_course')->middleware('userRolePermission:store_course');
    Route::post('/course-update', 'Admin\FrontSettings\SmCourseListController@update')->name('update_course')->middleware('userRolePermission:edit-course');
    Route::get('for-delete-course/{id}', 'Admin\FrontSettings\SmCourseListController@forDeleteCourse')->name('for-delete-course')->middleware('userRolePermission:delete-course');
    Route::get('delete-course/{id}', 'Admin\FrontSettings\SmCourseListController@destroy')->name('delete-course')->middleware('userRolePermission:delete-course');

    Route::get('edit-course/{id}', 'Admin\FrontSettings\SmCourseListController@edit')->name('edit-course')->middleware('userRolePermission:edit-course');
    Route::get('course-Details-admin/{id}', 'Admin\FrontSettings\SmCourseListController@courseDetails')->name('course-Details-admin')->middleware('userRolePermission:course-Details-admin');
   
   
    //for testimonial

    Route::get('/testimonial', 'Admin\FrontSettings\SmTestimonialController@index')->name('testimonial_index')->middleware('userRolePermission:testimonial_index');

    Route::post('/testimonial-store', 'Admin\FrontSettings\SmTestimonialController@store')->name('store_testimonial')->middleware('userRolePermission:store_testimonial');
    Route::post('/testimonial-update', 'Admin\FrontSettings\SmTestimonialController@update')->name('update_testimonial')->middleware('userRolePermission:edit-testimonial');
    Route::get('testimonial-details/{id}', 'Admin\FrontSettings\SmTestimonialController@testimonialDetails')->name('testimonial-details')->middleware('userRolePermission:testimonial-details');
    Route::get('for-delete-testimonial/{id}', 'Admin\FrontSettings\SmTestimonialController@forDeleteTestimonial')->name('for-delete-testimonial')->middleware('userRolePermission:for-delete-testimonial');
    Route::get('delete-testimonial/{id}', 'Admin\FrontSettings\SmTestimonialController@delete')->name('delete-testimonial');
    Route::get('edit-testimonial/{id}', 'Admin\FrontSettings\SmTestimonialController@edit')->name('edit-testimonial')->middleware('userRolePermission:edit-testimonial');
  
    // Contact us
    Route::get('contact-page', 'Admin\FrontSettings\SmContactUsController@index')->name('conpactPage')->middleware('userRolePermission:conpactPage');
    Route::get('contact-page/edit', 'Admin\FrontSettings\SmContactUsController@edit')->name('contactPageEdit');
    Route::post('contact-page/update', 'Admin\FrontSettings\SmContactUsController@update')->name('contactPageStore');

    // contact message
    Route::get('delete-message/{id}', 'SmFrontendController@deleteMessage')->name('delete-message')->middleware('userRolePermission:delete-message');


   
    //Social Media
    Route::get('social-media', 'Admin\FrontSettings\SmSocialMediaController@index')->name('social-media')->middleware('userRolePermission:social-media');
    Route::post('social-media-store', 'Admin\FrontSettings\SmSocialMediaController@store')->name('social-media-store');
    Route::get('social-media-edit/{id}', 'Admin\FrontSettings\SmSocialMediaController@edit')->name('social-media-edit');
    Route::post('social-media-update', 'Admin\FrontSettings\SmSocialMediaController@update')->name('social-media-update');
    Route::get('social-media-delete/{id}', 'Admin\FrontSettings\SmSocialMediaController@delete')->name('social-media-delete');

    //page
    Route::get('page-list', 'Admin\FrontSettings\SmPageController@index')->name('page-list')->middleware('userRolePermission:page-list');
    Route::get('create-page', 'Admin\FrontSettings\SmPageController@create')->name('create-page')->middleware('userRolePermission:save-page-data');
    Route::post('save-page-data', 'Admin\FrontSettings\SmPageController@store')->name('save-page-data')->middleware('userRolePermission:save-page-data');
    Route::get('edit-page/{id}', 'Admin\FrontSettings\SmPageController@edit')->name('edit-page')->middleware('userRolePermission:edit-page');
    Route::post('update-page-data', 'Admin\FrontSettings\SmPageController@update')->name('update-page-data')->middleware('userRolePermission:edit-page');
   
    // about us
    Route::get('about-page', 'Admin\FrontSettings\AboutPageController@index')->name('about-page')->middleware('userRolePermission:about-page');
    Route::get('about-page/edit', 'Admin\FrontSettings\AboutPageController@edit')->name('about-page/edit');
    Route::post('about-page/update', 'Admin\FrontSettings\AboutPageController@update')->name('about-page/update');
    //footer widget
    Route::get('custom-links', 'Admin\FrontSettings\SmFooterWidgetController@index')->name('custom-links')->middleware('userRolePermission:custom-links');
    Route::post('custom-links-update', 'Admin\FrontSettings\SmFooterWidgetController@update')->name('custom-links-update')->middleware('userRolePermission:custom-links');
    //student class assign -abunayem
    Route::get('student/{id}/assign-class', 'Admin\StudentInfo\SmStudentAdmissionController@assignClass')->name('student.assign-class');







    Route::post('student/record-delete', 'Admin\StudentInfo\SmStudentAdmissionController@deleteRecord')->name('student.record.delete');
    Route::get('ajax-get-academic','Admin\StudentInfo\SmStudentAdmissionController@getSchool')
    ->name('get-school');
    Route::post('student/record-store', 'Admin\StudentInfo\SmStudentAdmissionController@recordStore')->name('student.record.store');
    Route::get('student/assign-edit/{student_id}/{record_id}', 'Admin\StudentInfo\SmStudentAdmissionController@recordEdit')->name('student_assign_edit');
    Route::post('student/record-update', 'Admin\StudentInfo\SmStudentAdmissionController@recordUpdate')->name('student.record.update');
    Route::get('student/check-exit', 'Admin\StudentInfo\SmStudentAdmissionController@checkExitStudent');


    Route::get('mm', 'Admin\StudentInfo\SmStudentAdmissionController@mm');


    //Smart Web system modification
    Route::get('return_exam_view', 'Admin\Examination\SmExamController@examView')->name('examView')->middleware('userRolePermission:examView');
    Route::get('subject_mark_sheet', 'Admin\Report\SubjectMarkSheetReportController@index')->name('subjectMarkSheet')->middleware('userRolePermission:subjectMarkSheet');
    Route::post('subject_mark_sheet-search', 'Admin\Report\SubjectMarkSheetReportController@search')->name('subjectMarkSheetSearch')->middleware('userRolePermission:subjectMarkSheet');
    Route::post('subject_mark_sheet-print', 'Admin\Report\SubjectMarkSheetReportController@print')->name('subjectMarkSheetPrint')->middleware('userRolePermission:subjectMarkSheet');


    Route::get('final_mark_sheet', 'Admin\Report\SubjectMarkSheetReportController@finalMarkSheet')->name('finalMarkSheet')->middleware('userRolePermission:exam_schedule');
    Route::post('final_mark_sheet-search', 'Admin\Report\SubjectMarkSheetReportController@finalMarkSheetSearch')->name('finalMarkSheetSearch')->middleware('userRolePermission:exam_schedule');
    Route::post('final_mark_sheet-print', 'Admin\Report\SubjectMarkSheetReportController@finalMarkSheetPrint')->name('finalMarkSheetPrint')->middleware('userRolePermission:exam_schedule');


    Route::get('student_mark_sheet_final', 'Admin\Report\SubjectMarkSheetReportController@studentFinalMarkSheet')->name('studentFinalMarkSheet')->middleware('userRolePermission:exam_schedule_print');
    Route::post('student_mark_sheet_final_search', 'Admin\Report\SubjectMarkSheetReportController@studentFinalMarkSheetSearch')->name('studentFinalMarkSheetSearch')->middleware('userRolePermission:exam_schedule_print');
    Route::post('student_mark_sheet_final_print', 'Admin\Report\SubjectMarkSheetReportController@studentFinalMarkSheetPrint')->name('studentFinalMarkSheetPrint')->middleware('userRolePermission:exam_schedule_print');
   
    Route::get('view-as-role', 'Admin\Hr\StaffAsParentController@loginAsRole')->name('viewAsRole');
    Route::get('view-as-parent', 'Admin\Hr\StaffAsParentController@loginAsParent')->name('viewAsParent');

    Route::get('/test-route', function(){
        
    });


    //custom sms setting 
    Route::post('save-custom-sms-setting', 'Admin\SystemSettings\CustomSmsSettingController@store')->name('save-custom-sms-setting')->middleware('userRolePermission:save-custom-sms-setting');
    Route::get('edit-custom-sms-setting/{id}', 'Admin\SystemSettings\CustomSmsSettingController@edit')->name('edit-custom-sms-setting')->middleware('userRolePermission:edit-custom-sms-setting');
    Route::post('update-custom-sms-setting', 'Admin\SystemSettings\CustomSmsSettingController@update')->name('update-custom-sms-setting')->middleware('userRolePermission:edit-custom-sms-setting');
    Route::post('delete-custom-sms-setting', 'Admin\SystemSettings\CustomSmsSettingController@delete')->name('delete-custom-sms-setting')->middleware('userRolePermission:delete-custom-sms-setting');
    Route::post('send-test-sms', 'Admin\SystemSettings\CustomSmsSettingController@testSms')->name('send-test-sms')->middleware('userRolePermission:send-test-sms');


    // Unassigned Student
    Route::get('unassigned-student', ['as' => 'unassigned_student', 'uses' => 'SmStudentAdmissionController@unassignedStudent'])->middleware('userRolePermission:unassigned_student');
    Route::get('sorting-student-list/{class_id}', ['as' => 'sorting_student_list', 'uses' => 'SmStudentAdmissionController@sortingStudent'])->middleware('userRolePermission:unassigned_student');
    Route::get('sorting-student-section-list/{class_id}/{section_id}', ['as' => 'sorting_student_list_section', 'uses' => 'SmStudentAdmissionController@sortingSectionStudent'])->middleware('userRolePermission:unassigned_student');

    Route::get('multi-class-student', 'Admin\StudentInfo\StudentMultiRecordController@multiRecord')->name('student.multi-class-student')->middleware('userRolePermission:student.multi-class-student');

    Route::get('student-multi-record/{student_id}', 'Admin\StudentInfo\StudentMultiRecordController@studentMultiRecord')->name('student.student-multi-record');

    Route::post('student-record-delete', 'Admin\StudentInfo\StudentMultiRecordController@studentRecordDelete')->name('student.multi-record-delete');

    Route::POST('multi-record-store', 'Admin\StudentInfo\StudentMultiRecordController@multiRecordStore')
    ->name('multi-record-store');

    Route::get('delete-student-record', 'Admin\StudentInfo\StudentMultiRecordController@deleteStudentRecord')
    ->name('student.delete-student-record')->middleware('userRolePermission:student.delete-student-record');

    Route::get('student-record-restore/{record_id}', 'Admin\StudentInfo\StudentMultiRecordController@restoreStudentRecord')
    ->name('student-record-restore');

    Route::post('delete-student-record-permanently', 'Admin\StudentInfo\StudentMultiRecordController@studentRecordDeletePermanently')
    ->name('delete-student-record-permanently');

    Route::get('import-staff', [\App\Http\Controllers\ImportController::class, 'index'])->name('import-staff')
    ->middleware('userRolePermission:import-staff');

    Route::post('staff-bulk-store', [\App\Http\Controllers\ImportController::class, 'staffStore'])->name('staff-bulk-store')
    ;
    
    Route::get('lang-file-export/{lang}', 'LanguageController@index')->name('lang-file-export');
    Route::post('file-export', 'LanguageController@export')->name('file-export');
    Route::get('lang-file-import/{lang}', 'LanguageController@importLang')->name('lang-file-import');
    Route::post('file-import', 'LanguageController@import')->name('file-import');
    Route::get('backup-lang/{lang}', 'LanguageController@backupLanguage')->name('backup-lang');  

    

    Route::get('global-section', ['as' => 'global_section', 'uses' => 'Admin\Academics\GlobalSectionController@index'])->middleware('userRolePermission:265');

    Route::post('global-section-store', ['as' => 'global_section_store', 'uses' => 'Admin\Academics\GlobalSectionController@store'])->middleware('userRolePermission:266');
    Route::get('global-section-edit/{id}', ['as' => 'global_section_edit', 'uses' => 'Admin\Academics\GlobalSectionController@edit'])->middleware('userRolePermission:267');
    Route::post('global-section-update', ['as' => 'global_section_update', 'uses' => 'Admin\Academics\GlobalSectionController@update'])->middleware('userRolePermission:267');
    Route::get('global-section-delete/{id}', ['as' => 'global_section_delete', 'uses' => 'Admin\Academics\GlobalSectionController@delete'])->middleware('userRolePermission:268');

    // Class route
    Route::get('global-class', ['as' => 'global_class', 'uses' => 'Admin\Academics\GlobalClassController@index'])->middleware('userRolePermission:261');
    Route::post('global-class-store', ['as' => 'global_class_store', 'uses' => 'Admin\Academics\GlobalClassController@store'])->middleware('userRolePermission:266');
    Route::get('global-class-edit/{id}', ['as' => 'global_class_edit', 'uses' => 'Admin\Academics\GlobalClassController@edit'])->middleware('userRolePermission:263');
    Route::post('global-class-update', ['as' => 'global_class_update', 'uses' => 'Admin\Academics\GlobalClassController@update'])->middleware('userRolePermission:263');
    Route::get('global-class-delete/{id}', ['as' => 'global_class_delete', 'uses' => 'Admin\Academics\GlobalClassController@delete'])->middleware('userRolePermission:264');

            // Subject routes
    Route::get('global-subject', ['as' => 'global_subject', 'uses' => 'Admin\Academics\GlobalSubjectController@index'])->middleware('userRolePermission:global_subject');
    Route::post('global-subject-store', ['as' => 'global_subject_store', 'uses' => 'Admin\Academics\GlobalSubjectController@store'])->middleware('userRolePermission:global_subject_store');
    Route::get('global-subject-edit/{id}', ['as' => 'global_subject_edit', 'uses' => 'Admin\Academics\GlobalSubjectController@edit'])->middleware('userRolePermission:global_subject_edit');
    Route::post('global-subject-update', ['as' => 'global_subject_update', 'uses' => 'Admin\Academics\GlobalSubjectController@update'])->middleware('userRolePermission:global_subject_update');
    Route::get('global-subject-delete/{id}', ['as' => 'global_subject_delete', 'uses' => 'Admin\Academics\GlobalSubjectController@delete'])->middleware('userRolePermission:global_subject_delete');


    //assign subject
    Route::get('global-assign-subject', ['as' => 'global_assign_subject', 'uses' => 'Admin\Academics\GlobalAssignSubjectController@index'])->middleware('userRolePermission:global_assign_subject');

    Route::get('global-assign-subject-create', ['as' => 'global_assign_subject_create', 'uses' => 'Admin\Academics\GlobalAssignSubjectController@create'])->middleware('userRolePermission:global_assign_subject_create');

    Route::post('global-assign-subject-search', ['as' => 'global_assign_subject_search', 'uses' => 'Admin\Academics\GlobalAssignSubjectController@search'])->middleware('userRolePermission:global_assign_subject_search');
    Route::get('global-assign-subject-search', 'Admin\Academics\GlobalAssignSubjectController@create')->name('global-assign-subject-create')->middleware('userRolePermission:global-assign-subject-create');
    Route::post('global-assign-subject-store', 'Admin\Academics\GlobalAssignSubjectController@assignSubjectStore')->name('global_assign-subject-store')->middleware('userRolePermission:global_assign-subject-store');
    Route::get('global-assign-subject-store', 'Admin\Academics\GlobalAssignSubjectController@create');
    Route::post('global-assign-subject', 'Admin\Academics\GlobalAssignSubjectController@assignSubjectFind')->name('global_assign-subject')->middleware('userRolePermission:global_assign-subject');
    Route::get('global-assign-subject-get-by-ajax', 'Admin\Academics\GlobalAssignSubjectController@assignSubjectAjax');
    Route::get('global-get-assigned-subjects', 'Admin\Academics\GlobalAssignSubjectController@loadAssignedSubject')->name('loadAssignedSubject')->middleware('userRolePermission:loadAssignedSubject');;

    Route::post('global-save-assigned-subjects', 'Admin\Academics\GlobalAssignSubjectController@saveAssignedSubject')->name('saveAssignedSubject');

            //Study Material
    Route::get('global-upload-content', 'Admin\Academics\GlobalUploadContentController@index')->name('global-upload-content')->middleware('userRolePermission:global-upload-content');
    Route::post('global-save-upload-content', 'Admin\Academics\GlobalUploadContentController@store')->name('global-save-upload-content')->middleware('userRolePermission:global-save-upload-content');
    
    //
    Route::get('global-upload-content-edit/{id}', 'Admin\Academics\GlobalUploadContentController@uploadContentEdit')->name('global-upload-content-edit')->middleware('userRolePermission:global-upload-content-edit');
    Route::get('global-upload-content-view/{id}', 'Admin\Academics\GlobalUploadContentController@uploadContentView')->name('global-upload-content-view')->middleware('userRolePermission:global-upload-content-view');
    //
    Route::post('global-update-upload-content', 'Admin\Academics\GlobalUploadContentController@updateUploadContent')->name('global-update-upload-content');
    Route::post('global-delete-upload-content', 'Admin\Academics\GlobalUploadContentController@deleteUploadContent')->name('global-delete-upload-content')->middleware('userRolePermission:global-delete-upload-content');
    Route::get('global-upload-content-modal', 'Admin\Academics\GlobalUploadContentController@studyMaterialModal')->name('studyMaterialModal')->middleware('userRolePermission:studyMaterialModal');
    Route::post('assigned-global-upload-content', 'Admin\Academics\GlobalUploadContentController@studyMaterialAssigned')->name('studyMaterialAssigned')->middleware('userRolePermission:studyMaterialAssigned');

    Route::get('global-exam-type', 'Admin\Academics\GlobalExaminationController@exam_type')->name('global_exam-type')->middleware('userRolePermission:global_exam-type');
    Route::get('global-exam-type-edit/{id}', ['as' => 'global_exam_type_edit', 'uses' => 'Admin\Academics\GlobalExaminationController@exam_type_edit'])->middleware('userRolePermission:global_exam_type_edit');
    Route::post('global-exam-type-store', ['as' => 'global_exam_type_store', 'uses' => 'Admin\Academics\GlobalExaminationController@exam_type_store'])->middleware('userRolePermission:global_exam_type_store');
    Route::post('global-exam-type-update', ['as' => 'global_exam_type_update', 'uses' => 'Admin\Academics\GlobalExaminationController@exam_type_update'])->middleware('userRolePermission:global_exam_type_update');
    Route::get('global-exam-type-delete/{id}', ['as' => 'global_exam_type_delete', 'uses' => 'Admin\Academics\GlobalExaminationController@exam_type_delete'])->middleware('userRolePermission:global_exam_type_delete');

    Route::get('global-exam', 'Admin\Academics\GlobalExamController@index')->name('global-exam')->middleware('userRolePermission:global-exam');
    Route::post('global-exam', 'Admin\Academics\GlobalExamController@store')->name('global-exam');
    Route::get('global-exam/{id}', 'Admin\Academics\GlobalExamController@show')->name('global-exam-edit')->middleware('userRolePermission:global-exam-edit');
    Route::put('global-exam/{id}', 'Admin\Academics\GlobalExamController@update')->name('global-exam-update')->middleware('userRolePermission:global-exam-update');
    Route::delete('global-exam/{id}', 'Admin\Academics\GlobalExamController@destroy')->name('global-exam-delete')->middleware('userRolePermission:global-exam-delete');

    Route::get('return_global_exam_view', 'Admin\Academics\GlobalExamController@examView')->name('global-examView')->middleware('userRolePermission:global-examView');

    Route::get('global-assign', 'Admin\Academics\GlobalAssignSubjectController@globalAssign')->name('global-assign')->middleware('userRolePermission:global-assign');
    Route::post('global-save-assigned', 'Admin\Academics\GlobalAssignSubjectController@saveAssignedSubject')->name('saveAssignedSubject')->middleware('userRolePermission:saveAssignedSubject');


    Route::get('complaint-list-datatable', 'DatatableQueryController@complaintDetailsDatatable')->name('complaint_list_datatable');
    Route::get('unassign-student-list-datatable', 'DatatableQueryController@unAssignStudentList')->name('unassign-student-list-datatable');
    Route::get('disable-student-list-datatable', 'DatatableQueryController@disableStudentList')->name('disable-student-list-datatable');
    Route::get('upload-content-list-datatable', 'DatatableQueryController@uploadContentListDatatable')->name('upload-content-list-datatable');
    Route::get('other-download-list-datatable', 'DatatableQueryController@otherDownloadList')->name('other-download-list-datatable');
    Route::get('get-fees-payment-ajax', 'DatatableQueryController@ajaxFeesPayment')->name('ajaxFeesPayment');
    Route::get('get-bank-slip-ajax', 'DatatableQueryController@ajaxBankSlip')->name('ajaxBankSlip');
    Route::get('get-income-list-ajax', 'DatatableQueryController@ajaxIncomeList')->name('ajaxIncomeList');
    Route::get('get-expense-list-ajax', 'DatatableQueryController@ajaxExpenseList')->name('ajaxExpenseList');
    Route::get('pending-leave-request-ajax', 'DatatableQueryController@ajaxPendingLeave')->name('ajaxPendingLeave');

    Route::get('approve-leave-request-ajax', 'DatatableQueryController@ajaxApproveLeave')->name('ajaxApproveLeave');
    Route::get('homework-list-ajax', 'DatatableQueryController@homeworkListAjax')->name('homework-list-ajax')->middleware('userRolePermission:homework-list');
    Route::get('book-list-ajax', 'DatatableQueryController@bookListAjax')->name('book-list-ajax');
    Route::get('all-issed-book-ajax', 'DatatableQueryController@allIssuedBookAjax')->name('all-issed-book-ajax');
    Route::get('item-list-ajax', 'DatatableQueryController@itemsListAjax')->name('item-list-ajax');
    Route::get('item-receive-list-ajax', 'DatatableQueryController@itemReceiveListAjax')->name('item-receive-list-ajax');

    Route::get('student-transport-report-ajax',  'DatatableQueryController@studentTransportReportAjax')->name('studentTransportReportAjax');


    Route::get('due_fees_login_permission',  'Admin\FeesCollection\DueFeesLoginPermissionController@index')->name('due_fees_login_permission')->middleware('userRolePermission:due_fees_login_permission');
    Route::post('due_fees_login_permission',  'Admin\FeesCollection\DueFeesLoginPermissionController@search')->name('due_fees_login_permission')->middleware('userRolePermission:due_fees_login_permission');
    Route::get('due_fees_login_permission_store',  'Admin\FeesCollection\DueFeesLoginPermissionController@store')->name('due_fees_login_permission_store')->middleware('userRolePermission:due_fees_login_permission');
    Route::get('file_make' , function(){
       return $data = $str=file_get_contents('my.txt');
    });


    Route::get('frontend-page-builder',  'PageBuilderController@index')->name('frontend-page-builder');

    Route::get('exam-signature-settings',  'ExamSignatureSettingsController@index')->name('exam-signature-settings');
    Route::post('exam-signature-settings/store',  'ExamSignatureSettingsController@store')->name('exam-signature-settings-store');
    Route::post('exam-signature-settings/update',  'ExamSignatureSettingsController@update')->name('exam-signature-settings-update');
    
    // Fees Carry Forward
    Route::get('fees-carry-forward-settings-view', [SmFeesCarryForwardController::class, 'feesCarryForwardSettingsView'])->name('fees-carry-forward-settings-view');
    Route::get('fees-carry-forward-settings-view-fees-collection', [SmFeesCarryForwardController::class, 'feesCarryForwardSettingsView'])->name('fees-carry-forward-settings-view-fees-collection');
    Route::post('fees-carry-forward-settings-store', [SmFeesCarryForwardController::class, 'feesCarryForwardSettings'])->name('fees-carry-forward-settings-store');
    
    Route::get('fees-carry-forward-view', [SmFeesCarryForwardController::class, 'feesCarryForward'])->name('fees-carry-forward-view');
    Route::get('fees-carry-forward-view-fees-collection', [SmFeesCarryForwardController::class, 'feesCarryForward'])->name('fees-carry-forward-view-fees-collection');
    Route::post('fees-carry-forward-search', [SmFeesCarryForwardController::class, 'feesCarryForwardSearch'])->name('fees-carry-forward-search');
    Route::post('fees-carry-forward-store', [SmFeesCarryForwardController::class, 'feesCarryForwardStore'])->name('fees-carry-forward-store');
    
    Route::get('fees-carry-forward-log-view', [SmFeesCarryForwardController::class, 'feesCarryForwardLogView'])->name('fees-carry-forward-log-view');
    Route::get('fees-carry-forward-log-view-fees-collection', [SmFeesCarryForwardController::class, 'feesCarryForwardLogView'])->name('fees-carry-forward-log-view-fees-collection');
    Route::get('fees-carry-forward-log-search', [SmFeesCarryForwardController::class, 'feesCarryForwardLogSearch'])->name('fees-carry-forward-log-search');


});
