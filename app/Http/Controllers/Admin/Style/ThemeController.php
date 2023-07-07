<?php

namespace App\Http\Controllers\Admin\Style;

use App\SmStyle;
use Carbon\Carbon;
use App\Models\Color;
use App\Models\Theme;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\Style\ThemeFormRequest;

class ThemeController extends Controller
{

    public function index()
    {

        try {
            $themes = Theme::with('colors')->get();
            return view('backEnd.style.color_theme', compact('themes'));
        } catch (\Exception$e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function create()
    {          
        $data['colors'] = Color::where('is_color', 1)->where('status', 1)->get();
        return view('backEnd.style.create_theme', $data);
    }
    public function store(ThemeFormRequest $request)
    {
        // return $request->all();
        try {
            $color_format = [];

            foreach ($request->color as $key => $color) {
                $color_format[$key] = ['value' => $color];
            }

            if ($request->is_default) {
                Theme::where('is_default', 1)->where('created_by', auth()->user()->id)->update(['is_default' => 0]);
            }

            $theme = new Theme;
            $theme->title = $request->title;
            $theme->replicate_theme = color_theme()->title ?? null;
            $theme->path_main_style = color_theme()->path_main_style ?? null;
            $theme->path_infix_style = color_theme()->path_infix_style ?? null;
            $theme->color_mode = $request->color_mode ?? 'gradient';
            $theme->box_shadow = $request->box_shadow ?? 0;
            $theme->background_type = $request->background_type ?? 'image';
            $theme->background_image = fileUpload($request->background_image, 'public/backEnd/img/');
            $theme->background_color = $request->background_color;
            $theme->is_default = $request->is_default ? 1 : 0;
            $theme->created_by = auth()->user()->id;
            $theme->school_id = auth()->user()->school_id;
            $theme->save();

            $theme->colors()->sync($color_format);  
            Cache::forget('active_theme_school_' . Auth::user()->school_id);
            Cache::forget('active_theme_user_' . Auth::id());
                      
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function edit(Theme $theme)
    {
        // abort_if($theme->is_default, 404);
        $theme->load('colors');
        return view('backEnd.style.edit_theme', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ThemeFormRequest $request, Theme $theme)
    {
        
        try {
            $color_format = [];

            foreach ($request->color as $key => $color) {
                $color_format[$key] = ['value' => $color];
            }

            $theme->title = $request->title;
            $theme->box_shadow = $request->box_shadow ?? 0;
            if($request->filled('color_mode')) {
                $theme->color_mode = $request->color_mode;
            }
            $theme->background_type = $request->background_type;
            $theme->background_image = fileUpdate($theme->background_image, $request->background_image, 'public/backEnd/img/');
            $theme->background_color = $request->background_color;
            $theme->save();

            $theme->colors()->sync($color_format);
            $theme->refresh()->load('colors');

            if ($theme->is_default) {
                session()->put('color_theme', $theme);
            }
            Cache::forget('active_theme_school_' . Auth::user()->school_id);
            Cache::forget('active_theme_user_' . Auth::id());

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('color-style');
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Theme $theme)
    {
       
        try {
            if ($theme->is_default) {
                Toastr::error(__('style.You can not permitted to delete system theme'), __('common.Operation failed'));
                return redirect()->back();
            }
            $theme->delete();
            if ($theme->is_default) {
                Theme::first()->update(['is_default' => 1]);
            }
            Cache::forget('active_theme_school_' . Auth::user()->school_id);
            Cache::forget('active_theme_user_' . Auth::id());
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function copy(Theme $theme)
    {
        $theme->load('colors');
        $color_format = [];
        foreach ($theme->colors as $color) {
            $color_format[$color->id] = ['value' => $color->pivot->value];
        }

        $newTheme = $theme->replicate();
        $newTheme->title = __('style.Clone of') . ' ' . $theme->title;
        $newTheme->created_at = Carbon::now();
        $newTheme->is_default = false;
        $newTheme->is_system = false;
        $newTheme->created_by = Auth::id();
        $newTheme->save();

        $newTheme->colors()->sync($color_format);

        Toastr::success(__('style.Theme Cloned Successful'), __('common.success'));
        return redirect()->back();

    }

    function default(Theme $theme) {

        Theme::where('is_default', 1)->where('created_by', auth()->user()->id)->update(['is_default' => 0]);
        $theme->is_default = 1;
        $theme->save();

        session()->put('color_theme', $theme);
        Cache::forget('active_theme_school_' . Auth::user()->school_id);
        Cache::forget('active_theme_user_' . Auth::id());
        Toastr::success(__('style.Theme Set Default Successful'), __('common.success'));
        return redirect()->back();

    }

}
