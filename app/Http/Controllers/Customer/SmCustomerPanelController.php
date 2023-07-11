<?php

namespace App\Http\Controllers\Customer;

use App\SmStaff;
use App\SmProductPurchase;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmCustomerPanelController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function customerDashboard()
    {
        $id = Auth::user()->id;
        $staffDetails = SmStaff::where('user_id', $id)->get();
        try {
            return view('backEnd.customerPanel.customer_dashboard', compact('staffDetails'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function customerPurchases()
    {
        try {
            $id = Auth::user()->id;
            $customerDetails = SmStaff::where('user_id', $id)->get();
            $ProductPurchase = SmProductPurchase::where('user_id', $id)->get();
            return view('backEnd.customerPanel.customer_purchase', compact('customerDetails', 'ProductPurchase'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
