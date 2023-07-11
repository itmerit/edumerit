<?php
namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

class SchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $table=$model->getTable();
        if (Auth::check()) {
            if (app()->bound('school')) {
                if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator == "yes" && Session::get('isSchoolAdmin') == false && auth()->user()->role_id == 1) {
                    //
                } else {
                    $builder->where($table.'.school_id', app('school')->id);
                }
            } else {
                if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator == "yes" && Session::get('isSchoolAdmin') == false && auth()->user()->role_id == 1) {
                   //
                } else {
                    $builder->where($table.'.school_id', auth()->user()->school_id);
                }
            }
        }
    }
}
