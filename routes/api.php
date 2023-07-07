<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('db-correction', 'SmApiController@dbCorrections');
Route::post('deviceInfo', 'api\ApiSmStudentAttendanceController@deviceInfo');
// Route::post('system-disable', 'SmApiController@systemDisbale');

   // admin section visitor
    Route::any('login', 'SmApiController@mobileLogin');
    Route::get('user-demo', 'SmApiController@DemoUser');
    Route::any('saas-login', 'SmApiController@saasLogin');

   
    Route::any('login', 'SmApiController@mobileLogin');

    Route::get('user-permission/{role_id}/{school_id}/{is_saas}', 'SmApiController@userPermission');
    
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('auth/logout', 'api\SmAdminController@logout');
    });

Route::group(['middleware' => ['XSS','auth:api','json.response']], function () {
    Route::get('send-sms', 'SmApiController@SendSMS');
    Route::post('user_delete', 'SmApiController@deleteUser'); 
    Route::get('sync', 'SmApiController@sync');
    Route::get('set-fcm-token', 'SmApiController@setFcmToken');

    Route::get('privacy-permission/{id}', 'SmApiController@privacyPermission');
    Route::get('privacy-permission-status', 'SmApiController@privacyPermissionStatus');

    Route::get('force-sample-data/{email}','SmApiController@sample_data');
    Route::get('migrate/{email}','SmApiController@sample_migrate');
    Route::get('seed/{email}','SmApiController@sample_seed');



    // payment process and call back 

    Route::post('payment-data-save','api\SmPaymentGatewayController@dataSave');
    Route::post('payment-success-callback','api\SmPaymentGatewayController@successCallback');

    // -------------------Start admin Module------------------
    Route::any('is-enabled', 'SmApiController@checkColumnAvailable');

 
    Route::any('schools', 'SmApiController@allSchools');


    Route::get('class-id/{id}', 'SmApiController@get_class_name');
    Route::get('school/{school_id}/class-id/{id}', 'SmApiController@saas_get_class_name');
    Route::get('section-id/{id}', 'SmApiController@get_section_name');
    Route::get('school/{school_id}/section-id/{id}', 'SmApiController@saas_get_section_name');
    Route::get('teacher-id/{id}', 'SmApiController@get_teacher_name');
    Route::get('school/{school_id}/teacher-id/{id}', 'SmApiController@saas_get_teacher_name');
    Route::get('subject-id/{id}', 'SmApiController@get_subject_name');
    Route::get('school/{school_id}/subject-id/{id}', 'SmApiController@saas_get_subject_name');
    Route::get('room-id/{id}', 'SmApiController@get_room_name');
    Route::get('school/{school_id}/room-id/{id}', 'SmApiController@saas_get_room_name');
    Route::get('class-period-id/{id}', 'SmApiController@get_class_period_name');
    Route::get('school/{school_id}/class-period-id/{id}', 'SmApiController@saas_get_class_period_name');


    Route::get('visitor', ['as' => 'visitor', 'uses' => 'SmApiController@visitor_index']);
    Route::get('school/{school_id}/visitor', ['as' => 'saas_visitor', 'uses' => 'SmApiController@saas_visitor_index']);
    Route::post('visitor-store', ['as' => 'visitor_store', 'uses' => 'SmApiController@visitor_store']);
    Route::post('saas-visitor-store', ['as' => 'saas_visitor_store', 'uses' => 'SmApiController@saas_visitor_store']);
    Route::get('visitor-edit/{id}', ['as' => 'visitor_edit', 'uses' => 'SmApiController@visitor_edit']);
    Route::get('school/{school_id}/visitor-edit/{id}', ['as' => 'saas_visitor_edit', 'uses' => 'SmApiController@saas_visitor_edit']);

    Route::post('visitor-update', ['as' => 'visitor_update', 'uses' => 'SmApiController@visitor_update']);
    Route::post('saas-visitor-update', ['as' => 'saas_visitor_update', 'uses' => 'SmApiController@saas_visitor_update']);
    Route::get('visitor-delete/{id}', ['as' => 'visitor_delete', 'uses' => 'SmApiController@visitor_delete']);
    Route::get('school/{school_id}/visitor-delete/{id}', ['as' => 'saas_visitor_delete', 'uses' => 'SmApiController@saas_visitor_delete']);




    // admin section complaint
    Route::get('complaint', 'SmApiController@complaint');
    Route::post('complaint-store', 'SmApiController@complaintStore');


    Route::get('complaint', 'SmApiController@complaint_index');
    Route::get('school/{school_id}/complaint', 'SmApiController@saas_complaint_index');
    Route::post('complaint-store', 'SmApiController@complaint_store');
    Route::post('saas-complaint-store', 'SmApiController@saas_complaint_store');
    Route::get('complaint-edit/{id}', 'SmApiController@complaint_edit');
    Route::get('school/{school_id}/complaint-edit/{id}', 'SmApiController@saas_complaint_edit');
    Route::post('complaint-update', 'SmApiController@complaint_update');
    Route::post('saas-complaint-update', 'SmApiController@saas_complaint_update');
    Route::get('complaint-delete/{id}', 'SmApiController@complaint_update');


    // Admin section postal-receive

    Route::get('postal-receive', 'SmApiController@postal_receive_index');
    Route::get('school/{school_id}/postal-receive', 'SmApiController@saas_postal_receive_index');
    Route::post('postal-receive-store', 'SmApiController@postal_receive_store');
    Route::post('saas-postal-receive-store', 'SmApiController@saas_postal_receive_store');
    Route::post('postal-receive-edit/{id}', 'SmApiController@postal_receive_show');
    Route::post('school/{school_id}/postal-receive-edit/{id}', 'SmApiController@saas_postal_receive_show');
    Route::post('postal-receive-update', 'SmApiController@postal_receive_update');
    Route::post('saas-postal-receive-update', 'SmApiController@saas_postal_receive_update');
    Route::get('postal-receive-delete/{id}', 'SmApiController@postal_receive_destroy');
    Route::get('school/{school_id}/postal-receive-delete/{id}', 'SmApiController@saas_postal_receive_destroy');


    // Admin section postal-dispatch
    Route::get('postal-dispatch', 'SmApiController@postal_dispatch_index');
    Route::get('school/{school_id}/postal-dispatch', 'SmApiController@saas_postal_dispatch_index');
    Route::post('postal-dispatch-store', 'SmApiController@postal_dispatch_store');
    Route::post('saas-postal-dispatch-store', 'SmApiController@saas_postal_dispatch_store');
    Route::get('postal-dispatch-edit/{id}', 'SmApiController@postal_dispatch_show');
    Route::get('school/{school_id}/postal-dispatch-edit/{id}', 'SmApiController@saas_postal_dispatch_show');
    Route::post('postal-dispatch-update', 'SmApiController@postal_dispatch_update');
    Route::post('saas-postal-dispatch-update', 'SmApiController@saas_postal_dispatch_update');
    Route::get('postal-dispatch-delete/{id}', 'SmApiController@postal_dispatch_destroy');
    Route::get('school/{school_id}/postal-dispatch-delete/{id}', 'SmApiController@saas_postal_dispatch_destroy');
    // Phone Call Log
    Route::resource('phone-call', 'api\ApiSmPhoneCallLogController');

    // Admin Setup
    Route::resource('setup-admin', 'api\ApiSmSetupAdminController');
    Route::get('setup-admin-delete/{id}', 'SmApiController@setup_admin_destroy');

    // -------------------End admin Module------------------


    // -----------Start Student Information---------------
    // student list
    Route::get('student-list', ['as' => 'student_list', 'uses' => 'SmApiController@studentDetails']);
    Route::get('school/{school_id}/student-list', ['as' => 'saas_student_list', 'uses' => 'SmApiController@saas_studentDetails']);

    // student search

    Route::any('student-list-search', 'SmApiController@studentDetailsSearch');
    // Route::get('student-list-search', 'SmApiController@student_search_Details');
    Route::get('school/{school_id}/student-list-search', 'SmApiController@saas_student_search_Details');

    // student list
    Route::get('student-view/{id}', ['as' => 'student_view', 'uses' => 'SmApiController@studentView']);
    Route::get('school/{school_id}/student-view/{id}', ['as' => 'saas_student_view', 'uses' => 'SmApiController@saas_studentView']);
    // student delete
    Route::any('student-delete', ['as' => 'student_delete', 'uses' => 'SmApiController@studentDelete']);
    Route::any('school/{school_id}/student-delete', ['as' => 'saas_student_delete', 'uses' => 'SmApiController@saas_studentDelete']);
    // student edit
    Route::get('student-edit/{id}', ['as' => 'student_edit', 'uses' => 'SmApiController@studentEdit']);
    Route::get('school/{school_id}/student-edit/{id}', ['as' => 'saas_student_edit', 'uses' => 'SmApiController@saas_studentEdit']);


    // Student Attendance
    Route::get('student-attendance', ['as' => 'student_attendance', 'uses' => 'api\ApiSmStudentAttendanceController@student_attendance_index']);
    Route::get('school/{school_id}/student-attendance', ['as' => 'saas_student_attendance', 'uses' => 'api\ApiSmStudentAttendanceController@saas_student_attendance_index']);
    Route::post('student-search', 'api\ApiSmStudentAttendanceController@studentSearch');
    Route::post('school/{school_id}/student-search', 'api\ApiSmStudentAttendanceController@saaas_studentSearch');
    Route::get('student-search', 'api\ApiSmStudentAttendanceController@student_search_index');
    Route::get('school/{school_id}/student-search', 'api\ApiSmStudentAttendanceController@saas_student_search_index');

    Route::post('student-attendance-store', 'api\ApiSmStudentAttendanceController@studentAttendanceStore');
    Route::post('saas-student-attendance-store', 'api\ApiSmStudentAttendanceController@saas_studentAttendanceStore');

    Route::get('student-attendance-check', 'api\ApiSmStudentAttendanceController@studentAttendanceCheck');
    Route::get('school/{school_id}/student-attendance-check', 'api\ApiSmStudentAttendanceController@saas_studentAttendanceCheck');
    Route::get('student-attendance-store-first', 'api\ApiSmStudentAttendanceController@studentAttendanceStoreFirst');
    Route::get('school/{school_id}/student-attendance-store-first', 'api\ApiSmStudentAttendanceController@saas_studentAttendanceStoreFirst');
    Route::get('student-attendance-store-second', 'api\ApiSmStudentAttendanceController@studentAttendanceStoreSecond');
    Route::get('school/{school_id}/student-attendance-store-second', 'api\ApiSmStudentAttendanceController@saas_studentAttendanceStoreSecond');

    
    //Subject Wise Attendance

    Route::get('section-subject', 'api\SubjectWiseAttendanceController@SelectSubject');
    Route::get('attendance/search-student', 'api\SubjectWiseAttendanceController@studentSearch');
    Route::get('attendance/store', 'api\SubjectWiseAttendanceController@studentAttendanceStore');

    Route::get('student-subject-attendance-check', 'api\SubjectWiseAttendanceController@studentAttendanceCheck');
    Route::get('student-subject-attendance-store-first', 'api\SubjectWiseAttendanceController@studentAttendanceStoreFirst');
    Route::get('student-subject-attendance-store-second', 'api\SubjectWiseAttendanceController@studentAttendanceStoreSecond');


    // Student Attendance Report
    Route::get('student-attendance-report', ['as' => 'student_attendance_report', 'uses' => 'api\ApiSmStudentAttendanceController@studentAttendanceReport']);
    Route::get('school/{school_id}/student-attendance-report', ['as' => 'saas_student_attendance_report', 'uses' => 'api\ApiSmStudentAttendanceController@saas_studentAttendanceReport']);

    Route::post('student-attendance-report-search', ['as' => 'student_attendance_report_search', 'uses' => 'api\ApiSmStudentAttendanceController@studentAttendanceReportSearch']);
    Route::post('school/{school_id}/student-attendance-report-search', ['as' => 'saas_student_attendance_report_search', 'uses' => 'api\ApiSmStudentAttendanceController@saas_studentAttendanceReportSearch']);
    Route::get('student-attendance-report-search', 'api\ApiSmStudentAttendanceController@studentAttendanceReport_search');
    Route::get('school/{school_id}/student-attendance-report-search', 'api\ApiSmStudentAttendanceController@saas_studentAttendanceReport_search');

    // Student Category
    Route::get('student-category', ['as' => 'student_category', 'uses' => 'SmApiController@student_type_index']);
    Route::get('school/{school_id}/student-category', ['as' => 'saas_student_category', 'uses' => 'SmApiController@saas_student_type_index']);
    Route::post('student-category-store', ['as' => 'student_category_store', 'uses' => 'SmApiController@student_type_store']);
    Route::post('saas-student-category-store', ['as' => 'saas_student_category_store', 'uses' => 'SmApiController@saas_student_type_store']);
    Route::get('student-category-edit/{id}', ['as' => 'student_category_edit', 'uses' => 'SmApiController@student_type_edit']);
    Route::get('school/{school_id}/student-category-edit/{id}', ['as' => 'saas_student_category_edit', 'uses' => 'SmApiController@saas_student_type_edit']);
    Route::post('student-category-update', ['as' => 'student_category_update', 'uses' => 'SmApiController@student_type_update']);
    Route::post('saas-student-category-update', ['as' => 'saas_student_category_update', 'uses' => 'SmApiController@saas_student_type_update']);
    Route::get('student-category-delete/{id}', ['as' => 'student_category_delete', 'uses' => 'SmApiController@student_type_delete']);
    Route::get('school/{school_id}/student-category-delete/{id}', ['as' => 'saas_student_category_delete', 'uses' => 'SmApiController@saas_student_type_delete']);


    // Student Group Routes
    Route::get('student-group', ['as' => 'student_group', 'uses' => 'SmApiController@student_group_index']);
    Route::get('school/{school_id}/student-group', ['as' => 'saas_student_group', 'uses' => 'SmApiController@saas_student_group_index']);
    Route::post('student-group-store', ['as' => 'student_group_store', 'uses' => 'SmApiController@student_group_store']);
    Route::post('saas-student-group-store', ['as' => 'saas_student_group_store', 'uses' => 'SmApiController@saas_student_group_store']);
    Route::get('student-group-edit/{id}', ['as' => 'student_group_edit', 'uses' => 'SmApiController@student_group_edit']);
    Route::get('school/{school_id}/student-group-edit/{id}', ['as' => 'saas_student_group_edit', 'uses' => 'SmApiController@saas_student_group_edit']);
    Route::post('student-group-update', ['as' => 'student_group_update', 'uses' => 'SmApiController@student_group_update']);
    Route::post('saas-student-group-update', ['as' => 'saas_student_group_update', 'uses' => 'SmApiController@saas_student_group_update']);
    Route::get('student-group-delete/{id}', ['as' => 'student_group_delete', 'uses' => 'SmApiController@student_group_delete']);
    Route::get('school/{school_id}/student-group-delete/{id}', ['as' => 'saas_student_group_delete', 'uses' => 'SmApiController@saas_student_group_delete']);


    // Student Promote search
    Route::get('student-promote', ['as' => 'student_promote', 'uses' => 'SmApiController@studentPromote_index']);
    Route::get('school/{school_id}/student-promote', ['as' => 'saas_student_promote', 'uses' => 'SmApiController@saas_studentPromote_index']);

    Route::get('student-current-search', 'SmApiController@studentPromote');
    Route::get('school/{school_id}/student-current-search', 'SmApiController@saas_studentPromote');
    Route::post('student-current-search', 'SmApiController@studentCurrentSearch');
    Route::post('school/{school_id}/student-current-search', 'SmApiController@saas_studentCurrentSearch');
    Route::get('view-academic-performance/{id}', 'SmApiController@view_academic_performance');


    // // Student Promote Store
    Route::get('student-promote-store', 'SmApiController@studentPromote_store');
    Route::get('school/{school_id}/student-promote-store', 'SmApiController@saas_studentPromote_store');
    Route::post('student-promote-store', 'SmApiController@studentPromoteStore');

    // Disabled Student
    Route::get('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmApiController@disabledStudent']);
    Route::get('school/{school_id}/disabled-student', ['as' => 'saas_disabled_student', 'uses' => 'SmApiController@saas_disabledStudent']);
    Route::post('disabled-student', ['as' => 'post_disabled_student', 'uses' => 'SmApiController@disabledStudentSearch']);
    Route::post('school/{school_id}/disabled-student', ['as' => 'saas_disabled_student_post', 'uses' => 'SmApiController@saas_disabledStudentSearch']);
    // -----------End Student Information---------------

    // -------------------Teacher Module------------------
    // Start Upload Content
    Route::get('upload-content', 'SmApiController@uploadContentList');
    Route::get('school/{school_id}/upload-content', 'SmApiController@saas_uploadContentList');
    Route::post('save-upload-content', 'SmApiController@saveUploadContent'); // incomplete for API
    Route::get('delete-upload-content/{id}', 'SmApiController@deleteUploadContent');
    Route::get('school/{school_id}/delete-upload-content/{id}', 'SmApiController@saas_deleteUploadContent');
    Route::get('upload-content-view/{id}', 'api\ApiSmTeacherController@viewContent'); 
  
    // Start rest of the routes
    Route::get('assignment-list', 'SmApiController@assignmentList');
    Route::get('school/{school_id}/assignment-list', 'SmApiController@saas_assignmentList');
    Route::get('study-metarial-list', 'SmApiController@studyMetarialList');
    Route::get('school/{school_id}/study-metarial-list', 'SmApiController@saas_studyMetarialList');
    Route::get('syllabus-list', 'SmApiController@syllabusList');
    Route::get('school/{school_id}/syllabus-list', 'SmApiController@saas_syllabusList');
    Route::get('other-download-list', 'SmApiController@otherDownloadList');
    Route::get('school/{school_id}/other-download-list', 'SmApiController@saas_otherDownloadList');
    // End rest of the routes

    // ------------------- End Teacher Module------------------
    //--------------------HomwWork ----------------------
    Route::get('homework-list', ['as' => 'homework-list', 'uses' => 'api\ApiSmHomeWorkController@homeworkList']);
    Route::get('add-homeworks','api\ApiSmHomeWorkController@addHomework');
    Route::post('save-homework-data', ['as' => 'saveHomeworkData', 'uses' => 'api\ApiSmHomeWorkController@saveHomeworkData']);

  
    // ------------------End HomeWork -----------------


    //--------------- Start Fees Collection --------------

    // Collect Fees
    Route::get('collect-fees', ['as' => 'collect_fees', 'uses' => 'SmApiController@collectFees']);
    Route::get('school/{school_id}/collect-fees', ['as' => 'saas_collect_fees', 'uses' => 'SmApiController@saas_collectFees']);
    Route::get('fees-collect-student-wise/{id}', ['as' => 'fees_collect_student_wise', 'uses' => 'SmApiController@collectFeesStudentApi']);
    Route::get('school/{school_id}/fees-collect-student-wise/{id}', ['as' => 'saas_fees_collect_student_wise', 'uses' => 'SmApiController@saas_collectFeesStudentApi']);
    Route::post('collect-fees', ['as' => 'collect_fees_post', 'uses' => 'SmApiController@collectFeesSearch']);

    //Search Fees Payment
    Route::get('search-fees-payment', ['as' => 'search_fees_payment', 'uses' => 'SmApiController@searchFeesPayment']);
    Route::get('school/{school_id}/search-fees-payment', ['as' => 'saas_search_fees_payment', 'uses' => 'SmApiController@saas_searchFeesPayment']);
    Route::post('fees-payment-search', ['as' => 'fees_payment_search_post', 'uses' => 'SmApiController@feesPaymentSearch']);
    Route::post('school/{school_id}/fees-payment-search', ['as' => 'saas_fees_payment_search_post', 'uses' => 'SmApiController@saas_feesPaymentSearch']);
    Route::get('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'SmApiController@search_Fees_Payment']);
    Route::get('school/{school_id}/fees-payment-search', ['as' => 'saas_fees_payment_search', 'uses' => 'SmApiController@saas_search_Fees_Payment']);

    //Fees Search due
    Route::get('search-fees-due', ['as' => 'search_fees_due', 'uses' => 'SmApiController@searchFeesDue']);
    Route::get('school/{school_id}/search-fees-due', ['as' => 'saas_search_fees_due', 'uses' => 'SmApiController@saas_searchFeesDue']);
    Route::post('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'SmApiController@feesDueSearch']);
    Route::post('school/{school_id}/fees-due-search', ['as' => 'saas_fees_due_search', 'uses' => 'SmApiController@saas_feesDueSearch']);
    Route::get('fees-due-search', ['as' => 'fees_due_search_get', 'uses' => 'SmApiController@search_FeesDue']);
    Route::get('school/{school_id}/fees-due-search', ['as' => 'saas_fees_due_search_get', 'uses' => 'SmApiController@saas_search_FeesDue']);


    // Route::resource('fees-master', 'SmFeesMasterController');
    Route::post('fees-master-single-delete', 'SmApiController@deleteSingle');
    Route::post('school/{school_id}/fees-master-single-delete', 'SmApiController@saas_deleteSingle');
    Route::post('fees-master-group-delete', 'SmApiController@deleteGroup');
    Route::post('school/{school_id}/fees-master-group-delete', 'SmApiController@saas_deleteGroup');
    Route::get('fees-assign/{id}', ['as' => 'fees_assign', 'uses' => 'SmApiController@feesAssign']);
    Route::get('school/{school_id}/fees-assign/{id}', ['as' => 'saas_fees_assign', 'uses' => 'SmApiController@saas_feesAssign']);
    Route::get('fees-assign/{id}', ['as' => 'fees_assign_get', 'uses' => 'SmApiController@fees_Assign']);
    Route::get('school/{school_id}/fees-assign/{id}', ['as' => 'saas_fees_assign_get', 'uses' => 'SmApiController@saas_fees_Assign']);
    Route::post('fees-assign-search', 'SmApiController@feesAssignSearch');
    Route::post('school/{school_id}/fees-assign-search', 'SmApiController@saas_feesAssignSearch');

    // Fees Master
    Route::get('fees-master-store', ['as' => 'fees_master_add', 'uses' => 'SmApiController@feesMasterStore']);
    Route::get('school/{school_id}/fees-master-store', ['as' => 'saas_fees_master_add', 'uses' => 'SmApiController@saas_feesMasterStore']);
    Route::get('fees-master-update', ['as' => 'fees_master_update', 'uses' => 'SmApiController@feesMasterUpdate']);
    Route::get('school/{school_id}/fees-master-update', ['as' => 'saas_fees_master_update', 'uses' => 'SmApiController@saas_feesMasterUpdate']);

    // Fees Group routes
    Route::get('fees-group', ['as' => 'fees_group', 'uses' => 'api\ApiSmFeesGroupController@fees_group_index']);
    Route::get('school/{school_id}/fees-group', ['as' => 'saas_fees_group', 'uses' => 'api\ApiSmFeesGroupController@saas_fees_group_index']);
    Route::get('fees-group-store', ['as' => 'fees_group_store', 'uses' => 'api\ApiSmFeesGroupController@fees_group_store']);
    Route::get('school/{school_id}/fees-group-store', ['as' => 'saas_fees_group_store', 'uses' => 'api\ApiSmFeesGroupController@saas_fees_group_store']);
    Route::get('fees-group-edit/{id}', ['as' => 'fees_group_edit', 'uses' => 'api\ApiSmFeesGroupController@fees_group_edit']);
    Route::get('school/{school_id}/fees-group-edit/{id}', ['as' => 'saas_fees_group_edit', 'uses' => 'api\ApiSmFeesGroupController@saas_fees_group_edit']);
    Route::get('fees-group-update', ['as' => 'fees_group_update', 'uses' => 'api\ApiSmFeesGroupController@fees_group_update']);
    Route::get('school/{school_id}/fees-group-update', ['as' => 'saas_fees_group_update', 'uses' => 'api\ApiSmFeesGroupController@saas_fees_group_update']);
    Route::post('fees-group-delete', ['as' => 'fees_group_delete', 'uses' => 'api\ApiSmFeesGroupController@fees_group_delete']);
    Route::post('school/{school_id}/fees-group-delete', ['as' => 'saas_fees_group_delete', 'uses' => 'api\ApiSmFeesGroupController@saas_fees_group_delete']);

    // Fees type routes
    Route::get('fees-type', ['as' => 'fees_type', 'uses' => 'SmApiController@fees_type_index']);
    Route::get('school/{school_id}/fees-type', ['as' => 'saas_fees_type', 'uses' => 'SmApiController@saas_fees_type_index']);
    Route::post('fees-type-store', ['as' => 'fees_type_store', 'uses' => 'SmApiController@fees_type_store']);
    Route::post('saas-fees-type-store', ['as' => 'saas_fees_type_store', 'uses' => 'SmApiController@saas_fees_type_store']);
    Route::get('fees-type-edit/{id}', ['as' => 'fees_type_edit', 'uses' => 'SmApiController@fees_type_edit']);
    Route::get('school/{school_id}/fees-type-edit/{id}', ['as' => 'saas_fees_type_edit', 'uses' => 'SmApiController@saas_fees_type_edit']);
    Route::post('fees-type-update', ['as' => 'fees_type_update', 'uses' => 'SmApiController@fees_type_update']);
    Route::post('saas-fees-type-update', ['as' => 'saas_fees_type_update', 'uses' => 'SmApiController@saas_fees_type_update']);
    Route::get('fees-type-delete/{id}', ['as' => 'fees_type_delete', 'uses' => 'SmApiController@fees_type_delete']);
    Route::get('school/{school_id}/fees-type-delete/{id}', ['as' => 'saas_fees_type_delete', 'uses' => 'SmApiController@saas_fees_type_delete']);

    // Fees Discount routes
    Route::get('fees-discount', ['as' => 'fees_discount', 'uses' => 'SmApiController@fees_discount_index']);
    Route::get('school/{school_id}/fees-discount', ['as' => 'saas_fees_discount', 'uses' => 'SmApiController@saas_fees_discount_index']);
    Route::post('fees-discount-store', ['as' => 'fees_discount_store', 'uses' => 'SmApiController@fees_discount_store']);
    Route::post('saas-fees-discount-store', ['as' => 'saas_fees_discount_store', 'uses' => 'SmApiController@saas_fees_discount_store']);
    Route::get('fees-discount-edit/{id}', ['as' => 'fees_discount_edit', 'uses' => 'SmApiController@fees_discount_edit']);
    Route::get('school/{school_id}/fees-discount-edit/{id}', ['as' => 'saas_fees_discount_edit', 'uses' => 'SmApiController@saas_fees_discount_edit']);
    Route::post('fees-discount-update', ['as' => 'fees_discount_update', 'uses' => 'SmApiController@fees_discount_update']);
    Route::post('saas-fees-discount-update', ['as' => 'saas_fees_discount_update', 'uses' => 'SmApiController@saas_fees_discount_update']);
    Route::get('fees-discount-delete/{id}', ['as' => 'fees_discount_delete', 'uses' => 'SmApiController@fees_discount_delete']);
    Route::get('school/{school_id}/fees-discount-delete/{id}', ['as' => 'saas_fees_discount_delete', 'uses' => 'SmApiController@saas_fees_discount_delete']);
    Route::get('fees-discount-assign/{id}', ['as' => 'fees_discount_assign', 'uses' => 'SmApiController@feesDiscountAssign']);
    Route::get('school/{school_id}/fees-discount-assign/{id}', ['as' => 'saas_fees_discount_assign', 'uses' => 'SmApiController@saas_feesDiscountAssign']);
    Route::post('fees-discount-assign-search', 'SmApiController@feesDiscountAssignSearch');
    Route::post('school/{school_id}/fees-discount-assign-search', 'SmApiController@saas_feesDiscountAssignSearch');
    Route::get('fees-discount-assign-store', 'SmApiController@feesDiscountAssignStore');
    Route::get('school/{school_id}/fees-discount-assign-store', 'SmApiController@saas_feesDiscountAssignStore');

    Route::get('fees-generate-modal/{amount}/{student_id}/{type}', 'SmApiController@feesGenerateModal');
    Route::get('school/{school_id}/fees-generate-modal/{amount}/{student_id}/{type}', 'SmApiController@saas_feesGenerateModal');
    Route::get('fees-discount-amount-search', 'SmApiController@feesDiscountAmountSearch');
    Route::get('school/{school_id}/fees-discount-amount-search', 'SmApiController@saas_feesDiscountAmountSearch');
    // delete fees payment
    Route::post('fees-payment-delete', 'SmApiController@feesPaymentDelete');
    Route::post('school/{school_id}/fees-payment-delete', 'SmApiController@saas_feesPaymentDelete');

    // Fees carry forward
    Route::get('fees-forward', ['as' => 'fees_forward', 'uses' => 'SmApiController@feesForward']);
    Route::get('school/{school_id}/fees-forward', ['as' => 'saas_fees_forward', 'uses' => 'SmApiController@saas_feesForward']);
    Route::post('fees-forward-search', 'SmApiController@feesForwardSearch');
    Route::post('school/{school_id}/fees-forward-search', 'SmApiController@saas_feesForwardSearch');
    Route::get('fees-forward-search', 'SmApiController@fees_Forward');
    Route::get('school/{school_id}/fees-forward-search', 'SmApiController@saas_fees_Forward');

    Route::post('fees-forward-store', 'SmApiController@feesForwardStore');
    Route::post('school/{school_id}/fees-forward-store', 'SmApiController@saas_feesForwardStore');
    Route::get('fees-forward-store', 'SmApiController@Fees_fward');
    Route::get('school/{school_id}/fees-forward-store', 'SmApiController@saas_Fees_fward');

    //--------------- End Fees Collection --------------


    //--------------- Start Accounts Modules --------------

    // Profit of account
    Route::get('profit', ['as' => 'profit', 'uses' => 'SmApiController@profit']);
    Route::get('school/{school_id}/profit', ['as' => 'saas_profit', 'uses' => 'SmApiController@saas_profit']);
    Route::post('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'SmApiController@searchProfitByDate']);
    Route::post('school/{school_id}/search-profit-by-date', ['as' => 'saas_search_profit_by_date', 'uses' => 'SmApiController@saas_searchProfitByDate']);
    Route::get('search-profit-by-date', ['as' => 'search_profit_by_date_get', 'uses' => 'SmApiController@Accounts_Profit']);
    Route::get('school/{school_id}/search-profit-by-date', ['as' => 'saas_search_profit_by_date_get', 'uses' => 'SmApiController@saas_Accounts_Profit']);

    // add income routes
    Route::get('add-income', ['as' => 'add_income', 'uses' => 'SmApiController@income_index']);
    Route::get('school/{school_id}/add-income', ['as' => 'saas_add_income', 'uses' => 'SmApiController@saas_income_index']);
    Route::post('add-income-store', ['as' => 'add_income_store', 'uses' => 'SmApiController@income_store']);
    Route::post('saas-add-income-store', ['as' => 'saas_add_income_store', 'uses' => 'SmApiController@saas_income_store']);
    Route::get('add-income-edit/{id}', ['as' => 'add_income_edit', 'uses' => 'SmApiController@income_edit']);
    Route::get('school/{school_id}/add-income-edit/{id}', ['as' => 'saas_add_income_edit', 'uses' => 'SmApiController@saas_income_edit']);
    Route::post('add-income-update', ['as' => 'add_income_update', 'uses' => 'SmApiController@income_update']);
    Route::post('saas-add-income-update', ['as' => 'saas_add_income_update', 'uses' => 'SmApiController@saas_income_update']);
    Route::post('add-income-delete', ['as' => 'add_income_delete', 'uses' => 'SmApiController@income_delete']);
    Route::post('school/{school_id}/add-income-delete', ['as' => 'saas_add_income_delete', 'uses' => 'SmApiController@saas_income_delete']);

    // Add Expense
    Route::resource('add-expense', 'api\ApiSmAddExpenseController');

    //payment method
    Route::get('payment-method', ['as' => 'payment_method', 'uses' => 'SmApiController@payment_index']);
    Route::get('school/{school_id}/payment-method', ['as' => 'saas_payment_method', 'uses' => 'SmApiController@saas_payment_index']);
    Route::post('payment-method-store', ['as' => 'payment_method_store', 'uses' => 'SmApiController@payment_store']);
    Route::post('saas-payment-method-store', ['as' => 'saas_payment_method_store', 'uses' => 'SmApiController@saas_payment_store']);
    Route::get('payment-method-edit/{id}', ['as' => 'payment_method_edit', 'uses' => 'SmApiController@payment_edit']);
    Route::get('school/{school_id}/payment-method-edit/{id}', ['as' => 'saas_payment_method_edit', 'uses' => 'SmApiController@saas_payment_edit']);
    Route::post('payment-method-update', ['as' => 'payment_method_update', 'uses' => 'SmApiController@payment_update']);
    Route::post('saas-payment-method-update', ['as' => 'saas_payment_method_update', 'uses' => 'SmApiController@saas_payment_update']);
    Route::get('payment-method-delete/{id}', ['as' => 'payment_method_delete', 'uses' => 'SmApiController@payment_delete']);
    Route::get('school/{school_id}/payment-method-delete/{id}', ['as' => 'saas_payment_method_delete', 'uses' => 'SmApiController@saas_payment_delete']);

    //--------------- End Accounts Modules --------------


    //--------------- Start Human Resource  --------------

    // staff directory
    Route::get('staff-directory', ['as' => 'staff_directory', 'uses' => 'SmApiController@staffList']);
    Route::get('school/{school_id}/staff-directory', ['as' => 'saas_staff_directory', 'uses' => 'SmApiController@saas_staffList']);
    Route::get('staff-roles', ['as' => 'staff_roles', 'uses' => 'SmApiController@staffRoles']);
    Route::get('school/{school_id}/staff-roles', ['as' => 'saas_staff_roles', 'uses' => 'SmApiController@saas_staffRoles']);
    Route::get('staff-list/{role_id}', ['as' => 'staff_dlist', 'uses' => 'SmApiController@roleStaffList']);
    Route::get('school/{school_id}/staff-list/{role_id}', ['as' => 'saas_staff_dlist', 'uses' => 'SmApiController@saas_roleStaffList']);
    Route::get('staff-view/{id}', ['as' => 'staff_view', 'uses' => 'SmApiController@staffView']);
    Route::get('school/{school_id}/staff-view/{id}', ['as' => 'saas_staff_view', 'uses' => 'SmApiController@saas_staffView']);
    Route::get('search-staff', 'SmApiController@staff_List');
    Route::get('school/{school_id}/search-staff', 'SmApiController@saas_staff_List');
    Route::post('search-staff', ['as' => 'searchStaff', 'uses' => 'SmApiController@searchStaff']);
    Route::post('school/{school_id}/search-staff', ['as' => 'saas_searchStaff', 'uses' => 'SmApiController@saas_searchStaff']);
    Route::get('deleteStaff/{id}', 'SmApiController@deleteStaff');
    Route::get('school/{school_id}/deleteStaff/{id}', 'SmApiController@saas_deleteStaff');

    //Staff Attendance
    Route::get('staff-attendance', ['as' => 'staff_attendance', 'uses' => 'SmApiController@staffAttendance']);
    Route::get('school/{school_id}/staff-attendance', ['as' => 'saas_staff_attendance', 'uses' => 'SmApiController@saas_staffAttendance']);
    Route::post('staff-attendance-search', 'SmApiController@staffAttendanceSearch');
    Route::post('saas-staff-attendance-search', 'SmApiController@saas_staffAttendanceSearch');
    Route::post('staff-attendance-store', 'SmApiController@staffAttendanceStore');
    Route::post('saas-staff-attendance-store', 'SmApiController@saas_staffAttendanceStore');

    Route::get('staff-attendance-report', ['as' => 'staff_attendance_report', 'uses' => 'SmApiController@staffAttendanceReport']);
    Route::get('school/{school_id}/staff-attendance-report', ['as' => 'saas_staff_attendance_report', 'uses' => 'SmApiController@saas_staffAttendanceReport']);
    Route::post('staff-attendance-report-search', ['as' => 'staff_attendance_report_search', 'uses' => 'SmApiController@staffAttendanceReportSearch']);
    Route::post('school/{school_id}/staff-attendance-report-search', ['as' => 'saas_staff_attendance_report_search', 'uses' => 'SmApiController@saas_staffAttendanceReportSearch']);

    // Staff designation
    Route::resource('designation', 'api\ApiSmDesignationController');

    //Department
    Route::resource('department', 'api\ApiSmHumanDepartmentController');
    //--------------- End Human Resource  --------------


    //--------------- Start Leave module --------------

    //Start Approve Leave Request
    Route::get('approve-leave', 'api\ApiSmLeaveController@allAprroveList');
    Route::get('approve-leave/{user_id}', 'api\ApiSmLeaveController@userApproveLeave');
    Route::get('school/{school_id}/approve-leave', 'SmApiController@saas_Approve_Leave_index');
    Route::post('approve-leave-store', 'api\ApiSmLeaveController@leaveApprove');
    // Route::post('approve-leave-store', 'SmApiController@Approve_Leave_store');
    Route::post('saas-approve-leave-store', 'SmApiController@saas_Approve_Leave_store');
    Route::get('approve-leave-edit/{id}', 'SmApiController@Approve_Leave_edit');
    Route::get('school/{school_id}/approve-leave-edit/{id}', 'SmApiController@saas_Approve_Leave_edit');
    Route::get('staffNameByRole', 'SmApiController@staffNameByRole');
    Route::get('school/{school_id}/staffNameByRole', 'SmApiController@saas_staffNameByRole');
    Route::post('update-approve-leave', 'SmApiController@updateApproveLeave');
    Route::post('school/{school_id}/update-approve-leave', 'SmApiController@saas_updateApproveLeave');
    Route::get('view-leave-details-approve/{id}', 'SmApiController@viewLeaveDetails');
    Route::get('school/{school_id}/view-leave-details-approve/{id}', 'SmApiController@saas_viewLeaveDetails');
    //End Approve Leave Request

    //Start Apply Leave
    Route::get('apply-leave', 'SmApiController@apply_leave_index');
    Route::get('school/{school_id}/apply-leave', 'SmApiController@saas_apply_leave_index');
    Route::post('apply-leave-store', 'SmApiController@apply_leave_store');
    Route::post('saas-apply-leave-store', 'SmApiController@saas_apply_leave_store');
    Route::get('apply-leave-edit/{id}', 'SmApiController@apply_leave_show');
    Route::get('school/{school_id}/apply-leave-edit/{id}', 'SmApiController@saas_apply_leave_show');
    Route::post('apply-leave-update', 'SmApiController@apply_leave_update');
    Route::post('saas-apply-leave-update', 'SmApiController@saas_apply_leave_update');
    Route::get('view-leave-details-apply/{id}', 'SmApiController@view_Leave_Details');
    Route::get('school/{school_id}/view-leave-details-apply/{id}', 'SmApiController@saas_view_Leave_Details');
    Route::get('delete-apply-leave/{id}', 'SmApiController@apply_leave_destroy');
    Route::get('school/{school_id}/delete-apply-leave/{id}', 'SmApiController@saas_apply_leave_destroy');

    //End Apply Leave

    //Student leave
    Route::get('student-apply-leave/{user_id}', 'api\ApiSmLeaveController@studentleaveApply');
    Route::post('student-apply-leave-store', 'api\ApiSmLeaveController@leaveStoreStudent');
    Route::get('school/{school_id}/student-apply-leave', 'Parent\SmParentPanelController@saas_leaveApply');
    Route::get('student-view-leave-details-apply/{id}', 'Parent\SmParentPanelController@viewLeaveDetails');
    Route::get('student-apply-leave-edit/{id}', 'Parent\SmParentPanelController@parentLeaveEdit');
    Route::post('student-apply-leave-update', 'Parent\SmParentPanelController@update');
    // Route::post('student-apply-leave-store', 'Parent\SmParentPanelController@leaveStore');
    Route::get('student-delete-apply-leave/{id}', 'Parent\SmParentPanelController@DeleteLeave');
    Route::get('my-leave-type/{user_id}','api\ApiSmLeaveController@myLeaveType');

    //End student leave

    // Staff leave define
    Route::resource('leave-define', 'api\ApiSmLeaveDefineController');

    // Staff leave type
    Route::resource('leave-type', 'api\ApiSmLeaveTypeController');

    //--------------- End Leave module --------------


    //--------------- Start Examination Module--------------

    // Marks Grade
    Route::resource('marks-grade', 'api\ApiSmMarksGradeController');

    //--------------- End Examination Module--------------


    //--------------- Start Academic Module--------------

    // class routine new
    Route::get('class-routine-new', ['as' => 'class_routine_new', 'uses' => 'SmApiController@classRoutine']);
    Route::get('school/{school_id}/class-routine-new', ['as' => 'saas_class_routine_new', 'uses' => 'SmApiController@saas_classRoutine']);

    Route::post('class-routine-new', 'api\ApiSmClassRoutineController@classRoutineSearch');
    Route::post('add-new-class-routine-store', 'api\ApiSmClassRoutineController@addNewClassRoutineStore');
    Route::get('student-routine-view/{student_id}/{record_id}', 'api\ApiSmClassRoutineController@studentClassRoutine');
    Route::get('teacher-routine-view/{techer_id}', 'api\ApiSmClassRoutineController@teacherClassRoutine');
    Route::get('class-routine-view/{user_id}/{record_id}', 'api\ApiSmClassRoutineController@studentClassRoutine');
    Route::post('day-wise-class-routine', 'api\ApiSmClassRoutineController@dayWiseClassRoutine')->name('dayWise_class_routine');

    Route::post('school/{school_id}/class-routine-new', 'SmApiController@saas_classRoutineSearch');

    //assign subject
    Route::get('assign-subject', ['as' => 'assign_subject', 'uses' => 'SmApiController@assignSubject']);
    Route::get('school/{school_id}/assign-subject', ['as' => 'saas_assign_subject', 'uses' => 'SmApiController@saas_assignSubject']);
    Route::get('assign-subject-create', ['as' => 'assign_subject_create', 'uses' => 'SmApiController@assigSubjectCreate']);
    Route::get('school/{school_id}/assign-subject-create', ['as' => 'saas_assign_subject_create', 'uses' => 'SmApiController@saas_assigSubjectCreate']);
    Route::post('assign-subject-search', ['as' => 'assign_subject_search', 'uses' => 'SmApiController@assignSubjectSearch']);
    Route::post('school/{school_id}/assign-subject-search', ['as' => 'saas_assign_subject_search', 'uses' => 'SmApiController@saas_assignSubjectSearch']);
    Route::get('assign-subject-search', 'SmApiController@assign_Subject_Create');
    Route::get('school/{school_id}/assign-subject-search', 'SmApiController@saas_assign_Subject_Create');
    Route::post('assign-subject-store', 'SmApiController@assignSubjectStore');
    Route::post('school/{school_id}/assign-subject-store', 'SmApiController@saas_assignSubjectStore');
    Route::get('assign-subject-store', 'SmApiController@assignSubject_Create');
    Route::get('school/{school_id}/assign-subject-store', 'SmApiController@saas_assignSubject_Create');
    Route::post('assign-subject', 'SmApiController@assignSubjectFind');
    Route::post('school/{school_id}/assign-subject', 'SmApiController@saas_assignSubjectFind');
    Route::get('assign-subject-get-by-ajax', 'SmApiController@assignSubjectAjax');
    Route::get('school/{school_id}/assign-subject-get-by-ajax', 'SmApiController@saas_assignSubjectAjax');

    //Assign Class Teacher
    Route::resource('assign-class-teacher', 'api\ApiSmAssignClassTeacherControler');

    // Subject routes
    Route::get('subject', ['as' => 'subject', 'uses' => 'SmApiController@subject_index']);
    Route::get('library-subject', ['as' => 'library_subject', 'uses' => 'api\SmApiBookController@library_subject_index']);
    Route::get('school/{school_id}/subject', ['as' => 'saas_subject', 'uses' => 'SmApiController@saas_subject_index']);
    Route::post('subject-store', ['as' => 'subject_store', 'uses' => 'SmApiController@subject_store']);
    Route::post('saas-subject-store', ['as' => 'saas_subject_store', 'uses' => 'SmApiController@saas_subject_store']);
    Route::get('subject-edit/{id}', ['as' => 'subject_edit', 'uses' => 'SmApiController@subject_edit']);
    Route::get('school/{school_id}/subject-edit/{id}', ['as' => 'saas_subject_edit', 'uses' => 'SmApiController@saas_subject_edit']);
    Route::post('subject-update', ['as' => 'subject_update', 'uses' => 'SmApiController@subject_update']);
    Route::post('saas-subject-update', ['as' => 'saas_subject_update', 'uses' => 'SmApiController@saas_subject_update']);
    Route::get('subject-delete/{id}', ['as' => 'subject_delete', 'uses' => 'SmApiController@subject_delete']);
    Route::get('school/{school_id}/subject-delete/{id}', ['as' => 'saas_subject_delete', 'uses' => 'SmApiController@saas_subject_delete']);

    // Class route
    Route::get('class', ['as' => 'class', 'uses' => 'SmApiController@class_index']);
    Route::get('school/{school_id}/class', ['as' => 'saas_class', 'uses' => 'SmApiController@saas_class_index']);
    Route::post('class-store', ['as' => 'class_store', 'uses' => 'SmApiController@class_store']);
    Route::post('saas-class-store', ['as' => 'saas_class_store', 'uses' => 'SmApiController@saas_class_store']);
    Route::get('class-edit/{id}', ['as' => 'class_edit', 'uses' => 'SmApiController@class_edit']);
    Route::get('school/{school_id}/class-edit/{id}', ['as' => 'saas_class_edit', 'uses' => 'SmApiController@saas_class_edit']);
    Route::post('class-update', ['as' => 'class_update', 'uses' => 'SmApiController@class_update']);
    Route::post('saas-class-update', ['as' => 'saas_class_update', 'uses' => 'SmApiController@saas_class_update']);
    Route::get('class-delete/{id}', ['as' => 'class_delete', 'uses' => 'SmApiController@class_delete']);
    Route::get('school/{school_id}/class-delete/{id}', ['as' => 'saas_class_delete', 'uses' => 'SmApiController@saas_class_delete']);

    //Class Section routes
    Route::get('section', ['as' => 'section', 'uses' => 'SmApiController@Section_index']);
    Route::get('school/{school_id}/section', ['as' => 'saas_section', 'uses' => 'SmApiController@saas_Section_index']);
    Route::post('saas-section-store', ['as' => 'saas_section_store', 'uses' => 'SmApiController@Section_store']);
    Route::post('section-store', ['as' => 'section_store', 'uses' => 'SmApiController@saas_Section_store']);
    Route::get('section-edit/{id}', ['as' => 'section_edit', 'uses' => 'SmApiController@Section_edit']);
    Route::get('school/{school_id}/section-edit/{id}', ['as' => 'saas_section_edit', 'uses' => 'SmApiController@saas_Section_edit']);
    Route::post('section-update', ['as' => 'section_update', 'uses' => 'SmApiController@Section_update']);
    Route::post('saas-section-update', ['as' => 'saas_section_update', 'uses' => 'SmApiController@saas_Section_update']);
    Route::get('section-delete/{id}', ['as' => 'section_delete', 'uses' => 'SmApiController@Section_delete']);
    Route::get('school/{school_id}/section-delete/{id}', ['as' => 'saas_section_delete', 'uses' => 'SmApiController@saas_Section_delete']);


    // Class room
    Route::resource('class-room', 'api\ApiSmClassRoomController');

    //class time
    Route::resource('class-time', 'api\ApiSmClassTimeController');


    //class routine
    Route::get('student-class-routine/{id}', 'SmApiController@class_Routine');
    Route::get('school/{school_id}/student-class-routine/{id}', 'SmApiController@saas_class_Routine');
    //--------------- End Academic Module--------------


    //--------------- Start Homework Module--------------
    //homework list
    Route::get('homework-list/{user_id}', ['uses' => 'api\ApiSmHomeWorkController@homeworkList']);
    Route::get('school/{school_id}/homework-list', ['as' => 'saas_homework-list', 'uses' => 'api\ApiSmHomeWorkController@saas_homeworkList']);
    Route::post('homework-list', ['as' => 'homework-list_post', 'uses' => 'api\ApiSmHomeWorkController@searchHomework']);
    Route::post('school/{school_id}/homework-list', ['as' => 'saas_homework-list_post', 'uses' => 'SmApiController@saas_searchHomework']);
    Route::get('evaluation-homework/{class_id}/{section_id}/{homework_id}', ['as' => 'evaluation-homework', 'uses' => 'api\ApiSmHomeWorkController@evaluationHomework']);
    
    Route::get('school/{school_id}/evaluation-homework/{class_id}/{section_id}/{homework_id}', ['as' => 'saas-evaluation-homework', 'uses' => 'api\ApiSmHomeWorkController@saas_evaluationHomework']);
    
    Route::post('evaluate-homework', ['as' => 'evaluate-homework', 'uses' => 'api\ApiSmHomeWorkController@saveHomeworkEvaluationData']);
     Route::post('school/{school_id}/evaluate-homework', ['as' => 'saas-evaluate-homework', 'uses' => 'api\ApiSmHomeWorkController@saasSaveHomeworkEvaluationData']);
    

    Route::any('add-homework', 'api\ApiSmHomeWorkController@addHomework');
    Route::post('update-homework', 'api\ApiSmHomeWorkController@homeworkUpdate');
    Route::any('saas-add-homework', 'api\ApiSmHomeWorkController@saas_addHomework');
    Route::get('school/{school_id}/homework-list/{id}', 'api\ApiSmHomeWorkController@saas_homework_List_Teacher');
    //--------------- End Homework Module--------------


    //--------------- Start Communicate Module --------------
    // Communicate
    Route::get('notice-list', 'SmApiController@noticeList');
    Route::get('school/{school_id}/notice-list', 'SmApiController@saas_noticeList');
    Route::get('send-message', 'SmApiController@sendMessage');
    Route::get('school/{school_id}/send-message', 'SmApiController@saas_sendMessage');
    Route::post('save-notice-data', 'SmApiController@saveNoticeData');
    Route::post('saas-save-notice-data', 'SmApiController@saas_saveNoticeData');
    Route::get('edit-notice/{id}', 'SmApiController@editNotice');
    Route::get('school/{school_id}/edit-notice/{id}', 'SmApiController@saas_editNotice');
    Route::post('update-notice-data', 'SmApiController@updateNoticeData');
    Route::post('saas-update-notice-data', 'SmApiController@saas_updateNoticeData');
    Route::get('delete-notice-view/{id}', 'SmApiController@deleteNoticeView');
    Route::get('school/{school_id}/delete-notice-view/{id}', 'SmApiController@saas_deleteNoticeView');
    Route::get('send-email-sms-view', 'SmApiController@sendEmailSmsView');
    Route::get('school/{school_id}/send-email-sms-view', 'SmApiController@saas_sendEmailSmsView');
    Route::get('delete-notice/{id}', 'SmApiController@deleteNotice');
    Route::get('school/{school_id}/delete-notice/{id}', 'SmApiController@saas_deleteNotice');

    //Event
    Route::resource('event', 'api\ApiSmEventController');
    Route::get('delete-event-view/{id}', 'SmApiController@deleteEventView');
    Route::get('school/{school_id}/delete-event-view/{id}', 'SmApiController@saas_deleteEventView');
    Route::get('delete-event/{id}', 'SmApiController@deleteEvent');
    Route::get('school/{school_id}/delete-event/{id}', 'SmApiController@saas_deleteEvent');

    //--------------- Start Communicate Module --------------


    //--------------- Start Library Module --------------

    // Book
    Route::get('book-list', 'api\ApiSmBookController@Library_index');
    Route::get('library-subject', ['as' => 'library_subject', 'uses' => 'api\ApiSmBookController@library_subject_index']);
    Route::get('school/{school_id}/book-list', 'api\ApiSmBookController@saas_Library_index');
    // Route::get('add-book', 'SmBookController@addBook');
    Route::post('save-book-data', 'api\ApiSmBookController@saveBookData');
    Route::post('saas-save-book-data', 'api\ApiSmBookController@saas_saveBookData');
    Route::get('edit-book/{id}', 'api\ApiSmBookController@editBook');
    Route::get('school/{school_id}/edit-book/{id}', 'api\ApiSmBookController@saas_editBook');
    Route::post('update-book-data/{id}', 'api\ApiSmBookController@updateBookData');
    Route::post('saas-update-book-data/{id}', 'api\ApiSmBookController@saas_updateBookData');
    Route::get('delete-book-view/{id}', 'api\ApiSmBookController@deleteBookView');
    Route::get('school/{school_id}/delete-book-view/{id}', 'api\ApiSmBookController@saas_deleteBookView');
    Route::get('delete-book/{id}', 'api\ApiSmBookController@deleteBook');
    Route::get('school/{school_id}/delete-book/{id}', 'api\ApiSmBookController@saas_deleteBook');
    Route::get('member-list', 'SmApiController@memberList');
    Route::get('school/{school_id}/member-list', 'SmApiController@saas_memberList');
    Route::get('issue-books/{member_type}/{id}', 'SmApiController@issueBooks');
    Route::get('school/{school_id}/issue-books/{member_type}/{id}', 'SmApiController@saas_issueBooks');
    Route::post('save-issue-book-data', 'SmApiController@saveIssueBookData');
    Route::post('saas-save-issue-book-data', 'SmApiController@saas_saveIssueBookData');
    Route::get('return-book-view/{id}', 'SmApiController@returnBookView');
    Route::get('school/{school_id}/return-book-view/{id}', 'SmApiController@saas_returnBookView');
    Route::get('return-book/{id}', 'SmApiController@returnBook');
    Route::get('school/{school_id}/return-book/{id}', 'SmApiController@saas_returnBook');
    Route::get('all-issed-book', 'SmApiController@allIssuedBook');
    Route::get('school/{school_id}/all-issed-book', 'SmApiController@saas_allIssuedBook');
    Route::get('search-issued-book', 'SmApiController@searchIssuedBook');
    Route::get('school/{school_id}/search-issued-book', 'SmApiController@saas_searchIssuedBook');
    Route::get('search-issued-book', 'SmApiController@all_IssuedBook');
    Route::get('school/{school_id}/search-issued-book', 'SmApiController@saas_all_IssuedBook');

    //library member
    Route::resource('library-member', 'api\ApiSmLibraryMemberController');
    Route::post('add-library-member', 'api\ApiSmLibraryMemberController@library_member_store');
    Route::post('saas-add-library-member', 'api\ApiSmLibraryMemberController@saas_library_member_store');
    Route::get('library-member-role', 'SmApiController@member_role');
    Route::get('school/{school_id}/library-member-role', 'SmApiController@saas_member_role');
    Route::get('cancel-membership/{id}', 'SmApiController@cancelMembership');
    Route::get('school/{school_id}/cancel-membership/{id}', 'SmApiController@saas_cancelMembership');

    //--------------- End Library Module --------------


    //-----------------Start Inventory Module------------------------

    //Item Category
    Route::resource('item-category', 'api\ApiSmItemCategoryController');
    Route::get('delete-item-category-view/{id}', 'SmApiController@deleteItemCategoryView');
    Route::get('school/{school_id}/delete-item-category-view/{id}', 'SmApiController@saas_deleteItemCategoryView');
    Route::get('delete-item-category/{id}', 'SmApiController@deleteItemCategory');
    Route::get('school/{school_id}/delete-item-category/{id}', 'SmApiController@saas_deleteItemCategory');

    //Item List
    Route::resource('item-list', 'api\ApiSmItemController');
    Route::get('delete-item-view/{id}', 'SmApiController@deleteItemView');
    Route::get('delete-item/{id}', 'SmApiController@deleteItem');

    //Item Store
    Route::resource('item-store', 'api\ApiSmItemStoreController');
    Route::get('delete-store-view/{id}', 'SmApiController@deleteStoreView');
    Route::get('delete-store/{id}', 'SmApiController@deleteStore');

    //Supplier
    Route::resource('suppliers', 'api\ApiSmSupplierController');
    Route::get('delete-supplier-view/{id}', 'SmApiController@deleteSupplierView');
    Route::get('delete-supplier/{id}', 'SmApiController@deleteSupplier');

    //Issue Item
    Route::get('item-issue', 'SmApiController@itemIssueList');
    Route::post('save-item-issue-data', 'SmApiController@saveItemIssueData');
    Route::get('getItemByCategory', 'SmApiController@getItemByCategory');
    Route::get('return-item-view/{id}', 'SmApiController@returnItemView');
    Route::get('return-item/{id}', 'SmApiController@returnItem');
    //-----------------End Inventory Module------------------------


    //------------------Start Transport Module--------------

    //routes
    Route::resource('transport-route', 'api\ApiSmRouteController');
    Route::resource('saas-transport-route', 'api\SaasRouteController');

    //Vehicle
    Route::resource('vehicle', 'api\ApiSmSmVehicleController');
    Route::resource('saas-vehicle', 'api\SaasVehicleController');

    //Assign Vehicle
    Route::resource('assign-vehicle', 'api\ApiSmAssignVehicleController');
    Route::post('assign-vehicle-delete', 'SmApiController@Assign_Vehicle_delete');
    Route::post('school/{school_id}/assign-vehicle-delete', 'SmApiController@saas_Assign_Vehicle_delete');

    // student transport report
    Route::get('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'SmApiController@studentTransportReportApi']);
    Route::get('school/{school_id}/student-transport-report', ['as' => 'saas_student_transport_report', 'uses' => 'SmApiController@saas_studentTransportReportApi']);

    //Route::get('student-transport-reportApi', ['as' => 'student_transport_report', 'uses' => 'SmTransportController@studentTransportReportApi']);


    Route::post('student-transport-report', ['as' => 'student_transport_report_post', 'uses' => 'SmApiController@studentTransportReportSearch']);
    Route::post('school/{school_id}/student-transport-report', ['as' => 'saas_student_transport_report_post', 'uses' => 'SmApiController@saas_studentTransportReportSearch']);
    //------------------End Transport Module--------------


    // ---------------Start Dormitory Module-----------------

    //Room list
    Route::resource('room-list', 'api\ApiSmRoomListController');

    //Room Type
    Route::resource('room-type', 'api\ApiSmRoomTypeController');

    //Dormitory List
    Route::resource('dormitory-list', 'api\ApiSmDormitoryListController');

    // Student Dormitory Report
    Route::get('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'SmApiController@studentDormitoryReport']);
    Route::get('school/{school_id}/student-dormitory-report', ['as' => 'saas_student_dormitory_report', 'uses' => 'SmApiController@saas_studentDormitoryReport']);
    Route::post('student-dormitory-report', ['as' => 'student_dormitory_report_post', 'uses' => 'SmApiController@studentDormitoryReportSearch']);
    Route::post('school/{school_id}/student-dormitory-report', ['as' => 'saas_student_dormitory_report_post', 'uses' => 'api\ApiSmDormitoryListController@saas_studentDormitoryReportSearch']);

    // ---------------End Dormitory Module-----------------


    //------------- Start Report Module---------------------

    //Student Report
    Route::get('student-report', ['as' => 'student_report', 'uses' => 'SmApiController@studentReport']);
    Route::post('student-report', ['as' => 'saas_student_report', 'uses' => 'SmApiController@studentReportSearch']);

    //guardian report
    Route::get('guardian-report', ['as' => 'guardian_report', 'uses' => 'SmApiController@guardianReport']);
    Route::post('guardian-report-search', ['as' => 'guardian_report_search_post', 'uses' => 'SmApiController@guardianReportSearch']);
    Route::get('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'SmApiController@guardian_Report']);

    //Student history
    Route::get('student-history', ['as' => 'student_history', 'uses' => 'SmApiController@studentHistory']);
    Route::post('student-history-search', ['as' => 'student_history_search_post', 'uses' => 'SmApiController@studentHistorySearch']);
    Route::get('student-history-search', ['as' => '_post', 'uses' => 'SmApiController@student_History']);

    // student login report
    Route::get('student-login-report', ['as' => 'student_login_report', 'uses' => 'SmApiController@studentLoginReport']);
    Route::post('student-login-search', ['as' => 'student_login_search_post', 'uses' => 'SmApiController@studentLoginSearch']);
    Route::get('student-login-search', ['as' => 'student_login_search_repost', 'uses' => 'SmApiController@student_Login_Report']);

    // student & parent reset password
    Route::post('reset-student-password', 'SmApiController@resetStudentPassword');

    //Fees Statement
    Route::get('fees-statement', ['as' => 'fees_statement', 'uses' => 'SmApiController@feesStatemnt']);
    Route::post('fees-statement-search', ['as' => 'fees_statement_search', 'uses' => 'SmApiController@feesStatementSearch']);

    // Balance fees report
    Route::get('balance-fees-report', ['as' => 'balance_fees_report', 'uses' => 'SmApiController@balanceFeesReport']);
    Route::post('balance-fees-search', ['as' => 'balance_fees_search_post', 'uses' => 'SmApiController@balanceFeesSearch']);
    Route::get('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'SmApiController@balance_Fees_Report']);

    // Transaction Report
    Route::get('transaction-report', ['as' => 'transaction_report', 'uses' => 'SmApiController@transactionReport']);
    Route::post('transaction-report-search', ['as' => 'transaction_report_search_post', 'uses' => 'SmApiController@transactionReportSearch']);
    Route::get('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'SmApiController@transaction_Report']);

    // Class Report
    Route::get('class-report', ['as' => 'class_report', 'uses' => 'SmApiController@classReport']);
    Route::post('class-report', ['as' => 'class_report_post', 'uses' => 'SmApiController@classReportSearch']);

    // class routine report
    Route::get('class-routine-report', ['as' => 'class_routine_report', 'uses' => 'SmApiController@classRoutineReport']);
    Route::post('class-routine-report', 'api\ApiSmClassRoutineController@classRoutineReportSearch');

    // exam routine student
    Route::get('student-exam-schedule/{student_id}', ['as' => 'student-exam-schedule', 'uses' => 'api\ApiSmExamRoutineController@studentRoutine']);
    Route::post('student-exam-schedule', ['as' => 'student-exam-schedule', 'uses' => 'api\ApiSmExamRoutineController@studentExamRoutineSearch']);
    // exam routine report
    Route::get('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'SmApiController@examRoutineReport']);
    Route::post('exam-routine-report', ['as' => 'exam_routine_report_post', 'uses' => 'api\ApiSmExamRoutineController@examRoutineReportSearch']);

    //teacher class routine report
    Route::get('teacher-class-routine-report', ['as' => 'teacher_class_routine_report', 'uses' => 'SmApiController@teacherClassRoutineReport']);
    Route::post('teacher-class-routine-report', 'api\ApiSmClassRoutineController@teacherClassRoutineReportSearch');

    // merit list Report
    Route::get('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'SmApiController@meritListReport']);
    Route::post('merit-list-report', ['as' => 'merit_list_report_post', 'uses' => 'SmApiController@meritListReportSearch']);

    // online exam report
    Route::get('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'SmApiController@onlineExamReport']);
    Route::post('online-exam-report', ['as' => 'online_exam_report_post', 'uses' => 'SmApiController@onlineExamReportSearch']);

    //mark sheet report student
    Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReportStudent']);
    Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student_post', 'uses' => 'SmApiController@markSheetReportStudentSearch']);

    //mark sheet report student
    Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReport_Student']);
    Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student_post', 'uses' => 'SmApiController@markSheetReportStudent_Search']);

    // Tabulation Sheet Report
    Route::get('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'SmApiController@tabulationSheetReport']);
    Route::post('tabulation-sheet-report', ['as' => 'tabulation_sheet_report_post', 'uses' => 'SmApiController@tabulationSheetReportSearch']);

    // progress card report
    Route::get('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'SmApiController@progressCardReport']);
    Route::post('progress-card-report', ['as' => 'progress_card_report_post', 'uses' => 'SmApiController@progressCardReportSearch']);

    //student fine report
    Route::get('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'SmApiController@studentFineReport']);
    Route::post('student-fine-report', ['as' => 'student_fine_report_post', 'uses' => 'SmApiController@studentFineReportSearch']);

    //user log
    Route::get('user-log', ['as' => 'user_log', 'uses' => 'SmApiController@userLog']);
    //------------- End Report Module---------------------


    //------------Start System Settings Module--------------

    //General Settings
    Route::get('general-settings/{school_id}', 'SmApiController@generalSettingsView');
    Route::get('update-general-settings', 'SmApiController@updateGeneralSettings');
    Route::post('update-general-settings-data', 'SmApiController@updateGeneralSettingsData');
    Route::post('update-school-logo', 'SmApiController@updateSchoolLogo');

    //Role Setup
    Route::get('system-role', ['as' => 'system-role', 'uses' => 'SmApiController@systemRole']);

    Route::get('role', ['as' => 'role', 'uses' => 'SmApiController@role_index']);
    Route::post('role-store', ['as' => 'role_store', 'uses' => 'SmApiController@role_store']);
    Route::get('role-edit/{id}', ['as' => 'role_edit', 'uses' => 'SmApiController@role_edit']);
    Route::post('role-update', ['as' => 'role_update', 'uses' => 'SmApiController@role_update']);
    Route::post('role-delete', ['as' => 'role_delete', 'uses' => 'SmApiController@role_delete']);

    // Role Permission
    Route::get('assign-permission/{id}', ['as' => 'assign_permission', 'uses' => 'SmApiController@assignPermission']);
    Route::post('role-permission-store', ['as' => 'role_permission_store', 'uses' => 'SmApiController@rolePermissionStore']);

    // Base group
    Route::get('base-group', ['as' => 'base_group', 'uses' => 'SmApiController@base_group_index']);
    Route::post('base-group-store', ['as' => 'base_group_store', 'uses' => 'SmApiController@base_group_store']);
    Route::get('base-group-edit/{id}', ['as' => 'base_group_edit', 'uses' => 'SmApiController@base_group_edit']);
    Route::post('base-group-update', ['as' => 'base_group_update', 'uses' => 'SmApiController@base_group_update']);
    Route::get('base-group-delete/{id}', ['as' => 'base_group_delete', 'uses' => 'SmApiController@base_group_delete']);

    //academic year
    Route::resource('academic-year', 'api\ApiSmAcademicYearController');

    //Session
    Route::resource('session', 'api\ApiSmSessionController');

    //Holiday
    Route::resource('holiday', 'api\ApiSmHolidayController');
    Route::get('delete-holiday-view/{id}', 'SmApiController@deleteHolidayView');
    Route::get('delete-holiday/{id}', 'SmApiController@deleteHoliday');

    //weekend
    Route::resource('weekend', 'api\ApiSmWeekendController');

    //------------End System Settings Module--------------


    //******************Start Student Panel ********************


    //------------Start Student Dashboard --------------
    Route::get('student-homework/{user_id}/{record_id}', 'api\ApiSmHomeWorkController@studentHomework');
    Route::post('student-upload-homework','api\ApiSmHomeWorkController@studentUploadHomework');
    Route::get('school/{school_id}/student-homework/{user_id}/{record_id}', 'api\ApiSmHomeWorkController@saas_studentHomework');
     Route::post('school/{school_id}/student-upload-homework','api\ApiSmHomeWorkController@saas_studentUploadHomework');
    Route::get('student-dashboard/{id}', 'SmApiController@studentDashboard');
    Route::get('school/{school_id}/student-dashboard/{id}', 'SmApiController@saas_studentDashboard');
    Route::get('student-my-attendance/{id}/{record_id}', 'api\ApiSmStudentAttendanceController@studentMyAttendanceSearchAPI');
    Route::get('school/{school_id}/student-my-attendance/{id}/{record_id}', 'api\ApiSmStudentAttendanceController@saas_studentMyAttendanceSearchAPI');
    Route::get('student-noticeboard/{id}', 'SmApiController@studentNoticeboard');
    Route::get('school/{school_id}/student-noticeboard/{id}', 'SmApiController@saas_studentNoticeboard');
    //------------End Student Dashboard --------------


    //******************Start Student Panel ********************


    Route::get('studentSubject/{id}/{record_id}', 'SmApiController@studentSubjectApi');
    Route::get('school/{school_id}/studentSubject/{id}/{record_id}', 'SmApiController@saas_studentSubjectApi');
    Route::get('student-library/{id}', 'SmApiController@studentLibrary');
    Route::get('school/{school_id}/student-library/{id}', 'SmApiController@saas_studentLibrary');
    Route::get('studentTeacher/{id}', 'api\ApiSmStudentPanelController@studentTeacherApi');
    Route::get('school/{school_id}/studentTeacher/{user_id}/{record_id}', 'api\ApiSmStudentPanelController@saas_studentTeacherApi');

    Route::get('studentAssignment/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@studentAssignmentApi');
    Route::get('studentSyllabus/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@studentSyllabusApi');
    Route::get('studentOtherDownloads/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@studentOtherDownloadsApi');
    Route::get('school/{school_id}/studentAssignment/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@saas_studentAssignmentApi');
    Route::get('studentDocuments/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@studentsDocumentApi');
    Route::get('school/{school_id}/studentDocuments/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@saas_studentsDocumentApi');

    Route::get('student-dormitory', 'SmApiController@studentDormitoryApi');
    Route::get('school/{school_id}/student-dormitory', 'SmApiController@saas_studentDormitoryApi');

    Route::get('student-exam_schedule/{id}', 'SmApiController@studentExamScheduleApi');
    Route::get('school/{school_id}/student-exam_schedule/{id}', 'SmApiController@saas_studentExamScheduleApi');

    Route::get('student-timeline/{id}', 'SmApiController@studentTimelineApi');
    Route::get('school/{school_id}/student-timeline/{id}', 'SmApiController@saas_studentTimelineApi');

    Route::get('student-online-exam/{user_id}/{record_id}', 'api\ApiSmExamController@studentOnlineExamApi');
    Route::get('school/{school_id}/student-online-exam/{user_id}/{record_id}', 'api\ApiSmExamController@saas_studentOnlineExamApi');
    Route::get('choose-exam/{user_id}/{record_id}', 'api\ApiSmExamController@chooseExamApi');
    Route::get('school/{school_id}/choose-exam/{user_id}/{record_id}', 'api\ApiSmExamController@saas_chooseExamApi');
    Route::get('online-exam-result/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@examResultApi');
    Route::get('school/{school_id}/online-exam-result/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@saas_examResultApi');
    Route::get('getGrades/{marks}', 'SmApiController@getGrades');
    Route::get('school/{school_id}/getGrades/{marks}', 'SmApiController@saas_getGrades');


    //******************SYSTEM********************
    Route::get('getSystemVersion', 'SmApiController@getSystemVersion');
    Route::get('getSystemUpdate/{id}', 'SmApiController@getSystemUpdate');


    Route::get('exam-list/{user_id}/{record_id}', 'api\ApiSmExamController@examListApi');
    Route::get('school/{school_id}/exam-list/{user_id}/{record_id}', 'api\ApiSmExamController@saas_examListApi');
    Route::get('exam-schedule/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@examScheduleApi');
    Route::get('school/{school_id}/exam-schedule/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@saas_examScheduleApi');
    Route::get('exam-result/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@examResult_Api');
    Route::get('school/{school_id}/exam-result/{user_id}/{exam_id}/{record_id}', 'api\ApiSmExamController@saas_examResult_Api');

    //Add new exam setup
    Route::get('new-exam-setup', 'api\ApiSmExamController@NewExamSetup');
    Route::get('school/{school_id}/new-exam-setup', 'api\ApiSmExamController@saas_NewExamSetup');
    Route::get('new-exam-schedule', 'api\ApiSmExamController@NewExamSchedule');
    Route::get('school/{school_id}/new-exam-schedule', 'api\ApiSmExamController@saas_NewExamSchedule');

    Route::any('change-password', 'SmApiController@updatePassowrdStoreApi');
    Route::any('school/{school_id}/change-password', 'SmApiController@saas_updatePassowrdStoreApi');
    // exam routine 
    Route::get('exam-schedule-create', 'api\ApiSmExamRoutineController@examRoutine');
    Route::post('exam-schedule-create', 'api\ApiSmExamRoutineController@examScheduleSearch');
    Route::post('add-exam-routine-store', 'api\ApiSmExamRoutineController@addExamRoutineStore');

    // Parents

    Route::get('child-list/{id}', 'api\ApiSmParentPanelController@childListApi');
    Route::get('school/{school_id}/child-list/{id}', 'api\ApiSmParentPanelController@saas_childListApi');
    Route::get('child-info/{id}', 'SmApiController@childProfileApi');
    Route::get('school/{school_id}/child-info/{id}', 'SmApiController@saas_childProfileApi');
    Route::get('child-fees/{id}', 'SmApiController@collectFeesChildApi');
    Route::get('school/{school_id}/child-fees/{id}', 'SmApiController@saas_collectFeesChildApi');
    Route::get('child-class-routine/{id}', 'SmApiController@classRoutineApi');
    Route::get('school/{school_id}/child-class-routine/{id}', 'SmApiController@saas_classRoutineApi');
    Route::get('child-homework/{id}', 'SmApiController@childHomework');
    Route::get('school/{school_id}/child-homework/{id}', 'SmApiController@saas_childHomework');

    Route::get('child-attendance/{id}/{record_id}', 'SmApiController@childAttendanceAPI');
    Route::get('school/{school_id}/child-attendance/{id}/{record_id}', 'SmApiController@saas_childAttendanceAPI');

    Route::get('childInfo/{id}', 'api\ApiSmParentPanelController@childInfo');
    Route::get('school/{school_id}/childInfo/{id}', 'api\ApiSmParentPanelController@saas_childInfo');

    Route::get('parent-about', 'SmApiController@aboutApi');
    Route::get('school/{school_id}/parent-about', 'SmApiController@saas_aboutApi');


    //Route::get('parent-about', 'Parent\SmParentPanelController@aboutApi');


    //Teacher Api

    Route::any('search-student', 'api\ApiSmStudentController@searchStudent');
    Route::any('school/{school_id}/search-student', 'api\ApiSmStudentController@saas_searchStudent');
    // https://infixedu.com/api/search-student?class=2
    // https://infixedu.com/api/search-student?section=1&class=2
    // https://infixedu.com/api/search-student?name=Conner Stamm
    // https://infixedu.com/api/search-student?roll_no=28229
    Route::get('my-routine/{user_id}', 'api\ApiSmClassRoutineController@teacherClassRoutine');
    Route::get('school/{school_id}/my-routine/{id}', 'api\ApiSmClassRoutineController@sassTeacherClassRoutine');
    Route::get('section-routine/{user_id}/{class}/{section}', 'api\ApiSmClassRoutineController@sectionRoutine');
    Route::get('school/{school_id}/section-routine/{user_id}/{class}/{section}', 'SmApiController@saas_sectionRoutine');
    Route::get('class-section/{id}', 'SmApiController@classSection');
    Route::get('school/{school_id}/class-section/{id}', 'SmApiController@saas_classSection');
    Route::get('subject/{id}', 'SmApiController@subjectsName');
    Route::get('school/{school_id}/subject/{id}', 'SmApiController@saas_subjectsName');


    Route::get('teacher-class-list', 'SmApiController@teacherClassList');
    Route::get('school/{school_id}/teacher-class-list', 'SmApiController@saas_teacherClassList');
    Route::get('teacher-section-list', 'SmApiController@teacherSectionList');
    Route::get('school/{school_id}/teacher-section-list', 'SmApiController@saas_teacherSectionList');


    Route::get('my-attendance/{id}', 'api\ApiSmStaffAttendanceController@teacherMyAttendanceSearchAPI');
    Route::get('school/{school_id}/my-attendance/{id}', 'api\ApiSmStaffAttendanceController@saas_teacherMyAttendanceSearchAPI');
    Route::get('staff-leave-type', 'SmApiController@leaveTypeList');
    Route::get('school/{school_id}/staff-leave-type', 'SmApiController@saas_leaveTypeList');
    Route::any('staff-apply-leave', 'SmApiController@applyLeave');
    Route::any('saas-staff-apply-leave', 'SmApiController@saas_applyLeave');
    Route::get('staff-apply-list/{id}', 'SmApiController@staffLeaveList');
    Route::get('school/{school_id}/staff-apply-list/{id}', 'SmApiController@saas_staffLeaveList');

    // Route::get('upload-content-type', 'teacher\SmAcademicsController@contentType');
    Route::any('teacher-upload-content', 'SmApiController@uploadContent');
    Route::any('saas-teacher-upload-content', 'SmApiController@saas_uploadContent');
    Route::get('content-list', 'api\ApiSmTeacherController@uploadContentList');
    Route::get('content-list/{user_id}', 'api\ApiSmTeacherController@uploadContentListByUser');
    Route::get('school/{school_id}/content-list', 'api\ApiSmTeacherController@saasUploadContentList');
    Route::get('school/{school_id}/admin-content-list', 'api\ApiSmTeacherController@saas_contentList');
    Route::get('delete-content/{id}', 'api\ApiSmTeacherController@deleteContent');
    Route::get('school/{school_id}/delete-content/{id}', 'api\ApiSmTeacherController@saas_deleteContent');


   //for all staff/student
   Route::get('pending-leave/{user_id}', 'api\ApiSmLeaveController@pendingLeave');

    //Super Admin Api
    Route::get('pending-leave', 'api\ApiSmLeaveController@allPendingList');
    Route::get('school/{school_id}/pending-leave', 'SmApiController@saas_pendingLeave');
    Route::get('approved-leave', 'SmApiController@approvedLeave');
    Route::get('school/{school_id}/approved-leave', 'SmApiController@saas_approvedLeave');
    Route::get('reject-leave', 'api\ApiSmLeaveController@allRejectedList');
    Route::get('reject-leave/{user_id}', 'api\ApiSmLeaveController@rejectUserLeave');
    Route::get('school/{school_id}/reject-leave', 'api\ApiSmLeaveController@saas_rejectLeave');
    Route::any('staff-leave-apply', 'SmApiController@apply_Leave');
    Route::any('saas-staff-leave-apply', 'SmApiController@saas_apply_Leave');
    Route::get('update-leave', 'SmApiController@updateLeave');
    Route::get('school/{school_id}/update-leave', 'SmApiController@saas_updateLeave');

    Route::post('update-staff',  'SmApiController@UpdateStaffApi');
    Route::post('update-student',  'SmApiController@UpdateStudentApi');
    //Super Admin Student
    Route::any('set-token', 'SmApiController@setToken');
    Route::get('set-fcm-token', 'SmApiController@setFcmToken');
    Route::any('school/{school_id}/set-token', 'SmApiController@saas_setToken');

    Route::get('group-token', 'SmApiController@groupToken');
    Route::get('school/{school_id}/group-token', 'SmApiController@saas_groupToken');
    //infixedu.com/android/api/group-token?id=2&body=Notification body&title=Notification title
    Route::get('notification-api', 'SmSystemSettingController@notificationApi');

    Route::get('flutter-group-token', 'SmApiController@flutterGroupToken');
    Route::get('flutter-notification-api', 'SmSystemSettingController@flutterNotificationApi');
    Route::get('homework-notification-api', 'api\ApiSmHomeWorkController@HomeWorkNotification');

    Route::get('room-list', 'SmApiController@roomList');


    Route::get('myNotification/{user_id}', 'SmApiController@myNotification');
    Route::get('viewNotification/{user_id}/{notification_id}', 'SmApiController@viewNotification');
    Route::get('viewAllNotification/{user_id}', 'SmApiController@viewAllNotification');
    Route::post('child-bank-slip-store', 'SmApiController@childBankSlipStore');
    Route::get('banks', 'SmApiController@bankList');

    Route::get('room-type-list', 'SmApiController@roomTypeList');
    Route::get('school/{school_id}/room-type-list', 'SmApiController@saas_roomTypeList');
    Route::post('room-store', 'SmApiController@storeRoom');
    Route::post('saas-room-store', 'SmApiController@saas_storeRoom');
    Route::post('room-update', 'SmApiController@updateRoom');
    Route::post('saas-room-update', 'SmApiController@saas_updateRoom');
    Route::get('room-delete/{id}', 'SmApiController@deleteRoom');
    Route::get('school/{school_id}/room-delete/{id}', 'SmApiController@saas_deleteRoom');

    Route::get('dormitory-list', 'SmApiController@dormitoryList');
    Route::get('school/{school_id}/dormitory-list', 'SmApiController@saas_dormitoryList');
    Route::post('add-dormitory', 'SmApiController@addDormitory');
    Route::post('saas-add-dormitory', 'SmApiController@saas_addDormitory');
    Route::get('edit-dormitory', 'SmApiController@editDormitory');
    Route::get('edit-dormitory', 'SmApiController@saas_editDormitory');
    Route::get('delete-dormitory/{id}', 'SmApiController@deleteDormitory');
    Route::get('school/{school_id}/delete-dormitory/{id}', 'SmApiController@saas_deleteDormitory');

    Route::get('driver-list', 'SmApiController@getDriverList');
    Route::get('school/{school_id}/driver-list', 'SmApiController@saas_getDriverList');


    Route::get('book-category', 'SmApiController@bookCategory');
    //download file
    Route::get('download-content-document/{file_name}', 'SmApiController@DownloadContent');
    Route::get('download-complaint-document/{file_name}', 'SmApiController@DownloadComplaint');
    Route::get('download-visitor-document/{file_name}', 'SmApiController@DownloadVisitor');
    Route::get('postal-receive-document/{file_name}', 'SmApiController@DownloadPostal');
    Route::get('postal-dispatch-document/{file_name}', 'SmApiController@DownloadDispatch');


    // End Upload Content
    Route::post('custom-merit-list', 'CustomResultSettingController@meritListReport');

    Route::post('custom-progress-card', 'CustomResultSettingController@progressCardReport');
    Route::post('student-final-result', 'CustomResultSettingController@studentFinalResult');
    //User Info for demo

    
    Route::get('school/{school_id}/user-demo', 'SmApiController@SaasDemoUser');
    Route::get('currency-converter', 'SmApiController@convertCurrency'); //api/currency-converter?amount=2&from_currency=USD&to_currency=BDT
    Route::any('student-fees-payment', 'SmApiController@studentFeesPayment');
    Route::any('school/{school_id}/student-fees-payment', 'SmApiController@saas_studentFeesPayment');

    Route::get('banks/{school_id}', 'api\ApiSmSaasBankController@saas_bankList');
    Route::post('saas-child-bank-slip-store', 'api\ApiSmSaasBankController@saas_childBankSlipStore');
    Route::get('school/{school_id}/studentSyllabus/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@saas_studentSyllabusApi');
    Route::get('school/{school_id}/studentOtherDownloads/{user_id}/{record_id}', 'api\ApiSmStudyMaterialController@saas_studentOtherDownloadsApi');
    Route::get('school/{school_id}/room-list', 'api\ApiSmSaasBankController@saas_roomList');
    Route::any('saas-book-category', 'api\ApiSmSaasBankController@saas_bookCategory');
    Route::get('my-leave-type/{school_id}/{user_id}','api\ApiSmLeaveController@saas_myLeaveType');
    // update 1-14-2-2022
    Route::get('student-record/{student_id}','api\ApiStudentRecordController@getRecord');
    Route::get('student-record/{school_id}/{student_id}','api\ApiStudentRecordController@getRecordSaas');

    //class routine
    Route::get('student-class-routine/{user_id}/{record_id}', 'api\ApiSmClassRoutineController@studentClassRoutine');
    Route::get('school/{school_id}/student-class-routine/{user_id}/{record_id}', 'api\ApiSmClassRoutineController@sassclassRoutine');
    Route::post('student-attendance-store-all', 'api\ApiSmStudentAttendanceController@studentStoreAttendanceAllApi');

    //22/04/22
    //Attendance store all
    Route::post('student-attendance-store-all', 'api\ApiSmStudentAttendanceController@studentStoreAttendanceAllApi');

  
    Route::get('student-fees-installments/{record_id}', 'api\DirectFeesApiController@getInstallments');
    Route::get('student-fees-installment-make-payment/{record_id}', 'api\DirectFeesApiController@makePayment');
    Route::post('student-fees-installment-submit-payment/{record_id}', 'api\DirectFeesApiController@submitPayment');




    Route::post('class-section-subjectList', 'api\ApiSmStudentAttendanceController@subjectList');
    Route::post('student-subject-attendance-store', 'api\ApiSmStudentAttendanceController@studentSubjectAttendanceStore');
    Route::get('student-subject-attendance/{record_id}', 'api\ApiSmStudentAttendanceController@studentSubjectAttendanceSearch');
    Route::get('student-subject-attendance-check', 'api\ApiSmStudentAttendanceController@studentSubjectAttendanceCheck');

    Route::get('student-subject-attendance/{user_id}/{record_id}', 'api\ApiSmStudentAttendanceController@studentSubjectAttendanceSearch');

    Route::get('student-my-subject-attendance/{id}/{record_id}', 'api\ApiSmStudentAttendanceController@studentMySubjectAttendanceSearchAPI');
});

Route::get('apk-secret', function(){
    return response()->json(apk_secret());
});
