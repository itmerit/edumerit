<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationType extends Model
{
    use HasFactory;

    protected $table = 'chat_invitation_types';

    protected $fillable = ['invitation_id','type','section_id', 'class_teacher_id'];
}
