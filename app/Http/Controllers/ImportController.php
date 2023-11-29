<?php

namespace App\Http\Controllers;

use App\User;
use App\SmStaff;
use App\SmBaseSetup;
use App\SmDesignation;
use App\SmHumanDepartment;
use App\Imports\StaffsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StaffImportBulkTemporary;
use App\Http\Requests\StaffImportRequestForm;
use Modules\RolePermission\Entities\InfixRole;
use App\Http\Controllers\Admin\Hr\SmStaffController;

class ImportController extends Controller
{
    public function index()
    {
        $data['genders'] = SmBaseSetup::where('base_group_id', '=', '1')->get(['id', 'base_setup_name']);
        $data['roles'] = InfixRole::where('is_saas', 0)
            ->where('active_status', 1)
            ->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })
            ->whereNotIn('id', [1, 2, 3])
            ->orderBy('name', 'asc')
            ->get();

        $data['departments'] = SmHumanDepartment::where('is_saas', 0)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $data['designations'] = SmDesignation::where('is_saas', 0)
            ->orderBy('title', 'asc')
            ->get(['id', 'title']);

        return view('backEnd.humanResource.import_staff', $data);
    }
    public function staffStore(StaffImportRequestForm $request)
    {
        try {
            DB::beginTransaction();
            Excel::import(new StaffsImport, $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
            $bulk_staffs = StaffImportBulkTemporary::where('user_id', Auth::user()->id)->get();
            if (!empty($bulk_staffs)) {
                foreach ($bulk_staffs as $key => $singleStaff) {

                    if (isSubscriptionEnabled()) {

                        $active_staff = SmStaff::where('school_id', Auth::user()->school_id)->where('active_status', 1)->count();

                        if (\Modules\Saas\Entities\SmPackagePlan::staff_limit() <= $active_staff && saasDomain() != 'school') {
                            DB::commit();
                            StaffImportBulkTemporary::where('user_id', Auth::user()->id)->delete();
                            Toastr::error('Your Staff limit has been crossed.', 'Failed');
                            return redirect()->route('staff_directory');

                        }
                    }
                    $role_id = InfixRole::where('name', $singleStaff->role)->where('school_id', auth()->user()->school_id)->value('id') ?? null;
                   
                    $department_id = SmHumanDepartment::where('name', $singleStaff->department)->value('id') ?? null;
                    $designation_id = SmDesignation::where('title', $singleStaff->designation)->value('id') ?? null;

                    if ($this->checkExitUser($singleStaff->mobile, $singleStaff->email) || !$role_id) {
                        continue;
                    }

                    $user = new User();
                    $user->role_id = $role_id == 1 ? 5 : $role_id;
                    $user->username = $singleStaff->mobile ? $singleStaff->mobile : $singleStaff->email;
                    $user->email = $singleStaff->email;
                    $user->phone_number = $singleStaff->mobile;
                    $user->full_name = $singleStaff->first_name . ' ' . $singleStaff->last_name;
                    $user->password = Hash::make(123456);
                    $user->school_id = Auth::user()->school_id;
                    $user->save();

                    if ($role_id == 5) {
                        $this->assignChatGroup($user);
                    }

                    if ($user) {
                        $basic_salary = $singleStaff->basic_salary ?? 0;
                        
                        $staff = new SmStaff();
                        $staff->staff_no = $singleStaff->staff_no;
                        $staff->role_id =$role_id == 1 ? 5 : $role_id;
                        $staff->department_id = $department_id;
                        $staff->designation_id = $designation_id;
                        $staff->first_name = $singleStaff->first_name;
                        $staff->last_name = $singleStaff->last_name;
                        $staff->full_name = $singleStaff->first_name . ' ' . $singleStaff->last_name;
                        $staff->fathers_name = $singleStaff->fathers_name;
                        $staff->mothers_name = $singleStaff->mothers_name;
                        $staff->email = $singleStaff->email;
                        $staff->school_id = Auth::user()->school_id;
                        $staff->gender_id = $singleStaff->gender_id;
                        $staff->marital_status = $singleStaff->marital_status;
                        $staff->date_of_birth = date('Y-m-d', strtotime($singleStaff->date_of_birth));
                        $staff->date_of_joining = date('Y-m-d', strtotime($singleStaff->date_of_joining));
                        $staff->mobile = $singleStaff->mobile ?? null;
                        $staff->emergency_mobile = $singleStaff->emergency_mobile;
                        $staff->current_address = $singleStaff->current_address;
                        $staff->permanent_address = $singleStaff->permanent_address;
                        $staff->qualification = $singleStaff->qualification;
                        $staff->experience = $singleStaff->experience;
                        $staff->epf_no = $singleStaff->epf_no;
                        $staff->basic_salary = $basic_salary;
                        $staff->contract_type = $singleStaff->contract_type;
                        $staff->location = $singleStaff->location;
                        $staff->bank_account_name = $singleStaff->bank_account_name;
                        $staff->bank_account_no = $singleStaff->bank_account_no;
                        $staff->bank_name = $singleStaff->bank_name;
                        $staff->bank_brach = $singleStaff->bank_brach;
                        $staff->facebook_url = $singleStaff->facebook_url;
                        $staff->twiteer_url = $singleStaff->twitter_url;
                        $staff->linkedin_url = $singleStaff->linkedin_url;
                        $staff->instragram_url = $singleStaff->instagram_url;
                        $staff->user_id = $user->id;
                        $staff->driving_license = $singleStaff->driving_license;
                        $staff->save();
                    }

                }

                StaffImportBulkTemporary::where('user_id', Auth::user()->id)->delete();
                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect()->route('staff_directory');
            }

        } catch (\Throwable $th) {
        
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->route('staff_directory');
        }
    }
    private function checkExitUser($phone_number = null, $email = null):bool
    {
        $user = User::when($phone_number && !$email, function ($q) use ($phone_number) {
            $q->where('phone_number', $phone_number)->orWhere('username', $phone_number);
        })
        ->when($email && !$phone_number, function ($q) use ($email) {
            $q->where('email', $email)->orWhere('username', $email);
        })
        ->when($email && $phone_number, function ($q) use ($phone_number) {
            $q->where('phone_number', $phone_number);
        })
        ->first();
        if($user) {
            return true;
        }
        return false;
    }
    private function assignChatGroup($user)
    {
        $groups = \Modules\Chat\Entities\Group::where('school_id', auth()->user()->school_id)->get();
        foreach($groups as $group){
            createGroupUser($group, $user->id);
        }
    }
}
