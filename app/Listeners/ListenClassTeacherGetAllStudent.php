<?php

namespace App\Listeners;

use App\Events\ClassTeacherGetAllStudent;
use App\Models\InvitationType;
use App\SmSection;
use App\SmStaff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Chat\Entities\Invitation;

class ListenClassTeacherGetAllStudent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClassTeacherGetAllStudent  $event
     * @return void
     */
    public function handle(ClassTeacherGetAllStudent $event)
    {
        $section = SmSection::find($event->assign_class_teacher->section_id);
        if($section){
            if ( $event->type == 'update'){
                $old = InvitationType::where('type', 'class-teacher')->where('section_id', $section->id)->first();
                if($old){
                    Invitation::with(['type' =>function ($query) use($section, $event, $old){
                        $query->where('type', 'class-teacher');
                        $query->where('section_id', $section->id);
                        $query->where('class_teacher_id', $old->class_teacher_id);
                    }])->delete();
                }
            }

            $this->insertion($section, $event);
        }
       
    }


    public function insertion($section, ClassTeacherGetAllStudent $event)
    {
        $teacher = SmStaff::find($event->class_teacher->teacher_id)->staff_user;
        foreach ($section->students as $student) {
            $exist = Invitation::where('from', $teacher->id)->where('to', $student->id)->first();
            if (is_null($exist) && $teacher->id != $student->id){
                $invitation = Invitation::create([
                    'from' => $teacher->id,
                    'to' => $student->id,
                    'status' => 1
                ]);
                InvitationType::create([
                    'invitation_id' => $invitation->id,
                    'type' => 'class-teacher',
                    'section_id' => $section->id,
                    'class_teacher_id' => $teacher->id,
                ]);
            }
        }
    }
}
