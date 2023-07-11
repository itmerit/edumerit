<?php

namespace App\Listeners;

use App\Events\StudentPromotionGroupDisable;
use App\SmAssignSubject;
use App\SmClass;
use App\SmSection;
use App\SmStaff;
use App\SmSubject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Chat\Entities\Group;

class ListenStudentPromotionGroupDisable
{

    public function __construct()
    {
        //
    }

    public function handle(StudentPromotionGroupDisable $event)
    {
        $subjects = SmAssignSubject::where('section_id', $event->sectionId)->where('class_id', $event->classId)->get();
        foreach ($subjects as $index => $subject){
            $teacher = SmStaff::find($subject->teacher_id)->staff_user;

            $groupName = $this->groupName($subject->school_id, $subject->class_id, $subject->section_id, $subject->subject_id, $teacher->id);
            $group = Group::where('name','like','%'.$groupName.'%')->first();
            if ($group){
                $group->read_only = 1;
                $group->save();
            }

        }
    }

    public function groupName($schoolId,$classId, $sectionId,$subjectId, $teacherId){
        $class = SmClass::find($classId);
        $section = SmSection::find($sectionId);
        $subject = SmSubject::find($subjectId);

        $code = $schoolId.$classId.$sectionId.$subjectId.$teacherId;

        return $class->class_name. '('.$section->section_name. ')-'.$subject->subject_name.'-'.$code;
    }
}
