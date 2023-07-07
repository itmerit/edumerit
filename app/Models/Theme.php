<?php

namespace App\Models;

use App\Models\Color;
use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();
  
        static::addGlobalScope(new SchoolScope);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class)->where('status', 1)->withPivot(['value']);
    }

}
