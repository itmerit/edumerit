<?php

namespace App\Http\Controllers;
use App\SmNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmNotificationController extends Controller{
	public function __construct(){
        $this->middleware('PM');
        // User::checkAuth();
	}

	public function viewSingleNotification($id){
		try {

			$notification = SmNotification::find($id);
			$notification->is_read = 1;
			$notification->save();

			return redirect()->back();
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }


	public function viewAllNotification($id){
		try{
			$user = Auth()->user();
			if(Auth()->user()->role_id != 1){

				if($user->role_id == 2){
					SmNotification::where('user_id', Auth::user()->id)->where('role_id', 2)->update(['is_read' => 1]);
					Toastr::success('Operation successful', 'Success');
				}elseif($user->role_id == 3){
					SmNotification::where('user_id', Auth::user()->id)->where('role_id', '!=', 2)->update(['is_read' => 1]);
					Toastr::success('Operation successful', 'Success');
				}else{
					SmNotification::where('user_id', $user->id)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->update(['is_read' => 1]);
					Toastr::success('Operation successful', 'Success');
				}
			}else{
				SmNotification::where('user_id', $user->id)->where('role_id', 1)->update(['is_read' => 1]);
					Toastr::success('Operation successful', 'Success');
			}
			return redirect()->back();
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }

    public function udpateNotification($id){
        try {
            $notification = SmNotification::find($id);
            $notification->is_read = 1;
            $notification->save();
            $url = @$notification->url ? url($notification->url) : url('view/single/notification/'.$notification->id);
            return redirect(url($url));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
         return SmNotification::findOrFail($id);
    }

    public function viewNotice($id){
		Toastr::error('Operation Failed', 'Failed');
		return redirect()->back();
    }
}