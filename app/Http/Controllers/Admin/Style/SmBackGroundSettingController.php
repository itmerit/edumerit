<?php

namespace App\Http\Controllers\Admin\Style;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Style\SmBackGroundSettingRequest;
use App\SmBackgroundSetting;
use App\SmSchool;
use App\SmStyle;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmBackGroundSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {
        try {
            $background_settings = SmBackgroundSetting::where('school_id', Auth::user()->school_id)->orderby('id', 'DESC')->get();
            return view('backEnd.style.background_setting', compact('background_settings'));
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmBackGroundSettingRequest $request)
    {

        try {

            $destination = 'public/uploads/backgroundImage/';
            $fileName = fileUpload($request->image, $destination);
            //changes for lead module
            if ($request->style == 1) {
                $title = 'Dashboard Background';
            } elseif ($request->style == 2) {
                $title = 'Login Background';
            } elseif ($request->style == 3) {
                $title = 'Lead Form Background';
            }
            //end

            $background_setting = new SmBackgroundSetting();
            $background_setting->is_default = 0;
            $background_setting->title = $title;
            $background_setting->type = $request->background_type;
            $background_setting->school_id = Auth::user()->school_id;
            if ($request->background_type == 'color') {
                $background_setting->color = $request->color;
            } else {
                $background_setting->image = $fileName;
            }
            $background_setting->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try {
            $background = SmBackgroundSetting::find($id);
            if ($background->is_default == 1 && $background->title == "Login Background") {
                SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
            } else if ($background->is_default == 1 && $background->title == "Dashboard Background") {
                SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
            } else if ($background->is_default == 0 && $background->title == "Login Background") {
                SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
            } else if ($background->is_default == 0 && $background->title == "Dashboard Background") {
                SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
            }
            //changes for lead form background -abunayem
            if (moduleStatusCheck('Lead')==true) {
                if ($background->is_default == 1 && $background->title == "Lead Form Background") {
                    SmBackgroundSetting::where([['is_default', 1], ['title', 'Lead Form Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                    $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
                } else if ($background->is_default == 0 && $background->title == "Lead Form Background") {
                    SmBackgroundSetting::where([['is_default', 1], ['title', 'Lead Form Background']])->where('school_id', Auth::user()->school_id)->update(['is_default' => 0]);
                    $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmBackGroundSettingRequest $request)
    {

        try {

            $destination = 'public/uploads/backgroundImage/';

            $background_setting = SmBackgroundSetting::find(1);
            $background_setting->type = $request->type;
            if ($request->type == 'color') {
                $background_setting->color = $request->color;
                $background_setting->image = '';
                if ($background_setting->image != "" && file_exists($background_setting->image)) {
                    unlink($background_setting->image);
                }
            } else {
                $background_setting->color = '';
                $background_setting->image = fileUpload($background_setting->image, $request->image, $destination);
            }

            $background_setting->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $delBGS = SmBackgroundSetting::where('id', $id)->where('is_default', 1)->first();
            if (empty($delBGS)) {
                SmBackgroundSetting::find($id)->delete();

                Toastr::success('Operation successful', 'Success');
                return redirect()->back();

            } else {
                Toastr::warning('You cannot delete default Background', 'Warning');
                return redirect()->back();
            }

        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function colorTheme()
    {
        try {
            $color_styles = SmStyle::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.color_theme', compact('color_styles'));
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function colorThemeSet($id)
    {
        try {

            SmStyle::where('is_active', 1)->where('school_id', Auth::user()->school_id)->update(['is_active' => 0]);
            $result = SmStyle::where('id', $id)->update(['is_active' => 1]);
            if ($result) {
                session()->forget('all_styles');
                $all_styles = SmStyle::where('school_id', 1)->where('active_status', 1)->get();
                session()->put('all_styles', $all_styles);

                session()->forget('active_style');
                $active_style = SmStyle::where('school_id', 1)->where('is_active', 1)->first();
                session()->put('active_style', $active_style);

                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
