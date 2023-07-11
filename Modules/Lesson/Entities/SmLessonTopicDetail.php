<?php

namespace Modules\Lesson\Entities;

use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;

class SmLessonTopicDetail extends Model
{

    protected $fillable = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }

    public function lesson_title()
    {
        return $this->belongsTo('Modules\Lesson\Entities\SmLesson', 'lesson_id');
    }

    public function lessonPlan()
    {
        return $this->hasMany('Modules\Lesson\Entities\LessonPlanTopic', 'topic_id', 'id');
    }
}
