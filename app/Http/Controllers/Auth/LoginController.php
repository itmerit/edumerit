<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\InfixModuleManager;
use App\Scopes\ActiveStatusSchoolScope;
use App\SmAcademicYear;
use App\SmBackgroundSetting;
use App\SmDateFormat;
use App\SmGeneralSettings;
use App\SmLanguage;
use App\SmSchool;
use App\SmStaff;
use App\SmsTemplate;
use App\SmStudent;
use App\SmStyle;
use App\SmUserLog;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\University\Entities\UnAcademicYear;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {

            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes()
        );
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username())) . '|' . $request->ip();
    }

    /**
     * Get the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }

    public function login(Request $request)
    {
        $school = app('school');
        $request->merge(['school_id' => $school->id]);
        $logged_in = false;
        $gs = SmGeneralSettings::where('school_id',$school->id)->first();
        session()->forget('generalSetting');
        session()->put('generalSetting', $gs);
        if ($school->id !=1 && $school->active_status !=1) {
            Toastr::error('Your Institution is not Active, Please contact with administrator.', 'Failed');
            return redirect()->route('login');
        }
        if (config('app.app_sync') && $request->auto_login){
            $user = User::where('email', $request->email)->first();
            if ($user){
                $this->guard()->login($user);
                $logged_in = Auth::check();
            }
        } else{
            $this->validateLogin($request);
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            $user = User::where('username', $request->email)->where('school_id', $school->id)->first();

            if(!$user){
                $user = User::where('phone_number', $request->email)->where('school_id', $school->id)->first();
            }

            if($user){
                if(Hash::check($request->password, $user->password)) {
                    $this->guard()->login($user);
                    $logged_in = Auth::check();
                }
            } else{
                $logged_in = $this->attemptLogin($request);
            }

        }

        if ($logged_in) {

            if (!$school->active_status) {
                $this->guard()->logout();
                Toastr::error('Your Institution is not Approved, Please contact with administrator.', 'Failed');
                return redirect()->route('login');
            }

            if (!Auth::user()->access_status || !Auth::user()->active_status) {
                $this->guard()->logout();
                Toastr::error('You are not allowed, Please contact with administrator.', 'Failed');
                return redirect()->route('login');
            }

            // System date format save in session
            $date_format_id = generalSetting()->date_format_id;
            $system_date_format = 'jS M, Y';
            if($date_format_id){
                $system_date_format = SmDateFormat::where('id', $date_format_id)->first(['format'])->format;
            }

            session()->put('system_date_format', $system_date_format);

            // System academic session id in session

            $all_modules = [];
            $modules = InfixModuleManager::select('name')->get();
            foreach ($modules as $module) {
                $all_modules[] = $module->name;
            }

            session()->put('all_module', $all_modules);

            //Session put text decoration
            $ttl_rtl = generalSetting()->ttl_rtl;
            session()->put('text_direction', $ttl_rtl);

            $active_style = SmStyle::where('school_id', Auth::user()->school_id)->where('is_active', 1)->first();
            session()->put('active_style', $active_style);

            $all_styles = SmStyle::where('school_id', Auth::user()->school_id)->get();
            session()->put('all_styles', $all_styles);

            //Session put activeLanguage
            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage', $systemLanguage);
            //session put academic years

            if(moduleStatusCheck('University')){
                $academic_years = Auth::check() ? UnAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }else{
                $academic_years = Auth::check() ? SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }
            session()->put('academic_years', $academic_years);
            //session put sessions and selected language


            if (Auth::user()->role_id == 2) {
                $profile = SmStudent::where('user_id', Auth::id())->withOutGlobalScopes([StatusAcademicSchoolScope::class])->first();

                session()->put('profile', @$profile->student_photo);
                // $session_id = $profile ? $profile->academic_id : generalSetting()->session_id;
                $session_id = generalSetting()->session_id;
            } else {
                $profile = SmStaff::where('user_id', Auth::id())->first();
                if ($profile) {
                    session()->put('profile', $profile->staff_photo);
                }
                // $session_id = $profile && $profile->academic_id ? $profile->academic_id : generalSetting()->session_id;
                $session_id = generalSetting()->session_id;
            }

            if(moduleStatusCheck('University')){
                $session_id = generalSetting()->un_academic_id;
                if(!$session_id){
                    $session = UnAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = UnAcademicYear::find($session_id);
                }

                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }
            else{
                if(!$session_id){
                    $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = SmAcademicYear::find($session_id);
                }
                if(!$session){
                    $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->first();
                }

                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }


            session()->put('school_config', generalSetting());

            $dashboard_background = DB::table('sm_background_settings')->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            session()->put('dashboard_background', $dashboard_background);

            $email_template = SmsTemplate::where('school_id',Auth::user()->school_id)->first();
            session()->put('email_template', $email_template);

            session(['role_id' => Auth::user()->role_id]);
            $agent = new Agent();
            $user_log = new SmUserLog();
            $user_log->user_id = Auth::user()->id;
            $user_log->role_id = Auth::user()->role_id;
            $user_log->school_id = Auth::user()->school_id;
            $user_log->ip_address = $request->ip();
            if(moduleStatusCheck('University')){
                $user_log->un_academic_id = getAcademicid();
            }else{
                $user_log->academic_id = getAcademicid() ?? 1;
            }
            $user_log->user_agent = $agent->browser() . ', ' . $agent->platform();
            $user_log->save();

            userStatusChange(auth()->user()->id, 1);

            //generate two factor code if setting true for role
            if(moduleStatusCheck('TwoFactorAuth') && generalSetting()->two_factor){
                $this->twoFactorAuth(auth()->user());
            }


            return $this->sendLoginResponse($request);

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    //send two factor authentication code

    protected function twoFactorAuth($user){

        $setting = \Modules\TwoFactorAuth\Entities\TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
        $role_id = $user->role_id;
        $role_ids = [1,2,3,4,5];
        if($setting->for_student == $role_id){
            $user->generateCode();
        }
        elseif($setting->for_parent == $role_id){
            $user->generateCode();
        }
        elseif($setting->for_teacher == $role_id ){
            $user->generateCode();
        }
        elseif($setting->for_admin == $role_id ){
            $user->generateCode();
        }
        elseif($setting->for_admin == $role_id ){
            $user->generateCode();
        }
        elseif($setting->for_staff && (!in_array($role_id,$role_ids))){
            $user->generateCode();
        }
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $request->only($this->username(), 'password', 'school_id');
        } else {
            return $request->only('username', 'password', 'school_id');
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/after-login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginFormTwo()
    {
        $school = app('school');
        $data = [];

        $login_background = SmBackgroundSetting::where('school_id', $school->id)->where([['is_default', 1], ['title', 'Login Background']])->first();

        if (empty($login_background)) {
            $data['css'] = "background: url(" . url('public/backEnd/login2/img/login-bg.png') . ")  no-repeat center; background-size: cover; ";
        } else {
            if (!empty($login_background->image)) {
                $data['css'] = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
            } else {
                $data['css'] = "background:" . $login_background->color;
            }
        }

        if(config('app.app_sync')){
            $roles = [1];
            if (!moduleStatusCheck('Saas') or session('domain')) {
                $roles = [1, 2, 3, 4, 5, 6, 7, 8];
            }

            $data['users'] = User::whereIn('role_id', $roles)->select('role_id', 'email')->where('school_id', $school->id)->orderByRaw('FIELD(role_id, 1, 5, 4, 3, 6, 7, 8, 2)')->get()->groupBy('role_id');
            if (moduleStatusCheck('Saas')) {
                $data['schools'] = SmSchool::orderBy('school_name', 'asc')->take(5)->get()->except($school->id);
            }
        }

        return view('auth.loginCodeCanyon', $data);

    }

    public function loginCodeCanyon()
    {
        $school_id = 1;
        if(app()->bound('school')){
            $school_id = app('school')->id;
        }

        $login_background = SmBackgroundSetting::where('school_id', $school_id)->where([['is_default', 1], ['title', 'Login Background']])->first();

        if (empty($login_background)) {
            $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
        } else {
            if (!empty($login_background->image)) {
                $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";

            } else {
                $css = "background:" . $login_background->color;
            }
        }

        $users = User::whereIn('role_id', [1, 2, 3, 4, 5, 6, 7, 8])->select('email')->get();
        $data = [
            'user_1' => $users->where('role_id', 1)->first(),
            'user_2' => $users->where('role_id', 2)->first(),
            'user_3' => $users->where('role_id', 3)->first(),
            'user_4' => $users->where('role_id', 4)->first(),
            'user_5' => $users->where('role_id', 5)->first(),
            'user_6' => $users->where('role_id', 6)->first(),
            'user_7' => $users->where('role_id', 7)->first(),
            'user_8' => $users->where('role_id', 8)->first(),
        ];
        return view('auth.loginCodeCanyon', compact('css'))->with($data);

    }

    //user logout method
    public function logout(Request $request)
    {

        $user = Auth::user();
        userStatusChange($user->id, 0);
        Session::flush();
        Auth::logout();
        return redirect()->route('login');

    }
}
