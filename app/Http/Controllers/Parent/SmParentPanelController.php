<?php

namespace App\Http\Controllers\Parent;

use App\User;
use App\SmBook;
use App\SmExam;
use App\SmClass;
use App\SmEvent;
use App\SmRoute;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmSection;
use App\SmStudent;
use App\SmVehicle;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmExamType;
use App\SmHomework;
use App\SmRoomList;
use App\SmRoomType;
use App\SmBaseSetup;
use App\SmBookIssue;
use App\SmClassTime;
use App\SmLeaveType;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmLeaveDefine;
use App\SmNoticeBoard;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmLeaveRequest;
use App\SmStudentGroup;
use App\SmAssignSubject;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use App\SmLibraryMember;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\SmStudentCategory;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\Models\FeesInvoice;
use App\SmStudentAttendance;
use App\SmSubjectAttendance;
use App\Traits\CustomFields;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use App\SmClassOptionalSubject;
use App\SmOptionalSubjectAssign;
use App\SmStudentTakeOnlineExam;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use App\Models\SmStudentRegistrationField;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\OnlineExam\Entities\InfixOnlineExam;
use Modules\OnlineExam\Entities\InfixStudentTakeOnlineExam;
use App\Http\Requests\Admin\StudentInfo\SmStudentAdmissionRequest;

class SmParentPanelController extends Controller
{
    use CustomFields;
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function parentDashboard()
    {
        try {
            $holidays = SmHoliday::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->get();
            $my_childrens = auth()->user()->parent->childrens->load('assignSubjects','assignSubject','studentOnlineExams','studentRecords','examSchedule', 'studentRecords');
            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('school_id', auth()->user()->school_id)->get();
            $events = SmEvent::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', auth()->user()->school_id)
                ->where(function ($q) {
                    $q->where('for_whom', 'All')->orWhere('for_whom', 'Parents');
                })
                ->get();

            $count_event = 0;
            $calendar_events = array();

            foreach ($holidays as $k => $holiday) {

                $calendar_events[$k]['title'] = $holiday->holiday_title;

                $calendar_events[$k]['start'] = $holiday->from_date;

                $calendar_events[$k]['end'] = Carbon::parse($holiday->to_date)->addDays(1)->format('Y-m-d');

                $calendar_events[$k]['description'] = $holiday->details;

                $calendar_events[$k]['url'] = $holiday->upload_image_file;

                $count_event = $k;
                $count_event++;
            }

            foreach ($events as $k => $event) {

                $calendar_events[$count_event]['title'] = $event->event_title;

                $calendar_events[$count_event]['start'] = $event->from_date;

                $calendar_events[$count_event]['end'] = Carbon::parse($event->to_date)->addDays(1)->format('Y-m-d');
                $calendar_events[$count_event]['description'] = $event->event_des;
                $calendar_events[$count_event]['url'] = $event->uplad_image_file;
                $count_event++;
            }
            $totalNotices =  SmNoticeBoard::where('active_status', 1)->where('inform_to', 'LIKE', '%3%')
                ->orderBy('id', 'DESC')
                ->where('academic_id', getAcademicId())
                ->where('school_id', auth()->user()->school_id)->get();

            return view('backEnd.parentPanel.parent_dashboard', compact('holidays', 'calendar_events', 'events', 'totalNotices','my_childrens', 'sm_weekends'));
        } catch (\Exception $e) { 
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentUpdate(SmStudentAdmissionRequest $request)
    { 
        try {
             $student_detail = SmStudent::find($request->id);
             $validator = Validator::make($request->all(), $this->generateValidateRules("student_registration", $student_detail));
            if ($validator->fails()) {
                $errors = $validator->errors();
                foreach ($errors->all() as $error) {
                Toastr::error(str_replace('custom f.', '', $error), 'Failed');
                }
                return redirect()->back()->withInput();
            }
            // custom field validation End
        
        
            $destination = 'public/uploads/student/document/';
            $student_file_destination = 'public/uploads/student/';
            $student = SmStudent::find($request->id);
           
            $academic_year = $request->session ? SmAcademicYear::find($request->session) : '';
            DB::beginTransaction();
           
            if ($student) {
                $username = $request->phone_number ? $request->phone_number : $request->admission_number;
                $phone_number = $request->phone_number ? $request->phone_number : null;
                $user_stu =$this->addUser($student_detail->user_id, 2, $username, $request->email_address, $phone_number);
                //sibling || parent info user update
                if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {
                    $username = $request->guardians_phone ? $request->guardians_phone : $request->guardians_email;
                    $phone_number = $request->guardians_phone;
                    $user_parent =  $this->addUser($student_detail->parents->user_id, 3, $username, $request->guardians_email, $phone_number);

                    $user_parent->toArray();

                } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                    User::destroy($student_detail->parents->user_id);
                } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                } elseif ($request->sibling_id == 2 && $request->parent_id == "") {

                    $username = $request->guardians_phone ? $request->guardians_phone : $request->guardians_email;
                    $phone_number = $request->guardians_phone;
                    $user_parent = $this->addUser(null, 3, $username, $request->guardians_email, $phone_number);
                    $user_parent->toArray();
                }
                // end
                //sibling & parent info update
                if ($request->sibling_id == 0 && $request->parent_id != "") {
                    SmParent::destroy($student_detail->parent_id);
                } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                } else {

                    if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {
                        $parent = SmParent::find($student_detail->parent_id);
                    } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                        $parent = new SmParent();
                    }

                    $parent->user_id = $user_parent->id;
                    if ($request->filled('fathers_name')) {
                        $parent->fathers_name = $request->fathers_name;
                    }
                    if ($request->filled('fathers_phone')) {
                        $parent->fathers_mobile = $request->fathers_phone;
                    }
                    if ($request->filled('fathers_occupation')) {
                        $parent->fathers_occupation = $request->fathers_occupation;
                    }
                    if ($request->filled('fathers_photo')) {
                        $parent->fathers_photo = fileUpdate($parent->fathers_photo, $request->fathers_photo, $student_file_destination);
                    }
                    if ($request->filled('mothers_name')) {
                        $parent->mothers_name = $request->mothers_name;
                    }
                    if ($request->filled('mothers_phone')) {
                        $parent->mothers_mobile = $request->mothers_phone;
                    }
                    if ($request->filled('mothers_occupation')) {
                        $parent->mothers_occupation = $request->mothers_occupation;
                    }
                    if ($request->filled('mothers_photo')) {
                        $parent->mothers_photo = fileUpdate($parent->mothers_photo, $request->mothers_photo, $student_file_destination);
                    }
                    if ($request->filled('guardians_name')) {
                        $parent->guardians_name = $request->guardians_name;
                    }
                    if ($request->filled('guardians_phone')) {
                        $parent->guardians_mobile = $request->guardians_phone;
                    }
                    if ($request->filled('guardians_email')) {
                        $parent->guardians_email = $request->guardians_email;
                    }
                    if ($request->filled('guardians_occupation')) {
                        $parent->guardians_occupation = $request->guardians_occupation;
                    }
                    
                    if ($request->filled('relation')) {
                        $parent->guardians_relation = $request->relation;
                    }
                    if ($request->filled('relationButton')) {
                        $parent->relation = $request->relationButton;
                    }
                    if ($request->filled('guardians_photo')) {
                        $parent->guardians_photo = fileUpdate($student->parents->guardians_photo, $request->guardians_photo, $student_file_destination);
                    }
                    if ($request->filled('guardians_address')) {
                        $parent->guardians_address = $request->guardians_address;
                    }
                    if ($request->filled('is_guardian')) {
                        $parent->is_guardian = $request->is_guardian;
                    }
                   
                    if ($request->filled('session')) {
                        $parent->created_at = $academic_year->year . '-01-01 12:00:00';
                    }
                    $parent->save();
                    $parent->toArray();
                }
                // end sibling & parent info update
                // student info update
                $student = SmStudent::find($request->id);
                if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {
                        $student->parent_id = $parent->id;
                } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                        $student->parent_id = $request->parent_id;
                } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                        $student->parent_id = $request->parent_id;
                } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                        $student->parent_id = $parent->id;
                }
                if ($request->filled('class')) {
                    $student->class_id = $request->class;
                }
                if ($request->filled('section')) {
                    $student->section_id = $request->section;
                }
                if ($request->filled('session')) {
                    $student->session_id = $request->session;
                }
                if ($request->filled('admission_number')) {
                    $student->admission_no = $request->admission_number;
                }
                $student->user_id = $user_stu->id;
                if ($request->filled('roll_number')) {
                    $student->roll_no = $request->roll_number;
                }
                if ($request->filled('first_name')) {
                    $student->first_name = $request->first_name;
                }
                if ($request->filled('last_name')) {
                    $student->last_name = $request->last_name;
                }
                if ($request->filled('first_name') && $request->filled('last_name')) {
                    $student->full_name = $request->first_name . ' ' . $request->last_name;
                }
                if ($request->filled('gender')) {
                    $student->gender_id = $request->gender;
                }
                if ($request->filled('date_of_birth')) {
                    $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
                }
                if ($request->filled('age')) {
                    $student->age = $request->age;
                }
                if ($request->filled('caste')) {
                    $student->caste = $request->caste;
                }
                if ($request->filled('email_address')) {
                    $student->email = $request->email_address;
                }
                if ($request->filled('phone_number')) {
                    $student->mobile = $request->phone_number;
                }
                if ($request->filled('admission_date')) {
                    $student->admission_date = date('Y-m-d', strtotime($request->admission_date));
                }
                if ($request->filled('photo')) {
                    $student->student_photo = fileUpdate($parent->student_photo, $request->photo, $student_file_destination);
                }
                if ($request->filled('blood_group')) {
                    $student->bloodgroup_id = $request->blood_group;
                }
                if ($request->filled('religion')) {
                    $student->religion_id = $request->religion;
                }
                if ($request->filled('height')) {
                    $student->height = $request->height;
                }
                if ($request->filled('weight')) {
                    $student->weight = $request->weight;
                }
                if ($request->filled('current_address')) {
                    $student->current_address = $request->current_address;
                }
                if ($request->filled('permanent_address')) {
                    $student->permanent_address = $request->permanent_address;
                }
                if ($request->filled('student_category_id')) {
                    $student->student_category_id = $request->student_category_id;
                }
                if ($request->filled('student_group_id')) {
                    $student->student_group_id = $request->student_group_id;
                }
                if ($request->filled('route')) {
                    $student->route_list_id = $request->route;
                }
                if ($request->filled('dormitory_name')) {
                    $student->dormitory_id = $request->dormitory_name;
                }
                if ($request->filled('room_number')) {
                    $student->room_id = $request->room_number;
                }
                
                if (!empty($request->vehicle)) {
                    $driver = SmVehicle::where('id', '=', $request->vehicle)
                            ->select('driver_id')
                            ->first();
                    $student->vechile_id = $request->vehicle;
                    $student->driver_id = $driver->driver_id;
                }
                if ($request->filled('national_id_number')) {
                    $student->national_id_no = $request->national_id_number;
                }
                if ($request->filled('local_id_number')) {
                    $student->local_id_no = $request->local_id_number;
                }
                if ($request->filled('bank_account_number')) {
                    $student->bank_account_no = $request->bank_account_number;
                }
                if ($request->filled('bank_name')) {
                    $student->bank_name = $request->bank_name;
                }
                if ($request->filled('previous_school_details')) {
                    $student->previous_school_details = $request->previous_school_details;
                }
                if ($request->filled('additional_notes')) {
                    $student->aditional_notes = $request->additional_notes;
                }
                if ($request->filled('ifsc_code')) {
                    $student->ifsc_code = $request->ifsc_code;
                }
                if ($request->filled('document_title_1')) {
                    $student->document_title_1 = $request->document_title_1;
                }
                if ($request->filled('document_file_1')) {
                    $student->document_file_1 = fileUpdate($student->document_file_1, $request->file('document_file_1'), $destination);
                }
                if ($request->filled('document_title_2')) {
                    $student->document_title_2 = $request->document_title_2;
                }
                if ($request->filled('document_file_2')) {
                    $student->document_file_2 = fileUpdate($student->document_file_2, $request->file('document_file_2'), $destination);
                }
                if ($request->filled('document_title_3')) {
                    $student->document_title_3 = $request->document_title_3;
                }
                if ($request->filled('document_file_3')) {
                    $student->document_file_3 = fileUpdate($student->document_file_3, $request->file('document_file_3'), $destination);
                }
                if ($request->filled('document_title_4')) {
                    $student->document_title_4 = $request->document_title_4;
                }
                if ($request->filled('document_title_4')) {
                    $student->document_file_4 = fileUpdate($student->document_file_4, $request->file('document_file_3'), $destination);
                }

                if ($request->filled('session')) {
                    $student->created_at = $academic_year->year . '-01-01 12:00:00';
                    $student->academic_id = $academic_year->id;
                }


                if ($request->customF) {
                    $dataImage = $request->customF;
                    foreach ($dataImage as $label => $field) {
                    if (is_object($field) && $field != "") {
                        $key = "";

                    $maxFileSize = generalSetting()->file_size;
                                $file = $field;
                                $fileSize = filesize($file);
                                $fileSizeKb = ($fileSize / 1000000);
                                if ($fileSizeKb >= $maxFileSize) {
                                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                                    return redirect()->back();
                                }
                                $file = $field;
                                $key = $file->getClientOriginalName();
                                $file->move('public/uploads/customFields/', $key);
                                $dataImage[$label] = 'public/uploads/customFields/' . $key;

                            }
                        }

                        //Custom Field Start
                        $student->custom_field_form_name = "student_registration";
                        $student->custom_field = json_encode($dataImage, true);
                        //Custom Field End

                }
                if (moduleStatusCheck('Lead')==true) { 
                    if ($request->filled('lead_city')) {
                        $student->lead_city_id = $request->lead_city;
                    }
                    if ($request->filled('source_id')) {
                        $student->source_id = $request->source_id;
                    }
                }
                $student->save();
                DB::commit();
            }
               
            // session null
            $update_stud = SmStudent::where('user_id', $student->user_id)->first('student_photo');
            Session::put('profile', $update_stud->student_photo);
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('my_children', [$student->id]);

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    private function addUser($user_id, $role_id, $username, $email, $phone_number)
    {
        try {

            $user = $user_id == null ? new User() : User::find($user_id);
            $user->role_id = $role_id;
            if ($username !=null) {
                $user->username = $username;
            }
            if ($email !=null) {
                $user->email = $email;
            }
            if ($phone_number !=null) {
                $user->phone_number = $phone_number;
            }
            $user->save();
            return $user;

        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function UpdatemyChildren($id)
    {

        try {
            $student = SmStudent::find($id);

            $classes = SmClass::where('active_status', '=', '1')
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $religions = SmBaseSetup::where('active_status', '=', '1')
                ->where('base_group_id', '=', '2')
                ->get();

            $blood_groups = SmBaseSetup::where('active_status', '=', '1')
                ->where('base_group_id', '=', '3')
                ->get();

            $genders = SmBaseSetup::where('active_status', '=', '1')
                ->where('base_group_id', '=', '1')
                ->get();

            $route_lists = SmRoute::where('active_status', '=', '1')
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $vehicles = SmVehicle::where('active_status', '=', '1')
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $dormitory_lists = SmDormitoryList::where('active_status', '=', '1')
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $driver_lists = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $categories = SmStudentCategory::where('school_id', Auth::user()->school_id)
                ->get();

            $groups = SmStudentGroup::where('school_id', Auth::user()->school_id)
                ->get();

            $sessions = SmAcademicYear::where('active_status', '=', '1')
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $siblings = SmStudent::where('parent_id', $student->parent_id)
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $lead_city= [];
            $sources = [];
            
            if (moduleStatusCheck('Lead')==true) {
                    $lead_city = \Modules\Lead\Entities\LeadCity::where('school_id', auth()->user()->school_id)->get(['id', 'city_name']);
                    $sources = \Modules\Lead\Entities\Source::where('school_id', auth()->user()->school_id)->get(['id', 'source_name']);
            }
            $fields = SmStudentRegistrationField::where('school_id', auth()->user()->school_id)
            ->when(auth()->user()->role_id == 2, function ($query) {
                $query->where('student_edit', 1);
            })
            ->when(auth()->user()->role_id == 3, function ($query) {
                $query->where('parent_edit', 1);
            })
            ->pluck('field_name')->toArray();
            return view('backEnd.parentPanel.update_my_children', compact('student', 'classes', 'religions', 'blood_groups', 'genders', 'route_lists', 'vehicles', 'dormitory_lists', 'categories', 'groups', 'sessions', 'siblings', 'driver_lists', 'lead_city', 'fields', 'sources'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function myChildren($id)
    {
        try {
            $parent_info = Auth::user()->parent;
            $student_detail = SmStudent::where('id', $id)->where('parent_id', $parent_info->id)->with('studentRecords.directFeesInstallments.payments','studentAttendances','studentRecords.directFeesInstallments.installment','feesAssign','feesAssignDiscount','academicYear','defaultClass.class','category','religion')->first();
            $records = $student_detail->studentRecords;
            if ($student_detail) {
                $driver = SmVehicle::where('sm_vehicles.id', $student_detail->vechile_id)
                    ->join('sm_staffs', 'sm_vehicles.driver_id', '=', 'sm_staffs.id')
                    ->where('sm_staffs.school_id', Auth::user()->school_id)
                    ->first();

                $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $student_detail->class_id)->first();
                $student_optional_subject = SmOptionalSubjectAssign::where('student_id', $student_detail->id)
                    ->where('session_id', '=', $student_detail->session_id)
                    ->first();

                $fees_assigneds = $student_detail->feesAssign ;
                $invoice_settings = FeesInvoice::where('school_id',Auth::user()->school_id)->first();
                $fees_discounts = $student_detail->feesAssignDiscount;

                $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)
                    ->where('type', 'stu')
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)
                    ->where('type', 'stu')
                    ->where('academic_id', getAcademicId())
                    ->where('visible_to_student', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $exams = SmExamSchedule::where('class_id', $student_detail->class_id)
                    ->where('section_id', $student_detail->section_id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $grades = SmMarksGrade::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $maxgpa = $grades->max('gpa');

                $failgpa = $grades->min('gpa');

                $failgpaname = $grades->where('gpa', $failgpa)
                    ->first();

                $academic_year = $student_detail->academicYear;

                $exam_terms = SmExamType::where('school_id', Auth::user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->get();
                $custom_field_data = $student_detail->custom_field;

                if (!is_null($custom_field_data)) {
                    $custom_field_values = json_decode($custom_field_data);
                } else {
                    $custom_field_values = null;
                }

                $paymentMethods = SmPaymentMethhod::whereNotIn('method', ["Cash", "Wallet"])
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $bankAccounts = SmBankAccount::where('active_status', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

               if(moduleStatusCheck('Wallet')){
                   $walletAmounts = WalletTransaction::where('user_id', Auth::user()->id)
                       ->where('school_id', Auth::user()->school_id)
                       ->get();
               } else{
                   $walletAmounts = null;
               }

                $custom_field_data = $student_detail->custom_field;

                if (!is_null($custom_field_data)) {
                    $custom_field_values = json_decode($custom_field_data);
                } else {
                    $custom_field_values = null;
                }

                $data['bank_info'] = SmPaymentMethhod::where('method', 'Bank')->where('school_id', Auth::user()->school_id)->first();
                $data['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')->where('school_id', Auth::user()->school_id)->first();

                $leave_details = SmLeaveRequest::where('staff_id', $student_detail->user_id)->where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $payment_gateway = SmPaymentMethhod::first();
                $student = SmStudent::where('id', $id)->where('parent_id', $parent_info->id)->first();

                $now = Carbon::now();
                $year = $now->year;
                $month  = $now->month ;
                $days = cal_days_in_month(CAL_GREGORIAN, $now->month, $now->year);
                $studentRecord = StudentRecord::where('student_id', $student_detail->id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', $student_detail->school_id)
                    ->get();
                foreach($studentRecord as $record)
                {
                    $attendance = SmStudentAttendance::where('student_id', $record->student_id)
                        ->where('academic_id', $record->academic_id)
                        ->where('student_record_id', $record->id)
                        ->get();
    
                    $subjectAttendance = SmSubjectAttendance::with('student')->where('student_record_id', $record->id)
                        ->where('academic_id', $record->academic_id)
                        ->where('school_id', $record->school_id)
                        ->get();
                }
    
                if(moduleStatusCheck('University')){
                    $student_id = $student_detail->id;
                    $studentDetails = SmStudent::find($student_id);
                    $studentRecordDetails = StudentRecord::where('student_id',$student_id);
                    $studentRecords = $studentRecordDetails->distinct('un_academic_id')->get();
                    $print =1;
                    
                    return view('backEnd.parentPanel.my_children', compact('student_detail', 'fees_assigneds', 'driver', 'fees_discounts', 'exams', 'documents', 'timelines', 'grades', 'exam_terms','academic_year','leave_details','optional_subject_setup','student_optional_subject','maxgpa','failgpaname','custom_field_values', 'walletAmounts', 'bankAccounts', 'paymentMethods','records','studentDetails', 'studentRecordDetails','studentRecords','print','payment_gateway','student','data','invoice_settings'));
                }else{
                    return view('backEnd.parentPanel.my_children', compact('student_detail', 'fees_assigneds', 'driver', 'fees_discounts', 'exams', 'documents', 'timelines', 'grades', 'exam_terms','academic_year','leave_details','optional_subject_setup','student_optional_subject','maxgpa','failgpaname','custom_field_values', 'walletAmounts', 'bankAccounts', 'paymentMethods','records','payment_gateway','student','data', 'invoice_settings', 'attendance', 'subjectAttendance', 'days', 'year', 'month'));
                }
            } else {
                Toastr::warning('Invalid Student ID', 'Invalid');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamination($id)
    {

        try {
            // $student = Auth::user()->student;
            $student = SmStudent::findOrfail($id);
            $records = studentRecords(null, $student->id)->get();

            $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                ->where('school_id', Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);
            // $now = date('H:i:s');

            // ->where('start_time', '<', $now)
            if (moduleStatusCheck('OnlineExam') == true) {
                $online_exams = InfixOnlineExam::where('active_status', 1)->where('status', 1)->where('class_id', $student->class_id)->where('section_id', $student->section_id)
                    ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $marks_assigned = InfixStudentTakeOnlineExam::whereIn('online_exam_id', $online_exams->pluck('id')->toArray())->where('student_id', $student->id)->where('status', 2)
                    ->where('school_id', Auth::user()->school_id)->pluck('online_exam_id')->toArray();
            } else {
                $online_exams = SmOnlineExam::where('active_status', 1)->where('status', 1)->where('class_id', $student->class_id)->where('section_id', $student->section_id)
                    ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $marks_assigned = SmStudentTakeOnlineExam::whereIn('online_exam_id', $online_exams->pluck('id')->toArray())->where('student_id', $student->id)->where('status', 2)
                    ->where('school_id', Auth::user()->school_id)->pluck('online_exam_id')->toArray();
            }

            return view('backEnd.parentPanel.parent_online_exam', compact('online_exams', 'marks_assigned', 'student','records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function onlineExaminationResult($id)
    {

        try {
            if (moduleStatusCheck('OnlineExam') == true) {
                $result_views = InfixStudentTakeOnlineExam::
                    where('active_status', 1)->where('status', 2)
                    ->where('academic_id', getAcademicId())
                    ->where('student_id', $id)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            } else {
                $result_views = SmStudentTakeOnlineExam::
                    where('active_status', 1)->where('status', 2)
                    ->where('academic_id', getAcademicId())
                    ->where('student_id', $id)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            }
            $records = studentRecords(null, $id)->get();

            return view('backEnd.parentPanel.parent_online_exam_result', compact('result_views','records','id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentAnswerScript($exam_id, $s_id)
    {
        try {
            if (moduleStatusCheck('OnlineExam') == true) {
                $take_online_exam = InfixStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('school_id', Auth::user()->school_id)->first();
            } else {
                $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('school_id', Auth::user()->school_id)->first();
            }

            return view('backEnd.examination.online_answer_view_script_modal', compact('take_online_exam', 's_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentLeave($id)
    {

        try {
            $student = SmStudent::findOrfail($id);
            $apply_leaves = SmLeaveRequest::where('staff_id', '=', $student->user_id)
                ->join('sm_leave_defines', 'sm_leave_defines.id', '=', 'sm_leave_requests.leave_define_id')
                ->join('sm_leave_types', 'sm_leave_types.id', '=', 'sm_leave_defines.type_id')
                ->where('sm_leave_requests.academic_id', getAcademicId())
                ->where('sm_leave_requests.school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parent_leave', compact('apply_leaves', 'student'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function leaveApply(Request $request)
    {
        try {
            $user = Auth::user();
            $std_id = SmStudent::leftjoin('sm_parents', 'sm_parents.id', 'sm_students.parent_id')
                ->where('sm_parents.user_id', $user->id)
                ->where('sm_students.active_status', 1)
                ->where('sm_students.academic_id', getAcademicId())
                ->where('sm_students.school_id', Auth::user()->school_id)
                ->select('sm_students.user_id')
                ->first();
            $my_leaves = SmLeaveDefine::where('role_id', 2)->where('school_id', Auth::user()->school_id)->get();
            $apply_leaves = SmLeaveRequest::where('staff_id', $std_id->user_id)->where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $leave_types = SmLeaveDefine::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

           
            return view('backEnd.parentPanel.apply_leave', compact('apply_leaves', 'leave_types', 'my_leaves', 'user'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function leaveStore(Request $request)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $input = $request->all();
        $validator = Validator::make($input, [
            'student_id' => "required",
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => 'required|before_or_equal:leave_to',
            'leave_to' => "required",
            'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'reason' => "required",
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
            $input = $request->all();
            $fileName = "";
            if ($request->file('attach_file') != "") {
//                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }
            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = $request->student_id;
            $apply_leave->role_id = 2;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->type_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            $apply_leave->file = $fileName;
            $apply_leave->school_id = Auth::user()->school_id;
            $apply_leave->academic_id = getAcademicId();
            $result = $apply_leave->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been created successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewLeaveDetails(Request $request, $id)
    {
        try {
            $leaveDetails = SmLeaveRequest::find($id);
            $apply = "";
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['leaveDetails'] = $leaveDetails->toArray();
                $data['apply'] = $apply;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.viewLeaveDetails', compact('leaveDetails', 'apply'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function leaveEdit($id)
    {
    }

    public function pendingLeave(Request $request)
    {
        try {
            $apply_leaves = SmLeaveRequest::with('student', 'leaveDefine')->where([['active_status', 1], ['approve_status', 'P']])->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
            $leave_types = SmLeaveType::where('active_status', 1)->where('academic_id', getAcademicId())->whereOr(['school_id', Auth::user()->school_id],
                ['school_id', 1])->get();
            $roles = InfixRole::where('id', 2)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $pendingRequest = SmLeaveRequest::where('sm_leave_requests.active_status', 1)
                ->select('sm_leave_requests.id', 'full_name', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'sm_leave_types.type', 'approve_status')
                ->join('sm_leave_defines', 'sm_leave_requests.leave_define_id', '=', 'sm_leave_defines.id')
                ->join('sm_staffs', 'sm_leave_requests.staff_id', '=', 'sm_staffs.id')
                ->leftjoin('sm_leave_types', 'sm_leave_requests.type_id', '=', 'sm_leave_types.id')
                ->where('sm_leave_requests.approve_status', '=', 'P')
                ->where('sm_leave_requests.academic_id', getAcademicId())
                ->where('sm_leave_requests.school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['pending_request'] = $pendingRequest->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.pending_leave', compact('apply_leaves', 'leave_types', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentLeaveEdit(request $request, $id)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $my_leaves = SmLeaveDefine::where('role_id', 2)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', 2)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $my_leaves = SmLeaveDefine::where('role_id', $request->role_id)->where('school_id', Auth::user()->school_id)->get();
                $apply_leaves = SmLeaveRequest::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                $leave_types = SmLeaveDefine::where('role_id', $request->role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            }
            $apply_leave = SmLeaveRequest::find($id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['my_leaves'] = $my_leaves->toArray();
                $data['apply_leaves'] = $apply_leaves->toArray();
                $data['leave_types'] = $leave_types->toArray();
                $data['apply_leave'] = $apply_leave->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.parentPanel.apply_leave', compact('apply_leave', 'apply_leaves', 'leave_types', 'my_leaves', 'user'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'id' => "required",
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'login_id' => "required",
                'role_id' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        } else {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $fileName = "";
            if ($request->file('attach_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $apply_leave = SmLeaveRequest::find($request->id);
                if (file_exists($apply_leave->file)) {
                    unlink($apply_leave->file);
                }

                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }

            $user = Auth()->user();
            $apply_leave = SmLeaveRequest::find($request->id);
            $apply_leave->staff_id = $request->student_id;
            $apply_leave->role_id = 2;
            $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
            $apply_leave->leave_define_id = $request->leave_type;
            $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
            $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->reason;
            if ($fileName != "") {
                $apply_leave->file = $fileName;
            }
            $result = $apply_leave->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Leave Request has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-apply-leave');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function DeleteLeave(Request $request, $id)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $apply_leave = SmLeaveRequest::find($id);
            if ($apply_leave->file != "") {
                if (file_exists($apply_leave->file)) {
                    unlink($apply_leave->file);
                }

            }
            $result = $apply_leave->delete();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Request has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-apply-leave');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classRoutine($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();

            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;
            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $records = $student_detail->studentRecords;
            return view('backEnd.parentPanel.class_routine', compact('class_times', 'class_id', 'section_id', 'sm_weekends', 'student_detail', 'records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendance($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $academic_years = SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.attendance', compact('student_detail', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendanceSearch(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);

        try {
            $student_detail = SmStudent::where('id', $request->student_id)->first();
            $year = $request->year;
            $month = $request->month;
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $records = studentRecords(null, $student_detail->id)->with('studentAttendance')->get();
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('attendance_date', 'like', $request->year . '-' . $request->month . '%')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $academic_years = SmAcademicYear::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.attendance', compact('records', 'days', 'year', 'month', 'current_day', 'student_detail', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendancePrint($student_id, $id, $month, $year)
    {
        try {
            $student_detail = SmStudent::where('id', $student_id)->first();
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            //$students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();
            $attendances = SmStudentAttendance::where('student_record_id',$id)->where('student_id', $student_detail->id)->where('attendance_date', 'like', $year . '-' . $month . '%')->where('school_id', Auth::user()->school_id)->get();
            $customPaper = array(0, 0, 700.00, 1000.80);
            $pdf = PDF::loadView(
                'backEnd.parentPanel.attendance_print',
                [
                    'attendances' => $attendances,
                    'days' => $days,
                    'year' => $year,
                    'month' => $month,
                    'current_day' => $current_day,
                    'student_detail' => $student_detail,
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('my_child_attendance.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examinationSchedule($id)
    {
        try {
            $user = Auth::user();
            $parent = SmParent::where('user_id', $user->id)->first();
            $student_detail = SmStudent::where('id', $id)->first();
            $student_id = $student_detail->id;
            $records = studentRecords(null, $student_detail->id)->get();
            return view('backEnd.parentPanel.parent_exam_schedule', compact('student_id','records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutinePrint($class_id, $section_id, $exam_term_id)
    {

        try {

            $exam_type_id = $exam_term_id;
            $exam_type = SmExamType::find($exam_type_id)->title;
            $academic_id = SmExamType::find($exam_type_id)->academic_id;
            $academic_year = SmAcademicYear::find($academic_id);
            $class_name = SmClass::find($class_id)->class_name;
            $section_name = SmSection::find($section_id)->section_name;
            $exam_schedules = SmExamSchedule::where('class_id', $class_id)->where('section_id', $section_id)
                ->where('exam_term_id', $exam_type_id)->orderBy('date', 'ASC')->get();
                $print = request()->print;
                return view('backEnd.examination.exam_schedule_print',
                    [
                        'exam_schedules' => $exam_schedules,
                        'exam_type' => $exam_type,
                        'class_name' => $class_name,
                        'academic_year' => $academic_year,
                        'section_name' => $section_name,
                        'print' => $print,
                    ]);
            $pdf = PDF::loadView(
                'backEnd.examination.exam_schedule_print',
                [
                    'exam_schedules' => $exam_schedules,
                    'exam_type' => $exam_type,
                    'class_name' => $class_name,
                    'academic_year' => $academic_year,
                    'section_name' => $section_name,

                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('EXAM_SCHEDULE.pdf');

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentBookList()
    {

        try {
            $books = SmBook::where('active_status', 1)
                ->orderBy('id', 'DESC')
                ->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.parentBookList', compact('books'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function parentBookIssue()
    {
        try {
            $user = Auth::user();
            $parent_detail = SmParent::where('user_id', $user->id)->first();

            $library_member = SmLibraryMember::where('member_type', 3)->where('student_staff_id', $parent_detail->user_id)->first();
            if (empty($library_member)) {
                Toastr::error('You are not library member ! Please contact with librarian', 'Failed');
                return redirect()->back();
            }
            $issueBooks = SmBookIssue::where('member_id', $library_member->student_staff_id)
                ->leftjoin('sm_books', 'sm_books.id', 'sm_book_issues.book_id')
                ->leftjoin('library_subjects', 'library_subjects.id', 'sm_books.book_subject_id')
                ->where('sm_book_issues.issue_status', 'I')->where('sm_book_issues.school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parentBookIssue', compact('issueBooks'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examinationScheduleSearch(Request $request)
    {
       
        try {
            $request->validate([
                'exam' => 'required',
            ]);
            $user = Auth::user();
            $parent = SmParent::where('user_id', $user->id)->first();
            $student_detail = SmStudent::find($request->student_id);
            $records = studentRecords(null, $student_detail->id)->get();
            $smExam = SmExam::findOrFail($request->exam);
            $student_id = $student_detail->id;
            $assign_subjects = SmAssignSubject::where('class_id', $smExam->class_id)->where('section_id', $smExam->section_id)
                ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::error('No Subject Assigned. Please assign subjects in this class.', 'Failed');
                return redirect()->back();
            }

            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_id = $smExam->class_id;
            $section_id = $smExam->section_id;
            $exam_id = $smExam->id;
            $exam_type_id = $smExam->exam_type_id;
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_schedule_subjects = "";
            $assign_subject_check = "";

            $exam_routines = SmExamSchedule::where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('exam_term_id', $exam_type_id)->orderBy('date', 'ASC')->get();

            return view('backEnd.parentPanel.parent_exam_schedule', compact('exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_schedule_subjects', 'assign_subject_check', 'records', 'exam_type_id', 'exam_routines', 'exam_periods', 'student_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Empty Search');
            return redirect()->route('parent_exam_schedule', $request->student_id);
        }
    }
    public function examination($id)
    {
        try {
            $student_detail = SmStudent::withoutGlobalScope(StatusAcademicSchoolScope::class)->find($id);
            $records = studentRecords(null, $student_detail->id)->get();
            $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $student_detail->class_id)->first();

            $student_optional_subject = SmOptionalSubjectAssign::where('student_id', $student_detail->id)
                ->where('session_id', '=', $student_detail->session_id)
                ->first();

            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)
                ->where('section_id', $student_detail->section_id)
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $grades = SmMarksGrade::where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $failgpa = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->min('gpa');

            $failgpaname = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->where('gpa', $failgpa)
                ->first();
            $maxgpa = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->max('gpa');

            $exam_terms = SmExamType::where('school_id', Auth::user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            return view('backEnd.parentPanel.student_result', compact('student_detail', 'exams', 'grades', 'exam_terms', 'failgpaname', 'optional_subject_setup', 'student_optional_subject', 'maxgpa','records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function subjects($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $records = studentRecords(null, $student_detail->id)->get();
            return view('backEnd.parentPanel.subject', compact('records', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function teacherList($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $records = studentRecords(null, $student_detail->id)->get();
            return view('backEnd.parentPanel.teacher_list', compact('records', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function transport($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $routes = SmAssignVehicle::join('sm_vehicles', 'sm_assign_vehicles.vehicle_id', 'sm_vehicles.id')
                ->join('sm_students', 'sm_vehicles.id', 'sm_students.vechile_id')
                ->join('sm_parents', 'sm_parents.id', 'sm_students.parent_id')
                ->where('sm_assign_vehicles.active_status', 1)
                ->where('sm_parents.user_id', Auth::user()->id)
                ->where('sm_assign_vehicles.school_id', Auth::user()->school_id)
                ->get();
                
            return view('backEnd.parentPanel.transport', compact('routes', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function dormitory($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $room_lists = SmRoomList::where('active_status', 1)->where('id', $student_detail->room_id)->where('school_id', Auth::user()->school_id)->get();
            $room_lists = $room_lists->groupBy('dormitory_id');
            $room_types = SmRoomType::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('active_status', 1)->where('id', $student_detail->dormitory_id)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.parentPanel.dormitory', compact('room_lists', 'room_types', 'dormitory_lists', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homework($id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
           
            if(moduleStatusCheck('University')){
                $records = $student_detail->studentRecords;
            }else{
                $records = studentRecords(null, $student_detail->id)->with('homework')->get();
            }
            return view('backEnd.parentPanel.homework', compact('records','student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function homeworkView($class_id, $section_id, $homework_id)
    {
        try {
            $homeworkDetails = SmHomework::where('class_id', '=', $class_id)->where('section_id', '=', $section_id)->where('id', '=', $homework_id)->first();
            return view('backEnd.parentPanel.homeworkView', compact('homeworkDetails', 'homework_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function unStudentHomeworkView($sem_label_id, $homework){
        try {
            $homeworkDetails = SmHomework::find($homework);
            $homework_id = $homework;
            return view('backEnd.studentPanel.studentHomeworkView', compact('homeworkDetails', 'homework_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function parentNoticeboard()
    {
        try {
            $allNotices = SmNoticeBoard::where('active_status', 1)->where('inform_to', 'LIKE', '%3%')
                ->orderBy('id', 'DESC')
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.parentPanel.parentNoticeboard', compact('allNotices'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function childListApi(Request $request, $id)
    {
        try {
            $parent = SmParent::where('user_id', $id)->first();
            $student_info = DB::table('sm_students')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
            // ->join('sm_exams','sm_exams.id','=','sm_exam_types.id' )
            // ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id' )

                ->where('sm_students.parent_id', '=', $parent->id)

                ->select('sm_students.user_id', 'student_photo', 'sm_students.full_name as student_name', 'class_name', 'section_name', 'roll_no')

                ->where('sm_students.school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($student_info, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childProfileApi(Request $request, $id)
    {
        try {
            $student_detail = SmStudent::where('id', $id)->first();
            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $fees_assigneds = SmFeesAssign::where('student_id', $student_detail->id)->where('school_id', Auth::user()->school_id)->get();
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $student_detail->id)->where('school_id', Auth::user()->school_id)->get();
            $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')->where('school_id', Auth::user()->school_id)->get();
            $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)->where('type', 'stu')->where('visible_to_student', 1)->where('school_id', Auth::user()->school_id)->get();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('school_id', Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['fees_assigneds'] = $fees_assigneds->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                $data['exams'] = $exams->toArray();
                $data['documents'] = $documents->toArray();
                $data['timelines'] = $timelines->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['grades'] = $grades->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            //return view('backEnd.studentPanel.my_profile', compact('student_detail', 'fees_assigneds', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function collectFeesChildApi(Request $request, $id)
    {
        try {
            $student = SmStudent::where('id', $id)->first();
            $fees_assigneds = SmFeesAssign::where('student_id', $id)->orderBy('id', 'desc')->where('school_id', Auth::user()->school_id)->get();

            $fees_assigneds2 = DB::table('sm_fees_assigns')
                ->select('sm_fees_types.id as fees_type_id', 'sm_fees_types.name', 'sm_fees_masters.date as due_date', 'sm_fees_masters.amount as amount')
                ->join('sm_fees_masters', 'sm_fees_masters.id', '=', 'sm_fees_assigns.fees_master_id')
                ->join('sm_fees_types', 'sm_fees_types.id', '=', 'sm_fees_masters.fees_type_id')
                ->join('sm_fees_payments', 'sm_fees_payments.fees_type_id', '=', 'sm_fees_masters.fees_type_id')
                ->where('sm_fees_assigns.student_id', $student->id)
            //->where('sm_fees_payments.student_id', $student->id)
                ->where('sm_fees_assigns.school_id', Auth::user()->school_id)->get();
            $i = 0;
            return $fees_assigneds2;
            foreach ($fees_assigneds2 as $row) {
                $d[$i]['fees_name'] = $row->name;
                $d[$i]['due_date'] = $row->due_date;
                $d[$i]['amount'] = $row->amount;
                $d[$i]['paid'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('amount');
                $d[$i]['fine'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('fine');
                $d[$i]['discount_amount'] = DB::table('sm_fees_payments')->where('fees_type_id', $row->fees_type_id)->sum('discount_amount');
                $d[$i]['balance'] = ((float) $d[$i]['amount'] + (float) $d[$i]['fine']) - ((float) $d[$i]['paid'] + (float) $d[$i]['discount_amount']);
                $i++;
            }
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $id)->where('school_id', Auth::user()->school_id)->get();

            $applied_discount = [];
            foreach ($fees_discounts as $fees_discount) {
                $fees_payment = SmFeesPayment::where('active_status', 1)->select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->first();
                if (isset($fees_payment->fees_discount_id)) {
                    $applied_discount[] = $fees_payment->fees_discount_id;
                }
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees'] = $d;
                return ApiBaseMethod::sendResponse($fees_assigneds2, null);
            }

            return view('backEnd.feesCollection.collect_fees_student_wise', compact('student', 'fees_assigneds', 'fees_discounts', 'applied_discount'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classRoutineApi(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('id', $id)->first();
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $class_times = SmClassTime::where('type', 'class')->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                // $data['class_id'] = $class_id;
                // $data['section_id'] = $section_id;
                // $data['sm_weekends'] = $sm_weekends->toArray();
                // $data['class_times'] = $class_times->toArray();

                $weekenD = SmWeekend::where('school_id', Auth::user()->school_id)->get();

                foreach ($weekenD as $row) {
                    $data[$row->name] = DB::table('sm_class_routine_updates')
                        ->select('sm_class_times.period', 'sm_class_times.start_time', 'sm_class_times.end_time', 'sm_subjects.subject_name', 'sm_class_rooms.room_no')
                        ->join('sm_classes', 'sm_classes.id', '=', 'sm_class_routine_updates.class_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_class_routine_updates.section_id')
                        ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_class_routine_updates.class_period_id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_class_routine_updates.subject_id')
                        ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_class_routine_updates.room_id')

                        ->where([
                            ['sm_class_routine_updates.class_id', $class_id], ['sm_class_routine_updates.section_id', $section_id], ['sm_class_routine_updates.day', $row->id],
                        ])->where('sm_classes.school_id', Auth::user()->school_id)->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }

            //return view('backEnd.studentPanel.class_routine', compact('class_times', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childHomework(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $student_detail = SmStudent::where('id', $id)->first();

                $class_id = $student_detail->class->id;
                $subject_list = SmAssignSubject::where([['class_id', $class_id], ['section_id', $student_detail->section_id]])->where('school_id', Auth::user()->school_id)->get();

                $i = 0;
                foreach ($subject_list as $subject) {
                    $homework_subject_list[$subject->subject->subject_name] = $subject->subject->subject_name;
                    $allList[$subject->subject->subject_name] =
                    DB::table('sm_homeworks')
                        ->select('sm_homeworks.description', 'sm_subjects.subject_name', 'sm_homeworks.homework_date', 'sm_homeworks.submission_date', 'sm_homeworks.evaluation_date', 'sm_homeworks.file', 'sm_homeworks.marks', 'sm_homework_students.complete_status as status')
                        ->leftjoin('sm_homework_students', 'sm_homework_students.homework_id', '=', 'sm_homeworks.id')
                        ->leftjoin('sm_subjects', 'sm_subjects.id', '=', 'sm_homeworks.subject_id')
                        ->where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('subject_id', $subject->subject_id)->where('sm_homeworks.school_id', Auth::user()->school_id)->get();
                }

                $homeworkLists = SmHomework::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('school_id', Auth::user()->school_id)->get();
            }
            $data = [];

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                foreach ($allList as $r) {
                    foreach ($r as $s) {
                        $data[] = $s;
                    }
                }
                return ApiBaseMethod::sendResponse($data, null);
            }
            // return view('backEnd.studentPanel.student_homework', compact('homeworkLists', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function childAttendanceAPI(Request $request, $id)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $student_detail = SmStudent::where('id', $id)->first();

            $year = $request->year;
            $month = $request->month;
            if ($month < 10) {
                $month = '0' . $month;
            }
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $request->year);
            $previous_month = $month - 1;
            $previous_date = $year . '-' . $previous_month . '-' . $days2;
            $previousMonthDetails['date'] = $previous_date;
            $previousMonthDetails['day'] = $days2;
            $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)
                ->where('attendance_date', 'like', '%' . $request->year . '-' . $month . '%')
                ->select('attendance_type', 'attendance_date')
                ->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['previousMonthDetails'] = $previousMonthDetails;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
                return ApiBaseMethod::sendResponse($data, null);
            }
            //Test
            //return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function aboutApi(request $request)
    {
        try {
            $about = DB::table('sm_general_settings')
                ->join('sm_languages', 'sm_general_settings.language_id', '=', 'sm_languages.id')
                ->join('sm_academic_years', 'sm_general_settings.session_id', '=', 'sm_academic_years.id')
                ->join('sm_about_pages', 'sm_general_settings.school_id', '=', 'sm_about_pages.school_id')
                ->select('main_description', 'school_name', 'site_title', 'school_code', 'address', 'phone', 'email', 'logo', 'sm_languages.language_name', 'year as session', 'copyright_text')
                ->where('sm_general_settings.school_id', Auth::user()->school_id)->first();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($about, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function StudentDownload($file_name)
    {
        try {
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
