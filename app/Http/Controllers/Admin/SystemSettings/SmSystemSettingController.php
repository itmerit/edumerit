<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\User;
use App\SmExam;
use Illuminate\Validation\ValidationException;
use ZipArchive;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\Language;
use App\SmBackup;
use App\SmModule;
use App\SmSchool;
use App\SmCountry;
use App\SmSection;
use App\SmSubject;
use App\SmWeekend;
use App\SmCurrency;
use App\SmExamType;
use App\SmLanguage;
use App\SmTimeZone;
use App\Models\Theme;
use App\SmDateFormat;
use App\SmSmsGateway;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmAcademicYear;
use App\SmEmailSetting;
use App\SmAssignSubject;
use App\SmSystemVersion;
use App\SmChartOfAccount;
use App\SmLanguagePhrase;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\SmHomePageSetting;
use App\Traits\UploadTheme;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\SmFrontendPersmission;
use Illuminate\Support\Carbon;
use App\SmPaymentGatewaySetting;
use App\Traits\DatabaseTableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Dumper\Shuttle_Dumper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Dumper\Shuttle_Exception;
use Illuminate\Support\Facades\Validator;
use Modules\MenuManage\Entities\SidebarNew;
use Modules\RolePermission\Entities\Permission;
use Modules\University\Entities\UnAcademicYear;
use App\Http\Requests\PaymentGatewayFormRequest;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use App\Http\Requests\Admin\GeneralSettings\SmEmailSettingsRequest;
use App\Http\Requests\Admin\GeneralSettings\SmGeneralSettingsRequest;

class SmSystemSettingController extends Controller
{
    use UploadTheme;
    public function __construct()
    {
        $this->middleware('PM');
        if (empty(Auth::user()->id)) {
            return redirect('login');
        }
    }


    public function sendTestMail()
    {
        $e = SmEmailSetting::where('active_status',1)->where('school_id', Auth::user()->school_id)->first();
        if (empty($e)) {
            Toastr::error('Email Setting is Not Complete', 'Failed');
            return redirect()->back();
        }

        if ( ($e->mail_driver == "smtp") &&( $e->mail_username == '' || $e->mail_password == ''
                || $e->mail_encryption == ''
                || $e->mail_port == ''
                || $e->mail_host == ''
                || $e->mail_driver == '' ))
        {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }
        try {
            $reciver_email = Auth::user()->email ?? User::find(1)->email;
            $receiver_name = Auth::user()->full_name;
            $compact['user_name'] = $receiver_name;

            @send_mail($reciver_email, $receiver_name, "test_mail", $compact);
            Toastr::success('Test Mail Send Successfully Your Email', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function news()
    {

        try {
            return $exams = SmExam::where('academic_id', getAcademicId())->get();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->get();
            $classes = SmClass::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            $subjects = SmSubject::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            $sections = SmSection::where('academic_id', getAcademicId())->where('active_status', 1)->get();
            return view('frontEnd.home.light_news', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function notificationApi(Request $request)
    {

        try {
            return view('backEnd.api');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function flutterNotificationApi(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if ($user->notificationToken != '') {
                //echo 'Infix Edu';
                define('API_ACCESS_KEY', 'AAAAFyQhhks:APA91bGJqDLCpuPgjodspo7Wvp1S4yl3jYwzzSxet_sYQH9Q6t13CtdB_EiwD6xlVhNBa6RcHQbBKCHJ2vE452bMAbmdABsdPriJy_Pr9YvaM90yEeOCQ6VF7JEQ501Prhnu_2bGCPNp');
                //   $registrationIds = ;
                #prep the bundle
                $msg = array(
                    'body' => $_REQUEST['body'],
                    'title' => $_REQUEST['title'],

                );
                $fields = array(
                    'to' => $user->notificationToken,
                    'notification' => $msg
                );


                $headers = array(
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                #Send Reponse To FireBase Server
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                echo $result;
                curl_close($ch);
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Token not found');
                }
            }
        } catch (\Exception $e) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError($e);
            }
        }
    }

    // tableEmpty
    public function tableEmpty()
    {

        try {
            $sms_services = DB::table('migrations')->get();

            $tables = $this->getAllTables();
            $table_list = [];
            $table_list_with_count = [];
            $tableString = 'Tables_in_' . DB::connection()->getDatabaseName();

            foreach ($tables as $table) {
                $table_name = $table->$tableString;
                $table_list[] = $table_name;
                $count = DB::table($table_name)->count();
                $table_list_with_count[] = $table->$tableString . '(' . $count . ')';
                // $table_strings[] = '$this->call('. $table_name.'Seeder::class);';

            }
            return view('backEnd.systemSettings.tableEmpty', compact('table_list', 'table_list_with_count'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // end tableEmpty

    public function databaseDelete(Request $request)
    {
        try {
            $list_of_table = $request->permisions;

            if (empty($list_of_table)) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
            foreach ($list_of_table as $table) {

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table($table)->truncate();
                //            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $staff = new SmStaff();

            $staff->user_id = Auth::user()->id;
            $staff->role_id = 1;
            $staff->staff_no = 1;
            $staff->designation_id = 1;
            $staff->department_id = 1;
            $staff->first_name = 'Super';
            $staff->last_name = 'Admin';
            $staff->full_name = 'Super Admin';
            $staff->fathers_name = 'NA';
            $staff->mothers_name = 'NA';

            $staff->date_of_birth = '1980-12-26';
            $staff->date_of_joining = '2019-05-26';

            $staff->gender_id = 1;
            $staff->email = Auth::user()->email;
            $staff->mobile = '';
            $staff->emergency_mobile = '';
            $staff->merital_status = '';
            $staff->staff_photo = 'public/uploads/staff/staff1.jpg';

            $staff->current_address = '';
            $staff->permanent_address = '';
            $staff->qualification = '';
            $staff->experience = '';

            $staff->casual_leave = '12';
            $staff->medical_leave = '15';
            $staff->metarnity_leave = '45';

            $staff->driving_license = '';
            $staff->driving_license_ex_date = '2019-02-23';
            $staff->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function databaseRestory(Request $request)
    {

        try {
            set_time_limit(900);
            Artisan::call('db:seed');
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function displaySetting()
    {

        try {
            $sms_services = SmSmsGateway::all();
            $active_sms_service = SmSmsGateway::select('id')->where('active_status', 1)->first();
            return view('backEnd.systemSettings.displaySetting', compact('sms_services', 'active_sms_service'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function smsSettings()
    {
        try {
            $sms_services['Twilio'] = SmSmsGateway::where('gateway_name','Twilio')->where('school_id',Auth::user()->school_id)->firstOrCreate();
            $sms_services['Msg91'] = SmSmsGateway::where('gateway_name','Msg91')->where('school_id',Auth::user()->school_id)->firstOrCreate();
            $sms_services['TextLocal'] = SmSmsGateway::where('gateway_name','TextLocal')->where('school_id',Auth::user()->school_id)->firstOrCreate();
            $sms_services['AfricaTalking'] = SmSmsGateway::where('gateway_name','AfricaTalking')->where('school_id',Auth::user()->school_id)->firstOrCreate();
            $sms_services['Mobile SMS'] = SmSmsGateway::where('gateway_name','Mobile SMS')->where('school_id',Auth::user()->school_id)->firstOrCreate();
            if(moduleStatusCheck('HimalayaSms')){
                $sms_services['HimalayaSms'] = SmSmsGateway::where('gateway_name','HimalayaSms')->where('school_id',Auth::user()->school_id)->first();
                $all_sms_services= SmSmsGateway::where('school_id',Auth::user()->school_id)->get();
            }
            elseif( ! moduleStatusCheck('HimalayaSms')){
                $all_sms_services= SmSmsGateway::where('gateway_name', '!=','HimalayaSms')->where('school_id',Auth::user()->school_id)->get();
            }
            $active_sms_service = SmSmsGateway::where('school_id',Auth::user()->school_id)->where('active_status', 1)->first();


            return view('backEnd.systemSettings.smsSettings', compact('sms_services', 'active_sms_service','all_sms_services'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageSettings()
    {
        try {
            $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            $all_languages = Language::orderBy('code', 'ASC')->get()->except($sms_languages->pluck('lang_id')->toArray());
            return view('backEnd.systemSettings.languageSettings', compact('sms_languages', 'all_languages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageEdit($id)
    {

        try {
            $selected_languages = SmLanguage::find($id);
            $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            $all_languages = DB::table('languages')->where('school_id', Auth::user()->school_id)->orderBy('code', 'ASC')->get();
            return view('backEnd.systemSettings.languageSettings', compact('sms_languages', 'all_languages', 'selected_languages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageUpdate(Request $request)
    {

        try {
            $id = $request->id;
            $language_id = $request->language_id;
            $language_details = Language::find($language_id);

            if (!empty($language_id)) {
                $sms_languages = SmLanguage::find($id);
                $sms_languages->language_name = $language_details->name != null ? $language_details->name : '';
                $sms_languages->language_universal = $language_details->code;
                $sms_languages->native = $language_details->native;
                $sms_languages->lang_id = $language_details->id;

                $sms_languages->save();
                Toastr::success('Operation successful', 'Success');
                return redirect('language-settings');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function setEnvironmentValue()
    {

        try {
            $values['APP_LOCALE'] = 'en';
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            $res = file_put_contents($envFile, $str);
            return $res;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function ajaxLanguageChange(Request $request)
    {


        try {
            //     $uni = $request->id;
            //     SmLanguage::where('active_status', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);

            //     $updateLang = SmLanguage::where('language_universal', $uni)->where('school_id', Auth::user()->school_id)->first();

            //     $updateLang->active_status = 1;
            //     $updateLang->update();
            //     $langs = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            //     session()->put('systemLanguage',$langs);
            //    if($uni != 'en'){
            //     session()->put('lang', strtolower($uni));
            //    }
            //    App::setLocale($uni);


            // //    $values['APP_LOCALE'] = $updateLang->language_universal;
            // //     $envFile = app()->environmentFilePath();
            // //     $str = file_get_contents($envFile);
            // //     if (count($values) > 0) {
            // //         foreach ($values as $envKey => $envValue) {
            // //             $str .= "\n";
            // //             $keyPosition = strpos($str, "{$envKey}=");
            // //             $endOfLinePosition = strpos($str, "\n", $keyPosition);
            // //             $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            // //             if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
            // //                 $str .= "{$envKey}={$envValue}\n";
            // //             } else {
            // //                 $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            // //             }
            // //         }
            // //     }
            // //     $str = substr($str, 0, -1);
            // //     $res = file_put_contents($envFile, $str);
            //    return response()->json([$uni]);

            $uni = $request->id;
            SmLanguage::where('active_status', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);

            $updateLang = SmLanguage::where('language_universal', $uni)->where('school_id', Auth::user()->school_id)->first();

            $updateLang->active_status = 1;
            $updateLang->update();
            $langs = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage', $langs);

            $values['APP_LOCALE'] = $updateLang->language_universal;
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            $res = file_put_contents($envFile, $str);

            return response()->json([$updateLang]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSubjectDropdown(Request $request)
    {

        try {
            $class_id = $request->class;
            $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id]])->get();

            $subjectsName = [];
            foreach ($allSubjects as $allSubject) {
                $subjectsName[] = SmSubject::find($allSubject->subject_id);
            }

            return response()->json([$subjectsName]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageAdd(Request $request)
    {


        $request->validate([

            'lang_id' => 'required|max:255',
        ]);

        try {
            $lang_id = $request->lang_id;
            $language_details = DB::table('languages')->where('id', $lang_id)->first();

            if (!empty($language_details)) {
                $sms_languages = new SmLanguage();
                $sms_languages->language_name = $language_details->name;
                $sms_languages->language_universal = $language_details->code;
                $sms_languages->native = $language_details->native;
                $sms_languages->lang_id = $language_details->id;
                $sms_languages->active_status = '0';
                $sms_languages->school_id = Auth::user()->school_id;
                $sms_languages->save();

                if ($language_details->code != 'en') {
                    File::copyDirectory(base_path('/resources/lang/en'), base_path('/resources/lang/' . $language_details->code));
                    $modules = Module::all();
                    foreach ($modules as $module) {
                        File::copyDirectory(module_path($module->getName()) . '/Resources/lang/en', module_path($module->getName()) . '/Resources/lang/' . $language_details->code);
                    }
                }
                Cache::forget('translations');
                Toastr::success('Operation successful', 'Success');
                return redirect('language-settings');
            } //not empty language
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //backupSettings
    public function backupSettings()
    {
        try {
            $sms_dbs = SmBackup::where('academic_id', getAcademicId())->orderBy('id', 'DESC')->whereNull('lang_type')->get();
            return view('backEnd.systemSettings.backupSettings', compact('sms_dbs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function BackupStore(Request $request)
    {
        $request->validate([
            'content_file' => 'required|file',
        ]);


        try {
            if (!$request->file('content_file')) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
            $file = $request->file('content_file');
            if (in_array($file->getClientOriginalExtension(), ['sql', 'gz'])) {
                $file_name = 'Restore_' . date('d_m_Y_') . $file->getClientOriginalName();
                $file->move('public/databaseBackup/', $file_name);
                $content_file = 'public/databaseBackup/' . $file_name;
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            $result = false;
            if (isset($content_file)) {
                $store = new SmBackup();
                $store->file_name = $file_name;
                $store->source_link = $content_file;
                $store->active_status = 1;
                $store->created_by = Auth::user()->id;
                $store->updated_by = Auth::user()->id;
                $result = $store->save();
            }
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageSetup($language_universal)
    {


        try {
            $lang = 'en';
            $files['base']   = glob(resource_path('lang/' . $lang . '/*.php'));

            $modules = \Module::all();
            foreach ($modules as $module) {
                if (moduleStatusCheck($module->getName())) {
                    $file = glob(module_path($module->getName()) . '/Resources/lang/'.$lang.'/*.php');
                    if ($file) {
                        $files[$module->getLowerName()] = $file;
                    }
                }
            }

            $modules = [];
            foreach($files as $key => $module){
//                $files[] = $key;
                foreach($module as $file){
                    $file = basename($file, '.php');
                    if ($file != 'validation'){
                        $modules[$key][$key.'::'.$file] = $file;
                    }

                }
            }
            return view('backEnd.systemSettings.languageSetup', compact('language_universal', 'modules'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteDatabase($id)
    {

        try {
            $source_link = "";
            // $data        = SmBackup::find($id);
            if (checkAdmin()) {
                $data = SmBackup::find($id);
            } else {
                $data = SmBackup::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            }
            if (!empty($data)) {
                $source_link = $data->source_link;
                if (file_exists($source_link)) {
                    unlink($source_link);
                }
            }
            $result = SmBackup::where('id', $id)->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function CacheClear(){
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }
    //download database from public/databaseBackup
    public function downloadDatabase($id)
    {

        try {
            $source_link = "";
            // $data        = SmBackup::where('id', $id)->first();
            if (checkAdmin()) {
                $data = SmBackup::find($id);
            } else {
                $data = SmBackup::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            }
            if (!empty($data)) {
                $source_link = $data->source_link;
                if (file_exists($source_link)) {
                    unlink($source_link);
                }
            }

            if (file_exists($source_link)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($source_link) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($source_link));
                flush(); // Flush system output buffer
                readfile($source_link);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //restore database from public/databaseBackup
    public function restoreDatabase($id)
    {

        try {
            // $sm_db = SmBackup::where('id', $id)->first();
            if (checkAdmin()) {
                $sm_db = SmBackup::find($id);
            } else {
                $sm_db = SmBackup::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            }
            if (!empty($sm_db)) {
                $source_link = $sm_db->source_link;
            }

            $DB_HOST = env("DB_HOST", "");
            $DB_DATABASE = env("DB_DATABASE", "");
            $DB_USERNAME = env("DB_USERNAME", "");
            $DB_PASSWORD = env("DB_PASSWORD", "");

            $connection = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

            if (!file_exists($source_link)) {
                Toastr::error('File not found', 'Failed');
                return redirect()->back();
            }
            $handle = fopen($source_link, "r+");
            $contents = fread($handle, filesize($source_link));
            $sql = explode(';', $contents);
            $flag = 0;
            foreach ($sql as $query) {
                $result = mysqli_query($connection, $query);
                if ($result) {
                    $flag = 1;
                }
            }
            fclose($handle);

            if ($flag) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //get files Backup #file
    public function getfilesBackup($id)
    {
        set_time_limit(1600);
        try {
            if ($id == 1) {
                $path = base_path() . '/public/uploads';
                $zip_file = 'Backup_' . date('d_m_Y') . '_' . time() . '_Images.zip';
            } else if ($id == 2) {
                $path = base_path() . '';
                $zip_file = 'Backup_' . date('d_m_Y') . '_' . time() . '_Projects.zip';
            }
            if ($id == 1) {
                $folder = public_path() . '/Backup/ImageBackup/';
                if (!file_exists($folder)) {
                    File::makeDirectory($folder, $mode = 0777, true, true);
                }
                $temp = $folder . $zip_file;
            } else {
                $folder = public_path() . '/Backup/ProjectBackup/';
                if (!file_exists($folder)) {
                    File::makeDirectory($folder, $mode = 0777, true, true);
                }
                $temp = $folder . $zip_file;
            }
            $zip = new \ZipArchive();
            $zip->open($temp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            $store = new SmBackup();
            $store->file_name = $zip_file;
            $store->source_link = $temp;
            $store->active_status = 1;
            $store->file_type = $id;
            $store->created_by = Auth::user()->id;
            $store->updated_by = Auth::user()->id;
            $store->academic_id = getAcademicId();
            $result = $store->save();
            if ($id == 2) {
                return response()->download($zip_file);
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // download Files #file
    public function downloadFiles($id)
    {


        try {
            // $sm_db       = SmBackup::where('id', $id)->first();
            if (checkAdmin()) {
                $sm_db = SmBackup::find($id);
            } else {
                $sm_db = SmBackup::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            }
            $source_link = $sm_db->source_link;
            if (@file_exists(@$source_link)) {
                return response()->download($source_link);
            } else {
                Toastr::error('File not found', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getDatabaseBackup()
    {

        try {
            $db_export = Shuttle_Dumper::create(array(
                'host' => env('DB_HOST'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'db_name' => env('DB_DATABASE'),
            ));
            $folder = public_path('/Backup/DatabaseBackup/');

            File::ensureDirectoryExists($folder, 0777, true);

            $file = 'backup_' . date('Y_m_d_H_i_s') . '.sql.gz';
            $full_path = $folder . $file;
            $db_export->dump($full_path);

            $get_backup = new SmBackup();
            $get_backup->file_name = $file;
            $get_backup->source_link = $full_path;
            $get_backup->active_status = 1;
            $get_backup->file_type = 0;
            $get_backup->academic_id = getAcademicId();
            $get_backup->save();
            Toastr::success('Operation successful', 'Success');

        } catch (Shuttle_Exception $e) {
            Toastr::error($e, 'Failed');
        }

        return redirect()->back();
    }

    public function updateClickatellData(Request $request)
    {

        try {
            $gateway_id = $_POST['gateway_id'];
            $clickatell_username = $_POST['clickatell_username'];
            $clickatell_password = $_POST['clickatell_password'];
            $clickatell_api_id = $_POST['clickatell_api_id'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('gateway_name', $request->gateway_name)->where('school_id',Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {
                    $gatewayDetails = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetails->clickatell_username = $clickatell_username;
                    $gatewayDetails->clickatell_password = $clickatell_password;
                    $gatewayDetails->clickatell_api_id = $clickatell_api_id;
                    $results = $gatewayDetails->update();
                } else {
                    $gatewayDetail = new SmSmsGateway();
                    $gatewayDetail->gateway_name = $request->gateway_name;
                    $gatewayDetail->clickatell_username = $clickatell_username;
                    $gatewayDetail->clickatell_password = $clickatell_password;
                    $gatewayDetail->clickatell_api_id = $clickatell_api_id;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateTwilioData()
    {
        try {
            $gateway_id = $_POST['gateway_id'];
            $twilio_account_sid = $_POST['twilio_account_sid'];
            $twilio_authentication_token = $_POST['twilio_authentication_token'];
            $twilio_registered_no = $_POST['twilio_registered_no'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('gateway_name', $_POST['gateway_name'])->where('school_id',Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetailss = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetailss->twilio_account_sid = $twilio_account_sid;
                    $gatewayDetailss->twilio_authentication_token = $twilio_authentication_token;
                    $gatewayDetailss->twilio_registered_no = $twilio_registered_no;
                    $gatewayDetailss->school_id = Auth::user()->school_id;
                    $results = $gatewayDetailss->update();
                } else {

                    $gatewayDetail = new SmSmsGateway();
                    $gatewayDetail->gateway_name = $_POST['gateway_name'];
                    $gatewayDetail->twilio_account_sid = $twilio_account_sid;
                    $gatewayDetail->twilio_authentication_token = $twilio_authentication_token;
                    $gatewayDetail->twilio_registered_no = $twilio_registered_no;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo 1;
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateTextlocalData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'textlocal_username' => 'required',
            'textlocal_hash' => 'required',
            'textlocal_sender' => 'required',
            'textlocal_type'=>'required','in:com,in'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['textlocal_settings' => 'active']);
        }

        try {
            $gateway_id = $_POST['gateway_id'];
            $textlocal_username = $_POST['textlocal_username'];
            $textlocal_hash = $_POST['textlocal_hash'];
            $textlocal_sender = $_POST['textlocal_sender'];
            $textlocal_type = $_POST['textlocal_type'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('gateway_name', $request->gateway_name)->where('school_id',Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetails = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetails->textlocal_username = $textlocal_username;
                    $gatewayDetails->textlocal_hash = $textlocal_hash;
                    $gatewayDetails->textlocal_sender = $textlocal_sender;
                    $gatewayDetails->type = $textlocal_type;
                    $results = $gatewayDetails->update();
                } else {
                    $gatewayDetail = new SmSmsGateway();
                    $gatewayDetail->gateway_name = $request->gateway_name;
                    $gatewayDetail->textlocal_username = $textlocal_username;
                    $gatewayDetail->textlocal_hash = $textlocal_hash;
                    $gatewayDetail->textlocal_sender = $textlocal_sender;
                    $gatewayDetails->type = $textlocal_type;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back()->with(['textlocal_settings' => 'active']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['textlocal_settings' => 'active']);
        }
    }

    public function updateAfricaTalkingData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'africatalking_username' => 'required',
            'africatalking_api_key' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['africatalking_settings' => 'active']);
        }

        try {
            $gateway_id = $_POST['gateway_id'];
            $africatalking_username = $_POST['africatalking_username'];
            $africatalking_api_key = $_POST['africatalking_api_key'];

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('gateway_name', $request->gateway_name)->where('school_id',Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetails = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetails->africatalking_username = $africatalking_username;
                    $gatewayDetails->africatalking_api_key = $africatalking_api_key;
                    $results = $gatewayDetails->update();
                } else {

                    $gatewayDetail = new SmSmsGateway();
                    $gatewayDetail->gateway_name = $request->gateway_name;
                    $gatewayDetail->africatalking_username = $africatalking_username;
                    $gatewayDetail->africatalking_api_key = $africatalking_api_key;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back()->with(['africatalking_settings' => 'active']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['africatalking_settings' => 'active']);
        }
    }

    public function updateMsg91Data(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'msg91_authentication_key_sid' => 'required',
            'msg91_route' => 'required',
            'msg91_country_code' => 'required',
            'msg91_sender_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['msg91_settings' => 'active']);
        }


        try {
            $gateway_id = $request->gateway_id;
            $msg91_authentication_key_sid = $request->msg91_authentication_key_sid;
            $msg91_route = $request->msg91_route;
            $msg91_country_code = $request->msg91_country_code;
            $msg91_sender_id = $request->msg91_sender_id;

            $key1 = 'MSG91_KEY';
            $key2 = 'MSG91_SENDER_ID';
            $key3 = 'MSG91_COUNTRY';
            $key4 = 'MSG91_ROUTE';

            $value1 = $msg91_authentication_key_sid;
            $value2 = $msg91_sender_id;
            $value3 = $msg91_country_code;
            $value4 = $msg91_route;

            $path = base_path() . "/.env";
            $MSG91_KEY = env($key1);
            $MSG91_SENDER_ID = env($key2);
            $MSG91_COUNTRY = env($key3);
            $MSG91_ROUTE = env($key4);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    "$key1=" . $MSG91_KEY,
                    "$key1=" . $value1,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key2=" . $MSG91_SENDER_ID,
                    "$key2=" . $value2,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key3=" . $MSG91_COUNTRY,
                    "$key3=" . $value3,
                    file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    "$key4=" . $MSG91_ROUTE,
                    "$key4=" . $value4,
                    file_get_contents($path)
                ));
            }

            if ($gateway_id) {
                $gatewayDetails = SmSmsGateway::where('gateway_name', $request->gateway_name)->where('school_id',Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetails = SmSmsGateway::find($gatewayDetails->id);
                    $gatewayDetails->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                    $gatewayDetails->msg91_sender_id = $msg91_sender_id;
                    $gatewayDetails->msg91_route = $msg91_route;
                    $gatewayDetails->msg91_country_code = $msg91_country_code;
                    $results = $gatewayDetails->update();
                } else {
                    $gatewayDetail = new SmSmsGateway();
                    $gatewayDetail->gateway_name = $request->gateway_name;
                    $gatewayDetail->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                    $gatewayDetail->msg91_sender_id = $msg91_sender_id;
                    $gatewayDetail->msg91_route = $msg91_route;
                    $gatewayDetail->msg91_country_code = $msg91_country_code;

                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back()->with(['msg91_settings' => 'active']);
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back()->with(['msg91_settings' => 'active']);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['msg91_settings' => 'active']);
        }
    }

    public function activeSmsService()
    {

        try {
            $sms_service = $_GET['sms_service'];

            if ($sms_service) {
                $gatewayDetails = SmSmsGateway::where('school_id',Auth::user()->school_id)->where('active_status', '=', 1)
                    ->update(['active_status' => 0]);
            }

            $gatewayDetails = SmSmsGateway::find($sms_service);
            $gatewayDetails->active_status = 1;
            $results = $gatewayDetails->update();
            if($results){
                return response()->json('success');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generalSettingsView(Request $request)
    {
        try {


            $editData = generalSetting();

            $session = SmGeneralSettings::join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_general_settings.session_id')->find(1);
      
            return view('backEnd.systemSettings.generalSettingsView', compact('editData', 'session'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateGeneralSettings(Request $request)
    {
      
        try {
            $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $session_ids = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
            $dateFormats = SmDateFormat::where('active_status', 1)->get();
            $languages = SmLanguage::where('school_id',auth()->user()->school_id)->get();
            $countries = SmCountry::select('currency')->distinct('currency')->get();
            $currencies = SmCurrency::where('school_id',auth()->user()->school_id)->get();
            $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $time_zones = SmTimeZone::all();
            $weekends = SmWeekend::where('school_id', Auth::user()->school_id)->get();

            $sell_heads = SmChartOfAccount::where('active_status', '=', 1)
                ->where('school_id', Auth::user()->school_id)
                ->where('type', 'I')
                ->get();

            
            return view('backEnd.systemSettings.updateGeneralSettings', compact('editData', 'session_ids', 'dateFormats', 'languages', 'countries', 'currencies', 'academic_years', 'time_zones', 'weekends', 'sell_heads'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateGeneralSettingsData(SmGeneralSettingsRequest $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            
            $id = Auth::user()->school_id;
            $generalSettData = SmGeneralSettings::where('school_id', $id)->first();
            $generalSettData->school_name = $request->school_name;
            $generalSettData->site_title = $request->site_title;
            $generalSettData->school_code = $request->school_code;
            $generalSettData->address = $request->address;
            $generalSettData->phone = $request->phone;
            $generalSettData->email = $request->email;
            $generalSettData->income_head_id = $request->income_head;

            if(moduleStatusCheck('University')){
                $generalSettData->un_academic_id = $request->session_id;
            }else{
                $generalSettData->session_id = $request->session_id;
                $generalSettData->academic_id = $request->session_id;
            }
            $generalSettData->language_id = $request->language_id;
            $generalSettData->week_start_id = $request->week_start_id;
            $generalSettData->date_format_id = $request->date_format_id;
            $generalSettData->currency = $request->currency;
            $generalSettData->currency_symbol = $request->currency_symbol;
            $generalSettData->promotionSetting = $request->promotionSetting;
            $generalSettData->time_zone_id    = $request->time_zone;
            $generalSettData->file_size       = $request->file_size;
            $generalSettData->ss_page_load       = $request->ss_page_load;
            if(moduleStatusCheck('Fees')){
                $generalSettData->fees_status       = $request->fees_status;
            }
            if(moduleStatusCheck('Lms')){
                $generalSettData->lms_checkout       = $request->lms_checkout;
            }

            $generalSettData->attendance_layout       = $request->attendance_layout;
            $generalSettData->copyright_text  = $request->copyright_text;
            $generalSettData->multiple_roll  = $request->multiple_roll;
            $generalSettData->direct_fees_assign  = $request->direct_fees_assign;
            $generalSettData->result_type  = $request->result_type;
            $generalSettData->with_guardian  = $request->with_guardian;
            $generalSettData->due_fees_login  = $request->due_fees_login;
            $results = $generalSettData->save();

            if ($results) {
                session()->forget('generalSetting');
                session()->forget('system_date_format');
                session()->forget('sessionId');
                session()->put('sessionId', $request->session_id);
                session()->put('generalSetting', $generalSettData);
            }


            // weekend
            $w = SmWeekend::where('id', $request->week_start_id)->first();
            if ($w) {
                $greater_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->where('order', '>=', $w->order)->orderBy('order', 'ASC')->get();
                $less_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->where('order', '<', $w->order)->orderBy('order', 'ASC')->get();

                $i = 1;
                foreach ($greater_weekends as $greater_weekend) {
                    $g_weekend = SmWeekend::where('id', $greater_weekend->id)->first();
                    $g_weekend->order = $i++;
                    $g_weekend->save();
                }
                $max_order = SmWeekend::where('school_id', Auth::user()->school_id)->max('order');
                foreach ($less_weekends as $less_weekend) {
                    $l_weekend = SmWeekend::where('id', $less_weekend->id)->first();
                    $l_weekend->order = ++$max_order;
                    $l_weekend->save();
                }
            }


            // weekend end 

            $school = SmSchool::find(Auth::user()->school_id);
            $school->school_name = $request->school_name;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->phone = $request->phone;
            $school->email = $request->email;
            $school->save();

            if ($generalSettData->timeZone != "") {
                $value1 = $generalSettData->timeZone->time_zone;


                $key1 = 'APP_TIMEZONE';

                $path = base_path() . "/.env";
                $APP_TIMEZONE = env($key1);

                if (file_exists($path)) {
                    file_put_contents($path, str_replace(
                        "$key1=" . $APP_TIMEZONE,
                        "$key1=" . $value1,
                        file_get_contents($path)
                    ));
                }
            }

            $get_all_school_settings = SmGeneralSettings::get();

            foreach ($get_all_school_settings as $key => $school_setting) {
                $school_setup = SmGeneralSettings::find($school_setting->id);
                $school_setup->language_id = $request->language_id;
                $school_setup->date_format_id = $request->date_format_id;
                $school_setup->currency = $request->currency;
                $school_setup->currency_symbol = $request->currency_symbol;
                $school_setup->time_zone_id = $request->time_zone;
                $school_setup->copyright_text = $request->copyright_text;
                $results = $school_setup->save();
            }
            
            Toastr::success('Operation successful', 'Success');
            return redirect('general-settings');
        } catch (\Exception $e) {
        
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateSchoolLogo(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $validator = Validator::make($request->all(), [
            'main_school_logo' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
            'main_school_favicon' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
           
            if ($request->file('main_school_logo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_logo');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_logo = "";
                $file = $request->file('main_school_logo');
                $main_school_logo = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_logo);
                $main_school_logo = 'public/uploads/settings/' . $main_school_logo;
                $generalSettData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                $generalSettData->logo = $main_school_logo;
                $results = $generalSettData->update();

                if ($results) {
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                    session()->put('school_config', $school_config);

                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }
            } // for upload School favicon
            else if ($request->file('main_school_favicon') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_favicon');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_favicon = "";
                $file = $request->file('main_school_favicon');
                $main_school_favicon = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_favicon);
                $main_school_favicon = 'public/uploads/settings/' . $main_school_favicon;
                $generalSettData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                $generalSettData->favicon = $main_school_favicon;
                $results = $generalSettData->update();

                if ($results) {
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                    session()->put('school_config', $school_config);
                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }


            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('No change applied, please try again');
                }
                Toastr::error('No change applied', 'Failed');
                return redirect()->back();
            }
            if ($results) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function emailSettings()
    {

        try {
            $editData = SmEmailSetting::where('email_engine_type','smtp')->where('school_id',Auth::user()->school_id)->first();
            $editDataPhp = SmEmailSetting::where('email_engine_type','php')->where('school_id',Auth::user()->school_id)->first();
            $active_mail_driver = SmGeneralSettings::where('school_id',Auth::user()->school_id)->select('email_driver')->first()->email_driver;
            Session::put($active_mail_driver, "active");
            return view('backEnd.systemSettings.emailSettingsView', compact('editData','editDataPhp','active_mail_driver'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateEmailSettingsData(SmEmailSettingsRequest $request)
    {
        try {

            if ($request->engine_type == "smtp") {


                $e = SmEmailSetting::where('email_engine_type', 'smtp')
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                   
                if (empty($e)) {
                    $e = new SmEmailSetting();
                    $e->email_engine_type = 'smtp';
                    $e->mail_driver = $request->mail_driver;
                    $e->school_id = Auth::user()->school_id;
                }
                $e->from_name = $request->from_name;
                $e->from_email = $request->from_email;
                $e->mail_host = $request->mail_host;
                $e->mail_port = $request->mail_port;
                $e->mail_username = $request->mail_username;
                $e->mail_password = $request->mail_password;
                $e->mail_encryption = $request->mail_encryption;
                $e->active_status = $request->active_status;

                $results = $e->save();
              
                if ($request->active_status == 1) {
                    $gs = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
                    $gs->email_driver = "smtp";
                    $gs->save();
                    session()->forget('generalSetting');
                    session()->put('generalSetting', $gs);
                
                    $phpp = SmEmailSetting::where('email_engine_type', 'php')
                        ->where('school_id', Auth::user()->school_id)
                        ->first();
                    if ($phpp) {
                        $phpp->active_status = 0;
                        $phpp->save();
                    }

                }
            }

            if ($request->engine_type == "php") {

                $php = SmEmailSetting::where('email_engine_type', 'php')->where('school_id', Auth::user()->school_id)->first();

                if (empty($php)) {
                    $php = new SmEmailSetting();
                    $php->mail_driver = 'php';
                    $php->email_engine_type = 'php';
                    $php->school_id = Auth::user()->school_id;
                }
                $php->from_name = $request->from_name;
                $php->from_email = $request->from_email;
                $php->active_status = $request->active_status;
                $results = $php->save();

                if ($request->active_status == 1) {
                    $gs = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
                    $gs->email_driver = "php";
                    $gs->save();
                    session()->forget('generalSetting');
                    session()->put('generalSetting', $gs);
                    $smtp = SmEmailSetting::where('email_engine_type', 'smtp')->where('school_id', Auth::user()->school_id)->first();
                    if ($smtp) {
                        $smtp->active_status = 0;
                        $smtp->save();
                    }

                }

            }


            //========================

            try {
                $settings = SmEmailSetting::where('school_id',Auth::user()->school_id)->where('active_status', 1)->first();
                $reciver_email = $settings->from_email;
                $receiver_name = Auth::user()->full_name;
                $subject = 'Email Setup Testing';
                $view = "test_email";
                $compact['data'] = array('email' => $settings->from_email, 'name' => Auth::user()->full_name);
                @send_mail($reciver_email, $receiver_name, $subject, $view, $compact);


            } catch (\Exception $e) {
                Toastr::error('Email credentials maybe wrong !', 'Failed');
                return redirect()->back();
            }

            if ($results) {
                Session::put("php", null);
                Session::put("smtp", null);
                Toastr::success('Operation successful', 'Success');
                if( $request->engine_type == "php" ){
                    return redirect()->back()->with(['php' => 'active']);
                }
                else{
                    return redirect()->back()->with(['smtp' => 'active']);
                }

            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed,' . $e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodSettings()
    {
        try {
            $statement = "SELECT P.id as PID, D.id as DID, P.active_status as IsActive, P.method, D.* FROM sm_payment_methhods as P, sm_payment_gateway_settings D WHERE P.gateway_id=D.id";
            $PaymentMethods = DB::select($statement);

            $paymeny_gateway = SmPaymentMethhod::query();
            $paymeny_gateway = $paymeny_gateway->where('school_id', Auth::user()->school_id);
            if(moduleStatusCheck('XenditPayment') == False){
                $paymeny_gateway->where('method','!=','Xendit');
            }
            if(moduleStatusCheck('RazorPay') == False){
                $paymeny_gateway->where('method','!=','RazorPay');
            }
            if(moduleStatusCheck('Raudhahpay') == False){
                $paymeny_gateway->where('method','!=','Raudhahpay');
            }
            if(moduleStatusCheck('KhaltiPayment') == False){
                $paymeny_gateway->where('method','!=','Khalti');
            }

            if(moduleStatusCheck('MercadoPago') == False){
                $paymeny_gateway->where('method','!=','MercadoPago');
            }
            if(moduleStatusCheck('CcAveune') == False){
                $paymeny_gateway->where('method','!=','CcAveune');
            }

            $paymeny_gateway = $paymeny_gateway->withoutGlobalScope(ActiveStatusSchoolScope::class);
            $paymeny_gateway = $paymeny_gateway->get();
            $paymeny_gateway_settings = SmPaymentGatewaySetting::query();
            $paymeny_gateway_settings = $paymeny_gateway_settings->where('school_id', Auth::user()->school_id);
            if(moduleStatusCheck('XenditPayment') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','Xendit');
            }
            if(moduleStatusCheck('Raudhahpay') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','Raudhahpay');
            }
            if(moduleStatusCheck('RazorPay') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','RazorPay');
            }

            if(moduleStatusCheck('MercadoPago') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','MercadoPago');
            }

            if(moduleStatusCheck('CcAveune') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','CcAveune');
            }

            if(moduleStatusCheck('KhaltiPayment') == False){
                $paymeny_gateway_settings->where('gateway_name','!=','Khalti');
            }

            $paymeny_gateway_settings = $paymeny_gateway_settings->get();

            $payment_methods = SmPaymentMethhod::query();
            $payment_methods = $payment_methods->where('school_id', Auth::user()->school_id);
            if(moduleStatusCheck('XenditPayment') == False){
                $payment_methods->where('method','!=','Xendit');
            }
            if(moduleStatusCheck('RazorPay') == False){
                $payment_methods->where('method','!=','RazorPay');
            }

            if(moduleStatusCheck('KhaltiPayment') == False){
                $payment_methods->where('method','!=','Khalti');
            }

            if(moduleStatusCheck('MercadoPago') == False){
                $payment_methods->where('method','!=','MercadoPago');
            }

            if(moduleStatusCheck('CcAveune') == False){
                $payment_methods->where('method','!=','CcAveune');
            }

            $payment_methods = $payment_methods->get();

            $bank_accounts = SmBankAccount::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.paymentMethodSettings', compact('PaymentMethods', 'paymeny_gateway', 'paymeny_gateway_settings', 'payment_methods', 'bank_accounts'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePaymentGateway(PaymentGatewayFormRequest $request)
    {
       
        // if ($request->gateway_name == "Stripe" || $request->gateway_name == "Paystack") {
        //     $input = $request->all();

        //     $validator = Validator::make($input, [
        //         'gateway_username' => "required",
        //         'gateway_secret_key' => "required",
        //         'gateway_publisher_key' => "required",
        //     ]);

        //     if ($validator->fails()) {
        //         return redirect()->back()
        //             ->withErrors($validator)
        //             ->with(['gateway_name' => $request->gateway_name])
        //             ->with(['active_status' => 'active'])
        //             ->withInput();
        //     }
        // }elseif($request->gateway_name == "PayPal"){

        //     $input = $request->all();
        //     $validator = Validator::make($input, [
        //         'gateway_mode' => "required|in:sandbox,live",
        //     ], [
        //         'gateway_mode.in' => 'input should be sandbox or live',
        //     ]);

        //     if ($validator->fails()) {
        //         return redirect()->back()
        //             ->withErrors($validator)
        //             ->with(['gateway_name' => $request->gateway_name])
        //             ->with(['active_status' => 'active'])
        //             ->withInput();
        //     }
        // }


        try {
            $paymeny_gateway = [
                'gateway_name', 'gateway_username', 'gateway_password', 'gateway_signature', 'gateway_client_id', 'gateway_mode',
                'gateway_secret_key', 'gateway_secret_word', 'gateway_publisher_key', 'gateway_private_key', 'cheque_details', 'bank_details',
                'mercado_pago_public_key','mercado_pago_acces_token','service_charge', 'charge_type', 'charge' ,'cca_working_key','cca_merchant_id','cca_access_code'
            ];
            $count = 0;
            $gatewayDetails = SmPaymentGatewaySetting::where('gateway_name', $request->gateway_name)->where('school_id', Auth::user()->school_id)->first();

            foreach ($paymeny_gateway as $input_field) {
                if (isset($request->$input_field) && !empty($request->$input_field)) {
                    $gatewayDetails->$input_field = $request->$input_field;
                }
            }
            $results = $gatewayDetails->save();
            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back()->with(['gateway_name' => $request->gateway_name])->with(['active_status' => 'active']);
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back()->with(['gateway_name' => $request->gateway_name])->with(['active_status' => 'active']);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['gateway_name' => $request->gateway_name])->with(['active_status' => 'active']);
        }
    }

    public function isActivePayment(Request $request)
    {
        $request->validate(
            ['gateways' => 'required|array',],
            ['gateways.required' => 'At least one gateway required!',]
        );

        try {
            $update = SmPaymentMethhod::where('school_id', Auth::user()->school_id)
                ->where('active_status', '=', 1)
                ->update(['active_status' => 0]);
            foreach ($request->gateways as $pid => $isChecked) {
                $results = SmPaymentMethhod::where('school_id', Auth::user()->school_id)
                    ->where('id', '=', $pid)
                    ->withoutGlobalScope(ActiveStatusSchoolScope::class)
                    ->update(['active_status' => 1]);
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePaypalData()
    {
        try {
            $gateway_id = $_POST['gateway_id'];
            $paypal_username = $_POST['paypal_username'];
            $paypal_password = $_POST['paypal_password'];
            $paypal_signature = $_POST['paypal_signature'];
            $paypal_client_id = $_POST['paypal_client_id'];
            $paypal_secret_id = $_POST['paypal_secret_id'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetails = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetails->paypal_username = $paypal_username;
                    $gatewayDetails->paypal_password = $paypal_password;
                    $gatewayDetails->paypal_signature = $paypal_signature;
                    $gatewayDetails->paypal_client_id = $paypal_client_id;
                    $gatewayDetails->paypal_secret_id = $paypal_secret_id;
                    $results = $gatewayDetails->update();
                } else {

                    $gatewayDetail = new SmPaymentGatewaySetting();
                    $gatewayDetail->paypal_username = $paypal_username;
                    $gatewayDetail->paypal_password = $paypal_password;
                    $gatewayDetail->paypal_signature = $paypal_signature;
                    $gatewayDetail->paypal_client_id = $paypal_client_id;
                    $gatewayDetail->paypal_secret_id = $paypal_secret_id;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {

                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateStripeData()
    {

        try {
            $gateway_id = $_POST['gateway_id'];
            $stripe_api_secret_key = $_POST['stripe_api_secret_key'];
            $stripe_publisher_key = $_POST['stripe_publisher_key'];
            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->where('id', $gateway_id)->where('school_id', Auth::user()->school_id)->first();
                if (!empty($gatewayDetails)) {
                    $gatewayDetails = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetails->stripe_api_secret_key = $stripe_api_secret_key;
                    $gatewayDetails->stripe_publisher_key = $stripe_publisher_key;
                    $results = $gatewayDetails->update();
                } else {
                    $gatewayDetail = new SmPaymentGatewaySetting();
                    $gatewayDetail->stripe_api_secret_key = $stripe_api_secret_key;
                    $gatewayDetail->stripe_publisher_key = $stripe_publisher_key;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePayumoneyData()
    {

        try {
            $gateway_id = $_POST['gateway_id'];
            $pay_u_money_key = $_POST['pay_u_money_key'];
            $pay_u_money_salt = $_POST['pay_u_money_salt'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
                if (!empty($gatewayDetails)) {

                    $gatewayDetails = SmPaymentGatewaySetting::find($gatewayDetails->id);
                    $gatewayDetails->pay_u_money_key = $pay_u_money_key;
                    $gatewayDetails->pay_u_money_salt = $pay_u_money_salt;
                    $results = $gatewayDetails->update();
                } else {

                    $gatewayDetail = new SmPaymentGatewaySetting();
                    $gatewayDetail->pay_u_money_key = $pay_u_money_key;
                    $gatewayDetail->pay_u_money_salt = $pay_u_money_salt;
                    $gatewayDetail->school_id = Auth::user()->school_id;
                    $results = $gatewayDetail->save();
                }
            }

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function bankStatus(Request $request)
    {
        try {
            $active_bank = SmBankAccount::where('id', $request->account_id)
                ->where('school_id', Auth::user()->school_id)
                ->update(array('active_status' => $request->account_status));
            return response()->json('success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
        }
    }

    public function activePaymentGateway()
    {

        try {
            $gateway_id = $_POST['gateway_id'];

            if ($gateway_id) {
                $gatewayDetails = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->where('active_status', '=', 1)
                    ->update(['active_status' => 0]);
            }

            $results = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->where('gateway_name', '=', $gateway_id)
                ->update(['active_status' => 1]);

            if ($results) {
                echo "success";
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function languageDelete(Request $request)
    {

        $delete_directory = SmLanguage::find($request->id);


        try {

            if ($delete_directory) {
                if($delete_directory->language_universal != 'en'){
                    File::deleteDirectory(base_path('/resources/lang/' . $delete_directory->language_universal));
                    $modules = Module::all();
                    foreach ($modules as $module) {
                        File::deleteDirectory(module_path($module->getName()) . '/Resources/lang/' . $delete_directory->language_universal);
                    }
                }
                $delete_directory->delete();

                return redirect()->back()->with('message-success-delete', 'Language has been deleted successfully');

            } else {
                return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLocale($locale)
    {
        try {
            Session::put('locale', $locale);
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLanguage($id)
    {
        if (config('app.app_sync')) {
            if(request()->wantsJson()){
                return response()->json(['message' => 'Restricted in demo mode'], 422);
            }
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {

            if ($id) {
                $this->setDefaultLanguge((int) $id);
            }
            Cache::forget('translations');
            Toastr::success('Operation Success', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    private function setDefaultLanguge($id){

        SmLanguage::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);
        if(is_integer($id)){
            $language = SmLanguage::where('school_id', Auth::user()->school_id)->findOrFail($id);
        } else{
            $language = SmLanguage::where('school_id', Auth::user()->school_id)->where('language_universal', $id)->firstOrFail();
        }

        $language->active_status = 1;
        $language->save();



        $lang = Language::where('code', $language->language_universal)->first();

        $users = User::where('school_id',Auth::user()->school_id)->get();

        foreach($users as $user){
            $user->language = $lang->code;
            if($lang->rtl == 1){
                $user->rtl_ltl = 1;
                $user->save();
            }else{
                $user->rtl_ltl = 2;
                $user->save();
            }
            $user->save();
        }

        if( $lang->rtl == 1 ){
            session()->put('user_text_direction',1);
        }
        else{
            session()->put('user_text_direction',2);
        }

        session()->put('user_language', $lang->code);
        session()->put('locale', $lang->code);
    }

    public function getTranslationTerms(Request $request)
    {

        try {
            $file = explode('::', $request->id);
            $file_name = gv($file, 1);
            $module = gv($file, 0, 'base');
            if ( $module == 'base'){
                $file = resource_path('lang/'.$request->lu.'/'.$file_name.'.php');
                $en_file = resource_path('lang/en/'.$file_name.'.php');
            } else{
                $file = module_path($module) . '/Resources/lang/'.$request->lu.'/'.$file_name.'.php';
                $en_file = module_path($module) . '/Resources/lang/en/'.$file_name.'.php';
            }

            $terms = [];
            $en_terms = [];

            if (File::exists($file)){
                $terms = include  "{$file}";
            }
            if (File::exists($en_file)){
                $en_terms = include  "{$en_file}";
            }
            return response()->json(['terms' => $terms, 'en_terms' => $en_terms]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function translationTermUpdate(Request $request)
    {

        if (config('app.app_sync')) {
            if($request->wantsJson()){
                return response()->json(['message' => 'Restricted in demo mode'], 422);
            }
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $request->validate(
            [
                'module_id' => 'required',
                'language_universal' => 'required',
            ],
            [
                'module_id.required' => 'Please select at least one module',
            ]
        );
        try {

            $LU = $request->LU;
            $file = explode('::', $request->module_id);
            $file_name = gv($file, 1);
            $module = gv($file, 0, 'base');
            $language_universal = $request->language_universal;

            if ( $module == 'base'){
                $file = resource_path('lang/'.$language_universal.'/'.$file_name.'.php');
                $folder = resource_path('lang/'.$language_universal);
            } else{
                $file = module_path($module) . '/Resources/lang/'.$language_universal.'/'.$file_name.'.php';
                $folder = module_path($module) . '/Resources/lang/'.$language_universal;
            }

            if (file_exists($file)) {
                file_put_contents($file, '');
            } else{
                File::ensureDirectoryExists($folder);
                file_put_contents($file, '');
            }

            file_put_contents($file, '<?php return ' . var_export($LU, true) . ';');

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System is Availalbe

    public function recurse_copy($src, $dst)
    {

        try {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System is Availalbe
    public function academicIdUpdated()
    {
        try {

            $table_list = [
                "custom_result_settings",
                "sm_add_expenses",
                "sm_add_incomes",
                "sm_admission_queries",
                "sm_admission_query_followups",
                "sm_assign_class_teachers",
                "sm_assign_subjects",
                "sm_assign_vehicles",
                "sm_backups",
                "sm_bank_accounts",
                "sm_books",
                "sm_book_categories",
                "sm_book_issues",
                "sm_chart_of_accounts",
                "sm_classes",
                "sm_class_optional_subject",
                "sm_class_rooms",
                "sm_class_routines",
                "sm_class_routine_updates",
                "sm_class_sections",
                "sm_class_teachers",
                "sm_class_times",
                "sm_complaints",
                "sm_content_types",
                "sm_currencies",
                "sm_custom_temporary_results",
                "sm_dormitory_lists",
                "sm_email_settings",
                "sm_email_sms_logs",
                "sm_events",
                "sm_exams",
                "sm_exam_attendances",
                "sm_exam_attendance_children",
                "sm_exam_marks_registers",
                "sm_exam_schedules",
                "sm_exam_schedule_subjects",
                "sm_exam_setups",
                "sm_exam_types",
                "sm_expense_heads",
                "sm_fees_assigns",
                "sm_fees_assign_discounts",
                "sm_fees_carry_forwards",
                "sm_fees_discounts",
                "sm_fees_groups",
                "sm_fees_masters",
                "sm_fees_payments",
                "sm_fees_types",
                "sm_holidays",
                "sm_homeworks",
                "sm_homework_students",
                "sm_hourly_rates",
                "sm_hr_payroll_earn_deducs",
                "sm_hr_payroll_generates",
                "sm_hr_salary_templates",
                "sm_income_heads",
                "sm_inventory_payments",
                "sm_items",
                "sm_item_categories",
                "sm_item_issues",
                "sm_item_sells",
                "sm_item_sell_children",
                "sm_item_stores",
                "sm_leave_defines",
                "sm_leave_requests",
                "sm_leave_types",
                "sm_library_members",
                "sm_marks_grades",
                "sm_marks_registers",
                "sm_marks_register_children",
                "sm_mark_stores",
                "sm_news",
                "sm_notice_boards",
                "sm_notifications",
                "sm_online_exams",
                "sm_online_exam_marks",
                "sm_online_exam_questions",
                "sm_online_exam_question_assigns",
                "sm_online_exam_question_mu_options",
                "sm_optional_subject_assigns",
                "sm_parents",
                "sm_phone_call_logs",
                "sm_postal_dispatches",
                "sm_question_banks",
                "sm_question_bank_mu_options",
                "sm_question_groups",
                "sm_question_levels",
                "sm_result_stores",
                "sm_room_lists",
                "sm_room_types",
                "sm_routes",
                "sm_seat_plans",
                "sm_seat_plan_children",
                "sm_sections",
                "sm_send_messages",
                "sm_setup_admins",
                "sm_staff_attendance_imports",
                "sm_staff_attendences",
                "sm_students",
                "sm_student_attendances",
                "sm_student_attendance_imports",
                "sm_student_categories",
                "sm_student_certificates",
                "sm_student_documents",
                "sm_student_excel_formats",
                "sm_student_groups",
                "sm_student_id_cards",
                "sm_student_promotions",
                "sm_student_take_online_exams",
                "sm_student_take_online_exam_questions",
                "sm_student_take_onln_ex_ques_options",
                "sm_student_timelines",
                "sm_subjects",
                "sm_subject_attendances",
                "sm_suppliers",
                "sm_teacher_upload_contents",
                "sm_temporary_meritlists",
                "sm_to_dos",
                "sm_upload_homework_contents",
                "sm_user_logs",
                "sm_vehicles",
                "sm_visitors",
                "sm_weekends"
            ];


            $MyUpdatedTable = [];
            $academicYears = SmAcademicYear::select('year', 'id', 'school_id')->get();
            $ids = [];
            foreach ($table_list as $table) {

                foreach ($academicYears as $ac) {

                    $className = 'App\\' . Str::studly(Str::singular($table));
                    $rs = $className::whereYear('created_at', $ac->year)
                        ->where('school_id', $ac->school_id)
                        ->first();

                    if (!empty($rs)) {

                        if (!in_array($rs->id, $ids)) {
                            $ids[] = $rs->id;
                            $rs->academic_id = $ac->id;
                            $rs->save();
                            $MyUpdatedTable[] = $table;
                        }
                    }
                }


            }

            $ids = [];


            return $MyUpdatedTable;

        } catch (\Exception $e) {
        }

    }

    public function DbUpgrade()
    {

        try {
            if (!Schema::hasTable('infix_module_managers')) {
                Artisan::call('migrate --path=/database/migrations/2020_06_10_193309_create_infix_module_managers_table.php');
            }

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:cache');

            $table_list = [
                "countries",
                "custom_result_settings",
                "sm_add_expenses",
                "sm_add_incomes",
                "sm_admission_queries",
                "sm_admission_query_followups",
                "sm_assign_class_teachers",
                "sm_assign_subjects",
                "sm_assign_vehicles",
                "sm_backups",
                "sm_bank_accounts",
                "sm_books",
                "sm_book_categories",
                "sm_book_issues",
                "sm_chart_of_accounts",
                "sm_classes",
                "sm_class_optional_subject",
                "sm_class_rooms",
                "sm_class_routines",
                "sm_class_routine_updates",
                "sm_class_sections",
                "sm_class_teachers",
                "sm_class_times",
                "sm_complaints",
                "sm_content_types",
                "sm_currencies",
                "sm_custom_temporary_results",
                "sm_dormitory_lists",
                "sm_email_settings",
                "sm_email_sms_logs",
                "sm_events",
                "sm_exams",
                "sm_exam_attendances",
                "sm_exam_attendance_children",
                "sm_exam_marks_registers",
                "sm_exam_schedules",
                "sm_exam_schedule_subjects",
                "sm_exam_setups",
                "sm_exam_types",
                "sm_expense_heads",
                "sm_fees_assigns",
                "sm_fees_assign_discounts",
                "sm_fees_carry_forwards",
                "sm_fees_discounts",
                "sm_fees_groups",
                "sm_fees_masters",
                "sm_fees_payments",
                "sm_fees_types",
                "sm_general_settings",
                "sm_holidays",
                "sm_homeworks",
                "sm_homework_students",
                "sm_hourly_rates",
                "sm_hr_payroll_earn_deducs",
                "sm_hr_payroll_generates",
                "sm_hr_salary_templates",
                "sm_income_heads",
                "sm_inventory_payments",
                "sm_items",
                "sm_item_categories",
                "sm_item_issues",
                "sm_item_receives",
                "sm_item_receive_children",
                "sm_item_sells",
                "sm_item_sell_children",
                "sm_item_stores",
                "sm_leave_defines",
                "sm_leave_requests",
                "sm_leave_types",
                "sm_library_members",
                "sm_marks_grades",
                "sm_marks_registers",
                "sm_marks_register_children",
                "sm_marks_send_sms",
                "sm_mark_stores",
                "sm_news",
                "sm_notice_boards",
                "sm_notifications",
                "sm_online_exams",
                "sm_online_exam_marks",
                "sm_online_exam_questions",
                "sm_online_exam_question_assigns",
                "sm_online_exam_question_mu_options",
                "sm_optional_subject_assigns",
                "sm_parents",
                "sm_phone_call_logs",
                "sm_postal_dispatches",
                "sm_postal_receives",
                "sm_question_banks",
                "sm_question_bank_mu_options",
                "sm_question_groups",
                "sm_question_levels",
                "sm_result_stores",
                "sm_room_lists",
                "sm_room_types",
                "sm_routes",
                "sm_seat_plans",
                "sm_seat_plan_children",
                "sm_sections",
                "sm_send_messages",
                "sm_setup_admins",
                "sm_staff_attendance_imports",
                "sm_staff_attendences",
                "sm_students",
                "sm_student_attendances",
                "sm_student_attendance_imports",
                "sm_student_categories",
                "sm_student_certificates",
                "sm_student_documents",
                "sm_student_excel_formats",
                "sm_student_groups",
                "sm_student_homeworks",
                "sm_student_id_cards",
                "sm_student_promotions",
                "sm_student_take_online_exams",
                "sm_student_take_online_exam_questions",
                "sm_student_take_onln_ex_ques_options",
                "sm_student_timelines",
                "sm_subjects",
                "sm_subject_attendances",
                "sm_suppliers",
                "sm_teacher_upload_contents",
                "sm_temporary_meritlists",
                "sm_to_dos",
                "sm_upload_contents",
                "sm_upload_homework_contents",
                "sm_user_logs",
                "sm_vehicles",
                "sm_visitors",
                "sm_weekends"
            ];


            $name = 'academic_id';
            $data = [];
            foreach ($table_list as $row) {
                if (!Schema::hasColumn($row, $name)) {
                    Schema::table($row, function ($table) use ($name) {
                        $table->integer($name)->default(1)->nullable();
                    });
                } else {
                    $data[] = $row;

                }
            }

            return redirect('/login');
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System
    public function UpdateSystem()
    {
        try {
            $data = SmGeneralSettings::first();
            return view('backEnd.systemSettings.updateSettings', compact('data'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //about System
    public function AboutSystem()
    {

        try {

            $setting = SmGeneralSettings::first('software_version')->software_version;
            return view('backEnd.systemSettings.aboutSystem', compact('setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function admin_UpdateSystem(Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'content_file' => "required",
        ]);
        try {
            if (!file_exists('upgradeFiles')) {
                mkdir('upgradeFiles', 0777, true);
            }

            $fileName = "";
            if ($request->file('content_file') != "") {
                $file = $request->file('content_file');
                $fileName = time() . "." . $file->getClientOriginalExtension();
                $file->move('upgradeFiles/', $fileName);
                $fileName = 'upgradeFiles/' . $fileName;
            }


            $zip = new ZipArchive;
            $res = $zip->open($fileName);
            if ($res === TRUE) {
                $zip->extractTo('upgradeFiles/');
                $zip->close();
            } else {
                Toastr::error('Operation Failed, You have to select zip file', 'Failed');
                return redirect()->back();
            }
            $data = SmGeneralSettings::find(1);
            $data->system_version = $request->version_name;
            $data->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function UpgradeSettings(Request $request)
    {
        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }

    public function ajaxSelectCurrency(Request $request)
    {
        try {
            $select_currency_symbol = SmCurrency::select('symbol')->where('code', '=', $request->id)->where('school_id',auth()->user()->school_id)->first();

            $currency_symbol['symbol'] = $select_currency_symbol->symbol;

            return response()->json([$currency_symbol]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //ajax theme Style Active
    public function themeStyleActive(Request $request)
    {

        try {
            $selected = null;
            if ($request->id) {
                if(Auth::check() && Auth::user()->role_id==1){
                    $modified = Theme::where('is_default', 1)               
                    ->where('created_by', auth()->user()->id)
                    ->update(array('is_default' => 0));

                    $selected = Theme::where('created_by', auth()->user()->id)->where('id', $request->id)->first();
                  
                    if($selected) {
                        $selected->is_default = 1;
                        $res = $selected->save();
                    }                  
                  
                }
               if ($selected) {
                    $user=User::find(Auth::user()->id);
                    $user->style_id=$request->id;
                    $modified = $user->save();
               }
                $active_style = Theme::findOrFail($request->id);
                session()->put('active_style', $active_style);
                Cache::forget('active_theme_school_' . Auth::user()->school_id);
                Cache::forget('active_theme_user_' . Auth::id());
                return response()->json([$modified]);
            } else {
                return '';
            }
        }
        catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    //ajax theme Style Active
    public function themeStyleRTL(Request $request)
    {

        try {

            if ($request->id) {

                $selected = User::find(Auth::user()->id);
                $selected->rtl_ltl = $request->id;
                $selected->save();

                session()->forget('user_text_direction');
                session()->put('user_text_direction', $request->id);


                return response()->json([$selected]);
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxUserLanguageChange(Request $request)
    {

        if (config('app.app_sync')) {
            if($request->wantsJson()){
                return response()->json(['message' => 'Restricted in demo mode'], 422);
            }
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            if ($request->id) {
                $lang = Language::where('code', $request->id)->firstOrFail();
                $user = User::find(Auth::user()->id);
                $user->language = $request->id;
                if($user->role_id == 1 ){
                    $this->setDefaultLanguge($request->id);
                } else{
                    if($lang->rtl == 1){
                        $user->rtl_ltl = 1;
                        session()->put('user_text_direction',1);
                    }else{
                        $user->rtl_ltl = 2;
                        session()->put('user_text_direction',2);
                    }
                }
                $user->save();
                Cache::forget('translations');
                session()->put('user_language', $request->id);
                return response()->json([$request->id]);
            } else {
                return 'en';
            }
        } catch (\Exception $e) {
        }
    }

    //ajax session Active
    public function sessionChange(Request $request)
    {
        try {
            $school_id = Auth::user()->school_id;
            if ($request->id) {
                $selected = SmGeneralSettings::where('school_id', $school_id)->first();
                if(moduleStatusCheck('University')){
                    $data = UnAcademicYear::find($request->id);
                    $year = date('Y', strtotime($data->start_date));
                    $selected->un_academic_id = $request->id;
                }else{
                    $data = SmAcademicYear::find($request->id);
                    $year = $data->year;

                    $selected->session_id = $request->id;
                    $selected->academic_id = $request->id;
                }

                $selected->session_year = $year;
                $selected->save();
                

                session()->put('sessionId', $request->id);
                session()->put('generalSetting', $selected);
                return response()->json([$selected]);
            } else {
                return '';
            }
        } catch (\Exception $e) {
            return '';
        }
    }

    /* ******************************** homePageBackend ******************************** */
    public function homePageBackend()
    {
        try {
            $links = SmHomePageSetting::where('school_id', app('school')->id)->first();
            $permisions = SmFrontendPersmission::where('school_id', app('school')->id)->where('parent_id', 1)->get();
            return view('backEnd.systemSettings.homePageBackend', compact('links', 'permisions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homePageUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'long_title' => 'required',
            'short_description' => 'required',
            'permisions' => 'required|array',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);

        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize = filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if ($fileSizeKb >= $maxFileSize) {
                Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                return redirect()->back();
            }

            $permisionsArray = $request->permisions;
            SmFrontendPersmission::where('school_id', app('school')->id)->where('parent_id', 1)->update(['is_published' => 0]);
            foreach ($permisionsArray as $value) {
                SmFrontendPersmission::where('id', $value)->update(['is_published' => 1]);
            }

            $image = "";
            if ($request->file('image') != "") {
                $file = $request->file('image');
                $image_name = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $path = 'public/uploads/homepage';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move($path . '/', $image_name);
                $image = $path . '/' . $image_name;
            }

            //Update Home Page
            $update = SmHomePageSetting::where('school_id', app('school')->id)->first();
            $update->title = $request->title;
            $update->long_title = $request->long_title;
            $update->short_description = $request->short_description;
            $update->link_label = $request->link_label;
            $update->link_url = $request->link_url;
            $update->school_id = app('school')->id;
            if ($request->file('image') != "") {
                $update->image = $image;
            }
            $result = $update->save();

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /* ******************************** homePageBackend ******************************** */

    /* ******************************** customLinks ******************************** */



    /* ******************************** customLinks ******************************** */

    public function getSystemVersion(Request $request)
    {

        try {
            $version = SmSystemVersion::find(1);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['SystemVersion'] = $version;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getSystemUpdate(Request $request, $version_upgrade_id = null)
    {

        try {
            $data = [];
            if (Schema::hasTable('sm_update_files')) {
                $version = DB::table('sm_update_files')->where('version_name', $version_upgrade_id)->first();
                if (!empty($version->path)) {
                    $url = url('/') . '/' . $version->path;
                    header("Location: " . $url);
                    die();
                } else {
                    return redirect()->back();
                }
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function setFCMkey(Request $request){
        $request->validate([
            'fcm_key' => 'required',
        ]);
        try {
            // return $request;
            $path = base_path() . "/.env";

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $envKey='FCM_SECRET_KEY';
            $envValue='"'.$request->fcm_key.'"';
            $str .= "\n";
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            $settings = SmGeneralSettings::first();
            $settings->fcm_key = $request->fcm_key;
            $settings->save();


            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function apiPermission()
    {


        try {
            if (!Schema::hasColumn('sm_general_settings', 'api_url')) {
                Schema::table('sm_general_settings', function ($table) {
                    $table->integer('api_url')->default(0)->nullable();
                });
            }
            $settings = SmGeneralSettings::find(1);

            return view('backEnd.systemSettings.apiPermission', compact('settings'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function apiPermissionUpdate(Request $request)
    {

        try {
            if ($request->status == 'on') {
                $status = 1;
            } else {
                $status = 0;
            }
            $user = SmGeneralSettings::find(1);
            $user->api_url = $status;
            $user->save();

            return response()->json($user);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /*** RTL TTL ***/

    public function create_a_dynamic_column($table_name, $column_name, $column_type, $column_limit)
    {
        try {
            if (!Schema::hasColumn($table_name, $column_name)) {
                Schema::table($table_name, function ($table, $column_name, $column_type, $column_limit) {
                    $table->$column_type($column_name, $column_limit)->nullable();
                });
                return true;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function enable_rtl(Request $request)
    {

        try {
            if ($this->create_a_dynamic_column('sm_general_settings', 'ttl_rtl', 'integer', 11)) {
                $s = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
                $s->ttl_rtl = $request->status;
                $s->save();
                return response()->json($s);
            } else {
                $s['flag'] = false;
                $s['message'] = 'something went wrong!!';
                return response()->json($s);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function buttonDisableEnable()
    {

        try {

            // $settings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            return view('backEnd.systemSettings.buttonDisableEnable');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeWebsiteBtnStatus(Request $request)
    {


        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->website_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }

            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeDashboardBtnStatus(Request $request)
    {


        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->dashboard_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeReportBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->report_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeStyleBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->style_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLtlRtlBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->ltl_rtl_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function changeLanguageBtnStatus(Request $request)
    {

        try {
            $gettings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $gettings->lang_btn = $request->status;
            $result = $gettings->save();
            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $gettings);
            }
            return response()->json(null);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateWebsiteUrl(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'website_url' => "url",
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {

            $settings = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $settings->website_url = $request->website_url;
            $result = $settings->save();

            //Session put generalSetting
            if ($result) {
                session()->forget('generalSetting');
                session()->put('generalSetting', $settings);
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateCreatedDate()
    {

        try {
            $path = base_path() . "/.env";
            $db_name = env('DB_DATABASE', null);
            $column = 'created_at';
            $table_list = DB::select("SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE COLUMN_NAME ='$column'
                AND TABLE_SCHEMA='$db_name'");
            $tables = [];
            foreach ($table_list as $row) {
                $tables[] = $row->TABLE_NAME;
            }
            return $db_name;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function schoolSettingsView(Request $request)
    {

        $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        $school = SmSchool::where('id', '=', Auth::user()->school_id)->first();

        $academic_year = SmAcademicYear::findOrfail(@$editData->session_id);
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            return ApiBaseMethod::sendResponse($editData, null);
        }
        return view('saas::systemSettings.schoolGeneralSettingsView', compact('editData', 'school', 'academic_year'));
    }


    public function viewAsSuperadmin()
    {

        $school_id = Auth::user()->school_id;
        $role_id = Auth::user()->role_id;
        if ($school_id == 1 && $role_id == 1) {
            if (Session::get('isSchoolAdmin') == TRUE) {
                session(['isSchoolAdmin' => FALSE]);
                // Session::set('isSchoolAdmin', FALSE);
                Toastr::success('You are accessing as saas admin', 'Success');
                return redirect('superadmin-dashboard');
            } else {
                session(['isSchoolAdmin' => TRUE]);
                // Session::set('isSchoolAdmin', TRUE);
                Toastr::success('You are accessing as school admin', 'Success');
                return redirect('admin-dashboard');
            }
        }
    }




    public function versionUpdateInstall(Request $request){
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        ini_set('memory_limit', '-1');

        try {

            if (config('app.app_sync')) {
                Toastr::warning('This Feature Restricted in demo mode !');
                return back();
            }

            $request->validate([
                'updateFile' => ['required', 'mimes:zip'],
            ]);

            if ($request->hasFile('updateFile')) {
                $path = $request->updateFile->store('updateFile');
                $request->updateFile->getClientOriginalName();
                $zip = new ZipArchive;
                $res = $zip->open(storage_path('app/' . $path));
                if ($res === true) {
                    $zip->extractTo(storage_path('app/tempUpdate'));
                    $zip->close();
                } else {
                    abort(500, 'Error! Could not open File');
                }

                $str = @file_get_contents(storage_path('app/tempUpdate/config.json'), true);
                if ($str === false) {
                    abort(500, 'The update file is corrupt.');

                }

                $json = json_decode($str, true);

                if (!empty($json)) {
                    if (empty($json['version']) || empty($json['release_date'])) {
                        Toastr::error('Config File Missing', trans('common.error'));
                        return redirect()->back();
                    }
                } else {
                    Toastr::error('Config File Missing', trans('common.error'));
                    return redirect()->back();
                }
                $setting = SmGeneralSettings::first();

                $current_version = Storage::exists('.version') && Storage::get('.version') ? rtrim(Storage::get('.version'), '\n') : $setting->system_version;

                if ($current_version < $json['min']) {
                    Toastr::error($json['min'] . ' or greater is  required for this version', trans('common.error'));
                    return redirect()->back();
                }

                $src = storage_path('app/tempUpdate');
                $dst = base_path('/');

                $this->recurse_copy($src, $dst);

                if (isset($json['migrations']) & !empty($json['migrations'])) {
                    foreach ($json['migrations'] as $migration) {

                        Artisan::call('migrate',
                            array(
                                '--path' => $migration,
                                '--force' => true));
                    }
                }
                $setting->last_update = Carbon::now();
                $setting->system_version = $json['version'];
                $setting->software_version = $json['version'];
                $setting->save();
            }


            if (storage_path('app/updateFile')) {
                $this->delete_directory(storage_path('app/updateFile'));
            }
            if (storage_path('app/tempUpdate')) {
                $this->delete_directory(storage_path('app/tempUpdate'));
            }

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');

            Toastr::success("Your system successfully updated", 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            if (storage_path('app/updateFile')) {
                $this->delete_directory(storage_path('app/updateFile'));
            }
            if (storage_path('app/tempUpdate')) {
                $this->delete_directory(storage_path('app/tempUpdate'));
            }

            Toastr::error($e->getMessage(), trans('common.error'));
            return redirect()->back();
        }
    }


    public function moduleFileUpload(Request $request)
    {
        ini_set('memory_limit', '-1');

        if (Config::get('app.app_sync')) {
            Toastr::warning("Disabled For Demo" , "Warning");
            return redirect()->back();
        }
        try {
            if (empty($request->module_file)) {
                Toastr::error('File is required', 'Failed');
                return redirect()->back();
            }

            $request->validate([
                'module_file' => ['required', 'mimes:zip'],
            ]);


            if ($request->hasFile('module_file')) {
                $path = $request->module_file->store('module_file');
                $zip = new ZipArchive;
                $res = $zip->open(storage_path('app/' . $path));
                if ($res === true) {
                    $zip->extractTo(storage_path('app/tempUpdate'));
                    $zip->close();
                } else {
                    abort(500, 'Error! Could not open File');
                }

                $src = storage_path('app/tempUpdate');

                $dir = opendir($src);
                $module = '';

                while ($file = readdir($dir)) {
                    if ($file != "." && $file != "..") {
                        $module = $file;
                    }
                }

                $src = storage_path('app/tempUpdate');


                $dst = base_path('/Modules/');
                $this->recurse_copy($src, $dst);


                if (storage_path('app/module_file')) {
                    $this->delete_directory(storage_path('app/module_file'));
                }
                if (storage_path('app/tempUpdate')) {
                    $this->delete_directory(storage_path('app/tempUpdate'));
                }

                if(function_exists('moduleVerify')){
                    moduleVerify($request->module_file->getClientOriginalName());
                }

                if (moduleStatusCheck($module)) {
                    $this->moduleMigration($module);
                }

            }
            Toastr::success("Your module successfully uploaded", 'Success');
            return redirect()->back();


        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), trans('Failed'));
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

    public function moduleMigration($module)
    {
        try {
            Artisan::call('module:migrate', [
                'module' => $module,
                '--force' => true
            ]);

            return true;
        } catch (\Exception $e) {
            Log::info($e);
            return false;
        }

    }
}
