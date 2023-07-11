<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Envato\Envato;
use App\SmGeneralSettings;
use Brian2694\Toastr\Facades\Toastr;
use HP;

class VerifyController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
			$o = Envato::verifyPurchase(HP::set()->purchasecode);
            if(isset($o['item']) && $o['item']['id'] == "23876323" && $o['buyer'] == HP::set()->envatouser){
                return redirect('/');
            }else{
                return view('verifycode');
            }
		}catch (\Exception $e) {
		      Toastr::error('Operation Failed', 'Failed');
		       return redirect()->back();
		}

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePurchasecode(Request $request, $id)
    {
        try{
			$settings                       =   SmGeneralSettings::find($id);
            $settings->envato_user          =   $request->envatouser;
            $settings->system_purchase_code =   $request->purchasecode;
            $settings->save();
            return redirect('/dashboard/')->with('success', 'Purchase Code Verified');
		}catch (\Exception $e) {
		      Toastr::error('Operation Failed', 'Failed');
		       return redirect()->back();
		}


    }

}
