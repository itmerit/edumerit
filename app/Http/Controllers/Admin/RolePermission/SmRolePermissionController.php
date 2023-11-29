<?php

namespace App\Http\Controllers\Admin\RolePermission;
use App\Role;
use App\SmModule;
use App\ApiBaseMethod;
use App\SmRolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;

class SmRolePermissionController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}



    public function assignPermission(Request $request,$id){

		try{
			// $role = InfixRole::find($id);
			 if (checkAdmin()) {
				$role = InfixRole::find($id);
			}else{
				$role = InfixRole::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
			}
			$modulesRole = SmModule::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
			$role_permissions = SmRolePermission::where('role_id', $id)->where('school_id',Auth::user()->school_id)->get();
			$already_assigned = [];
			foreach($role_permissions as $role_permission){
				$already_assigned[] = $role_permission->module_link_id;
			}

			if (ApiBaseMethod::checkUrl($request->fullUrl())) {
				$data = [];
				$data['role'] = $role;
				$data['modules'] = $modulesRole->toArray();
				$data['already_assigned'] = $already_assigned;
				return ApiBaseMethod::sendResponse($data, null);
			}
			return view('backEnd.systemSettings.role.assign_role_permission', compact('role', 'modulesRole', 'already_assigned'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }


    public function rolePermissionStore(Request $request){
		try{
			
			// SmRolePermission::where('role_id', $request->role_id)->delete();
			 if (checkAdmin()) {
				SmRolePermission::where('role_id', $request->role_id)->delete();
			}else{
				SmRolePermission::where('role_id', $request->role_id)->where('school_id',Auth::user()->school_id)->delete();
			}

			if(isset($request->permissions)){
				foreach($request->permissions as $permission){
					$role_permission = new SmRolePermission();
					$role_permission->role_id = $request->role_id;
					$role_permission->module_link_id = $permission;
					$role_permission->school_id = Auth::user()->school_id;
					$role_permission->save();
				}
			}
			if (ApiBaseMethod::checkUrl($request->fullUrl())) {
				return ApiBaseMethod::sendResponse(null, 'Role permission has been assigned successfully');
			}
			Toastr::success('Role permission has been assigned successfully', 'Success');
            return redirect()->back();
			// return redirect('role')->with('message-success-delete', 'Role permission has been assigned successfully');
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }
}
