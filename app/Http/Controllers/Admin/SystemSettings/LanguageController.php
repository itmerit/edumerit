<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\Language;
use App\tableList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\GeneralSettings\SmLanguageRequest;

class LanguageController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index()
    {
        $languages=Language::where('school_id',Auth::user()->school_id)->get();
        return view('backEnd.systemSettings.language',compact('languages'));
    }

    public function create()
    {
        //
    }

    public function store(SmLanguageRequest $request)
    {
  
        try {
            $s = new Language();
            $s->name = $request->name;
            $s->code = $request->code;
            $s->native = $request->native;
            $s->rtl = $request->rtl;
            $s->school_id = Auth::user()->school_id;
            $s->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('language-list');
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect('language-list');
        }
    }


    public function show($id)
    {

        try {
            $languages=Language::where('school_id',Auth::user()->school_id)->get();
            $editData = $languages->where('id',$id)->first();
            return view('backEnd.systemSettings.language',compact('languages','editData'));

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(SmLanguageRequest $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            $s = Language::findOrfail($request->id);
            $s->name = $request->name;
            $s->code = $request->code;
            $s->native = $request->native;
            $s->rtl = $request->rtl;
            $s->school_id = Auth::user()->school_id;
            $s->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('language-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('language-list');
        }
    }


    public function destroy($id)
    {
        try {
            $tables = tableList::getTableList('lang_id', $id);
            if (empty($tables)) {
                $s = Language::findOrfail($id);
                $s->delete();
                Toastr::success('Operation successful', 'Success');
                return redirect('language-list');
            }else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
				Toastr::error($msg, 'Failed');
				return redirect()->back();
            }

            
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('language-list');
        }
    }
}