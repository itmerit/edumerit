<?php

namespace App\Http\Controllers;

use App\ApiBaseMethod;
use App\Imports\StudentAttendanceImport;
use App\SmAssignSubject;
use App\SmClass;
use App\SmClassSection;
use App\SmSection;
use App\SmStaff;
use App\SmStudent;
use App\SmStudentAttendance;
use App\StudentAttendanceBulk;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SmStudentAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {
        try {
            if (teacherAccess()) {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->distinct('sm_classes.id')
                    ->get();
            } else {
                $classes = SmClass::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            }
            return view('backEnd.studentInformation.student_attendance', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'attendance_date' => 'required',
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
            $date = $request->attendance_date;
            if (teacherAccess()) {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->distinct('sm_classes.id')
                    ->get();
            } else {
                $classes = SmClass::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            }
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();

            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('student-attendance');
            }

            $already_assigned_students = [];
            $new_students = [];
            $attendance_type = "";
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if ($attendance != "") {
                    $already_assigned_students[] = $attendance;
                    $attendance_type = $attendance->attendance_type;
                } else {
                    $new_students[] = $student;
                }
            }
            $class_id = $request->class;
            $section_id = $request->section;
            $class_info = SmClass::find($request->class);
            $section_info = SmSection::find($request->section);

            $search_info['class_name'] = $class_info->class_name;
            $search_info['section_name'] = $section_info->section_name;
            $search_info['date'] = $request->attendance_date;

            $sections = SmClassSection::with('sectionName')->where('class_id', $class_id)->where('academic_id', getAcademicId())->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['date'] = $date;
                $data['class_id'] = $class_id;
                $data['already_assigned_students'] = $already_assigned_students;
                $data['new_students'] = $new_students;
                $data['attendance_type'] = $attendance_type;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_attendance', compact('classes', 'sections', 'date', 'class_id', 'section_id', 'date', 'already_assigned_students', 'new_students', 'attendance_type', 'search_info'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAttendanceStore(Request $request)
    {
        $attendance = SmStudentAttendance::where('student_id', $request->student_id)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))->first();
        try {
            foreach ($request->id as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student)->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                    ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->first();

                if ($attendance) {
                    $attendance->delete();
                }

                $attendance = new SmStudentAttendance();
                $attendance->student_id = $student;
                if (isset($request->mark_holiday)) {
                    $attendance->attendance_type = "H";
                } else {
                    $attendance->attendance_type = $request->attendance[$student];
                    $attendance->notes = $request->note[$student];
                }
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->school_id = Auth::user()->school_id;
                $attendance->academic_id = getAcademicId();
                $attendance->save();

                if ($request->attendance[$student] == 'P') {
                    $student_info = SmStudent::find($student);
                    $compact['attendance_date'] = $attendance->attendance_date;
                    $compact['user_email'] = $student_info->email;
                    $compact['student_id'] = $student_info;
                    @send_sms($student_info->mobile, 'student_attendance', $compact);

                    $compact['user_email'] = @$student_info->parents->guardians_email;
                    @send_sms(@$student_info->parents->guardians_mobile, 'student_attendance_for_parent', $compact);

                } elseif ($request->attendance[$student] == 'A') {
                    

                } elseif ($request->attendance[$student] == 'L') {
                    
                }
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('student-attendance');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAttendanceHoliday(Request $request)
    {
        $students = SmStudent::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('active_status', 1)->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();
        if ($students->isEmpty()) {
            Toastr::error('No Result Found', 'Failed');
            return redirect('student-attendance');
        }

        if ($request->purpose == "mark") {

            foreach ($students as $student) {

                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();

                    $attendance = new SmStudentAttendance();
                    $attendance->attendance_type = "H";
                    $attendance->notes = "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $student->id;
                    $attendance->academic_id = getAcademicId();
                    $attendance->school_id = Auth::user()->school_id;
                    $attendance->save();
                } else {
                    $attendance = new SmStudentAttendance();
                    $attendance->attendance_type = "H";
                    $attendance->notes = "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $student->id;
                    $attendance->academic_id = getAcademicId();
                    $attendance->school_id = Auth::user()->school_id;
                    $attendance->save();
                }
            }
        } elseif ($request->purpose == "unmark") {
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();
                }
            }
        }

        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }

    public function studentAttendanceImport()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.studentInformation.student_attendance_import', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function downloadStudentAtendanceFile()
    {

        try {
            $studentsArray = ['admission_no', 'class_id', 'section_id', 'attendance_date', 'in_time', 'out_time'];

            return Excel::create('student_attendance_sheet', function ($excel) use ($studentsArray) {
                $excel->sheet('student_attendance_sheet', function ($sheet) use ($studentsArray) {
                    $sheet->fromArray($studentsArray);
                });
            })->download('xlsx');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function studentAttendanceBulkStore(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required',
            'file' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);
        $file_type = strtolower($request->file->getClientOriginalExtension());
        if ($file_type != 'csv' && $file_type != 'xlsx' && $file_type != 'xls') {
            Toastr::warning('The file must be a file of type: xlsx, csv or xls', 'Warning');
            return redirect()->back();
        } else {
            try {
                $max_admission_id = SmStudent::where('school_id', Auth::user()->school_id)->max('admission_no');
                $path = $request->file('file')->getRealPath();

                Excel::import(new StudentAttendanceImport($request->class, $request->section), $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
                $data = StudentAttendanceBulk::get();

                if (!empty($data)) {
                    $class_sections = [];
                    foreach ($data as $key => $value) {
                        if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                            $class_sections[] = $value->class_id . '-' . $value->section_id;
                        }
                    }
                    DB::beginTransaction();

                    $all_student_ids = [];
                    $present_students = [];
                    foreach (array_unique($class_sections) as $value) {

                        $class_section = explode('-', $value);
                        $students = SmStudent::where('class_id', $class_section[0])->where('section_id', $class_section[1])->where('school_id', Auth::user()->school_id)->get();

                        foreach ($students as $student) {
                            StudentAttendanceBulk::where('student_id', $student->id)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                                ->delete();
                            $all_student_ids[] = $student->id;
                        }

                    }

                    try {
                        foreach ($data as $key => $value) {
                            if ($value != "") {

                                if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                                    $student = SmStudent::select('id')->where('id', $value->student_id)->where('school_id', Auth::user()->school_id)->first();

                                    // return $student;

                                    if ($student != "") {
                                        // SmStudentAttendance
                                        $attendance_check = SmStudentAttendance::where('student_id', $student->id)
                                            ->where('attendance_date', date('Y-m-d', strtotime($value->attendance_date)))->first();
                                        if ($attendance_check) {
                                            $attendance_check->delete();
                                        }
                                        $present_students[] = $student->id;
                                        $import = new SmStudentAttendance();
                                        $import->student_id = $student->id;
                                        $import->attendance_date = date('Y-m-d', strtotime($value->attendance_date));
                                        $import->attendance_type = $value->attendance_type;
                                        $import->notes = $value->note;
                                        $import->school_id = Auth::user()->school_id;
                                        $import->academic_id = getAcademicId();
                                        $import->save();
                                    }
                                } else {
                                    // Toastr::error('Attendance Date not Matched', 'Failed');
                                    $bulk = StudentAttendanceBulk::where('student_id', $value->student_id)->delete();
                                }

                            }

                        }

                        // foreach ($all_student_ids as $all_student_id) {
                        //     if(!in_array($all_student_id, $present_students)){
                        //         $attendance_check=SmStudentAttendance::where('student_id',$all_student_id)->where('attendance_date',date('Y-m-d', strtotime($value->attendance_date)))->first();
                        //         if ($attendance_check) {
                        //            $attendance_check->delete();
                        //         }
                        //         $import = new SmStudentAttendance();
                        //         $import->student_id = $all_student_id;
                        //         $import->attendance_type = 'A';
                        //         $import->in_time = '';
                        //         $import->out_time = '';
                        //         $import->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                        //         $import->school_id = Auth::user()->school_id;
                        //         $import->academic_id = getAcademicId();
                        //         $import->save();

                        //         $bulk= StudentAttendanceBulk::where('student_id',$all_student_id)->delete();
                        //     }
                        // }

                    } catch (\Exception $e) {
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }
}
