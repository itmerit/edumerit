<?php

namespace Modules\Lesson\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonPlanTopic extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Lesson\Database\factories\LessonPlanTopicFactory::new();
    }
    public function topicName()
    {
        return $this->belongsTo('Modules\Lesson\Entities\SmLessonTopicDetail', 'topic_id', 'id')->withDefault();
    }
    public function lessonDetail()
    {
    	return $this->belongsTo('Modules\Lesson\Entities\LessonPlanner', 'lesson_planner_id','id')->withDefault();
    }
}
