<?php

namespace Database\Seeders;

use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmVehicle;
use App\SmVisitor;
use App\SmLanguage;
use App\SmClassRoom;
use App\SmExpenseHead;
use App\GlobalVariable;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;

class sm_schoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // if(moduleStatusCheck('Saas') == true){
        // for($i=2; $i<=5; $i++){

        //     $email= $faker->email;
        //     $school= $faker->company. ' School';

        //     $store= new SmSchool();
        //     $store->school_name= $school;
        //     $store->email= $email;
        //     $store->created_at = date('Y-m-d h:i:s');
        //     $store->starting_date = date('Y-m-d');
        //     $store->is_email_verified = 1;
        //     $store->save();
            
        //     $general_setting = new SmGeneralSettings();
        //     $general_setting->school_name = $school;
        //     $general_setting->email = $email;
        //     $general_setting->address = '';
        //     $general_setting->school_code = '';
        //     $general_setting->school_id = $i;
        //     $general_setting->phone = '';
        //     $general_setting->time_zone_id = 1;
        //     $general_setting->save();


          
        //     $user            = new User();
        //     $user->role_id   = 1;
        //     $user->full_name = $faker->name;
        //     $user->email     = $email;
        //     $user->username  = $email;
        //     $user->password  = Hash::make('123456');
        //     $user->school_id = $i;
        //     $user->save();
        //     $user->toArray();

        //     //user details
        //     $staff                  = new SmStaff();
        //     $staff->user_id         = $user->id;
        //     $staff->role_id         = 1;
        //     $staff->staff_no        = 1;
        //     $staff->designation_id  = 1;
        //     $staff->department_id   = 1;
        //     $staff->first_name      = 'School';
        //     $staff->last_name       = 'Admin';
        //     $staff->full_name       = 'School Admin';
        //     $staff->date_of_birth   = '1980-12-26';
        //     $staff->date_of_joining = '2019-05-26';
        //     $staff->gender_id       = 1;
        //     $staff->school_id       = $i;
        //     $staff->email           = $email;
        //     $staff->staff_photo     = 'public/uploads/staff/1_infix_edu.jpg';
        //     $staff->casual_leave    = '12';
        //     $staff->medical_leave   = '15';
        //     $staff->metarnity_leave = '15';
        //     $staff->save();

        // }

        // }


        for($i=2; $i<=5; $i++){

            $email= $faker->email;
            $school= $faker->company. ' School';

            $store= new SmSchool();
            $store->school_name= $school;
            $store->email= $email;
            $store->created_at = date('Y-m-d h:i:s');
            $store->starting_date = date('Y-m-d');
            $store->is_email_verified = 1;
            $store->save();

            $academic_year = new SmAcademicYear();
            $academic_year->year = date('Y');
            $academic_year->title = ' academic year ' . date('Y');
            $academic_year->school_id = $i;
            $academic_year->starting_date = date('Y') . '-01-01';
            $academic_year->ending_date = date('Y') . '-12-31';
            $academic_year->save();
            
            $general_setting = new SmGeneralSettings();
            $general_setting->school_name = $school;
            $general_setting->email = $email;
            $general_setting->address = $faker->address;
            $general_setting->school_code = $i;
            $general_setting->school_id = $i;
            $general_setting->phone = $faker->phoneNumber;
            $general_setting->time_zone_id = 1;
            $general_setting->save();


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

            for ($j = 2; $j <= 21; ++$j) {
                $assign = new SaasSchoolModulePermissionAssign();
                $assign->module_id = $i;
                $assign->created_by = 1;
                $assign->updated_by = 1;
                $assign->school_id = $i;
                $assign->save();
            }

            if(moduleStatusCheck('RazorPay')== TRUE ){
                $payment_methods = ['Cash', 'Cheque', 'Bank', 'Stripe', 'Paystack','PayPal', 'RazorPay'];
            }else{
                $payment_methods = ['Cash', 'Cheque', 'Bank', 'Stripe', 'Paystack','PayPal'];
            }

            foreach ($payment_methods as $payment_method) {
                $method = new SmPaymentMethhod();
                $method->method = $payment_method;
                $method->type = 'System';
                $method->school_id = $i;
                $method->save();
            }

            DB::table('sm_payment_gateway_settings')->insert([
                [
                    'gateway_name'          => 'Stripe',
                    'gateway_username'      => 'demo@strip.com',
                    'gateway_password'      => '12334589',
                    'gateway_client_id'     => '',
                    'gateway_secret_key'    => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j',
                    'gateway_secret_word'   => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1',
                    'school_id'    => $i
                ]
            ]);


            DB::table('sm_payment_gateway_settings')->insert([
                [
                    'gateway_name'          => 'Paystack',
                    'gateway_username'      => 'demo@gmail.com',
                    'gateway_password'      => '12334589',
                    'gateway_client_id'     => '',
                    'gateway_secret_key'    => 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1',
                    'gateway_publisher_key' => 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71',
                    'school_id'    => $i
                ],

            ]);

            DB::table('sm_payment_gateway_settings')->insert([
                [
                    'gateway_name'          => 'PayPal',
                    'gateway_username'      => 'demo@paypal.com',
                    'gateway_password'      => '12334589',
                    'gateway_client_id'     => 'AaCPtpoUHZEXCa3v006nbYhYfD0HIX-dlgYWlsb0fdoFqpVToATuUbT43VuUE6pAxgvSbPTspKBqAF0x69',
                    'gateway_secret_key'    => 'EJ6q4h8w0OanYO1WKtNbo9o8suDg6PKUkHNKv-T6F4APDiq2e19OZf7DfpL5uOlEzJ_AMgeE0L2PtTEj69',
                    'gateway_publisher_key' => '',
                    'school_id'    => $i
                ],

            ]);

            if(moduleStatusCheck('RazorPay')== TRUE ){
                DB::table('sm_payment_gateway_settings')->insert([
                    [
                        'gateway_name'          => 'RazorPay',
                        'gateway_username'      => 'demo@gmail.com',
                        'gateway_password'      => '12334589',
                        'gateway_client_id'     => '',
                        'gateway_secret_key'    => '',
                        'gateway_publisher_key' => '',
                        'school_id'    => $i
                    ],

                ]);
            }

            DB::table('sm_payment_gateway_settings')->insert([
                [
                    'gateway_name'          => 'Bank',
                    'school_id'    => $i
                ],

            ]);

            DB::table('sm_payment_gateway_settings')->insert([
                [
                    'gateway_name'          => 'Cheque',
                    'school_id'    => $i
                ],

            ]);

            DB::table('sm_email_settings')->insert([
                [
                    'email_engine_type' => 'smtp',
                    'from_name' => $school,
                    'from_email' => $email,
                    'mail_driver'    => 'smtp',
                    'mail_host'    => 'smtp.gmail.com',
                    'mail_port'    => 587,
                    'mail_username'    =>  $email,
                    'mail_password'    => '12345678',
                    'mail_encryption'    => 'tls',
                    'school_id'    => $i,
                    'active_status'    => 0,
                    'academic_id'    => $academic_year->id,
                ],
                [
                    'email_engine_type' => 'php',
                    'from_name' => $school,
                    'from_email' => $email,
                    'mail_driver'    => 'php',
                    'mail_host'    => '',
                    'mail_port'    => '',
                    'mail_username'    =>  '',
                    'mail_password'    => '',
                    'mail_encryption'    => '',
                    'school_id'    => $i,
                    'active_status'    => 1,
                    'academic_id'    => $academic_year->id,
                ],
                
            ]);

            DB::table('sm_sms_gateways')->insert([
                [
                    'gateway_name' => 'Clickatell',
                    'clickatell_username' => 'demo1',
                    'clickatell_password' => '122334',
                    'school_id'    => $i
                ],
                [
                    'gateway_name' => 'Twilio',
                    'clickatell_username' => 'demo2',
                    'clickatell_password' => '12336',
                    'school_id'    => $i
                ],
                [
                    'gateway_name' => 'Msg91',
                    'clickatell_username' => 'demo3',
                    'clickatell_password' => '23445',
                    'school_id'    => $i
                ]
            ]);


            $sm_langs = SmLanguage::where('school_id',$i)->get();
                        foreach($sm_langs as $lang){
                            $newLang = new SmLanguage();
                            $newLang->language_name= $lang->language_name;
                            $newLang->native= $lang->native;
                            $newLang->language_universal= $lang->language_universal;
                            $newLang->active_status= $lang->active_status;
                            $newLang->school_id= $i;
                            $newLang->save();
                        }

            DB::table('sm_background_settings')->insert([
                [
                    'title'         => 'Dashboard Background',
                    'type'          => 'image',
                    'image'         => 'public/backEnd/img/body-bg.jpg',
                    'color'         => '',
                    'school_id'         => $i,
                    'is_default'    => 1,
                ]
            ]);

            $s = new SmStyle();
            $s->style_name = 'Default';
            $s->path_main_style = 'style.css';
            $s->path_infix_style = 'infix.css';
            $s->primary_color = 'var(--base_color)';
            $s->primary_color2 = '#7c32ff';
            $s->title_color = '#222222';
            $s->text_color = '#828bb2';
            $s->white = '#ffffff';
            $s->black = '#000000';
            $s->sidebar_bg = '#e7ecff';
            $s->barchart1 = '#8a33f8';
            $s->barchart2 = '#f25278';
            $s->barcharttextcolor = 'var(--base_color)';
            $s->barcharttextfamily = '"poppins", sans-serif';
            $s->areachartlinecolor1 = 'rgba(124, 50, 255, 0.5)';
            $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
            $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
            $s->school_id = $i;
            $s->is_active = 1;
            $s->is_active = 1;
            $s->save();

            $s = new  SmStyle();
            $s->style_name = 'Lawn Green';
            $s->path_main_style = 'lawngreen_version/style.css';
            $s->path_infix_style = 'lawngreen_version/infix.css';
            $s->primary_color = 'var(--base_color)';
            $s->primary_color2 = '#03e396';
            $s->title_color = '#222222';
            $s->text_color = '#828bb2';
            $s->white = '#ffffff';
            $s->black = '#000000';
            $s->sidebar_bg = '#e7ecff';
            $s->barchart1 = 'var(--base_color)';
            $s->barchart2 = '#03e396';
            $s->barcharttextcolor = '#03e396';
            $s->barcharttextfamily = '"Cerebri Sans", Helvetica, Arial, sans-serif';
            $s->areachartlinecolor1 = 'var(--base_color)';
            $s->areachartlinecolor2 = '#03e396';
            $s->dashboardbackground = '#e7ecff';
            $s->school_id = $i;
            $s->save();

            DB::table('sm_weekends')->insert([
                [
                    'name' => 'Saturday',
                    'order' => 1,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Sunday',
                    'order' => 2,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Monday',
                    'order' => 3,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Tuesday',
                    'order' => 4,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Wednesday',
                    'order' => 5,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Thursday',
                    'order' => 6,
                    'is_weekend' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ],
                [
                    'name' => 'Friday',
                    'order' => 7,
                    'is_weekend' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'academic_id' => $academic_year->id,
                    'school_id' => $i
                ]
            ]);



            for ($j = 1; $j <= 541; $j++) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $j;
                $permission->role_id = 5;
                $permission->school_id = $i;
                $permission->save();
            }

            $admins = [800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,900,901,902,903,904];

            foreach ($admins as $key => $value) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;   
                $permission->role_id = 5;                
                $permission->school_id = $i;                      
                $permission->save();
            }

            $ids = [399,400,401,402,403,404,428,429,430,431,456,457,458,459,460,461,462,463,478,482,483,484,549];
            foreach ($ids as $id) {
                $permission = InfixPermissionAssign::where('school_id',$i)->where('role_id',5)->where('module_id',$id)->first();
                if($permission){
                    $permission->delete();
                }          
            }

            // for teacher
            $teachers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 277, 278, 279, 280, 281, 282, 283, 284, 285,800,801,802,803,804,805,806,807,808,809,833,834,900,901,902,903,904];

            foreach ($teachers as $key => $value) {

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission->role_id = 4;
                $permission->school_id = $i;
                $permission->save();
            }

            // for receiptionists
            $receiptionists = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 376, 377, 378, 379, 380,900,901,902,903,904];

            foreach ($receiptionists as $key => $value) {

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission->role_id = 7;
                $permission->school_id = $i;
                $permission->save();
            }

            // for librarians
            $librarians = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 376, 377, 378, 379, 380,900,901,902,903,904];

            foreach ($librarians as $key => $value) {

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission->role_id = 8;
                $permission->school_id = $i;
                $permission->save();
            }

            // for drivers
            $drivers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 188, 193, 194, 19,900,901,902,903,904];

            foreach ($drivers as $key => $value) {

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission->role_id = 9;
                $permission->school_id = $i;
                $permission->save();
            }

            // for accountants
            $accountants = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 68, 69, 70, 83, 84, 85, 86, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 188, 193, 194, 195, 376, 377, 378, 379, 380, 381, 382, 383,900,901,902,903,904];

            foreach ($accountants as $key => $value) {

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission->role_id = 6;
                $permission->school_id = $i;
                $permission->save();
            }

            // student
            for ($j = 1; $j <= 55; $j++) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $j;
                $permission->role_id = 2;
                $permission->school_id = $i;
                $permission->save();
            }

                        
            $students = [800,810,815,900,901,902,903,904];
            foreach ($students as $key => $value) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;                      
                $permission->role_id = 2;
                $permission->school_id = $i;
                $permission->save();
            }


            // parent
            for ($j = 56; $j <= 99; $j++) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $j;
                $permission->role_id = 3;
                $permission->school_id = $i;
                $permission->save();
            }
            // chat module
            $parents = [910,911,912,913,914];
            foreach ($parents as $key => $value) {
                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;                   
                $permission->role_id = 3;
                $permission->school_id = $i;
                $permission->save();
            }


            $sectionData=['A','B','C','D','E'];
            foreach ($sectionData as $row) {
                for ($j = 2; $j <= 2; $j++) {
                    $s= new SmSection();
                    $s->section_name=$row.' '.$j;
                    $s->created_at = date('Y-m-d h:i:s');
                    $s->school_id=$i;
                    $s->save();
                }
            } 

            $classData = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine','Ten'];
            foreach ($classData as $row) {
                for ($j = 2; $j <= 2; $j++) {
                    $s = new SmClass();
                    $s->class_name = $row . ' ' . $j;
                    $s->created_at = date('Y-m-d h:i:s');
                    $s->school_id = $i;
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
                        $s->created_at = date('Y-m-d h:i:s');
                        $s->save();
                    }
                } 
            }


            DB::table('sm_subjects')->insert([

                [
                    'school_id'=> $i,
                    'subject_name'=> 'Bangla',
                    'subject_code'=> 'BAN-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
                    'subject_name'=> 'English For Today',
                    'subject_code'=> 'ENG-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
                    'subject_name'=> 'Mathematics',
                    'subject_code'=> 'MATH-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
                    'subject_name'=> 'Agricultural Education',
                    'subject_code'=> 'AG-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
                    'subject_name'=> 'Information and Communication Technology',
                    'subject_code'=> 'ICT-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
                    'subject_name'=> 'Science',
                    'subject_code'=> 'SI-01',
                    'subject_type'=> 'T',
                    'active_status'=> 1,
                    'created_at' => date('Y-m-d h:i:s')
                ],
                [
                    'school_id'=> $i,
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
                for ($j = 2; $j < 3; $j++) {
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
                $newUser->save();
                $newUser->toArray();
                $staff_id_number = $newUser->id;

             //   DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
                        'created_at' => date('Y-m-d h:i:s')
                    ]


                ]);
                }
            }

            for ($j = 2; $j <= 10; $j++) {
                $store = new SmExpenseHead();
                $store->name = $faker->word;
                $store->description = $faker->realText($maxNbChars = 100, $indexSize = 1);
                $store->school_id = $i;
                $store->save();
            }

            for ($j = 2; $j <= 10; $j++) {
                $store = new SmAddExpense();
                $store->name = 'Utility Bills';
                $store->expense_head_id = 4;
                $store->payment_method_id = 1;
                $store->date = '2019-0' . $j . '-05';
                $store->amount = 1200 + rand() % 10000;
                $store->description = 'Sample Data ' . $j;
                $store->save();
            }



        }
        
    }
}