<?php

namespace App\Http\Controllers;

use App\Role;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\YearCheck;
use App\SmStudentIdCard;
use App\SmGeneralSettings;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;

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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'page_layout_style' => 'required',
            'applicable_user.*' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg',

        ]);
        if($request->applicable_user == 0){
            $request->validate([
                'role' => 'required'
            ]);
        }

        try {
            $fileNameLogo = "";
            if ($request->file('logo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('logo');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('logo');
                $fileNameLogo = 'logo-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameLogo);
                $fileNameLogo = 'public/uploads/studentIdCard/' . $fileNameLogo;
            }

            $fileNameSignature = "";
            if ($request->file('signature') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('signature');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('signature');
                $fileNameSignature = 'signature-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameSignature);
                $fileNameSignature = 'public/uploads/studentIdCard/' . $fileNameSignature;
            }

            $fileNameBackground = "";
            if ($request->file('background_img') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('background_img');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('background_img');
                $fileNameBackground = 'background_img-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameBackground);
                $fileNameBackground = 'public/uploads/studentIdCard/' . $fileNameBackground;
            }

            $fileNameProfileImage = "";
            if ($request->file('profile_image') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('profile_image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('profile_image');
                $fileNameProfileImage = 'profile_image-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameProfileImage);
                $fileNameProfileImage = 'public/uploads/studentIdCard/' . $fileNameProfileImage;
            }

            $id_card = new SmStudentIdCard();
            $id_card->title = $request->title;
            $id_card->logo = $fileNameLogo;
            $id_card->school_id = Auth::user()->school_id;
            $id_card->academic_id = getAcademicId();

            if (isset($fileNameSignature)) {
                $id_card->signature = $fileNameSignature;
            }
            if (isset($fileNameBackground)) {
                $id_card->background_img = $fileNameBackground;
            }
            if (isset($fileNameProfileImage)) {
                $id_card->profile_image = $fileNameProfileImage;
            }
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
            $id_card->class = $request->class;
            $id_card->father_name = $request->father_name;
            $id_card->mother_name = $request->mother_name;
            $id_card->student_address = $request->student_address;
            $id_card->dob = $request->dob;
            $id_card->blood = $request->blood;
            if(in_array(3, $request->applicable_user)){
                $id_card->phone_number = $request->phone_number;
            }
            
            $result = $id_card->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('student-id-card');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $id_cards = SmStudentIdCard::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $roles = InfixRole::select('*')->where('id', '!=', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
             if (checkAdmin()) {
                $id_card = SmStudentIdCard::find($id);
            }else{
                $id_card = SmStudentIdCard::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            return view('backEnd.admin.idCard.student_id_card', compact('id_cards', 'id_card','roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'page_layout_style' => 'required',
            'applicable_user' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'signature' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if($request->applicable_user == 0){
            $request->validate([
                'role' => 'required'
            ]);
        }

        try {
            $fileNamelogo = "";
            if ($request->file('logo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('logo');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                // $id_card = SmStudentIdCard::find($request->id);
                // if ($id_card->logo != "") {
                //     if (file_exists($id_card->logo)) {
                //         unlink($id_card->logo);
                //     }
                // }

                $file = $request->file('logo');
                $fileNamelogo = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNamelogo);
                $fileNamelogo = 'public/uploads/studentIdCard/' . $fileNamelogo;
            }

            $fileNameSignature = "";
            if ($request->file('signature') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('signature');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                // $id_card = SmStudentIdCard::find($request->id);
                // if ($id_card->signature != "") {
                //     if (file_exists($id_card->signature)) {
                //         unlink($id_card->signature);
                //     }
                // }

                $file = $request->file('signature');
                $fileNameSignature = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameSignature);
                $fileNameSignature = 'public/uploads/studentIdCard/' . $fileNameSignature;
            }

            $fileNameBackground = "";
            if ($request->file('background_img') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('background_img');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                // $id_card = SmStudentIdCard::find($request->id);
                // if ($id_card->background_img != "") {
                //     if (file_exists($id_card->background_img)) {
                //         unlink($id_card->background_img);
                //     }
                // }

                $file = $request->file('background_img');
                $fileNameBackground = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameBackground);
                $fileNameBackground = 'public/uploads/studentIdCard/' . $fileNameBackground;
            }

            $fileNameProfileImage = "";
            if ($request->file('profile_image') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('profile_image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                // $id_card = SmStudentIdCard::find($request->id);
                // if ($id_card->profile_image != "") {
                //     if (file_exists($id_card->profile_image)) {
                //         unlink($id_card->profile_image);
                //     }
                // }

                $file = $request->file('profile_image');
                $fileNameProfileImage = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/studentIdCard/', $fileNameProfileImage);
                $fileNameProfileImage = 'public/uploads/studentIdCard/' . $fileNameProfileImage;
            }

             if (checkAdmin()) {
                $id_card = SmStudentIdCard::find($request->id);
            }else{
                $id_card = SmStudentIdCard::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $id_card->title = $request->title;
            if ($fileNamelogo != "") {
                $id_card->logo = $fileNamelogo;
            }

            if ($fileNameBackground!= "") {
                $id_card->background_img = $fileNameBackground;
            }
            if ($fileNameProfileImage!= "") {
                $id_card->profile_image = $fileNameProfileImage;
            }
            // if($request->applicable_user == 2){
            //     $id_card->role_id = '["2"]';
            // }else{
            //     $id_card->role_id = json_encode($request->role);
            // }
            if(in_array(2, $request->applicable_user) || in_array(3, $request->applicable_user)){
                $id_card->role_id = json_encode($request->applicable_user);
            }else{
                $id_card->role_id = json_encode($request->role);
            }

            if ($fileNameSignature != "") {
                $id_card->signature = $fileNameSignature;
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
            $id_card->class = $request->class;
            $id_card->father_name = $request->father_name;
            $id_card->mother_name = $request->mother_name;
            $id_card->student_address = $request->student_address;
            $id_card->dob = $request->dob;
            $id_card->blood = $request->blood;

            if(in_array(3, $request->applicable_user)){
                $id_card->phone_number = $request->phone_number;
            }

            $result = $id_card->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('student-id-card');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {

        try {
            $id_card = SmStudentIdCard::find($request->id);
            if (checkAdmin()) {
                $id_card = SmStudentIdCard::find($request->id);
            }else{
                $id_card = SmStudentIdCard::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            if ($id_card->logo != "") {
                unlink($id_card->logo);
            }

            if ($id_card->signature != "") {
                unlink($id_card->signature);
            }

            if ($id_card->profile_image != "") {
                unlink($id_card->profile_image);
            }

            if ($id_card->background_img != "") {
                unlink($id_card->background_img);
            }

            $result = $id_card->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('student-id-card');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function generateIdCard()
    {

        try {
            $id_cards = SmStudentIdCard::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $roles = Role::where('school_id', Auth::user()->school_id)->whereNotIn('id',[1])->get();
            return view('backEnd.admin.idCard.generate_id_card', compact('id_cards','roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxIdCard(Request $request){
      
            
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

        }
    public function generateIdCardBulk(Request $request){
        
        $request->validate([
            'role' => 'required',
            'id_card' => 'required',         
            'grid_gap' => 'required',         
        ]);
        if($request->role==2){
            $s_students=SmStudent::query();
            if($request->class){
                $s_students->where('class_id',$request->class_id);
            }
            if($request->section){
                $request->where('section_id',$request->section_id);
            }
           $s_students = $s_students->status()->get();
       }elseif($request->role==3){
           $studentGuardian = SmStudent::where('school_id', Auth::user()->school_id)->get('parent_id');
           $s_students = SmParent::whereIn('id',$studentGuardian)->get();
       }
       else{
           $s_students=SmStaff::whereRole($request->role)->status()->get();
       }
       $id_card = SmStudentIdCard::status()->find($request->id_card);

       $role_id=$request->role;

       $gridGap = $request->grid_gap;

     return view('backEnd.admin.idCard.student_id_card_print_bulk', ['id_card' => $id_card, 's_students' => $s_students,'role_id'=>$role_id,'gridGap'=>$gridGap]);

     $pdf = PDF::loadView('backEnd.admin.student_id_card_print_2', ['id_card' => $id_card, 's_students' => $s_students]);
     return $pdf->stream($id_card->title . '.pdf');
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
       


                $students = SmStudent::with('class','parents','section','gender')->where('active_status', 1)
                  ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();


            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $id_cards = SmStudentIdCard::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
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
