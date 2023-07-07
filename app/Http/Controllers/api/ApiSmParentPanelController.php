<?php

namespace App\Http\Controllers\api;

use App\SmParent;
use App\SmStudent;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiSmParentPanelController extends Controller
{
    public function childInfo(Request $request, $user_id)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $user = SmStudent::with('parents', 'studentRecords')->where('user_id', $user_id)->first();
                $data = [];

                $data['user'] = $user->toArray();
                if ($user) {
                    $class_sec = [];
                    foreach ($user->studentRecords as $classSec) {
                        $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
                    }
                }
                $data['userDetails'] = [
                    'id' => $user->id,
                    'user_id' => $user->user_id,
                    'full_name' => $user->full_name,
                    'phone_number' => $user->phone_number,
                    'admission_no' => $user->admission_no,
                    'class_section' => $class_sec,
                    'father_name' => $user->parents->father_name,
                    'fathers_mobile' => $user->parents->fathers_mobile,
                    'mother_name' => $user->parents->mother_name,
                    'guardians_name' => $user->parents->guardians_name,
                    'guardians_mobile' => $user->parents->guardians_mobile,
                    'guardians_email' => $user->parents->guardians_email,
                    'mothers_mobile' => $user->parents->mothers_mobile,
                ];

                $data['religion'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.religion_id')
                    ->where('sm_students.id', $user->id)
                    ->first();

                $data['blood_group'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.bloodgroup_id')
                    ->where('sm_students.id', $user->id)
                    ->first();

                $data['transport'] = DB::table('sm_students')
                    ->select('sm_vehicles.vehicle_no', 'sm_vehicles.vehicle_model', 'sm_staffs.full_name as driver_name', 'sm_vehicles.note')
                    ->join('sm_vehicles', 'sm_vehicles.id', '=', 'sm_students.vechile_id')
                    ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
                    ->where('sm_students.id', $user->id)
                    ->first();

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_childInfo(Request $request, $school_id, $user_id)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $user =  SmStudent::with('parents', 'studentRecords')->where('user_id', $user_id)->where('school_id', $school_id)->first();
                $data = [];

                $data['user'] = @$user->toArray();
                if ($user) {
                    $class_sec = [];
                    foreach ($user->studentRecords as $classSec) {
                        $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
                    }
                }
                $data['userDetails'] = [
                    'id' => $user->id,
                    'user_id' => $user->user_id,
                    'full_name' => $user->full_name,
                    'phone_number' => $user->phone_number,
                    'admission_no' => $user->admission_no,
                    'class_section' => $class_sec,
                    'father_name' => $user->parents->father_name,
                    'fathers_mobile' => $user->parents->fathers_mobile,
                    'mother_name' => $user->parents->mother_name,
                    'guardians_name' => $user->parents->guardians_name,
                    'guardians_mobile' => $user->parents->guardians_mobile,
                    'guardians_email' => $user->parents->guardians_email,
                    'mothers_mobile' => $user->parents->mothers_mobile,
                ];

                $data['religion'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.religion_id')
                    ->where('sm_students.id', $user->id)
                    ->where('sm_students.school_id', $school_id)->first();

                $data['blood_group'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.bloodgroup_id')
                    ->where('sm_students.id', $user->id)
                    ->where('sm_students.school_id', $school_id)->first();

                $data['transport'] = DB::table('sm_students')
                    ->select('sm_vehicles.vehicle_no', 'sm_vehicles.vehicle_model', 'sm_staffs.full_name as driver_name', 'sm_vehicles.note')
                    ->join('sm_vehicles', 'sm_vehicles.id', '=', 'sm_students.vechile_id')
                    ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_students.vechile_id')
                    ->where('sm_students.id', $user->id)
                    ->where('sm_students.school_id', $school_id)->first();

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function childListApi(Request $request, $id)
    {

        $parent = SmParent::where('user_id', $id)->first();
        $students = SmStudent::where('parent_id', $parent->id)->get();
        $student_info=[];
        foreach ($students as $student) {
            $class_sec = [];
            foreach ($student->studentRecords as $classSec) {
                $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
            }
            $d['id']= $student->id;
            $d['user_id']= $student->user_id;
            $d['full_name']= $student->full_name;
            $d['photo'] = $student->student_photo;
            $d['phone_number']= $student->phone_number;
            $d['admission_no']= $student->admission_no;
            $d['class_section']= $class_sec;
            $d['father_name']= $student->parents->father_name;
            $d['fathers_mobile']= $student->parents->fathers_mobile;
            $d['mother_name']= $student->parents->mother_name;
            $d['guardians_name']= $student->parents->guardians_name;
            $d['guardians_mobile']= $student->parents->guardians_mobile;
            $d['guardians_email']= $student->parents->guardians_email;
            $d['mothers_mobile']= $student->parents->mothers_mobile;
            $student_info []=$d;
        }
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            return ApiBaseMethod::sendResponse($student_info, null);
        }
    }
    public function saas_childListApi(Request $request, $school_id, $id)
    {
        $data['student_info']=[];
        $parent = SmParent::where('user_id', $id)->where('school_id', $school_id)->first();
        $students = SmStudent::where('parent_id', $parent->id)->where('school_id', $school_id)->get();
        $student_info=[];
        foreach ($students as $student) {
            $class_sec = [];
            foreach ($student->studentRecords as $classSec) {
                $class_sec[] = $classSec->class->class_name . '(' . $classSec->section->section_name . '), ';
            }
            $d['id']= $student->id;
            $d['user_id']= $student->user_id;
            $d['full_name']= $student->full_name;
            $d['photo'] = $student->student_photo;
            $d['phone_number']= $student->phone_number;
            $d['admission_no']= $student->admission_no;
            $d['class_section']= $class_sec;
            $d['father_name']= $student->parents->father_name;
            $d['fathers_mobile']= $student->parents->fathers_mobile;
            $d['mother_name']= $student->parents->mother_name;
            $d['guardians_name']= $student->parents->guardians_name;
            $d['guardians_mobile']= $student->parents->guardians_mobile;
            $d['guardians_email']= $student->parents->guardians_email;
            $d['mothers_mobile']= $student->parents->mothers_mobile;
            $student_info []=$d;
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            return ApiBaseMethod::sendResponse($student_info, null);
        }
    }
}
