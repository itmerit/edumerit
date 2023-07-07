<?php

namespace App\Http\Controllers\Admin\AdminSection;

use App\Role;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\YearCheck;
use App\SmStudentIdCard;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;
use App\Http\Requests\Admin\AdminSection\SmStudentIdCardRequest;

class SmStudentIdCardController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index()
    {
        try {
            $id_cards = SmStudentIdCard::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.admin.idCard.student_id_card_list',compact('id_cards'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function create_id_card()
    {
        try{
            $id_cards = SmStudentIdCard::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $roles = InfixRole::select('*')->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            return view('backEnd.admin.idCard.student_id_card', compact('id_cards','roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmStudentIdCardRequest $request)
    {
        try {
            $destination='public/uploads/studentIdCard/';
            $id_card = new SmStudentIdCard();
            $id_card->title = $request->title;
            $id_card->logo = $request->logo ? fileUpload($request->logo, $destination) : generalSetting()->logo;
            $id_card->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $id_card->un_academic_id = getAcademicId();
            }else{
                $id_card->academic_id = getAcademicId();
            }        
            $id_card->signature = fileUpload($request->signature, $destination);          
            $id_card->background_img = fileUpload($request->background_img,$destination); 
            $id_card->profile_image = fileUpload($request->profile_image,$destination);           
            if(in_array(2, $request->applicable_user) || in_array(3, $request->applicable_user)){
                $id_card->role_id = json_encode($request->applicable_user);
            }else{
                $id_card->role_id = json_encode($request->role);
            }
            
            $id_card->page_layout_style = $request->page_layout_style;
            $id_card->user_photo_style = $request->user_photo_style;
            $id_card->user_photo_width = $request->user_photo_width;
            $id_card->user_photo_height = $request->user_photo_height;
            $id_card->pl_width = $request->pl_width;
            $id_card->pl_height = $request->pl_height;
            $id_card->t_space = $request->t_space;
            $id_card->b_space = $request->b_space;
            $id_card->l_space = $request->l_space;
            $id_card->r_space = $request->r_space;
            $id_card->admission_no = $request->admission_no;
            $id_card->student_name = $request->student_name;
            $id_card->class = $request->class ?? 0;
            if (moduleStatusCheck('University')) {
                $id_card->un_session = $request->un_session_id;
                $id_card->un_faculty = $request->un_faculty_id;
                $id_card->un_department = $request->un_department_id;
                $id_card->un_academic = $request->un_academic_id;
                $id_card->un_semester = $request->un_semester_id;
                $id_card->un_semester_label = $request->un_semester_label_id;
            }
            $id_card->father_name = $request->father_name;
            $id_card->mother_name = $request->mother_name;
            $id_card->student_address = $request->student_address;
            $id_card->dob = $request->dob;
            $id_card->blood = $request->blood;
            if (in_array(3, $request->applicable_user)) {
                $id_card->phone_number = $request->phone_number;
            }
            
            $id_card->save();
          
            Toastr::success('Operation successful', 'Success');
            return redirect('student-id-card');
           
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $id_cards = SmStudentIdCard::get();
            $roles = InfixRole::select('*')->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $id_card = SmStudentIdCard::find($id);
            return view('backEnd.admin.idCard.student_id_card', compact('id_cards', 'id_card','roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmStudentIdCardRequest $request, $id)
    {
        try {
            $destination='public/uploads/studentIdCard/';  
            $id_card = SmStudentIdCard::find($request->id);
            $id_card->title = $request->title;
            $id_card->logo = fileUpdate($id_card->logo,$request->logo,$destination);          
            $id_card->background_img = fileUpdate($id_card->background_img,$request->background_img,$destination);          
            $id_card->profile_image = fileUpdate($id_card->profile_image,$request->profile_image,$destination);
            if(in_array(2, $request->applicable_user) || in_array(3, $request->applicable_user)){
                $id_card->role_id = json_encode($request->applicable_user);
            }else{
                $id_card->role_id = json_encode($request->role);
            }
            $id_card->signature = fileUpdate($id_card->signature,$request->signature,$destination);
            $id_card->page_layout_style = $request->page_layout_style;
            $id_card->user_photo_style = $request->user_photo_style;
            $id_card->user_photo_width = $request->user_photo_width;
            $id_card->user_photo_height = $request->user_photo_height;
            $id_card->pl_width = $request->pl_width;
            $id_card->pl_height = $request->pl_height;
            $id_card->t_space = $request->t_space;
            $id_card->b_space = $request->b_space;
            $id_card->l_space = $request->l_space;
            $id_card->r_space = $request->r_space;
            $id_card->admission_no = $request->admission_no;
            $id_card->student_name = $request->student_name;
            $id_card->class = $request->class;
            $id_card->father_name = $request->father_name;
            $id_card->mother_name = $request->mother_name;
            $id_card->student_address = $request->student_address;
            $id_card->dob = $request->dob;
            $id_card->blood = $request->blood;
            if (moduleStatusCheck('University')) {
                $id_card->un_session = $request->un_session_id;
                $id_card->un_faculty = $request->un_faculty_id;
                $id_card->un_department = $request->un_department_id;
                $id_card->un_academic = $request->un_academic_id;
                $id_card->un_semester = $request->un_semester_id;
                $id_card->un_semester_label = $request->un_semester_label_id;
            }
            if(in_array(3, $request->applicable_user)){
                $id_card->phone_number = $request->phone_number;
            }
            $id_card->save();
            
            Toastr::success('Operation successful', 'Success');
            return redirect('student-id-card');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {

        try {
            $id_card = SmStudentIdCard::find($request->id);
            
            if ($id_card->logo != "" && file_exists($id_card->logo)) {
                unlink($id_card->logo);
            }

            if ($id_card->signature != "" && file_exists($id_card->signature)) {
                unlink($id_card->signature);
            }

            if ($id_card->profile_image != "" && file_exists($id_card->profile_image)) {
                unlink($id_card->profile_image);
            }

            if ($id_card->background_img != "" && file_exists($id_card->background_img)) {
                unlink($id_card->background_img);
            }

            $id_card->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect('student-id-card');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generateIdCard()
    {

        try {
            $id_cards = SmStudentIdCard::get();
            $roles = Role::get();
            return view('backEnd.admin.idCard.generate_id_card', compact('id_cards','roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generateIdCardBulk(Request $request)
    {
        
        $request->validate([
            'role' => 'required',
            'id_card' => 'required',
            'grid_gap' => 'required',
        ]);
        if ($request->role==2) {
            $s_students = SmStudent::query()->with('parents', 'bloodGroup');
            $s_students = $s_students->get();
        } elseif ($request->role==3) {
            $studentGuardian = SmStudent::get('parent_id');
            $s_students = SmParent::whereIn('id', $studentGuardian)->get();
        } else {
            $s_students = SmStaff::whereRole($request->role)->status()->get();
        }
        $id_card = SmStudentIdCard::status()->find($request->id_card);

        $role_id = $request->role;
        $gridGap = $request->grid_gap;

        return view('backEnd.admin.idCard.student_id_card_print_bulk', ['id_card' => $id_card, 's_students' => $s_students,'role_id'=>$role_id,'gridGap'=>$gridGap]);

        $pdf = PDF::loadView('backEnd.admin.student_id_card_print_2', ['id_card' => $id_card, 's_students' => $s_students]);
        return $pdf->stream($id_card->title . '.pdf');
    }

    public function ajaxIdCard(Request $request){
        try {
            
            $role_id=$request->role_id;
            $id_cards = SmStudentIdCard::status()->get();
            $idCards=[];
            foreach($id_cards as $id_card){
                $role_ids=json_decode($id_card->role_id);
                if(in_array($role_id,$role_ids)){
                    $d['id']=$id_card->id;
                    $d['title']=$id_card->title;
                    $idCards[]=$d;
                }
            }
        
            return response()->json([$idCards]);

        } catch (\Throwable $th) {
           
        }
    }

    public function generateIdCardSearch(Request $request)
    {
        return $request->all();

        $request->validate([
            'class' => 'required',
            'id_card' => 'required',
        ]);

        try {
            $card_id = $request->id_card;
            $class_id = $request->class; 
            $students = SmStudent::with('class','parents','section','gender')->get();
            $classes = SmClass::get();
            $id_cards = SmStudentIdCard::get();
            return view('backEnd.admin.idCard.generate_id_card_old', compact('id_cards', 'class_id', 'classes', 'students', 'card_id','section'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxStudentIdCardPrint()
    {
        try {
            $pdf = PDF::loadView('backEnd.admin.idCard.student_id_card_print');
            return response()->$pdf->stream('certificate.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generateIdCardPrint($s_id, $c_id)
    {
        set_time_limit(2700);
        try {

            $s_ids = explode('-', $s_id);
            $students = [];
            foreach ($s_ids as $sId) {
                $students[] = SmStudent::find($sId);
            }

           

            $id_card = SmStudentIdCard::find($c_id);

            return view('backEnd.admin.idCard.student_id_card_print_2', ['id_card' => $id_card, 'students' => $students]);

            $pdf = PDF::loadView('backEnd.admin.idCard.student_id_card_print_2', ['id_card' => $id_card, 'students' => $students]);
            return $pdf->stream($id_card->title . '.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
