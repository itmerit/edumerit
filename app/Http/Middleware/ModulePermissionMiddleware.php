<?php

namespace App\Http\Middleware;

use Closure;
use App\SmModulePermission;
use App\Models\SchoolModule;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ModulePermissionMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if ($user->school_id == 1 || isModuleForSchool($module)) {
            return $next($request);
        }
        Toastr::error('Module Not Active', 'Failed');
        return redirect()->route('dashboard');

    }
}
