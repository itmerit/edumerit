<?php

namespace Database\Seeders\Lesson;

use App\SmAssignSubject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Lesson\Entities\SmLesson;
use Modules\Lesson\Entities\SmLessonTopic;
use Modules\Lesson\Entities\SmLessonTopicDetail;

class SmTopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        // $topic = ['theory', 'poem', 'practical', 'others'];
        // $lesson_id = SmLesson::where('class_id', 1)->where('section_id', 1)->where('school_id', $school_id)->where('academic_id', $academic_id)->first()->id;
        // $assignSubject = SmAssignSubject::where('school_id', $school_id)
        // ->where('academic_id', $academic_id)
        // ->first();
        // $is_duplicate = SmLessonTopic::where('class_id', $assignSubject->class_id)->where('lesson_id', $lesson_id)->where('section_id', $assignSubject->sction_id)->where('subject_id', $assignSubject->subject_id)->first();
        // if ($is_duplicate) {
        //     $length = count($topic);
        //     for ($i = 0; $i < $length; $i++) {
        //         $topic_title = $topic[$i++];
  
        //         $topicDetail = new SmLessonTopicDetail;
        //         $topicDetail->topic_id = $is_duplicate->id;
        //         $topicDetail->topic_title = $topic_title ? $topic_title.'0'.$i : '0'.$i;
        //         $topicDetail->lesson_id = $lesson_id;
        //         $topicDetail->school_id = $school_id;
        //         $topicDetail->academic_id = $academic_id;
        //         $topicDetail->save();
  
        //     }
        //     DB::commit();
  
        // } else {
  
        //     $smTopic = new SmLessonTopic;
        //     $smTopic->class_id = $assignSubject->class_id;
        //     $smTopic->section_id = $assignSubject->section_id;
        //     $smTopic->subject_id = $assignSubject->subject_id;
        //     $smTopic->lesson_id = $lesson_id;
        //     $smTopic->school_id = $school_id;
        //     $smTopic->academic_id = $academic_id;
        //     $smTopic->save();
        //     $smTopic_id = $smTopic->id;
        //     $length = count($topic);
  
        //     for ($i = 0; $i < $length; $i++) {
        //         $topic_title = $topic[$i];
  
        //         $topicDetail = new SmLessonTopicDetail;
        //         $topicDetail->topic_id = $smTopic_id;
        //         $topicDetail->topic_title = $topic_title ? $topic_title.'0'.$i : '0'.$i;
        //         $topicDetail->lesson_id = $lesson_id;
        //         $topicDetail->school_id = $school_id;
        //         $topicDetail->academic_id = $academic_id;
        //         $topicDetail->save();
  
        //     }
        //     DB::commit();
  
        // }
    }
}
