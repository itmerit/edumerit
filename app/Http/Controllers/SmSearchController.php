<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;

class SmSearchController extends Controller
{
  public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}
  function search(Request $r){
        try{
          if($r->ajax())
          {
           $output = '';
           $query = $r->get('search');
           if($query != '')
           {
            $permissionIds = Permission::where('name', 'like', '%' . $query . '%')
            ->roleWise()->where('is_menu', 1)->where('permission_section', 0)
             ->pluck('id')->toArray();
             return Sidebar::where('user_id', auth()->user()->id)
             ->whereIn('permission_id', $permissionIds)->get()->map(function($value) {
               return [
                 'name'=>$value->permissionInfo->name,
                 'route'=>$value->permissionInfo->route ? $value->permissionInfo->route : null,
               ];
             });
          }
          else {
              return response()->json(['not found'=>'Not Foound'], 404);

            }

          }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

}
