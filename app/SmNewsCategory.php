<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmNewsCategory extends Model
{
    use HasFactory;
    public function news()
    {
        return $this->hasMany('App\SmNews');
    }
}
