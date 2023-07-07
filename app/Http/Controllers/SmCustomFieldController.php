<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\SmCustomField;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmCustomFieldController extends Controller
{
    public function index()
    {
        $custom_fields = SmCustomField::where('form_name','student_registration')->where('school_id',Auth::user()->school_id)->orderby('id','DESC')->get();
        return view('backEnd.customField.studentRegistration',compact('custom_fields'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'type' => 'required',
            'width'=> 'required',
            'name_value.*'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
            'name_value'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
        ]);
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $exist = SmCustomField::where('form_name','student_registration')
                                ->where('school_id',Auth::user()->school_id)
                                ->where('label',$request->label)->first();

        if($exist){
            Toastr::warning("Label Name Already Exist !", 'Warning');
            return redirect()->back()->withInput();
        }                        
        try{
            $name = "student_registration";
            $this->storeData($request,$name);
            
            Toastr::success('Operation successful', 'Success');
            return redirect('student-registration-custom-field');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $v_custom_field = SmCustomField::find($id);
        $custom_fields = SmCustomField::where('form_name','student_registration')->where('school_id',Auth::user()->school_id)->get();
        return view('backEnd.customField.studentRegistration',compact('custom_fields','v_custom_field'));
    }

    public function update(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'type' => 'required',
            'width'=> 'required',
            'name_value.*'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
            'name_value'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $error){
                Toastr::warning($error, 'Failed');
            }
            return redirect()->back()->withInput();
        }

        $valueExist = SmCustomField::where('id', '!=', $request->id)
                ->where('form_name','student_registration')
                ->where('school_id',Auth::user()->school_id)
                ->where('label',$request->label)
                ->get();

        if(count($valueExist) > 0){
            Toastr::warning("Label Name Already Exist !", 'Warning');
            return redirect()->back()->withInput();
        }

        try{
            $name = "student_registration";
            $this->updateData($request,$name);

            Toastr::success('Operation successful', 'Success');
            return redirect('student-registration-custom-field');

        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try{
            $this->deleteData($request->id);
            Toastr::success('Operation successful', 'Success');
            return redirect('student-registration-custom-field');
            
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function staff_reg_custom_field(){
        $custom_fields = SmCustomField::where('form_name','staff_registration')->where('school_id',Auth::user()->school_id)->orderby('id','DESC')->get();
        return view('backEnd.customField.staffRegistration',compact('custom_fields'));
    }

    public function store_staff_registration_custom_field(Request $request){
        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'type' => 'required',
            'width'=> 'required',
            'name_value.*'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
            'name_value'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $error){
                Toastr::warning($error, 'Failed');
            }
            return redirect()->back()->withInput();
        }

        $exist = SmCustomField::where('form_name','staff_registration')
                                ->where('school_id',Auth::user()->school_id)
                                ->where('label',$request->label)->first();

            if($exist){
                Toastr::warning("Label Name Already Exist !", 'Warning');
                return redirect()->back()->withInput();
            }

        try{
            $name = "staff_registration";
            $this->storeData($request,$name);

            Toastr::success('Operation successful', 'Success');
            return redirect('staff-reg-custom-field');

        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit_staff_custom_field($id){
        $v_custom_field = SmCustomField::find($id);
        $custom_fields = SmCustomField::where('form_name','staff_registration')->where('school_id',Auth::user()->school_id)->get();
        return view('backEnd.customField.staffRegistration',compact('custom_fields','v_custom_field'));
    }

    public function update_staff_custom_field(Request $request){
        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'type' => 'required',
            'width'=> 'required',
            'name_value.*'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
            'name_value'=> 'required_if:type,radioInput|required_if:type,checkboxInput|required_if:type,dropdownInput',
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $error){
                Toastr::warning($error, 'Failed');
            }
            return redirect()->back()->withInput();
        }

        $valueExist = SmCustomField::where('id', '!=', $request->id)
                ->where('form_name','staff_registration')
                ->where('school_id',Auth::user()->school_id)
                ->where('label',$request->label)
                ->get();

        if(count($valueExist) > 0){
            Toastr::warning("Label Name Already Exist !", 'Warning');
            return redirect()->back()->withInput();
        }
        
        try{
            $name = "staff_registration";
            $this->updateData($request,$name);

            Toastr::success('Operation successful', 'Success');
            return redirect('staff-reg-custom-field');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete_staff_custom_field(Request $request)
    {
        try{
            $this->deleteData($request->id);

            Toastr::success('Operation successful', 'Success');
            return redirect('staff-reg-custom-field');
        }catch(Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

//Add, Update, Delete Data
    public static function storeData($request, $name) {
        $store= new SmCustomField();
        $store->form_name = $name;
        $store->label = $request->label;
        $store->type = $request->type;
        $store->min_max_length = json_encode($request->min_max_length);
        $store->min_max_value = json_encode($request->min_max_value);
        $store->name_value = json_encode($request->name_value);
        $store->width = $request->width;
        $store->required = $request->required;
        if(moduleStatusCheck('ParentRegistration')== TRUE) {/* added for online student registration custom field showing --abunayem */ 
            $store->is_showing = $request->is_showing_online_registration ?? 0;
        }
        $store->school_id = Auth::user()->school_id;
        $store->academic_id = getAcademicId();
        $store->save();
    }

    public static function updateData($request, $name) {
        $update = SmCustomField::find($request->id);
        $update->form_name = $name;
        $update->label = $request->label;
        $update->type = $request->type;
        $update->min_max_length = json_encode($request->min_max_length);
        $update->min_max_value = json_encode($request->min_max_value);
        $update->name_value = json_encode($request->name_value);
        $update->width = $request->width;
        $update->required = $request->required;
        if(moduleStatusCheck('ParentRegistration')== TRUE) {/* added for online student registration custom field showing --abunayem */ 
            $update->is_showing = $request->is_showing_online_registration;
        }

        $update->school_id = Auth::user()->school_id;
        $update->academic_id = getAcademicId();
        $update->update();
    }

    private function deleteData($id){
        SmCustomField::find($id)->delete();
    }
}
