<?php

namespace App\Http\Controllers;

use App\SmRoomType;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmComplaintTypeController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index()
    {
        try {
            $room_types = SmRoomType::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.admin.complaint_type', compact('room_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
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
    public function show($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    public function destroy($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
}
