<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorTheme extends Model
{
    protected $guarded = [];
    protected $table = 'color_theme';
    use HasFactory;
}
