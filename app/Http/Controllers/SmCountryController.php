<?php

namespace App\Http\Controllers;

use App\SmCountry;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class SmCountryController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index()
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function create()
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function show()
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function edit()
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function destroy()
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
}
