<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmFrontendPersmission extends Model
{
    use HasFactory;
    protected $fillable = ['name','school_id'];
}
