<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\RolePermission\Entities\InfixModuleInfo;

class RouteListController extends Controller
{
    
    public function index()
    {

        $zoom = InfixModuleInfo::where('name', 'like', '%' . 'zoom' . '%')->orWhere('route', 'like', '%' . 'zoom' . '%')->update(['module_name'=> 'Zoom']);
        $saas = InfixModuleInfo::where('name', 'like', '%' . 'saas' . '%')->orWhere('route', 'like', '%' . 'saas' . '%')->update(['module_name'=> 'Saas']);
        $bbb = InfixModuleInfo::where('name', 'like', '%' . 'bbb' . '%')
        ->orWhere('route', 'like', '%' . 'bbb' . '%')
        ->orWhere('route', 'like', '%' . 'bigbluebutton' . '%')
        ->update(['module_name'=> 'BBB']);
        $jitsi = InfixModuleInfo::where('name', 'like', '%' . 'jitsi' . '%')->orWhere('route', 'like', '%' . 'jitsi' . '%')->update(['module_name'=> 'Jitsi']);
        $parentregistration = InfixModuleInfo::where('name', 'like', '%' . 'parentregistration' . '%')->orWhere('route', 'like', '%' . 'parentregistration' . '%')->update(['module_name'=> 'ParentRegistration']);
        
        $formatRouteList = $this->routeFormat();
        foreach($formatRouteList as $key => $list){
           $va = InfixModuleInfo::updateOrCreate([
               'id'=>$key],
              ['name'=>$list['name'], 
               'route'=>$list['route'], 
               'parent_route'=>$list['parent_route'],
               'type'=>$list['type'],               
            ]);
        }
    }
    public function update()
    {
   //   return $this->adminSection();
      $all = InfixModuleInfo::get();
      $routes =  InfixModuleInfo::where('parent_id', 0)->get();
      $modifiyList = [];
      foreach($all as $item)
      {
         // $parent_route = InfixModuleInfo::where('parent_id', $item->id)->where('parent_id', '!=',0)->update(['parent_route'=>$item->route]);
         // $modifiyList[]=[
         //    'id'=>$item->id,
         //    'type'=>$item->type,
         //    'route'=>$item->route,
         //    'parent_route'=>$parent_route,
         //    'parent_id'=>$item->parent_id
         // ];
        
        
      }
      // return  $modifiyList;
      //   foreach($routes as $item) {      
      //           $str = str_replace('&', '', $item->name);
      //           $str = str_replace('  ', ' ', $str);
      //           $str = trim($str);
      //           $name = strtolower(str_replace(' ', '_', $str));
      //           $item->update(['route'=>$name]);
      //   }
      
         $routes =  InfixModuleInfo::get(['id','name', 'route', 'parent_route', 'type']);
         $formatRoute = [];
         foreach($routes as $route){
            $formatRoute[$route->id]= [
               'name' => $route->name,
               'route' => $route->route,
               'parent_route' => $route->parent_route,
               'type' => $route->type,
            ];
         }
         file_put_contents('route_list_format.php', '<?php  ' . var_export($formatRoute, true) . ';');
         
   
   }
    private function adminSection()
    {
       $data = [     
         [11, 2, 0, '1',  0, 'Admin Section','','admin_section','flaticon-analytics', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [12, 2, 11, '2', 0,  'Admission Query','admission_query','admission_query','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [13, 2, 12, '3', 0,  'Add','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [14, 2, 12, '3', 0,  'Edit','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [15, 2, 12, '3', 0,  'Delete','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

         [16, 2, 11, '2', 0, 'Visitor Book','visitor','visitor','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [17, 2, 16, '3', 0, 'Add','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [18, 2, 16, '3', 0, 'Edit','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [19, 2, 16, '3', 0, 'Delete','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
         [20, 2, 16, '3', 0, 'Download','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22']
      ];
      $modifiyList = [];
      foreach($data as $item) {
         $modifiyList[]=[
            'id'=>$item[0],
            'type'=>$item[3],
            'route'=>$item[6]
         ];
        
      }
      
    }
    private function routeFormat()
    {
       return $formatRouteList = array (
         1 => 
         array (
           'name' => 'Dashboard',
           'route' => 'dashboard',
           'parent_route' => NULL,
           'type' => 1,
         ),
         2 => 
         array (
           'name' => '➡ Number of Student',
           'route' => 'number-of-student',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         3 => 
         array (
           'name' => '➡ Number of Teacher',
           'route' => 'number-of-teacher',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         4 => 
         array (
           'name' => '➡ Number of Parents',
           'route' => 'number-of-parent',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         5 => 
         array (
           'name' => '➡ Number of Staff',
           'route' => 'number-of-staff',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         6 => 
         array (
           'name' => '➡ Current Month Income and Expense Chart',
           'route' => 'month-income-expense',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         7 => 
         array (
           'name' => '➡ Current Year Income and Expense Chart',
           'route' => 'year-income-expense',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         8 => 
         array (
           'name' => '➡ Notice Board',
           'route' => 'notice-board',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         9 => 
         array (
           'name' => '➡ Calendar Section',
           'route' => 'calender-section',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         10 => 
         array (
           'name' => '➡ To Do list',
           'route' => 'to-do-list',
           'parent_route' => 'dashboard',
           'type' => 3,
         ),
         11 => 
         array (
           'name' => 'Admin Section',
           'route' => 'admin_section',
           'parent_route' => NULL,
           'type' => 1,
         ),
         12 => 
         array (
           'name' => 'Admission Query',
           'route' => 'admission_query',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         13 => 
         array (
           'name' => 'Add',
           'route' => 'admission_query_store_a',
           'parent_route' => 'admission_query',
           'type' => 3,
         ),
         14 => 
         array (
           'name' => 'Edit',
           'route' => 'admission_query_edit',
           'parent_route' => 'admission_query',
           'type' => 3,
         ),
         15 => 
         array (
           'name' => 'Delete',
           'route' => 'admission_query_delete',
           'parent_route' => 'admission_query',
           'type' => 3,
         ),
         16 => 
         array (
           'name' => 'Visitor Book',
           'route' => 'visitor',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         17 => 
         array (
           'name' => 'Add',
           'route' => 'visitor_store',
           'parent_route' => 'visitor',
           'type' => 3,
         ),
         18 => 
         array (
           'name' => 'Edit',
           'route' => 'visitor_edit',
           'parent_route' => 'visitor',
           'type' => 3,
         ),
         19 => 
         array (
           'name' => 'Delete',
           'route' => 'visitor_delete',
           'parent_route' => 'visitor',
           'type' => 3,
         ),
         20 => 
         array (
           'name' => 'Download',
           'route' => 'visitor_download',
           'parent_route' => 'visitor',
           'type' => 3,
         ),
         21 => 
         array (
           'name' => 'Complaint',
           'route' => 'complaint',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         22 => 
         array (
           'name' => 'Add',
           'route' => 'complaint_store',
           'parent_route' => 'complaint',
           'type' => 3,
         ),
         23 => 
         array (
           'name' => 'Edit',
           'route' => 'complaint_edit',
           'parent_route' => 'complaint',
           'type' => 3,
         ),
         24 => 
         array (
           'name' => 'Delete',
           'route' => 'complaint_delete',
           'parent_route' => 'complaint',
           'type' => 3,
         ),
         25 => 
         array (
           'name' => 'Download',
           'route' => 'download-complaint-document',
           'parent_route' => 'complaint',
           'type' => 3,
         ),
         26 => 
         array (
           'name' => 'View',
           'route' => 'complaint_show',
           'parent_route' => 'complaint',
           'type' => 3,
         ),
         27 => 
         array (
           'name' => 'Postal Receive',
           'route' => 'postal-receive',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         28 => 
         array (
           'name' => 'Add',
           'route' => 'postal-receive-store',
           'parent_route' => 'postal-receive',
           'type' => 3,
         ),
         29 => 
         array (
           'name' => 'Edit',
           'route' => 'postal-receive_edit',
           'parent_route' => 'postal-receive',
           'type' => 3,
         ),
         30 => 
         array (
           'name' => 'Delete',
           'route' => 'postal-receive_delete',
           'parent_route' => 'postal-receive',
           'type' => 3,
         ),
         31 => 
         array (
           'name' => 'Download',
           'route' => 'postal-receive-document',
           'parent_route' => 'postal-receive',
           'type' => 3,
         ),
         32 => 
         array (
           'name' => 'Postal Dispatch',
           'route' => 'postal-dispatch',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         33 => 
         array (
           'name' => 'Add',
           'route' => 'postal-dispatch-store',
           'parent_route' => 'postal-dispatch',
           'type' => 3,
         ),
         34 => 
         array (
           'name' => 'Edit',
           'route' => 'postal-dispatch_edit',
           'parent_route' => 'postal-dispatch',
           'type' => 3,
         ),
         35 => 
         array (
           'name' => 'Delete',
           'route' => 'postal-dispatch_delete',
           'parent_route' => 'postal-dispatch',
           'type' => 3,
         ),
         36 => 
         array (
           'name' => 'Phone Call Log',
           'route' => 'phone-call',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         37 => 
         array (
           'name' => 'Add',
           'route' => 'phone-call-store',
           'parent_route' => 'phone-call',
           'type' => 3,
         ),
         38 => 
         array (
           'name' => 'Edit',
           'route' => 'phone-call_edit',
           'parent_route' => 'phone-call',
           'type' => 3,
         ),
         39 => 
         array (
           'name' => 'Delete',
           'route' => 'phone-call_delete',
           'parent_route' => 'phone-call',
           'type' => 3,
         ),
         40 => 
         array (
           'name' => 'Download',
           'route' => 'postal-dispatch-document',
           'parent_route' => 'postal-dispatch',
           'type' => 3,
         ),
         41 => 
         array (
           'name' => 'Admin Setup',
           'route' => 'setup-admin',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         42 => 
         array (
           'name' => 'Add',
           'route' => 'setup-admin-store',
           'parent_route' => 'setup-admin',
           'type' => 3,
         ),
         43 => 
         array (
           'name' => 'Edit',
           'route' => 'setup-admin-edit',
           'parent_route' => 'setup-admin',
           'type' => 3,
         ),
         44 => 
         array (
           'name' => 'Delete',
           'route' => 'setup-admin-delete',
           'parent_route' => 'setup-admin',
           'type' => 3,
         ),
         45 => 
         array (
           'name' => 'Student ID Card',
           'route' => 'student-id-card',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         46 => 
         array (
           'name' => 'Add',
           'route' => 'create-id-card',
           'parent_route' => 'student-id-card',
           'type' => 3,
         ),
         47 => 
         array (
           'name' => 'Edit',
           'route' => 'student-id-card-edit',
           'parent_route' => 'student-id-card',
           'type' => 3,
         ),
         48 => 
         array (
           'name' => 'Delete',
           'route' => 'student-id-card-delete',
           'parent_route' => 'student-id-card',
           'type' => 3,
         ),
         49 => 
         array (
           'name' => 'Student Certificate',
           'route' => 'student-certificate',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         50 => 
         array (
           'name' => 'Add',
           'route' => 'student-certificate-store',
           'parent_route' => 'student-certificate',
           'type' => 3,
         ),
         51 => 
         array (
           'name' => 'Edit',
           'route' => 'student-certificate-edit',
           'parent_route' => 'student-certificate',
           'type' => 3,
         ),
         52 => 
         array (
           'name' => 'Delete',
           'route' => 'student-certificate-delete',
           'parent_route' => 'student-certificate',
           'type' => 3,
         ),
         53 => 
         array (
           'name' => 'Generate Certificate',
           'route' => 'generate_certificate',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         54 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'generate_certificate',
           'type' => 3,
         ),
         55 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'generate_certificate',
           'type' => 3,
         ),
         56 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'generate_certificate',
           'type' => 3,
         ),
         57 => 
         array (
           'name' => 'Generate ID Card',
           'route' => 'generate_id_card',
           'parent_route' => 'admin_section',
           'type' => 2,
         ),
         58 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'generate_id_card',
           'type' => 3,
         ),
         59 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'generate_id_card',
           'type' => 3,
         ),
         60 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'generate_id_card',
           'type' => 3,
         ),
         61 => 
         array (
           'name' => 'Student Info',
           'route' => 'student_info',
           'parent_route' => NULL,
           'type' => 1,
         ),
         62 => 
         array (
           'name' => 'Add Student',
           'route' => 'student_admission',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         63 => 
         array (
           'name' => 'Import Student',
           'route' => 'import_student',
           'parent_route' => 'student_admission',
           'type' => 3,
         ),
         64 => 
         array (
           'name' => 'Student List',
           'route' => 'student_list',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         65 => 
         array (
           'name' => 'Add',
           'route' => 'student_store',
           'parent_route' => 'student_list',
           'type' => 3,
         ),
         66 => 
         array (
           'name' => 'Edit',
           'route' => 'student_edit',
           'parent_route' => 'student_list',
           'type' => 3,
         ),
         67 => 
         array (
           'name' => 'Delete',
           'route' => 'disabled_student',
           'parent_route' => 'student_list',
           'type' => 3,
         ),
         68 => 
         array (
           'name' => 'Student Attendance',
           'route' => 'student_attendance',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         69 => 
         array (
           'name' => 'Add',
           'route' => 'student-attendance-store',
           'parent_route' => 'student_attendance',
           'type' => 3,
         ),
         70 => 
         array (
           'name' => 'Student Attendance Report',
           'route' => 'student_attendance_report',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         71 => 
         array (
           'name' => 'Student Category',
           'route' => 'student_category',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         72 => 
         array (
           'name' => 'Add',
           'route' => 'student_category_store',
           'parent_route' => 'student_category',
           'type' => 3,
         ),
         73 => 
         array (
           'name' => 'Edit',
           'route' => 'student_category_edit',
           'parent_route' => 'student_category',
           'type' => 3,
         ),
         74 => 
         array (
           'name' => 'Delete',
           'route' => 'student_category_delete',
           'parent_route' => 'student_category',
           'type' => 3,
         ),
         75 => 
         array (
           'name' => 'Download',
           'route' => '',
           'parent_route' => 'student_category',
           'type' => 3,
         ),
         76 => 
         array (
           'name' => 'Student Group',
           'route' => 'student_group',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         77 => 
         array (
           'name' => 'Add',
           'route' => 'student_group_store',
           'parent_route' => 'student_group',
           'type' => 3,
         ),
         79 => 
         array (
           'name' => 'Edit',
           'route' => 'student_group_edit',
           'parent_route' => 'student_group',
           'type' => 3,
         ),
         80 => 
         array (
           'name' => 'Delete',
           'route' => 'student_group_delete',
           'parent_route' => 'student_group',
           'type' => 3,
         ),
         81 => 
         array (
           'name' => 'Student Promote',
           'route' => 'student_promote',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         82 => 
         array (
           'name' => 'Add',
           'route' => 'student-promote-store',
           'parent_route' => 'student_promote',
           'type' => 3,
         ),
         83 => 
         array (
           'name' => 'Disabled Students',
           'route' => 'disabled_student',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         84 => 
         array (
           'name' => 'Search',
           'route' => 'disabled_student_search',
           'parent_route' => 'disabled_student',
           'type' => 3,
         ),
         85 => 
         array (
           'name' => 'Enable',
           'route' => 'enable_student',
           'parent_route' => 'disabled_student',
           'type' => 3,
         ),
         86 => 
         array (
           'name' => 'Delete',
           'route' => 'disable_student_delete',
           'parent_route' => 'disabled_student',
           'type' => 3,
         ),
         87 => 
         array (
           'name' => 'Study Material',
           'route' => 'study_material',
           'parent_route' => NULL,
           'type' => 1,
         ),
         88 => 
         array (
           'name' => 'Upload Content',
           'route' => 'upload-content',
           'parent_route' => 'study_material',
           'type' => 2,
         ),
         89 => 
         array (
           'name' => 'Add',
           'route' => 'save-upload-content',
           'parent_route' => 'upload-content',
           'type' => 3,
         ),
         90 => 
         array (
           'name' => 'Download',
           'route' => 'download-content-document',
           'parent_route' => 'upload-content',
           'type' => 3,
         ),
         91 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-upload-content',
           'parent_route' => 'upload-content',
           'type' => 3,
         ),
         92 => 
         array (
           'name' => 'Assignment',
           'route' => 'assignment-list',
           'parent_route' => 'study_material',
           'type' => 2,
         ),
         93 => 
         array (
           'name' => 'Edit',
           'route' => 'assignment-list-edit',
           'parent_route' => 'assignment-list',
           'type' => 3,
         ),
         94 => 
         array (
           'name' => 'Download',
           'route' => 'assignment-list-download',
           'parent_route' => 'assignment-list',
           'type' => 3,
         ),
         95 => 
         array (
           'name' => 'Delete',
           'route' => 'assignment-list-delete',
           'parent_route' => 'assignment-list',
           'type' => 3,
         ),
         100 => 
         array (
           'name' => 'Syllabus',
           'route' => 'syllabus-list',
           'parent_route' => 'study_material',
           'type' => 2,
         ),
         101 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'syllabus-list',
           'type' => 3,
         ),
         102 => 
         array (
           'name' => 'Edit',
           'route' => 'syllabus-list-edit',
           'parent_route' => 'syllabus-list',
           'type' => 3,
         ),
         103 => 
         array (
           'name' => 'Delete',
           'route' => 'syllabus-list-delete',
           'parent_route' => 'syllabus-list',
           'type' => 3,
         ),
         104 => 
         array (
           'name' => 'Download',
           'route' => 'syllabus-list-download',
           'parent_route' => 'syllabus-list',
           'type' => 3,
         ),
         105 => 
         array (
           'name' => 'Other Downloads',
           'route' => 'other-download-list',
           'parent_route' => 'study_material',
           'type' => 2,
         ),
         106 => 
         array (
           'name' => 'Download',
           'route' => 'other-download-list-download',
           'parent_route' => 'other-download-list',
           'type' => 3,
         ),
         107 => 
         array (
           'name' => 'Delete',
           'route' => 'other-download-list-delete',
           'parent_route' => 'other-download-list',
           'type' => 3,
         ),
         108 => 
         array (
           'name' => 'Fees Collection',
           'route' => 'fees_collection',
           'parent_route' => NULL,
           'type' => 1,
         ),
         109 => 
         array (
           'name' => 'Collect Fees',
           'route' => 'collect_fees',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         110 => 
         array (
           'name' => ' Collect Fees',
           'route' => 'fees_collect_student_wise',
           'parent_route' => 'collect_fees',
           'type' => 3,
         ),
         111 => 
         array (
           'name' => 'Add',
           'route' => 'fees-generate-modal',
           'parent_route' => 'collect_fees',
           'type' => 3,
         ),
         112 => 
         array (
           'name' => 'Print',
           'route' => 'fees_payment_print',
           'parent_route' => 'collect_fees',
           'type' => 3,
         ),
         113 => 
         array (
           'name' => 'Search Fees Payment',
           'route' => 'search_fees_payment',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         114 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'search_fees_payment',
           'type' => 3,
         ),
         115 => 
         array (
           'name' => 'edit',
           'route' => 'edit-fees-payment',
           'parent_route' => 'search_fees_payment',
           'type' => 3,
         ),
         116 => 
         array (
           'name' => 'Search Fees Due',
           'route' => 'search_fees_due',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         117 => 
         array (
           'name' => 'View',
           'route' => '',
           'parent_route' => 'search_fees_due',
           'type' => 3,
         ),
         118 => 
         array (
           'name' => 'Fees Master',
           'route' => 'fees-master',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         119 => 
         array (
           'name' => 'Add',
           'route' => 'fees-master-store',
           'parent_route' => 'fees-master',
           'type' => 3,
         ),
         120 => 
         array (
           'name' => 'Edit',
           'route' => 'fees-master-edit',
           'parent_route' => 'fees-master',
           'type' => 3,
         ),
         121 => 
         array (
           'name' => 'Delete',
           'route' => 'fees-master-delete',
           'parent_route' => 'fees-master',
           'type' => 3,
         ),
         122 => 
         array (
           'name' => 'Assign',
           'route' => 'fees_assign',
           'parent_route' => 'fees-master',
           'type' => 3,
         ),
         123 => 
         array (
           'name' => 'Fees Group',
           'route' => 'fees_group',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         124 => 
         array (
           'name' => 'Add',
           'route' => 'fees_group_store',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         125 => 
         array (
           'name' => 'Edit',
           'route' => 'fees_group_edit',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         126 => 
         array (
           'name' => 'Delete',
           'route' => 'fees_group_delete',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         127 => 
         array (
           'name' => 'Fees Type',
           'route' => 'fees_type',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         128 => 
         array (
           'name' => 'Add',
           'route' => 'fees_type_store',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         129 => 
         array (
           'name' => 'Edit',
           'route' => 'fees_type_edit',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         130 => 
         array (
           'name' => 'Delete',
           'route' => 'fees_type_delete',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         131 => 
         array (
           'name' => 'Fees Discount',
           'route' => 'fees_discount',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         132 => 
         array (
           'name' => 'Add',
           'route' => 'fees_discount_store',
           'parent_route' => 'fees_discount',
           'type' => 3,
         ),
         133 => 
         array (
           'name' => 'Edit',
           'route' => 'fees_discount_edit',
           'parent_route' => 'fees_discount',
           'type' => 3,
         ),
         134 => 
         array (
           'name' => 'Delete',
           'route' => 'fees_discount_delete',
           'parent_route' => 'fees_discount',
           'type' => 3,
         ),
         135 => 
         array (
           'name' => 'Assign',
           'route' => 'fees_discount_assign',
           'parent_route' => 'fees_discount',
           'type' => 3,
         ),
         136 => 
         array (
           'name' => 'Fees Carry Forward',
           'route' => 'fees_forward',
           'parent_route' => 'fees_collection',
           'type' => 3,
         ),
         137 => 
         array (
           'name' => 'Accounts',
           'route' => 'accounts',
           'parent_route' => NULL,
           'type' => 1,
         ),
         138 => 
         array (
           'name' => 'Profit & Loss',
           'route' => 'profit',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         139 => 
         array (
           'name' => 'Income',
           'route' => 'add_income',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         140 => 
         array (
           'name' => 'Add',
           'route' => 'add_income_store',
           'parent_route' => 'add_income',
           'type' => 3,
         ),
         141 => 
         array (
           'name' => 'Edit',
           'route' => 'add_income_edit',
           'parent_route' => 'add_income',
           'type' => 3,
         ),
         142 => 
         array (
           'name' => 'Delete',
           'route' => 'add_income_delete',
           'parent_route' => 'add_income',
           'type' => 3,
         ),
         143 => 
         array (
           'name' => 'Expense',
           'route' => 'add-expense',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         144 => 
         array (
           'name' => 'Add',
           'route' => 'add-expense-store',
           'parent_route' => 'add-expense',
           'type' => 3,
         ),
         145 => 
         array (
           'name' => 'Edit',
           'route' => 'add-expense-edit',
           'parent_route' => 'add-expense',
           'type' => 3,
         ),
         146 => 
         array (
           'name' => 'Delete',
           'route' => 'add-expense-delete',
           'parent_route' => 'add-expense',
           'type' => 3,
         ),
         148 => 
         array (
           'name' => 'Chart of Account',
           'route' => 'chart-of-account',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         149 => 
         array (
           'name' => 'Add',
           'route' => 'chart-of-account-store',
           'parent_route' => 'chart-of-account',
           'type' => 3,
         ),
         150 => 
         array (
           'name' => 'Edit',
           'route' => 'chart-of-account-edit',
           'parent_route' => 'chart-of-account',
           'type' => 3,
         ),
         151 => 
         array (
           'name' => 'Delete',
           'route' => 'chart-of-account-delete',
           'parent_route' => 'chart-of-account',
           'type' => 3,
         ),
         153 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         154 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         155 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         156 => 
         array (
           'name' => 'Bank Account',
           'route' => 'bank-account',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         157 => 
         array (
           'name' => 'Add',
           'route' => 'bank-account-store',
           'parent_route' => 'bank-account',
           'type' => 3,
         ),
         158 => 
         array (
           'name' => 'Bank Transaction',
           'route' => 'bank-transaction',
           'parent_route' => 'bank-account',
           'type' => 3,
         ),
         159 => 
         array (
           'name' => 'Delete',
           'route' => 'bank-account-delete',
           'parent_route' => 'bank-account',
           'type' => 3,
         ),
         160 => 
         array (
           'name' => 'Human Resource',
           'route' => 'human_resource',
           'parent_route' => NULL,
           'type' => 1,
         ),
         161 => 
         array (
           'name' => 'Staff Directory',
           'route' => 'staff_directory',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         162 => 
         array (
           'name' => 'Add',
           'route' => 'addStaff',
           'parent_route' => 'staff_directory',
           'type' => 3,
         ),
         163 => 
         array (
           'name' => 'Edit',
           'route' => 'editStaff',
           'parent_route' => 'staff_directory',
           'type' => 3,
         ),
         164 => 
         array (
           'name' => 'Delete',
           'route' => 'deleteStaff',
           'parent_route' => 'staff_directory',
           'type' => 3,
         ),
         165 => 
         array (
           'name' => 'Staff Attendance',
           'route' => 'staff_attendance',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         166 => 
         array (
           'name' => 'Add',
           'route' => 'staff-attendance-store',
           'parent_route' => 'staff_attendance',
           'type' => 3,
         ),
         167 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'staff_attendance',
           'type' => 3,
         ),
         168 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'staff_attendance',
           'type' => 3,
         ),
         169 => 
         array (
           'name' => 'Staff Attendance Report',
           'route' => 'staff_attendance_report',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         170 => 
         array (
           'name' => 'Payroll',
           'route' => 'payroll',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         171 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         172 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         173 => 
         array (
           'name' => 'Search',
           'route' => 'payroll-search',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         174 => 
         array (
           'name' => 'Generate Payroll',
           'route' => 'generate-Payroll',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         175 => 
         array (
           'name' => 'Create',
           'route' => 'savePayrollData',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         176 => 
         array (
           'name' => 'Proceed To Pay',
           'route' => 'pay-payroll',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         177 => 
         array (
           'name' => 'View Payslip',
           'route' => 'view-payslip',
           'parent_route' => 'payroll',
           'type' => 3,
         ),
         178 => 
         array (
           'name' => 'Payroll Report',
           'route' => 'payroll-report',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         179 => 
         array (
           'name' => 'Report Search',
           'route' => '',
           'parent_route' => 'payroll-report',
           'type' => 3,
         ),
         180 => 
         array (
           'name' => 'Designation',
           'route' => 'designation',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         181 => 
         array (
           'name' => 'Add',
           'route' => 'designation-store',
           'parent_route' => 'designation',
           'type' => 3,
         ),
         182 => 
         array (
           'name' => 'Edit',
           'route' => 'designation-edit',
           'parent_route' => 'designation',
           'type' => 3,
         ),
         183 => 
         array (
           'name' => 'Delete',
           'route' => 'designation-delete',
           'parent_route' => 'designation',
           'type' => 3,
         ),
         184 => 
         array (
           'name' => 'Department',
           'route' => 'department',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         185 => 
         array (
           'name' => 'Add',
           'route' => 'department-store',
           'parent_route' => 'department',
           'type' => 3,
         ),
         186 => 
         array (
           'name' => 'Edit',
           'route' => 'department-edit',
           'parent_route' => 'department',
           'type' => 3,
         ),
         187 => 
         array (
           'name' => 'Delete',
           'route' => 'department-delete',
           'parent_route' => 'department',
           'type' => 3,
         ),
         188 => 
         array (
           'name' => 'Leave',
           'route' => 'leave',
           'parent_route' => NULL,
           'type' => 1,
         ),
         189 => 
         array (
           'name' => 'Approve Leave Request',
           'route' => 'approve-leave',
           'parent_route' => 'leave',
           'type' => 2,
         ),
         190 => 
         array (
           'name' => 'Add',
           'route' => 'approve-leave-store',
           'parent_route' => 'approve-leave',
           'type' => 3,
         ),
         191 => 
         array (
           'name' => 'Edit',
           'route' => 'approve-leave-edit',
           'parent_route' => 'approve-leave',
           'type' => 3,
         ),
         192 => 
         array (
           'name' => 'Delete',
           'route' => 'approve-leave-delete',
           'parent_route' => 'approve-leave',
           'type' => 3,
         ),
         193 => 
         array (
           'name' => 'Apply Leave',
           'route' => 'apply-leave',
           'parent_route' => 'leave',
           'type' => 2,
         ),
         194 => 
         array (
           'name' => 'View',
           'route' => 'view-leave-details-apply',
           'parent_route' => 'apply-leave',
           'type' => 3,
         ),
         195 => 
         array (
           'name' => 'Delete',
           'route' => 'apply-leave-delete',
           'parent_route' => 'apply-leave',
           'type' => 3,
         ),
         196 => 
         array (
           'name' => 'Pending Leave',
           'route' => 'pending-leave',
           'parent_route' => 'leave',
           'type' => 2,
         ),
         197 => 
         array (
           'name' => 'View',
           'route' => 'view-leave-details-approve',
           'parent_route' => 'pending-leave',
           'type' => 3,
         ),
         198 => 
         array (
           'name' => 'Delete',
           'route' => 'apply-leave-delete',
           'parent_route' => 'pending-leave',
           'type' => 3,
         ),
         199 => 
         array (
           'name' => 'Leave Define',
           'route' => 'leave-define',
           'parent_route' => 'leave',
           'type' => 2,
         ),
         200 => 
         array (
           'name' => 'Add',
           'route' => 'leave-define-store',
           'parent_route' => 'leave-define',
           'type' => 3,
         ),
         201 => 
         array (
           'name' => 'Edit',
           'route' => 'leave-define-edit',
           'parent_route' => 'leave-define',
           'type' => 3,
         ),
         202 => 
         array (
           'name' => 'Delete',
           'route' => 'leave-define-delete',
           'parent_route' => 'leave-define',
           'type' => 3,
         ),
         203 => 
         array (
           'name' => 'Leave Type',
           'route' => 'leave-type',
           'parent_route' => 'leave',
           'type' => 2,
         ),
         204 => 
         array (
           'name' => 'Add',
           'route' => 'leave-type-store',
           'parent_route' => 'leave-type',
           'type' => 3,
         ),
         205 => 
         array (
           'name' => 'Edit',
           'route' => 'leave-type-edit',
           'parent_route' => 'leave-type',
           'type' => 3,
         ),
         206 => 
         array (
           'name' => 'Delete',
           'route' => 'leave-type-delete',
           'parent_route' => 'leave-type',
           'type' => 3,
         ),
         207 => 
         array (
           'name' => 'Examination',
           'route' => 'examination',
           'parent_route' => NULL,
           'type' => 1,
         ),
         208 => 
         array (
           'name' => 'Exam Type',
           'route' => 'exam-type',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         209 => 
         array (
           'name' => 'Add',
           'route' => 'exam_type_store',
           'parent_route' => 'exam-type',
           'type' => 3,
         ),
         210 => 
         array (
           'name' => 'Edit',
           'route' => 'exam_type_edit',
           'parent_route' => 'exam-type',
           'type' => 3,
         ),
         211 => 
         array (
           'name' => 'Delete',
           'route' => 'exam_type_delete',
           'parent_route' => 'exam-type',
           'type' => 3,
         ),
         214 => 
         array (
           'name' => 'Exam Setup',
           'route' => 'exam',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         215 => 
         array (
           'name' => 'Add',
           'route' => 'exam-setup-store',
           'parent_route' => 'exam',
           'type' => 3,
         ),
         216 => 
         array (
           'name' => 'Delete',
           'route' => 'exam-delete',
           'parent_route' => 'exam',
           'type' => 3,
         ),
         217 => 
         array (
           'name' => 'Exam Schedule',
           'route' => 'exam_schedule',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         218 => 
         array (
           'name' => 'Add',
           'route' => 'exam_schedule_store',
           'parent_route' => 'exam_schedule',
           'type' => 3,
         ),
         219 => 
         array (
           'name' => 'print',
           'route' => 'exam-routine-print',
           'parent_route' => 'exam_schedule',
           'type' => 3,
         ),
         220 => 
         array (
           'name' => 'Exam Attendance',
           'route' => 'exam_attendance',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         221 => 
         array (
           'name' => 'Add',
           'route' => 'exam_attendance_create',
           'parent_route' => 'exam_attendance',
           'type' => 3,
         ),
         222 => 
         array (
           'name' => 'Marks Register',
           'route' => 'marks_register',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         223 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'marks_register',
           'type' => 3,
         ),
         224 => 
         array (
           'name' => 'Create',
           'route' => 'marks_register_create',
           'parent_route' => 'marks_register',
           'type' => 3,
         ),
         225 => 
         array (
           'name' => 'Marks Grade',
           'route' => 'marks-grade',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         226 => 
         array (
           'name' => 'Add',
           'route' => 'marks-grade-store',
           'parent_route' => 'marks-grade',
           'type' => 3,
         ),
         227 => 
         array (
           'name' => 'Edit',
           'route' => 'marks-grade-edit',
           'parent_route' => 'marks-grade',
           'type' => 3,
         ),
         228 => 
         array (
           'name' => 'Delete',
           'route' => 'marks-grade-delete',
           'parent_route' => 'marks-grade',
           'type' => 3,
         ),
         229 => 
         array (
           'name' => 'Send Marks By SMS',
           'route' => 'send_marks_by_sms',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         230 => 
         array (
           'name' => 'Question Group',
           'route' => 'question-group',
           'parent_route' => 'online_exam',
           'type' => 2,
         ),
         231 => 
         array (
           'name' => 'Add',
           'route' => 'question-group-store',
           'parent_route' => 'question-group',
           'type' => 3,
         ),
         232 => 
         array (
           'name' => 'Edit',
           'route' => 'question-group-edit',
           'parent_route' => 'question-group',
           'type' => 3,
         ),
         233 => 
         array (
           'name' => 'Delete',
           'route' => 'question-group-delete',
           'parent_route' => 'question-group',
           'type' => 3,
         ),
         234 => 
         array (
           'name' => 'Question Bank',
           'route' => 'question-bank',
           'parent_route' => 'online_exam',
           'type' => 2,
         ),
         235 => 
         array (
           'name' => 'Add',
           'route' => 'question-bank-store',
           'parent_route' => 'question-bank',
           'type' => 3,
         ),
         236 => 
         array (
           'name' => 'Edit',
           'route' => 'question-bank-edit',
           'parent_route' => 'question-bank',
           'type' => 3,
         ),
         237 => 
         array (
           'name' => 'Delete',
           'route' => 'question-bank-delete',
           'parent_route' => 'question-bank',
           'type' => 3,
         ),
         238 => 
         array (
           'name' => 'Online Exam',
           'route' => 'online-exam',
           'parent_route' => 'online_exam',
           'type' => 2,
         ),
         239 => 
         array (
           'name' => 'Add',
           'route' => 'online-exam-store',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         240 => 
         array (
           'name' => 'Edit',
           'route' => 'online-exam-edit',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         241 => 
         array (
           'name' => 'Delete',
           'route' => 'online-exam-delete',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         242 => 
         array (
           'name' => 'Manage Question',
           'route' => 'manage_online_exam_question',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         243 => 
         array (
           'name' => 'Marks Register',
           'route' => 'online_exam_marks_register',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         244 => 
         array (
           'name' => 'Result',
           'route' => 'online_exam_result',
           'parent_route' => 'online-exam',
           'type' => 3,
         ),
         245 => 
         array (
           'name' => 'Academics',
           'route' => 'academics',
           'parent_route' => NULL,
           'type' => 1,
         ),
         246 => 
         array (
           'name' => 'Class Routine',
           'route' => 'class_routine_new',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         247 => 
         array (
           'name' => 'Add',
           'route' => 'add-new-class-routine-store',
           'parent_route' => 'class_routine_new',
           'type' => 3,
         ),
         248 => 
         array (
           'name' => 'Print',
           'route' => 'classRoutinePrint',
           'parent_route' => 'class_routine_new',
           'type' => 3,
         ),
         249 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-class-routine',
           'parent_route' => 'class_routine_new',
           'type' => 3,
         ),
         250 => 
         array (
           'name' => 'Assign Subject',
           'route' => 'assign_subject',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         251 => 
         array (
           'name' => 'Add',
           'route' => 'assign-subject-store',
           'parent_route' => 'assign_subject',
           'type' => 3,
         ),
         252 => 
         array (
           'name' => 'view',
           'route' => 'assign_subject_create',
           'parent_route' => 'assign_subject',
           'type' => 3,
         ),
         253 => 
         array (
           'name' => 'Assign Class Teacher',
           'route' => 'assign-class-teacher',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         254 => 
         array (
           'name' => 'Add',
           'route' => 'assign-class-teacher-store',
           'parent_route' => 'assign-class-teacher',
           'type' => 3,
         ),
         255 => 
         array (
           'name' => 'Edit',
           'route' => 'assign-class-teacher-edit',
           'parent_route' => 'assign-class-teacher',
           'type' => 3,
         ),
         256 => 
         array (
           'name' => 'Delete',
           'route' => 'assign-class-teacher-delete',
           'parent_route' => 'assign-class-teacher',
           'type' => 3,
         ),
         257 => 
         array (
           'name' => 'Subjects',
           'route' => 'subject',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         258 => 
         array (
           'name' => 'Add',
           'route' => 'subject_store',
           'parent_route' => 'subject',
           'type' => 3,
         ),
         259 => 
         array (
           'name' => 'Edit',
           'route' => 'subject_edit',
           'parent_route' => 'subject',
           'type' => 3,
         ),
         260 => 
         array (
           'name' => 'Delete',
           'route' => 'subject_delete',
           'parent_route' => 'subject',
           'type' => 3,
         ),
         261 => 
         array (
           'name' => 'Class',
           'route' => 'class',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         262 => 
         array (
           'name' => 'Add',
           'route' => 'class_store',
           'parent_route' => 'class',
           'type' => 3,
         ),
         263 => 
         array (
           'name' => 'Edit',
           'route' => 'class_edit',
           'parent_route' => 'class',
           'type' => 3,
         ),
         264 => 
         array (
           'name' => 'Delete',
           'route' => 'class_delete',
           'parent_route' => 'class',
           'type' => 3,
         ),
         265 => 
         array (
           'name' => 'Section',
           'route' => 'section',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         266 => 
         array (
           'name' => 'Add',
           'route' => 'section_store',
           'parent_route' => 'section',
           'type' => 3,
         ),
         267 => 
         array (
           'name' => 'Edit',
           'route' => 'section_edit',
           'parent_route' => 'section',
           'type' => 3,
         ),
         268 => 
         array (
           'name' => 'Delete',
           'route' => 'section_delete',
           'parent_route' => 'section',
           'type' => 3,
         ),
         269 => 
         array (
           'name' => 'Class Room',
           'route' => 'class-room',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         270 => 
         array (
           'name' => 'Add',
           'route' => 'class-room-store',
           'parent_route' => 'class-room',
           'type' => 3,
         ),
         271 => 
         array (
           'name' => 'Edit',
           'route' => 'class-room-edit',
           'parent_route' => 'class-room',
           'type' => 3,
         ),
         272 => 
         array (
           'name' => 'Delete',
           'route' => 'class-room-delete',
           'parent_route' => 'class-room',
           'type' => 3,
         ),
         273 => 
         array (
           'name' => 'Class Time Setup',
           'route' => 'class-time',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         274 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'class-time',
           'type' => 3,
         ),
         275 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'class-time',
           'type' => 3,
         ),
         276 => 
         array (
           'name' => 'Delete',
           'route' => '',
           'parent_route' => 'class-time',
           'type' => 3,
         ),
         277 => 
         array (
           'name' => 'Homework',
           'route' => 'homework',
           'parent_route' => NULL,
           'type' => 1,
         ),
         278 => 
         array (
           'name' => 'Add Homework',
           'route' => 'add-homeworks',
           'parent_route' => 'homework',
           'type' => 2,
         ),
         279 => 
         array (
           'name' => 'Add',
           'route' => 'saveHomeworkData',
           'parent_route' => 'add-homeworks',
           'type' => 3,
         ),
         280 => 
         array (
           'name' => 'Homework List',
           'route' => 'homework-list',
           'parent_route' => 'homework',
           'type' => 2,
         ),
         281 => 
         array (
           'name' => 'Evaluation',
           'route' => 'evaluation-homework',
           'parent_route' => 'homework-list',
           'type' => 3,
         ),
         282 => 
         array (
           'name' => 'Edit',
           'route' => 'homework_edit',
           'parent_route' => 'homework-list',
           'type' => 3,
         ),
         283 => 
         array (
           'name' => 'Delete',
           'route' => 'homework_delete',
           'parent_route' => 'homework-list',
           'type' => 3,
         ),
         284 => 
         array (
           'name' => 'Homework Evaluation Report',
           'route' => 'evaluation-report',
           'parent_route' => 'homework',
           'type' => 2,
         ),
         285 => 
         array (
           'name' => 'View',
           'route' => 'view-evaluation-report',
           'parent_route' => 'evaluation-report',
           'type' => 3,
         ),
         286 => 
         array (
           'name' => 'Communicate',
           'route' => 'communicate',
           'parent_route' => NULL,
           'type' => 1,
         ),
         287 => 
         array (
           'name' => 'Notice Board',
           'route' => 'notice-list',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         288 => 
         array (
           'name' => 'Add',
           'route' => 'add-notice',
           'parent_route' => 'notice-list',
           'type' => 3,
         ),
         289 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-notice',
           'parent_route' => 'notice-list',
           'type' => 3,
         ),
         290 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-notice-view',
           'parent_route' => 'notice-list',
           'type' => 3,
         ),
         291 => 
         array (
           'name' => 'Send Email / SMS ',
           'route' => 'send-email-sms-view',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         292 => 
         array (
           'name' => 'Send',
           'route' => 'send-email-sms',
           'parent_route' => 'send-email-sms-view',
           'type' => 3,
         ),
         293 => 
         array (
           'name' => 'Email / SMS Log',
           'route' => 'email-sms-log',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         294 => 
         array (
           'name' => 'Event',
           'route' => 'event',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         295 => 
         array (
           'name' => 'Add',
           'route' => 'event-store',
           'parent_route' => 'event',
           'type' => 3,
         ),
         296 => 
         array (
           'name' => 'Edit',
           'route' => 'event-edit',
           'parent_route' => 'event',
           'type' => 3,
         ),
         297 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-event-view',
           'parent_route' => 'event',
           'type' => 3,
         ),
         298 => 
         array (
           'name' => 'Library',
           'route' => 'library',
           'parent_route' => NULL,
           'type' => 1,
         ),
         299 => 
         array (
           'name' => 'Add Book',
           'route' => 'add-book',
           'parent_route' => 'library',
           'type' => 2,
         ),
         300 => 
         array (
           'name' => 'Add',
           'route' => 'save-book-data',
           'parent_route' => 'add-book',
           'type' => 3,
         ),
         301 => 
         array (
           'name' => 'Book List ',
           'route' => 'book-list',
           'parent_route' => 'library',
           'type' => 2,
         ),
         302 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-book',
           'parent_route' => 'book-list',
           'type' => 3,
         ),
         303 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-book-view',
           'parent_route' => 'book-list',
           'type' => 3,
         ),
         304 => 
         array (
           'name' => 'Book Categories',
           'route' => 'book-category-list',
           'parent_route' => 'library',
           'type' => 2,
         ),
         305 => 
         array (
           'name' => 'Add',
           'route' => 'book-category-list-store',
           'parent_route' => 'book-category-list',
           'type' => 3,
         ),
         306 => 
         array (
           'name' => 'Edit',
           'route' => 'book-category-list-edit',
           'parent_route' => 'book-category-list',
           'type' => 3,
         ),
         307 => 
         array (
           'name' => 'Delete',
           'route' => 'book-category-list-delete',
           'parent_route' => 'book-category-list',
           'type' => 3,
         ),
         308 => 
         array (
           'name' => 'Add Member',
           'route' => 'library-member',
           'parent_route' => 'library',
           'type' => 2,
         ),
         309 => 
         array (
           'name' => 'Add',
           'route' => 'library-member-store',
           'parent_route' => 'library-member',
           'type' => 3,
         ),
         310 => 
         array (
           'name' => 'Cancel',
           'route' => 'cancel-membership',
           'parent_route' => 'library-member',
           'type' => 3,
         ),
         311 => 
         array (
           'name' => 'Issue/Return Book',
           'route' => 'member-list',
           'parent_route' => 'library',
           'type' => 2,
         ),
         312 => 
         array (
           'name' => 'Issue',
           'route' => 'issue-books',
           'parent_route' => 'member-list',
           'type' => 3,
         ),
         313 => 
         array (
           'name' => 'Return',
           'route' => 'return-book-view',
           'parent_route' => 'member-list',
           'type' => 3,
         ),
         314 => 
         array (
           'name' => 'All Issued Book',
           'route' => 'all-issed-book',
           'parent_route' => 'library',
           'type' => 2,
         ),
         315 => 
         array (
           'name' => 'Inventory',
           'route' => 'inventory',
           'parent_route' => NULL,
           'type' => 1,
         ),
         316 => 
         array (
           'name' => 'Item Category',
           'route' => 'item-category',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         317 => 
         array (
           'name' => 'Add',
           'route' => 'item-category-store',
           'parent_route' => 'item-category',
           'type' => 3,
         ),
         318 => 
         array (
           'name' => 'Edit',
           'route' => 'item-category-edit',
           'parent_route' => 'item-category',
           'type' => 3,
         ),
         319 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-item-category-view',
           'parent_route' => 'item-category',
           'type' => 3,
         ),
         320 => 
         array (
           'name' => 'Item List',
           'route' => 'item-list',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         321 => 
         array (
           'name' => 'Add',
           'route' => 'item-list-store',
           'parent_route' => 'item-list',
           'type' => 3,
         ),
         322 => 
         array (
           'name' => 'Edit',
           'route' => 'item-list-edit',
           'parent_route' => 'item-list',
           'type' => 3,
         ),
         323 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-item-view',
           'parent_route' => 'item-list',
           'type' => 3,
         ),
         324 => 
         array (
           'name' => 'Item Store',
           'route' => 'item-store',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         325 => 
         array (
           'name' => 'Add',
           'route' => 'item-store-store',
           'parent_route' => 'item-store',
           'type' => 3,
         ),
         326 => 
         array (
           'name' => 'Edit',
           'route' => 'item-store-edit',
           'parent_route' => 'item-store',
           'type' => 3,
         ),
         327 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-store-view',
           'parent_route' => 'item-store',
           'type' => 3,
         ),
         328 => 
         array (
           'name' => 'Supplier',
           'route' => 'suppliers',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         329 => 
         array (
           'name' => 'Add',
           'route' => 'suppliers-store',
           'parent_route' => 'suppliers',
           'type' => 3,
         ),
         330 => 
         array (
           'name' => 'Edit',
           'route' => 'suppliers-edit',
           'parent_route' => 'suppliers',
           'type' => 3,
         ),
         331 => 
         array (
           'name' => 'Delete',
           'route' => 'suppliers-delete',
           'parent_route' => 'suppliers',
           'type' => 3,
         ),
         332 => 
         array (
           'name' => 'Item Receive',
           'route' => 'item-receive',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         333 => 
         array (
           'name' => 'Add',
           'route' => 'save-item-receive-data',
           'parent_route' => 'item-receive',
           'type' => 3,
         ),
         334 => 
         array (
           'name' => 'Item Receive List',
           'route' => 'item-receive-list',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         335 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'item-receive-list',
           'type' => 3,
         ),
         336 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-item-receive',
           'parent_route' => 'item-receive-list',
           'type' => 3,
         ),
         337 => 
         array (
           'name' => 'View',
           'route' => 'view-receive-payments',
           'parent_route' => 'item-receive-list',
           'type' => 3,
         ),
         338 => 
         array (
           'name' => 'Cancel',
           'route' => 'delete-item-receive-view',
           'parent_route' => 'item-receive-list',
           'type' => 3,
         ),
         339 => 
         array (
           'name' => 'Item Sell',
           'route' => 'item-sell-list',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         340 => 
         array (
           'name' => 'Add',
           'route' => 'save-item-sell-data',
           'parent_route' => 'item-sell-list',
           'type' => 3,
         ),
         341 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-item-sell',
           'parent_route' => 'item-sell-list',
           'type' => 3,
         ),
         342 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-item-sale-view',
           'parent_route' => 'item-sell-list',
           'type' => 3,
         ),
         343 => 
         array (
           'name' => 'Add Payment',
           'route' => 'add-payment-sell',
           'parent_route' => 'item-sell-list',
           'type' => 3,
         ),
         344 => 
         array (
           'name' => 'View Payment',
           'route' => 'view-sell-payments',
           'parent_route' => 'item-sell-list',
           'type' => 3,
         ),
         345 => 
         array (
           'name' => 'Item Issue',
           'route' => 'item-issue',
           'parent_route' => 'inventory',
           'type' => 2,
         ),
         346 => 
         array (
           'name' => 'Add',
           'route' => 'save-item-issue-data',
           'parent_route' => 'item-issue',
           'type' => 3,
         ),
         347 => 
         array (
           'name' => 'Return',
           'route' => 'return-item-view',
           'parent_route' => 'item-issue',
           'type' => 3,
         ),
         348 => 
         array (
           'name' => 'Transport',
           'route' => 'transport',
           'parent_route' => NULL,
           'type' => 1,
         ),
         349 => 
         array (
           'name' => 'Routes',
           'route' => 'transport-route',
           'parent_route' => 'transport',
           'type' => 2,
         ),
         350 => 
         array (
           'name' => 'Add',
           'route' => 'transport-route-store',
           'parent_route' => 'transport-route',
           'type' => 3,
         ),
         351 => 
         array (
           'name' => 'Edit',
           'route' => 'transport-route-edit',
           'parent_route' => 'transport-route',
           'type' => 3,
         ),
         352 => 
         array (
           'name' => 'Delete',
           'route' => 'transport-route-delete',
           'parent_route' => 'transport-route',
           'type' => 3,
         ),
         353 => 
         array (
           'name' => 'Vehicle',
           'route' => 'vehicle',
           'parent_route' => 'transport',
           'type' => 2,
         ),
         354 => 
         array (
           'name' => 'Add',
           'route' => 'vehicle-store',
           'parent_route' => 'vehicle',
           'type' => 3,
         ),
         355 => 
         array (
           'name' => 'Edit',
           'route' => 'vehicle-edit',
           'parent_route' => 'vehicle',
           'type' => 3,
         ),
         356 => 
         array (
           'name' => 'Delete',
           'route' => 'vehicle-delete',
           'parent_route' => 'vehicle',
           'type' => 3,
         ),
         357 => 
         array (
           'name' => 'Assign Vehicle',
           'route' => 'assign-vehicle',
           'parent_route' => 'transport',
           'type' => 2,
         ),
         358 => 
         array (
           'name' => 'Add',
           'route' => 'assign-vehicle-store',
           'parent_route' => 'assign-vehicle',
           'type' => 3,
         ),
         359 => 
         array (
           'name' => 'Edit',
           'route' => 'assign-vehicle-edit',
           'parent_route' => 'assign-vehicle',
           'type' => 3,
         ),
         360 => 
         array (
           'name' => 'Delete',
           'route' => 'assign-vehicle-delete',
           'parent_route' => 'assign-vehicle',
           'type' => 3,
         ),
         361 => 
         array (
           'name' => 'Student Transport Report',
           'route' => 'student_transport_report',
           'parent_route' => 'transport',
           'type' => 2,
         ),
         362 => 
         array (
           'name' => 'Dormitory',
           'route' => 'dormitory',
           'parent_route' => NULL,
           'type' => 1,
         ),
         363 => 
         array (
           'name' => 'Dormitory Rooms',
           'route' => 'room-list',
           'parent_route' => 'dormitory',
           'type' => 2,
         ),
         364 => 
         array (
           'name' => 'Add',
           'route' => 'room-list-store',
           'parent_route' => 'room-list',
           'type' => 3,
         ),
         365 => 
         array (
           'name' => 'Edit',
           'route' => 'room-list-edit',
           'parent_route' => 'room-list',
           'type' => 3,
         ),
         366 => 
         array (
           'name' => 'Delete',
           'route' => 'room-list-delete',
           'parent_route' => 'room-list',
           'type' => 3,
         ),
         367 => 
         array (
           'name' => 'Dormitory',
           'route' => 'dormitory-list',
           'parent_route' => 'dormitory',
           'type' => 2,
         ),
         368 => 
         array (
           'name' => 'Add',
           'route' => 'dormitory-list-store',
           'parent_route' => 'dormitory-list',
           'type' => 3,
         ),
         369 => 
         array (
           'name' => 'Edit',
           'route' => 'dormitory-list-edit',
           'parent_route' => 'dormitory-list',
           'type' => 3,
         ),
         370 => 
         array (
           'name' => 'Delete',
           'route' => 'dormitory-list-delete',
           'parent_route' => 'dormitory-list',
           'type' => 3,
         ),
         371 => 
         array (
           'name' => 'Room Type',
           'route' => 'room-type',
           'parent_route' => 'dormitory',
           'type' => 2,
         ),
         372 => 
         array (
           'name' => 'Add',
           'route' => 'room-type-store',
           'parent_route' => 'room-type',
           'type' => 3,
         ),
         373 => 
         array (
           'name' => 'Edit',
           'route' => 'room-type-edit',
           'parent_route' => 'room-type',
           'type' => 3,
         ),
         374 => 
         array (
           'name' => 'Delete',
           'route' => 'room-type-delete',
           'parent_route' => 'room-type',
           'type' => 3,
         ),
         375 => 
         array (
           'name' => 'Student Dormitory Report',
           'route' => 'student_dormitory_report',
           'parent_route' => 'dormitory',
           'type' => 2,
         ),
         376 => 
         array (
           'name' => 'Reports',
           'route' => 'reports',
           'parent_route' => NULL,
           'type' => 1,
         ),
         377 => 
         array (
           'name' => 'Guardian Reports',
           'route' => 'guardian_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         378 => 
         array (
           'name' => 'Student History',
           'route' => 'student_history',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         379 => 
         array (
           'name' => 'Student Login Report',
           'route' => 'student_login_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         380 => 
         array (
           'name' => 'Update',
           'route' => 'student_login_update',
           'parent_route' => 'student_login_report',
           'type' => 3,
         ),
         381 => 
         array (
           'name' => 'Fees Statement',
           'route' => 'fees_statement',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         382 => 
         array (
           'name' => 'Balance Fees Report',
           'route' => 'balance_fees_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         383 => 
         array (
           'name' => 'Collection Report',
           'route' => 'transaction_report',
           'parent_route' => 'fees_collection',
           'type' => 3,
         ),
         384 => 
         array (
           'name' => 'Class Report',
           'route' => 'class_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         385 => 
         array (
           'name' => 'Class Routine',
           'route' => 'class_routine_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         386 => 
         array (
           'name' => 'Exam Routine',
           'route' => 'exam_routine_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         387 => 
         array (
           'name' => 'Teacher Class Routine',
           'route' => 'teacher_class_routine_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         388 => 
         array (
           'name' => 'Merit List Report',
           'route' => 'merit_list_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         389 => 
         array (
           'name' => 'Online Exam Report',
           'route' => 'online_exam_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         390 => 
         array (
           'name' => 'Mark Sheet Report',
           'route' => 'mark_sheet_report_student',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         391 => 
         array (
           'name' => 'Tabulation Sheet Report',
           'route' => 'tabulation_sheet_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         392 => 
         array (
           'name' => 'Progress Card Report',
           'route' => 'progress_card_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         394 => 
         array (
           'name' => 'User Log',
           'route' => 'user_log',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         395 => 
         array (
           'name' => 'Add',
           'route' => '',
           'parent_route' => 'user_log',
           'type' => 3,
         ),
         396 => 
         array (
           'name' => 'Edit',
           'route' => '',
           'parent_route' => 'user_log',
           'type' => 3,
         ),
         397 => 
         array (
           'name' => 'Exam Setup Edit',
           'route' => 'exam-edit',
           'parent_route' => 'exam',
           'type' => 3,
         ),
         398 => 
         array (
           'name' => 'System Settings',
           'route' => 'system_settings',
           'parent_route' => NULL,
           'type' => 1,
         ),
         399 => 
         array (
           'name' => 'Module Manager',
           'route' => 'manage-adons',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         400 => 
         array (
           'name' => 'Verify',
           'route' => 'manage-adons-verify',
           'parent_route' => 'manage-adons',
           'type' => 3,
         ),
         401 => 
         array (
           'name' => 'Manage Currency',
           'route' => 'manage-currency',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         402 => 
         array (
           'name' => 'Add',
           'route' => 'currency-store',
           'parent_route' => 'manage-currency',
           'type' => 3,
         ),
         403 => 
         array (
           'name' => 'Edit',
           'route' => 'currency_edit',
           'parent_route' => 'manage-currency',
           'type' => 3,
         ),
         404 => 
         array (
           'name' => 'Delete',
           'route' => 'currency_delete',
           'parent_route' => 'manage-currency',
           'type' => 3,
         ),
         405 => 
         array (
           'name' => 'General Settings',
           'route' => 'general-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         406 => 
         array (
           'name' => 'Logo Change',
           'route' => 'update-school-logo',
           'parent_route' => 'general-settings',
           'type' => 3,
         ),
         407 => 
         array (
           'name' => 'Favicon Change',
           'route' => 'update-school-favicon',
           'parent_route' => 'general-settings',
           'type' => 3,
         ),
         408 => 
         array (
           'name' => 'Edit',
           'route' => 'update-general-settings',
           'parent_route' => 'general-settings',
           'type' => 3,
         ),
         409 => 
         array (
           'name' => 'Update',
           'route' => 'update-general-settings',
           'parent_route' => 'general-settings',
           'type' => 3,
         ),
         410 => 
         array (
           'name' => 'Email Setting',
           'route' => 'email-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         411 => 
         array (
           'name' => 'Update',
           'route' => 'update-email-settings-data',
           'parent_route' => 'email-settings',
           'type' => 3,
         ),
         412 => 
         array (
           'name' => 'Payment Method Settings',
           'route' => 'payment-method-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         413 => 
         array (
           'name' => 'Gateway Update',
           'route' => 'is-active-payment',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         414 => 
         array (
           'name' => 'Gateway Info Update',
           'route' => 'update-payment-gateway',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         415 => 
         array (
           'name' => 'Stripe Update',
           'route' => '',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         416 => 
         array (
           'name' => 'Paystack Update',
           'route' => '',
           'parent_route' => 'payment-method-settings',
           'type' => 3,
         ),
         417 => 
         array (
           'name' => 'Role & Permission',
           'route' => 'role_permission',
           'parent_route' => NULL,
           'type' => 1,
         ),
         418 => 
         array (
           'name' => 'Add',
           'route' => 'rolepermission/role-store',
           'parent_route' => 'rolepermission/role',
           'type' => 3,
         ),
         419 => 
         array (
           'name' => 'Edit',
           'route' => 'rolepermission/role-edit',
           'parent_route' => 'rolepermission/role',
           'type' => 3,
         ),
         420 => 
         array (
           'name' => 'Delete',
           'route' => 'rolepermission/role-delete',
           'parent_route' => 'rolepermission/role',
           'type' => 3,
         ),
         421 => 
         array (
           'name' => 'Login Permission',
           'route' => 'login-access-control',
           'parent_route' => 'role_permission',
           'type' => 2,
         ),
         422 => 
         array (
           'name' => 'On',
           'route' => 'login-access-control-on',
           'parent_route' => 'login-access-control',
           'type' => 3,
         ),
         423 => 
         array (
           'name' => 'Off',
           'route' => 'login-access-control-off',
           'parent_route' => 'login-access-control',
           'type' => 3,
         ),
         424 => 
         array (
           'name' => 'Optional Subject Setup',
           'route' => 'class_optional',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         425 => 
         array (
           'name' => 'Add',
           'route' => 'optional_subject_setup_post',
           'parent_route' => 'class_optional',
           'type' => 3,
         ),
         426 => 
         array (
           'name' => 'Edit',
           'route' => 'class_optional_edit',
           'parent_route' => 'class_optional',
           'type' => 3,
         ),
         427 => 
         array (
           'name' => 'Delete',
           'route' => 'delete_optional_subject',
           'parent_route' => 'class_optional',
           'type' => 3,
         ),
         428 => 
         array (
           'name' => 'Base Setup',
           'route' => 'base_setup',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         429 => 
         array (
           'name' => 'Add',
           'route' => 'base_setup_store',
           'parent_route' => 'base_setup',
           'type' => 3,
         ),
         430 => 
         array (
           'name' => 'Edit',
           'route' => 'base_setup_edit',
           'parent_route' => 'base_setup',
           'type' => 3,
         ),
         431 => 
         array (
           'name' => 'Delete',
           'route' => 'base_setup_delete',
           'parent_route' => 'base_setup',
           'type' => 3,
         ),
         432 => 
         array (
           'name' => 'Academic Year',
           'route' => 'academic-year',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         433 => 
         array (
           'name' => 'Add',
           'route' => 'academic-year-store',
           'parent_route' => 'academic-year',
           'type' => 3,
         ),
         434 => 
         array (
           'name' => 'Edit',
           'route' => 'academic-year-edit',
           'parent_route' => 'academic-year',
           'type' => 3,
         ),
         435 => 
         array (
           'name' => 'Delete',
           'route' => 'academic-year-delete',
           'parent_route' => 'academic-year',
           'type' => 3,
         ),
         436 => 
         array (
           'name' => 'Setup Exam Rule',
           'route' => 'custom-result-setting',
           'parent_route' => 'exam-settings',
           'type' => 3,
         ),
         437 => 
         array (
           'name' => 'Setup Final Exam Rule',
           'route' => 'custom-result-setting/store',
           'parent_route' => 'custom-result-setting',
           'type' => 3,
         ),
         438 => 
         array (
           'name' => 'Step Skip',
           'route' => 'exam.step.skip.update',
           'parent_route' => 'custom-result-setting',
           'type' => 3,
         ),
         439 => 
         array (
           'name' => 'Merit list Settings',
           'route' => 'merit-list-settings',
           'parent_route' => 'custom-result-setting',
           'type' => 3,
         ),
         440 => 
         array (
           'name' => 'Holiday',
           'route' => 'holiday',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         441 => 
         array (
           'name' => 'Add',
           'route' => 'holiday-store',
           'parent_route' => 'holiday',
           'type' => 3,
         ),
         442 => 
         array (
           'name' => 'Edit',
           'route' => 'holiday-edit',
           'parent_route' => 'holiday',
           'type' => 3,
         ),
         443 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-holiday-data-view',
           'parent_route' => 'holiday',
           'type' => 3,
         ),
         444 => 
         array (
           'name' => 'Sms Settings',
           'route' => 'sms-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         445 => 
         array (
           'name' => ' Select SMS Service',
           'route' => 'update-clickatell-data',
           'parent_route' => 'sms-settings',
           'type' => 3,
         ),
         446 => 
         array (
           'name' => 'Twilio Update',
           'route' => 'update-twilio-data',
           'parent_route' => 'sms-settings',
           'type' => 3,
         ),
         447 => 
         array (
           'name' => 'MSG91 Update',
           'route' => 'update-msg91-data',
           'parent_route' => 'sms-settings',
           'type' => 3,
         ),
         448 => 
         array (
           'name' => 'Weekend',
           'route' => 'weekend',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
     
         451 => 
         array (
           'name' => 'Language Settings',
           'route' => 'language-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         452 => 
         array (
           'name' => 'Add',
           'route' => 'language-add',
           'parent_route' => 'language-settings',
           'type' => 3,
         ),
         453 => 
         array (
           'name' => 'Make Default',
           'route' => 'change-language',
           'parent_route' => 'language-settings',
           'type' => 3,
         ),
         454 => 
         array (
           'name' => 'Setup',
           'route' => 'language-setup',
           'parent_route' => 'language-settings',
           'type' => 3,
         ),
         455 => 
         array (
           'name' => 'Remove',
           'route' => 'language-delete',
           'parent_route' => 'language-settings',
           'type' => 3,
         ),
         456 => 
         array (
           'name' => 'Backup',
           'route' => 'backup-settings',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         457 => 
         array (
           'name' => 'Add',
           'route' => 'backup-store',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         458 => 
         array (
           'name' => 'Download',
           'route' => 'download-files',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         459 => 
         array (
           'name' => 'Delete',
           'route' => 'delete_database',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         460 => 
         array (
           'name' => 'Image',
           'route' => 'get-backup-files',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         461 => 
         array (
           'name' => 'Full Project',
           'route' => '',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         462 => 
         array (
           'name' => 'Database',
           'route' => 'get-backup-db',
           'parent_route' => 'backup-settings',
           'type' => 3,
         ),
         463 => 
         array (
           'name' => 'Header Option',
           'route' => 'button-disable-enable',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         464 => 
         array (
           'name' => 'Custom URL Update',
           'route' => 'update-website-url',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         465 => 
         array (
           'name' => 'Status Change',
           'route' => 'status-change',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         466 => 
         array (
           'name' => 'Website Off',
           'route' => 'status-disable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         467 => 
         array (
           'name' => 'Dashboard On',
           'route' => 'dashboard-enable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         468 => 
         array (
           'name' => 'Dashboard Off',
           'route' => 'dashboard-disable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         469 => 
         array (
           'name' => 'Report On',
           'route' => 'report-enable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         470 => 
         array (
           'name' => 'Report Off',
           'route' => 'report-disable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         471 => 
         array (
           'name' => 'Language On',
           'route' => 'lang-enable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         472 => 
         array (
           'name' => 'Language Off',
           'route' => 'lang-disable',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         473 => 
         array (
           'name' => 'Style On',
           'route' => '',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         474 => 
         array (
           'name' => 'Style Off',
           'route' => '',
           'parent_route' => 'button-disable-enable',
           'type' => 3,
         ),
         478 => 
         array (
           'name' => 'About & Update',
           'route' => 'update-system',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         479 => 
         array (
           'name' => 'Add',
           'route' => 'admin/update-system',
           'parent_route' => 'update-system',
           'type' => 3,
         ),
         480 => 
         array (
           'name' => 'Email Template',
           'route' => 'templatesettings.email-template',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         481 => 
         array (
           'name' => 'Save',
           'route' => '',
           'parent_route' => 'templatesettings.email-template',
           'type' => 3,
         ),
         482 => 
         array (
           'name' => 'API Permission',
           'route' => 'api/permission',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         483 => 
         array (
           'name' => 'On',
           'route' => '',
           'parent_route' => '',
           'type' => 3,
         ),
         484 => 
         array (
           'name' => 'Off',
           'route' => '',
           'parent_route' => '',
           'type' => 3,
         ),
         485 => 
         array (
           'name' => 'Style',
           'route' => 'style',
           'parent_route' => NULL,
           'type' => 1,
         ),
         486 => 
         array (
           'name' => 'Background Settings',
           'route' => 'background-setting',
           'parent_route' => 'style',
           'type' => 2,
         ),
         487 => 
         array (
           'name' => 'Add',
           'route' => 'background-settings-store',
           'parent_route' => 'background-setting',
           'type' => 3,
         ),
         488 => 
         array (
           'name' => 'Delete',
           'route' => 'background-setting-delete',
           'parent_route' => 'background-setting',
           'type' => 3,
         ),
         489 => 
         array (
           'name' => 'Make Default',
           'route' => 'background_setting-status',
           'parent_route' => 'background-setting',
           'type' => 3,
         ),
         490 => 
         array (
           'name' => 'Color Theme',
           'route' => 'color-style',
           'parent_route' => 'style',
           'type' => 2,
         ),
         491 => 
         array (
           'name' => 'Make Default',
           'route' => 'themes.default',
           'parent_route' => 'color-style',
           'type' => 3,
         ),
         492 => 
         array (
           'name' => 'Front Settings',
           'route' => 'front_settings',
           'parent_route' => NULL,
           'type' => 1,
         ),
         493 => 
         array (
           'name' => 'Home Page',
           'route' => 'admin-home-page',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         494 => 
         array (
           'name' => 'Update',
           'route' => 'admin-home-page-update',
           'parent_route' => 'admin-home-page',
           'type' => 3,
         ),
         495 => 
         array (
           'name' => 'News List',
           'route' => 'news_index',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         496 => 
         array (
           'name' => 'View',
           'route' => 'newsDetails',
           'parent_route' => 'news_index',
           'type' => 3,
         ),
         497 => 
         array (
           'name' => 'Add',
           'route' => 'store_news',
           'parent_route' => 'news_index',
           'type' => 3,
         ),
         498 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-news',
           'parent_route' => 'news_index',
           'type' => 3,
         ),
         499 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-news',
           'parent_route' => 'news_index',
           'type' => 3,
         ),
         500 => 
         array (
           'name' => 'News Category',
           'route' => 'news-category',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         501 => 
         array (
           'name' => 'Add',
           'route' => 'store_news_category',
           'parent_route' => 'news-category',
           'type' => 3,
         ),
         502 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-news-category',
           'parent_route' => 'news-category',
           'type' => 3,
         ),
         503 => 
         array (
           'name' => 'Delete',
           'route' => 'for-delete-news-category',
           'parent_route' => 'news-category',
           'type' => 3,
         ),
         504 => 
         array (
           'name' => 'Testimonial',
           'route' => 'testimonial_index',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         505 => 
         array (
           'name' => 'View',
           'route' => 'testimonial-details',
           'parent_route' => 'testimonial_index',
           'type' => 3,
         ),
         506 => 
         array (
           'name' => 'Add',
           'route' => 'store_testimonial',
           'parent_route' => 'testimonial_index',
           'type' => 3,
         ),
         507 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-testimonial',
           'parent_route' => 'testimonial_index',
           'type' => 3,
         ),
         508 => 
         array (
           'name' => 'Delete',
           'route' => 'for-delete-testimonial',
           'parent_route' => 'testimonial_index',
           'type' => 3,
         ),
         509 => 
         array (
           'name' => 'Course List',
           'route' => 'course-list',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         510 => 
         array (
           'name' => 'View',
           'route' => 'course-Details-admin',
           'parent_route' => 'course-list',
           'type' => 3,
         ),
         511 => 
         array (
           'name' => 'Add',
           'route' => 'store_course',
           'parent_route' => 'course-list',
           'type' => 3,
         ),
         512 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-course',
           'parent_route' => 'course-list',
           'type' => 3,
         ),
         513 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-course',
           'parent_route' => 'course-list',
           'type' => 3,
         ),
         514 => 
         array (
           'name' => 'Contact Page',
           'route' => 'conpactPage',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         515 => 
         array (
           'name' => 'Store',
           'route' => 'contactPageStore',
           'parent_route' => 'conpactPage',
           'type' => 3,
         ),
         516 => 
         array (
           'name' => 'Edit',
           'route' => 'contactPageEdit',
           'parent_route' => 'conpactPage',
           'type' => 3,
         ),
         517 => 
         array (
           'name' => 'Contact Messages',
           'route' => 'contactMessage',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         519 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-message',
           'parent_route' => 'contactMessage',
           'type' => 3,
         ),
         520 => 
         array (
           'name' => 'About Us',
           'route' => 'about-page',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         521 => 
         array (
           'name' => 'View',
           'route' => 'about-page/view',
           'parent_route' => 'about-page',
           'type' => 3,
         ),
         522 => 
         array (
           'name' => 'Edit',
           'route' => 'about-page/edit',
           'parent_route' => 'about-page',
           'type' => 3,
         ),
         523 => 
         array (
           'name' => 'News Heading',
           'route' => 'news-heading-update',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         524 => 
         array (
           'name' => 'Update',
           'route' => '',
           'parent_route' => 'news-heading-update',
           'type' => 3,
         ),
         525 => 
         array (
           'name' => 'Course Details Heading',
           'route' => 'course-heading-update',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         526 => 
         array (
           'name' => 'Update',
           'route' => 'course-heading-update',
           'parent_route' => 'course-heading-update',
           'type' => 3,
         ),
         527 => 
         array (
           'name' => 'Footer Widget',
           'route' => 'custom-links',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         528 => 
         array (
           'name' => 'Update',
           'route' => '',
           'parent_route' => 'custom-links',
           'type' => 3,
         ),
         529 => 
         array (
           'name' => 'Social Media',
           'route' => 'social-media',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         530 => 
         array (
           'name' => 'Add',
           'route' => 'social-media-store',
           'parent_route' => 'social-media',
           'type' => 3,
         ),
         531 => 
         array (
           'name' => 'Edit',
           'route' => 'social-media-edit',
           'parent_route' => 'social-media',
           'type' => 3,
         ),
         532 => 
         array (
           'name' => 'Delete',
           'route' => 'social-media-delete',
           'parent_route' => 'social-media',
           'type' => 3,
         ),
         533 => 
         array (
           'name' => 'Subject Wise Attendance',
           'route' => 'subject-wise-attendance',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         534 => 
         array (
           'name' => 'Save',
           'route' => 'subject-attendance-store',
           'parent_route' => 'subject-wise-attendance',
           'type' => 3,
         ),
         535 => 
         array (
           'name' => 'Subject Wise Attendance Report',
           'route' => 'subject-attendance-report',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         536 => 
         array (
           'name' => 'Print',
           'route' => 'subject-attendance/print',
           'parent_route' => 'subject-attendance-report',
           'type' => 3,
         ),
         537 => 
         array (
           'name' => 'Optional Subject',
           'route' => 'optional-subject',
           'parent_route' => 'academics',
           'type' => 2,
         ),
         538 => 
         array (
           'name' => 'Student Report',
           'route' => 'student_report',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         539 => 
         array (
           'name' => 'Previous Result',
           'route' => 'previous-class-results',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         540 => 
         array (
           'name' => 'previous record',
           'route' => 'previous-record',
           'parent_route' => 'reports',
           'type' => 2,
         ),
         541 => 
         array (
           'name' => 'Assign Permission',
           'route' => 'rolepermission/assign-permission',
           'parent_route' => 'rolepermission/role',
           'type' => 3,
         ),

         549 => 
         array (
           'name' => 'Language',
           'route' => 'language-list',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         550 => 
         array (
           'name' => 'Add',
           'route' => 'language_store',
           'parent_route' => 'language-list',
           'type' => 3,
         ),
         551 => 
         array (
           'name' => 'Edit',
           'route' => 'language_edit',
           'parent_route' => 'language-list',
           'type' => 3,
         ),
         552 => 
         array (
           'name' => 'Delete',
           'route' => 'language_delete',
           'parent_route' => 'language-list',
           'type' => 3,
         ),
         553 => 
         array (
           'name' => 'Add',
           'route' => 'apply-leave-store',
           'parent_route' => 'apply-leave',
           'type' => 3,
         ),

        
         577 => 
         array (
           'name' => 'Status On',
           'route' => '',
           'parent_route' => 'staff_directory',
           'type' => 3,
         ),
         578 => 
         array (
           'name' => 'Status Off',
           'route' => '',
           'parent_route' => 'staff_directory',
           'type' => 3,
         ),
         579 => 
         array (
           'name' => 'Subject',
           'route' => 'library_subject',
           'parent_route' => 'library',
           'type' => 2,
         ),
         580 => 
         array (
           'name' => 'Add',
           'route' => 'library_subject_store',
           'parent_route' => 'library_subject',
           'type' => 3,
         ),
         581 => 
         array (
           'name' => 'Edit',
           'route' => 'library_subject_edit',
           'parent_route' => 'library_subject',
           'type' => 3,
         ),
         582 => 
         array (
           'name' => 'Delete',
           'route' => 'library_subject_delete',
           'parent_route' => 'library_subject',
           'type' => 3,
         ),
         585 => 
         array (
           'name' => 'Role',
           'route' => 'rolepermission/role',
           'parent_route' => 'role_permission',
           'type' => 2,
         ),
         586 => 
         array (
           'name' => 'edit',
           'route' => 'upload-content-edit',
           'parent_route' => 'upload-content',
           'type' => 3,
         ),
         587 => 
         array (
           'name' => 'edit',
           'route' => 'upload-content-edit',
           'parent_route' => 'assignment-list',
           'type' => 3,
         ),
         588 => 
         array (
           'name' => 'edit',
           'route' => 'other-download-list-edit',
           'parent_route' => 'other-download-list',
           'type' => 3,
         ),

         638 => 
         array (
           'name' => 'Human Resource',
           'route' => 'human_resource',
           'parent_route' => NULL,
           'type' => 1,
         ),

         650 => 
         array (
           'name' => 'Header Menu Manager',
           'route' => 'header-menu-manager',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         651 => 
         array (
           'name' => 'Add',
           'route' => 'add-element',
           'parent_route' => 'header-menu-manager',
           'type' => 3,
         ),
         652 => 
         array (
           'name' => 'Edit',
           'route' => 'element-update',
           'parent_route' => 'header-menu-manager',
           'type' => 3,
         ),
         653 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-element',
           'parent_route' => 'header-menu-manager',
           'type' => 3,
         ),
         654 => 
         array (
           'name' => 'Pages',
           'route' => 'page-list',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         655 => 
         array (
           'name' => 'View',
           'route' => 'view-page',
           'parent_route' => 'page-list',
           'type' => 3,
         ),
         656 => 
         array (
           'name' => 'Add',
           'route' => 'save-page-data',
           'parent_route' => 'page-list',
           'type' => 3,
         ),
         657 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-page',
           'parent_route' => 'page-list',
           'type' => 3,
         ),
         658 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-page',
           'parent_route' => 'page-list',
           'type' => 3,
         ),
         659 => 
         array (
           'name' => 'Download',
           'route' => 'download-page',
           'parent_route' => 'page-list',
           'type' => 3,
         ),

         663 => 
         array (
           'name' => 'Student Export',
           'route' => 'all-student-export',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         664 => 
         array (
           'name' => 'Export To CSV',
           'route' => 'all-student-export-excel',
           'parent_route' => 'all-student-export',
           'type' => 3,
         ),
         665 => 
         array (
           'name' => 'Export To PDF',
           'route' => 'all-student-export-pdf',
           'parent_route' => 'all-student-export',
           'type' => 3,
         ),
         669 => 
         array (
           'name' => 'Designation',
           'route' => 'designation',
           'parent_route' => 'human_resource',
           'type' => 3,
         ),
         670 => 
         array (
           'name' => 'Department',
           'route' => 'department',
           'parent_route' => 'human_resource',
           'type' => 3,
         ),
         671 => 
         array (
           'name' => 'Add Staff',
           'route' => 'addStaff',
           'parent_route' => 'human_resource',
           'type' => 3,
         ),
         672 => 
         array (
           'name' => 'Staff Directory',
           'route' => 'staff_directory',
           'parent_route' => 'human_resource',
           'type' => 3,
         ),
         673 => 
         array (
           'name' => 'Course Category',
           'route' => 'course-category',
           'parent_route' => 'front_settings',
           'type' => 2,
         ),
         674 => 
         array (
           'name' => 'Add',
           'route' => 'store-course-category',
           'parent_route' => 'course-category',
           'type' => 3,
         ),
         675 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-course-category',
           'parent_route' => 'course-category',
           'type' => 3,
         ),
         676 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-course-category',
           'parent_route' => 'course-category',
           'type' => 3,
         ),

         700 => 
         array (
           'name' => 'Report',
           'route' => 'accounts-report',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         701 => 
         array (
           'name' => 'Fine Report',
           'route' => 'fine-report',
           'parent_route' => 'accounts-report',
           'type' => 3,
         ),
         702 => 
         array (
           'name' => 'Payroll Report',
           'route' => 'accounts-payroll-report',
           'parent_route' => 'accounts-report',
           'type' => 3,
         ),
         703 => 
         array (
           'name' => 'Transaction',
           'route' => 'transaction',
           'parent_route' => 'accounts-report',
           'type' => 3,
         ),
         704 => 
         array (
           'name' => 'Fund Transfer',
           'route' => 'fund-transfer',
           'parent_route' => 'accounts',
           'type' => 2,
         ),
         705 => 
         array (
           'name' => 'Transfer',
           'route' => 'fund-transfer-store',
           'parent_route' => 'fund-transfer',
           'type' => 3,
         ),
         706 => 
         array (
           'name' => 'Format Settings',
           'route' => 'exam-settings',
           'parent_route' => '',
           'type' => 3,
         ),
         707 => 
         array (
           'name' => 'Add',
           'route' => 'save-exam-content',
           'parent_route' => 'exam-settings',
           'type' => 3,
         ),
         708 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-exam-settings',
           'parent_route' => 'exam-settings',
           'type' => 3,
         ),
         709 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-content',
           'parent_route' => 'exam-settings',
           'type' => 3,
         ),
         710 => 
         array (
           'name' => 'Sms Templete',
           'route' => 'sms-template-new',
           'parent_route' => 'communicate',
           'type' => 2,
         ),
         711 => 
         array (
           'name' => 'Add',
           'route' => 'sms-template-new-store',
           'parent_route' => 'sms-template-new',
           'type' => 3,
         ),
         712 => 
         array (
           'name' => 'Add Staff',
           'route' => 'addStaff',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         800 => 
         array (
           'name' => 'Lesson',
           'route' => 'lesson',
           'parent_route' => NULL,
           'type' => 1,
         ),
         801 => 
         array (
           'name' => 'Lesson',
           'route' => 'lesson',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         802 => 
         array (
           'name' => 'Add',
           'route' => 'lesson.create-store',
           'parent_route' => 'lesson',
           'type' => 3,
         ),
         803 => 
         array (
           'name' => 'Edit',
           'route' => 'lesson-edit',
           'parent_route' => 'lesson',
           'type' => 3,
         ),
         804 => 
         array (
           'name' => 'Delete',
           'route' => 'lesson-delete',
           'parent_route' => 'lesson',
           'type' => 3,
         ),
         805 => 
         array (
           'name' => 'Topic',
           'route' => 'lesson.topic',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         806 => 
         array (
           'name' => 'Add',
           'route' => 'lesson.topic.store',
           'parent_route' => 'lesson.topic',
           'type' => 3,
         ),
         807 => 
         array (
           'name' => 'Edit',
           'route' => 'topic-edit',
           'parent_route' => 'lesson.topic',
           'type' => 3,
         ),
         808 => 
         array (
           'name' => 'Delete',
           'route' => 'topic-delete',
           'parent_route' => 'lesson.topic',
           'type' => 3,
         ),
         809 => 
         array (
           'name' => 'Topic Overview',
           'route' => 'topic-overview',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         810 => 
         array (
           'name' => 'Lesson Plan',
           'route' => 'lesson.lesson-planner',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         811 => 
         array (
           'name' => 'Add',
           'route' => 'add-new-lesson-plan',
           'parent_route' => 'lesson.lesson-planner',
           'type' => 3,
         ),
         812 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-lesson-planner-lesson',
           'parent_route' => 'lesson.lesson-planner',
           'type' => 3,
         ),
         813 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-lesson-planner-lesson',
           'parent_route' => 'lesson.lesson-planner',
           'type' => 3,
         ),
         814 => 
         array (
           'name' => 'view',
           'route' => 'view-lesson-planner-lesson',
           'parent_route' => 'lesson.lesson-planner',
           'type' => 3,
         ),
         815 => 
         array (
           'name' => 'Lesson Plan Overview',
           'route' => 'lesson.lessonPlan-overiew',
           'parent_route' => 'lesson',
           'type' => 1,
         ),

         833 => 
         array (
           'name' => 'My Lesson Plan ',
           'route' => 'view-teacher-lessonPlan',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         834 => 
         array (
           'name' => 'My Lesson Plan Overview',
           'route' => 'view-teacher-lessonPlan-overview',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         835 => 
         array (
           'name' => 'Lesson Plan Setting',
           'route' => 'lesson.lesson-planner.setting',
           'parent_route' => 'lesson',
           'type' => 1,
         ),
         840 => 
         array (
           'name' => 'Report',
           'route' => '',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         850 => 
         array (
           'name' => 'BigBlueButton',
           'route' => 'bigbluebutton',
           'parent_route' => NULL,
           'type' => 1,
         ),

         870 => 
         array (
           'name' => 'Settings',
           'route' => '',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         871 => 
         array (
           'name' => 'Bank Payment',
           'route' => 'bank-payment-slip',
           'parent_route' => 'fees_collection',
           'type' => 2,
         ),
         875 => 
         array (
           'name' => 'Online Exam',
           'route' => 'online_exam',
           'parent_route' => NULL,
           'type' => 1,
         ),
         900 => 
         array (
           'name' => 'Chat',
           'route' => 'chat',
           'parent_route' => NULL,
           'type' => 1,
         ),
         901 => 
         array (
           'name' => 'Chat Box',
           'route' => 'chat.index',
           'parent_route' => 'chat',
           'type' => 2,
         ),
         902 => 
         array (
           'name' => 'New Chat',
           'route' => '',
           'parent_route' => 'chat.index',
           'type' => 3,
         ),
         903 => 
         array (
           'name' => 'Invitation',
           'route' => 'chat.invitation',
           'parent_route' => 'chat',
           'type' => 2,
         ),
         904 => 
         array (
           'name' => 'Blocked User',
           'route' => 'chat.blocked.users',
           'parent_route' => 'chat',
           'type' => 2,
         ),
         905 => 
         array (
           'name' => 'Chat Settings',
           'route' => 'chat.settings',
           'parent_route' => 'chat',
           'type' => 2,
         ),
         920 => 
         array (
           'name' => 'Bulk Print',
           'route' => 'bulk_print',
           'parent_route' => NULL,
           'type' => 1,
         ),
         921 => 
         array (
           'name' => 'Id Card',
           'route' => 'student-id-card-bulk-print',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),
         922 => 
         array (
           'name' => 'Student Certificate',
           'route' => 'certificate-bulk-print',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),
        //  923 => 
        //  array (
        //    'name' => 'Staff Id Card',
        //    'route' => 'staff-id-card-bulk-print',
        //    'parent_route' => 'bulk_print',
        //    'type' => 2,
        //  ),
         924 => 
         array (
           'name' => 'Payroll Bulk Print',
           'route' => 'payroll-bulk-print',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),
         925 => 
         array (
           'name' => 'Fees Invoice Bulk Print Settings',
           'route' => 'invoice-settings',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),
         926 => 
         array (
           'name' => 'Fees invoice Bulk Print',
           'route' => 'fees-bulk-print',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),
         950 => 
         array (
           'name' => 'Time Setup',
           'route' => 'notification_time_setup',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         951 => 
         array (
           'name' => 'Student Settings',
           'route' => 'student_settings',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         952 => 
         array (
           'name' => 'Settings',
           'route' => 'staff_settings',
           'parent_route' => 'human_resource',
           'type' => 2,
         ),
         1001 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-fees-payment',
           'parent_route' => 'search_fees_payment',
           'type' => 3,
         ),
         1002 => 
         array (
           'name' => 'Delete',
           'route' => 'fees-payment-delete',
           'parent_route' => 'search_fees_payment',
           'type' => 3,
         ),
         1100 => 
         array (
           'name' => 'Custom Field',
           'route' => 'custom_field',
           'parent_route' => NULL,
           'type' => 1,
         ),
         1101 => 
         array (
           'name' => 'Student Registration',
           'route' => 'student-reg-custom-field',
           'parent_route' => 'custom_field',
           'type' => 2,
         ),
         1102 => 
         array (
           'name' => 'Add',
           'route' => 'store-student-registration-custom-field',
           'parent_route' => 'student-reg-custom-field',
           'type' => 3,
         ),
         1103 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-custom-field',
           'parent_route' => 'student-reg-custom-field',
           'type' => 3,
         ),
         1104 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-custom-field',
           'parent_route' => 'student-reg-custom-field',
           'type' => 3,
         ),
         1105 => 
         array (
           'name' => 'Staff Registration',
           'route' => 'staff-reg-custom-field',
           'parent_route' => 'custom_field',
           'type' => 2,
         ),
         1106 => 
         array (
           'name' => 'Add',
           'route' => 'store-staff-registration-custom-field',
           'parent_route' => 'staff-reg-custom-field',
           'type' => 3,
         ),
         1107 => 
         array (
           'name' => 'Edit',
           'route' => 'edit-staff-custom-field',
           'parent_route' => 'staff-reg-custom-field',
           'type' => 3,
         ),
         1108 => 
         array (
           'name' => 'Delete',
           'route' => 'delete-staff-custom-field',
           'parent_route' => 'staff-reg-custom-field',
           'type' => 3,
         ),
         1109 => 
         array (
           'name' => 'Wallet',
           'route' => 'wallet',
           'parent_route' => NULL,
           'type' => 1,
         ),
         1110 => 
         array (
           'name' => 'Pending Diposite',
           'route' => 'wallet.pending-diposit',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1111 => 
         array (
           'name' => 'Approve',
           'route' => 'wallet.approve-payment',
           'parent_route' => 'wallet.reject-payment',
           'type' => 3,
         ),
         1112 => 
         array (
           'name' => 'Reject',
           'route' => 'wallet.reject-payment',
           'parent_route' => 'wallet.reject-payment',
           'type' => 3,
         ),
         1113 => 
         array (
           'name' => 'Download',
           'route' => 'wallet.download',
           'parent_route' => 'wallet.reject-payment',
           'type' => 3,
         ),
         1114 => 
         array (
           'name' => 'Approve Diposite',
           'route' => 'wallet.approve-diposit',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1115 => 
         array (
           'name' => 'Download',
           'route' => '',
           'parent_route' => '',
           'type' => 2,
         ),
         1116 => 
         array (
           'name' => 'Reject Diposite',
           'route' => 'wallet.reject-diposit',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1117 => 
         array (
           'name' => 'Download',
           'route' => '',
           'parent_route' => '',
           'type' => 2,
         ),
         1118 => 
         array (
           'name' => 'Wallet Transaction',
           'route' => 'wallet.wallet-transaction',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1119 => 
         array (
           'name' => 'Wallet Refund Request',
           'route' => 'wallet.wallet-refund-request',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1120 => 
         array (
           'name' => 'Approve',
           'route' => 'wallet.approve-refund',
           'parent_route' => 'wallet.wallet-refund-request',
           'type' => 2,
         ),
         1121 => 
         array (
           'name' => 'Reject',
           'route' => 'wallet.reject-refund',
           'parent_route',
           'parent_route' => 'wallet.wallet-refund-request',
           'type' => 2,
         ),
         1122 => 
         array (
           'name' => 'Download',
           'route' => 'wallet.reject-download',
           'parent_route',
           'parent_route' => 'wallet.wallet-refund-request',
           'type' => 2,
         ),
         1123 => 
         array (
           'name' => 'Wallet Report',
           'route' => 'wallet-report',
           'parent_route' => 'wallet',
           'type' => 2,
         ),
         1130 => 
         array (
           'name' => 'Fees',
           'route' => 'fees',
           'parent_route' => NULL,
           'type' => 1,
         ),
         1131 => 
         array (
           'name' => 'Fees Group',
           'route' => 'fees_group',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1132 => 
         array (
           'name' => 'Add',
           'route' => 'fees_group_store',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         1133 => 
         array (
           'name' => 'Edit',
           'route' => 'fees_group_edit',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         1134 => 
         array (
           'name' => 'Delete',
           'route' => 'fees_group_delete',
           'parent_route' => 'fees_group',
           'type' => 3,
         ),
         1135 => 
         array (
           'name' => 'Fees Type',
           'route' => 'fees_type',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1136 => 
         array (
           'name' => 'Add',
           'route' => 'fees_type_store',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         1137 => 
         array (
           'name' => 'Edit',
           'route' => 'fees_type_edit',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         1138 => 
         array (
           'name' => 'Delete',
           'route' => 'fees_type_delete',
           'parent_route' => 'fees_type',
           'type' => 3,
         ),
         1139 => 
         array (
           'name' => 'Fees Invoice',
           'route' => 'fees.fees-invoice',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1140 => 
         array (
           'name' => 'Add',
           'route' => 'fees.fees-invoice-store',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1141 => 
         array (
           'name' => 'View Payment',
           'route' => 'fees.fees-view-payment',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1142 => 
         array (
           'name' => 'View',
           'route' => 'fees.fees-invoice-view',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1143 => 
         array (
           'name' => 'print',
           'route' => 'fees.fees-invoice-print',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1144 => 
         array (
           'name' => 'Add Payment',
           'route' => 'fees.add-fees-payment',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1145 => 
         array (
           'name' => 'Edit',
           'route' => 'fees.fees-invoice-edit',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1146 => 
         array (
           'name' => 'Delete',
           'route' => 'fees.fees-invoice-delete',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1147 => 
         array (
           'name' => 'Fees Collect',
           'route' => 'fees.fees-payment-store',
           'parent_route' => 'fees.fees-invoice',
           'type' => 3,
         ),
         1148 => 
         array (
           'name' => 'Bank Payment',
           'route' => 'fees.bank-payment',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1149 => 
         array (
           'name' => 'Search',
           'route' => 'fees.search-bank-payment',
           'parent_route' => 'fees.bank-payment',
           'type' => 3,
         ),
         1150 => 
         array (
           'name' => 'Approve Payment',
           'route' => 'fees.approve-bank-payment',
           'parent_route' => 'fees.bank-payment',
           'type' => 3,
         ),
         1151 => 
         array (
           'name' => 'Reject Payment',
           'route' => 'fees.reject-bank-payment',
           'parent_route' => 'fees.bank-payment',
           'type' => 3,
         ),
         1152 => 
         array (
           'name' => 'Fees Invoice Settings',
           'route' => 'fees.fees-invoice-settings',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1153 => 
         array (
           'name' => 'Update',
           'route' => 'fees.fees-invoice-settings-update',
           'parent_route' => 'fees',
           'type' => 3,
         ),
         1154 => 
         array (
           'name' => 'Report',
           'route' => 'fees.fees-report',
           'parent_route' => 'fees',
           'type' => 2,
         ),
         1155 => 
         array (
           'name' => 'Fees Due',
           'route' => 'fees.due-fees',
           'parent_route' => 'fees.fees-report',
           'type' => 3,
         ),
         1158 => 
         array (
           'name' => 'Fine Report',
           'route' => 'fees.fine-report',
           'parent_route' => 'fees.fees-report',
           'type' => 3,
         ),
         1159 => 
         array (
           'name' => 'Payment Report',
           'route' => 'fees.payment-report',
           'parent_route' => 'fees.fees-report',
           'type' => 3,
         ),
         1160 => 
         array (
           'name' => 'Balance Report',
           'route' => 'fees.balance-report',
           'parent_route' => 'fees.fees-report',
           'type' => 3,
         ),
         1161 => 
         array (
           'name' => 'Waiver Report',
           'route' => 'fees.waiver-report',
           'parent_route' => 'fees.fees-report',
           'type' => 3,
         ),
         1162 => 
         array (
           'name' => 'Fees Invoice',
           'route' => '',
           'parent_route' => 'bulk_print',
           'type' => 2,
         ),


         2200 => 
         array (
           'name' => 'Preloader Setting',
           'route' => 'setting.preloader',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         3100 => 
         array (
           'name' => 'ExamPlan',
           'route' => 'examplan',
           'parent_route' => NULL,
           'type' => 1,
         ),
         3101 => 
         array (
           'name' => 'Admit Card',
           'route' => 'examplan.admitcard.index',
           'parent_route' => 'examplan',
           'type' => 2,
         ),
         3102 => 
         array (
           'name' => 'Setting',
           'route' => 'examplan.admitcard.setting',
           'parent_route' => 'examplan',
           'type' => 3,
         ),
         3103 => 
         array (
           'name' => 'Generate',
           'route' => 'examplan.admitcard.generate',
           'parent_route' => 'examplan.admitcard.index',
           'type' => 3,
         ),
         3104 => 
         array (
           'name' => 'Save',
           'route' => '',
           'parent_route' => '',
           'type' => 3,
         ),
         3105 => 
         array (
           'name' => 'Seat Plan',
           'route' => 'examplan.seatplan.index',
           'parent_route' => 'examplan',
           'type' => 2,
         ),
         3106 => 
         array (
           'name' => 'Seat Plan Setting',
           'route' => 'examplan.seatplan.setting',
           'parent_route' => 'examplan',
           'type' => 3,
         ),
         3107 => 
         array (
           'name' => 'Generate',
           'route' => 'examplan.seatplan.generate',
           'parent_route' => 'examplan.seatplan.index',
           'type' => 3,
         ),
         3214 => 
         array (
           'name' => 'MarkSheet Report',
           'route' => 'custom-marksheet-report',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         3215 => 
         array (
           'name' => 'Print',
           'route' => 'percent-marksheet-print',
           'parent_route' => 'custom-marksheet-report',
           'type' => 3,
         ),
         3216 => 
         array (
           'name' => 'Subject Mark Sheet',
           'route' => 'exam_schedule',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         3217 => 
         array (
           'name' => 'Print',
           'route' => 'exam_schedule_print',
           'parent_route' => 'exam_schedule',
           'type' => 3,
         ),
         3218 => 
         array (
           'name' => 'Final Mark Sheet',
           'route' => 'exam_attendance',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         3219 => 
         array (
           'name' => 'Print',
           'route' => '',
           'parent_route' => 'exam_attendance',
           'type' => 3,
         ),
         3220 => 
         array (
           'name' => 'Student Final Mark Sheet',
           'route' => 'marks_register',
           'parent_route' => 'examination',
           'type' => 2,
         ),
         3221 => 
         array (
           'name' => 'Print',
           'route' => '',
           'parent_route' => 'marks_register',
           'type' => 3,
         ),
         4000 => 
         array (
           'name' => 'Utilities',
           'route' => 'utility',
           'parent_route' => 'system_settings',
           'type' => 2,
         ),
         5000 => 
         array (
           'name' => 'Position Setup',
           'route' => 'exam-report-position',
           'parent_route' => '',
           'type' => 2,
         ),
         
         15201 => 
         array (
           'name' => 'Multi Class Student',
           'route' => 'student.multi-class-student',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         15205 => 
         array (
           'name' => 'Delete Student Record',
           'route' => 'student.delete-student-record',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
         15209 => 
         array (
           'name' => 'UnAssign Student',
           'route' => 'unassigned_student',
           'parent_route' => 'student_info',
           'type' => 2,
         ),
       );
    }
}
