<?php

namespace App\Models;

use App\Envato\Envato;
use App\SmStudentCategory;
use App\SmStudentGroup;
use App\Traits\UserChatMethods;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UserChatMethods;

    public static $email = "info@spondonit.com";  //23876323 //22014245 //23876323
    public static $item = "23876323";  //23876323 //22014245 //23876323
    public static $api = "https://sp.uxseven.com/api/system-details";
    public static $apiModule = "https://sp.uxseven.com/api/module/";



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'phone', 'password','is_administrator'
    ];

    protected $table = 'users';

    protected $appends = [
        'first_name', 'avatar_url', 'blocked_by_me'
    ];

    public function getFirstNameAttribute()
    {
        return $this->full_name;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('saas', function (Builder $builder) {
            $builder->where('school_id', '=', auth()->user()->school_id);
        });

        static::created(function (User $model) {
            if (Schema::hasTable('users')){
                userStatusChange($model->id, 0);
            }
        });
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function student()
    {
        return $this->belongsTo('App\SmStudent', 'id', 'user_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\SmStaff', 'id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(SmStudentCategory::class,'category_id','id');
    }
    public function group()
    {
        return $this->belongsTo(SmStudentGroup::class,'group_id','id');
    }

    public function parent()
    {
        return $this->belongsTo('App\SmParent', 'id', 'user_id');
    }

    public function school()
    {
        return $this->belongsTo('App\SmSchool', 'school_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'role_id', 'id');
    }

    public function getProfileAttribute()
    {
        $role_id = Auth::user()->role_id;
        $student = SmStudent::where('user_id', Auth::user()->id)->first();
        $parent = SmParent::where('user_id', Auth::user()->id)->first();
        $staff = SmStaff::where('user_id', Auth::user()->id)->first();
        if ($role_id == 2)
            $profile = $student ? $student->student_photo : 'public/backEnd/img/admin/message-thumb.png';
        elseif ($role_id == 3)
            $profile = $parent ? $parent->fathers_photo : 'public/backEnd/img/admin/message-thumb.png';
        else
            $profile = $staff ? $staff->staff_photo : 'public/backEnd/img/admin/message-thumb.png';
        return $profile;
    }

    public static function checkAuth()
    {
        return true;
       
    }



    public static function checkPermission($name)
    {
        return true;
    }

     /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }

}