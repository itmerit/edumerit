<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;


class SmCustomTemporaryResultController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}
    public function index()
    {
        try {
            return redirect('login');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
