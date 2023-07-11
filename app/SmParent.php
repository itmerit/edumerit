<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SmParent extends Model
{
    use HasFactory;
	protected static function boot (){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
    
    public function parent_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\SmStudent', 'parent_id', 'id')->where('active_status', 1);
    }

    public static function myChildrens()
    {

        try {
            if (Session::get('childrens')) {
                return Session::get('childrens');
            } else {
                $parent = Auth::user()->parent;
                $childrens = $parent->childrens;
                Session::put('childrens', $childrens);
                return Session::get('childrens');
            }

        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}
