<?php

namespace Database\Seeders;

use App\SmAssignSubject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Entities\SmLesson;
use Modules\Lesson\Entities\SmLessonTopic;
use Modules\Lesson\Entities\SmLessonTopicDetail;

class sm_lesson_topicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $topic = ['theory', 'poem', 'practical', 'others'];
      $lesson_id = SmLesson::where('class_id', 1)->where('section_id', 1)->first()->id;
      $is_duplicate = SmLessonTopic::where('class_id', 1)->where('lesson_id', 1)->where('section_id', 1)->where('subject_id', 1)->first();
      if ($is_duplicate) {
          $length = count($topic);
          for ($i = 0; $i < $length; $i++) {
              $topic_title = $topic[$i++];

              $topicDetail = new SmLessonTopicDetail;
              $topicDetail->topic_id = $is_duplicate->id;
              $topicDetail->topic_title = $topic_title;
              $topicDetail->lesson_id = $lesson_id;
              $topicDetail->save();

          }
          DB::commit();

      } else {

          $smTopic = new SmLessonTopic;
          $smTopic->class_id = 1;
          $smTopic->section_id = 1;
          $smTopic->subject_id = 1;
          $smTopic->lesson_id = $lesson_id;
          $smTopic->save();
          $smTopic_id = $smTopic->id;
          $length = count($topic);

          for ($i = 0; $i < $length; $i++) {
              $topic_title = $topic[$i];

              $topicDetail = new SmLessonTopicDetail;
              $topicDetail->topic_id = $smTopic_id;
              $topicDetail->topic_title = $topic_title;
              $topicDetail->lesson_id = $lesson_id;
              $topicDetail->school_id = 1;
              $topicDetail->academic_id = 1;
              $topicDetail->save();

          }
          DB::commit();

      }
  }
}
