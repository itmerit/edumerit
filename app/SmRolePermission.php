<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmRolePermission extends Model
{
    use HasFactory;
    public function moduleLink()
    {
        return $this->belongsTo('App\SmModuleLink', 'module_link_id', 'id');
    }
}
