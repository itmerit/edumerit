<?php

namespace Database\Seeders\Lesson;

use App\SmWeekend;
use Illuminate\Database\Seeder;
use Modules\Lesson\Entities\SmLesson;
use Modules\Lesson\Entities\LessonPlanner;
use Modules\Lesson\Entities\SmLessonTopicDetail;

class SmLessonPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count)
    {
        //
        $days = SmWeekend::where('school_id', $school_id)->get();
        $lesson_id = SmLesson::where('school_id', $school_id)
                                ->where('academic_id', $academic_id)
                                ->value('id');
        $topic_id = SmLessonTopicDetail::where('lesson_id', $lesson_id)
                                        ->where('school_id', $school_id)
                                        ->where('academic_id', $academic_id)
                                        ->value('topic_id');
        foreach($days as $day) {
            $lessonPlanner = new LessonPlanner;
            $lessonPlanner->day = $day->id;
            $lessonPlanner->lesson_detail_id = $lesson_id;
            $lessonPlanner->lesson_id = $lesson_id;
            $lessonPlanner->topic_id = $topic_id;
            $lessonPlanner->sub_topic = $day->name;
            $lessonPlanner->school_id=$school_id;
            $lessonPlanner->academic_id=$academic_id;
            $lessonPlanner->save();
        }

    }
}
