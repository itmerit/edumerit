<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmHeaderMenuManager extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function childs(){
        return $this->hasMany(SmHeaderMenuManager::class,'parent_id','id')->orderBy('position');
    }

}
