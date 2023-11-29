<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\SmClass;
use App\SmStudent;
use App\SmAcademicYear;
use App\Traits\CustomFields;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Traits\DatabaseTableTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentRecordTemporary;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;

class StudentMultiRecordController extends Controller
{
    use DatabaseTableTrait;
    public function multiRecord(Request $request)
    {
        $students  = null;
        $data = [];
        
        if(!empty($request->all())) {
            $record_student_ids  = StudentRecord::when($request->class_id, function($q) use($request) {
                $q->where('class_id', $request->class_id);
            })->when($request->section_id, function($q) use($request){
                $q->where('section_id', $request->section_id);
            })->when($request->academic_year, function($q) use($request){
                $q->where('session_id', $request->academic_year);
            }, function($q){
                $q->where('session_id', getAcademicId());
            })->when($request->student, function ($q) use ($request) {
                $q->where('student_id', $request->student);
            })->pluck('student_id')->toArray();

         
          $students = SmStudent::whereIn('id', $record_student_ids)->where('active_status', 1)->get();
        }       
        $selected['student_id'] = $request->student;
        $selected['academic_year'] = $request->academic_year;
        $selected['class_id'] = $request->class_id;
        $selected['section_id'] = $request->section_id;
        
        $sessions = SmAcademicYear::where('school_id', auth()->user()->school_id)->get();       
        $classes = SmClass::get();       
        return view('backEnd.studentInformation.multi_class_student', compact('sessions', 'students', 'classes', 'data', 'selected'));
    }
    public function studentMultiRecord($student_id)
    {    
        $classes = SmClass::get();
        $student = SmStudent::findOrFail($student_id);
        return view('backEnd.studentInformation.inc._multiple_class_record', compact('student', 'classes'));
    }
    public function multiRecordStore(Request $request)
    {
       
        try {
            $class_list = [];
            $section_list = [];
            $default_id = $request->default ? (int)$request->default : null;
           if($request->new_record)  {   
                foreach($request->new_record as $row_id => $newRecord) {
                    $is_default = ($row_id == $default_id) ? 1 : null;
                    $class = isset($newRecord['class'][0]) ? $newRecord['class'][0] : null;
                    $section = isset($newRecord['section'][0]) ? $newRecord['section'][0] : null;
                    if(is_null($class) || is_null($section)){
                        $status = false;
                        $message = __('student.Record info updated Failed');
                        return response()->json(['status'=>$status, 'message'=>$message]);
                    }
                    $roll_number = isset($newRecord['roll_number'][0]) ? $newRecord['roll_number'][0] : null;
                    $request = $request->merge([
                                        'class'=>$class,
                                        'section'=>$section,
                                        'roll_number'=>$roll_number,
                                        'is_default' => $is_default
                                    ]);
                    $checkExit = $this->checkExitRecord($request);
                    $checkExitRollNumber = $this->checkExitRollNumber($request);
                    
                    if($class && $section && !$checkExit && !$checkExitRollNumber) {                 
                        $this->insertStudentRecord($request);
                    }
                }
            }
            if($request->old_record)  { 
                foreach($request->old_record as $record_id => $oldRecord) {
                $is_default = $record_id == $default_id ? 1 : null;
                $class = isset($oldRecord['class'][0]) ? $oldRecord['class'][0] : null;
                $section = isset($oldRecord['section'][0]) ? $oldRecord['section'][0] : null;
                $roll_number = isset($oldRecord['roll_number'][0]) ? $oldRecord['roll_number'][0] : null;
                $request = $request->merge([
                                        'class'=>$class,
                                        'section'=>$section,
                                        'record_id'=>$record_id,
                                        'roll_number'=>$roll_number,
                                        'is_default' => $is_default
                                    ]);
                            
                $checkExit = $this->checkExitRecord($request);
                $checkExitRollNumber = $this->checkExitRollNumber($request);
                if($class && $section && !$checkExit && !$checkExitRollNumber) {                  
                        $this->insertStudentRecord($request);
                    }
                }
                if($request->old_record)  { 
                    foreach($request->old_record as $record_id => $oldRecord) {
                        $is_default = ($record_id == $default_id) ? 1 : null;
                    
                        $class = isset($oldRecord['class'][0]) ? $oldRecord['class'][0] : null;
                        $section = isset($oldRecord['section'][0]) ? $oldRecord['section'][0] : null;
                        $roll_number = isset($oldRecord['roll_number'][0]) ? $oldRecord['roll_number'][0] : null;
                        $request = $request->merge([
                                                'class'=>$class,
                                                'section'=>$section,
                                                'record_id'=>$record_id,
                                                'roll_number'=>$roll_number,
                                                'is_default' => $is_default
                                            ]);
                                    
                        $checkExit = $this->checkExitRecord($request);
                        
                        $checkExitRollNumber = $this->checkExitRollNumber($request);
                        if($class && $section && !$checkExit && !$checkExitRollNumber) {                  
                                $this->insertStudentRecord($request);
                        }
                        if ($checkExit) {
                            array_push($class_list, $checkExit->class->class_name);                        
                            array_push($section_list, $checkExit->section->section_name);
                        }
                    }
                
                }     
                $validation = '';
                if(!empty($class_list && $section_list))
                {
                    $validation = implode(',', $class_list).' & '. implode(',', $section_list).' ' . __('student.Record already exit ,please Delete or restore');
                }
                $status = true;
                $message = __('student.Record info updated');
                return response()->json(['status'=>$status, 'message'=>$message, 'validation'=>$validation]);
            }
        } catch (\Throwable $th) {
            $status = false;
            $message = __('student.Record info updated Failed');
            return response()->json(['status'=>$status, 'message'=>$th->getMessage()]);
        }

    }
    public function insertStudentRecord($request, $pre_record = null)
    {
        if ($request->is_default != null) {
            StudentRecord::when(moduleStatusCheck('University'), function ($query) {
                $query->where('un_academic_id', getAcademicId());
            }, function ($query) {
                $query->where('academic_id', getAcademicId());
            })->where('student_id', $request->student_id)
            ->where('school_id', auth()->user()->school_id)->update([
                'is_default' => 0,
            ]);
        }
        if (generalSetting()->multiple_roll == 0 && $request->roll_number) {
           
            StudentRecord::where('student_id', $request->student_id)
            ->where('school_id', auth()->user()->school_id)
            ->when(moduleStatusCheck('University'), function ($query) {
                $query->where('un_academic_id', getAcademicId());
            }, function ($query) {
                $query->where('academic_id', getAcademicId());
            })->update([
                    'roll_no' => $request->roll_number,
                ]);
        } 
       
        if ($request->record_id) {
            $studentRecord = StudentRecord::with('studentDetail')->find($request->record_id);
            $groups = \Modules\Chat\Entities\Group::where([
                'class_id' => $studentRecord->class_id,
                'section_id' => $studentRecord->section_id,
                'academic_id' => $studentRecord->academic_id,
                'school_id' => $studentRecord->school_id
                ])->get();
            if($studentRecord->studentDetail){
                $user = $studentRecord->studentDetail->user;
                if($user){
                    foreach($groups as $group){
                        removeGroupUser($group, $user->id);
                    }
                }
            }
        } else {
            $studentRecord = new StudentRecord;
        }

        $studentRecord->student_id = $request->student_id;
        if ($request->roll_number) {
            $studentRecord->roll_no = $request->roll_number;
        }
        $studentRecord->is_promote = $request->is_promote ?? 0;
        if($request->is_default != null){
            $studentRecord->is_default = $request->is_default;
        }

        if (moduleStatusCheck('Lead') == true) {
            $studentRecord->lead_id = $request->lead_id;
        }
        if (moduleStatusCheck('University')) {
            $studentRecord->un_academic_id = $request->un_academic_id;
            $studentRecord->un_section_id = $request->un_section_id;
            $studentRecord->un_session_id = $request->un_session_id;
            $studentRecord->un_department_id = $request->un_department_id;
            $studentRecord->un_faculty_id = $request->un_faculty_id;
            $studentRecord->un_semester_id = $request->un_semester_id;
            $studentRecord->un_semester_label_id = $request->un_semester_label_id;
        } else {
            $studentRecord->class_id = $request->class;
            $studentRecord->section_id = $request->section;
            $studentRecord->session_id = $request->session ?? getAcademicId();
        }
        $studentRecord->school_id = Auth::user()->school_id;
        $studentRecord->academic_id = $request->session ?? getAcademicId();
        $studentRecord->save();
       
        if (moduleStatusCheck('University')) {
            $this->assignSubjectStudent($studentRecord, $pre_record);
        }
        if(directFees()){
            $this->assignDirectFees($studentRecord->id, $studentRecord->class_id, $studentRecord->section_id,null);
        }

        $groups = \Modules\Chat\Entities\Group::where([
            'class_id' => $request->class,
            'section_id' => $request->section,
            'academic_id' => $request->session,
            'school_id' => auth()->user()->school_id
            ])->get();
        $student = SmStudent::where('school_id', auth()->user()->school_id)->find($request->student_id);
        if($student){
            $user = $student->user;
            foreach($groups as $group){
                createGroupUser($group, $user->id, 2, auth()->id());
            }
        }
    }
    public function restoreStudentRecord(int $record_id)
    {
        try {
            $record = StudentRecord::find($record_id);           
            $this->deleteRecordCondition( $record->student_id, $record->id, 'restore');            
            Toastr::success('Operation Successful', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage());
            return redirect()->back();
        }
    }
    public function studentRecordDelete(Request $request)
    {
        
        try {
            $record_id = $request->record_id;
            $student_id = $request->student_id;
            if ($record_id && $student_id) {
                $this->deleteRecordCondition($student_id, $record_id, 'disable');
            }
            $status = true;
            $message = __('student.Record Remove Successfully');
            return response()->json(['status'=>$status, 'message'=>$message]);
        } catch (\Throwable $th) {
            $status = false;
            $message = $th->getMessage();
            return response()->json(['status'=>$status, 'message'=>$message]);
        }
       
    }
    public function studentRecordDeletePermanently(Request $request)
    {
        try {
            $record = StudentRecord::find($request->id);           
            $this->deleteRecordCondition( $record->student_id, $record->id, 'delete');
            $record->delete();
            Toastr::success('Operation Successful', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage());
            return redirect()->back();
        }
        
    }
    public function deleteRecordCondition(int $student_id, int $record_id, string $type = 'disable')
    {
        $tableWithRecordIdActiveStatus = $this->tableWithRecordIdActiveStatus();
        if ($type =='delete') {
            foreach($tableWithRecordIdActiveStatus as $table) {
                if ((Schema::hasColumn($table, 'record_id'))) {
                    $model = DB::table($table)->where('record_id', $record_id)->where('school_id', auth()->user()->school_id)->limit(1);
                }
                if ((Schema::hasColumn($table, 'student_record_id'))) {
                    $model = DB::table($table)->where('student_record_id', $record_id)->where('school_id', auth()->user()->school_id)->limit(1);
                }
            
                if($model) {
                    // dump($model);
                    if ($type =='delete') {
               
                        $model->Delete();
                    } else if($type == 'disable') {
                        $model->update([
                            'active_status'=>0
                        ]);
                    } else if($type == 'restore') {
                        $model->update([
                            'active_status'=>1
                        ]);
                    }
                }
            }
        }
        if($type == 'disable') {

            $recordTemporary = StudentRecordTemporary::updateOrCreate([
                'sm_student_id'=>$student_id,
                'student_record_id'=>$record_id,
            ]);
            $recordTemporary->user_id = auth()->user()->id;
            $recordTemporary->school_id = auth()->user()->school_id;
            $recordTemporary->save();

            StudentRecord::where('student_id', $student_id)->where('id', $record_id)->update([
                'active_status'=>0
            ]);
        }
        if ($type =='delete' || $type == 'restore') {
            $model = StudentRecordTemporary::where('sm_student_id', $student_id)->where('student_record_id', $record_id)->first();
            if ($model) {
                $model->delete();
            }
            if($type == 'restore') {
                StudentRecord::where('student_id', $student_id)->where('id', $record_id)->update([
                    'active_status'=>1
                ]);
            }
        }
             
       
    }
    public function deleteStudentRecord(Request $request)
    {
        $studentRecords = StudentRecord::with('studentDetail')
        ->where('active_status', 0)
        ->where('school_id', auth()->user()->school_id)
        ->where('academic_id', getAcademicId())->get();
        return view("backEnd.studentInformation.back_up_student_record", compact('studentRecords'));
    }
    private function checkExitRecord(Request $request)
    {      
     
        $exit = StudentRecord::where('class_id', $request->class)
                    ->where('section_id', $request->section)                  
                    ->where('student_id', $request->student_id)
                    ->when($request->record_id, function($q) use($request) {
                        $q->where('id', '!=', $request->record_id);
                    })
                    ->where('school_id', auth()->user()->school_id)
                    ->first();
       
        if ($exit) {            
            return $exit;
        }
        return false;            
    }
    private function checkExitRollNumber(Request $request) : bool
    {
        if(!$request->roll_number && generalSetting()->multiple_roll == 0) return false;

        $roll_number =  StudentRecord::where('class_id', $request->class)
        ->where('section_id', $request->section)                  
        ->when($request->roll_number, function($q) use($request) {
            $q->where('roll_no', $request->roll_number);
        })->where('school_id', auth()->user()->school_id)
        ->first(); 
        if($roll_number) {
            return true;
        }
        return false;
    }
}
