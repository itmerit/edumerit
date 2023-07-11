<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibrarySubject extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    public function subjectBook()
    {
        return $this->belongsTo('App\Book', 'book', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\SmBookCategory', 'sb_category_id', 'id');
    }
}
