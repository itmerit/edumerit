<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SmModule extends Model
{
    use HasFactory;
    public function moduleLink(){
    	return $this->hasMany('App\SmModuleLink', 'module_id', 'id');
    }
}
