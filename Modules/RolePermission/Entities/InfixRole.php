<?php

namespace Modules\RolePermission\Entities;
use Illuminate\Database\Eloquent\Model;

class InfixRole extends Model
{
    protected $fillable = [];
    protected $casts = [
        ' saas_schools' => 'array',
    ]; 
    public function assignedPermission()
    {
        return $this->hasMany(AssignPermission::class, 'role_id', 'id')->where('school_id', auth()->user()->school_id);
    }  
}
