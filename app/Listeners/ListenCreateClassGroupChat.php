<?php

namespace App\Listeners;

use App\Events\CreateClassGroupChat;
use App\Models\InvitationType;
use App\Models\StudentRecord;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmAcademicYear;
use App\SmAssignSubject;
use App\SmClass;
use App\SmSection;
use App\SmStaff;
use App\SmSubject;
use App\User;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\GroupUser;
use Modules\Chat\Entities\Invitation;

class ListenCreateClassGroupChat
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
     * @param  CreateClassGroupChat  $event
     * @return void
     */
    public function handle(CreateClassGroupChat $event)
    {
        $group = Group::where([
            'class_id' => $event->assign_subject->class_id,
            'section_id' => $event->assign_subject->section_id,
            'subject_id' => $event->assign_subject->subject_id,
            'school_id' => $event->assign_subject->school_id,
            'academic_id' => $event->assign_subject->academic_id
        ])->first();

        $section = SmSection::find($event->assign_subject->section_id);
        $studentRecords = StudentRecord::with('studentDetail')->where('class_id', $event->assign_subject->class_id)->where('section_id', $event->assign_subject->section_id)->where('school_id', $event->assign_subject->school_id)->get();
       
        if (!$group){

            $group = Group::create([
               'name' => $this->groupName($event->assign_subject),
               'class_id' => $event->assign_subject->class_id,
                'section_id' => $event->assign_subject->section_id,
                'subject_id' => $event->assign_subject->subject_id,
                'school_id' => $event->assign_subject->school_id,
                'academic_id' => $event->assign_subject->academic_id,
                'created_by' => $event->assign_subject->teacher_id ?? 1,
            ]);

            foreach ($studentRecords as $record){
               
                GroupUser::create([
                    'user_id' => $record->studentDetail->user_id,
                    'group_id' => $group->id,
                    'added_by' => $group->created_by,
                    'role' => 2
                ]);

                $exist = Invitation::where('from', $group->created_by)->where('to', $record->studentDetail->user_id)->first();
                if (is_null($exist) && $group->created_by != $record->studentDetail->user_id){
                    $invitation = Invitation::create([
                        'from' => $group->created_by,
                        'to' => $record->studentDetail->user_id,
                        'status' => 1
                    ]);
                    InvitationType::create([
                        'invitation_id' => $invitation->id,
                        'type' => 'class-teacher',
                        'section_id' => $section->id,
                        'class_teacher_id' => $group->created_by,
                    ]);
                }
            }
        }

        GroupUser::where([
            'group_id' => $group->id,
            'role' => 1
        ])->delete();

        $teachers = SmAssignSubject::where([
            'class_id' => $event->assign_subject->class_id,
            'section_id' => $event->assign_subject->section_id,
            'subject_id' => $event->assign_subject->subject_id,
            'school_id' => $event->assign_subject->school_id,
            'academic_id' => $event->assign_subject->academic_id
        ])->get();


        foreach($teachers as $teacher){
            $teacher = SmStaff::find($teacher->teacher_id);
            if($teacher && $teacher = $teacher->staff_user){
                createGroupUser($group, $teacher->id);
            }

        }

        $admins = User::whereIn('role_id', [5])->where('school_id', $event->assign_subject->school_id)->get();

        foreach($admins as $admin){
            createGroupUser($group, $admin->id);
        }

    }

    public function groupName($data, $withTeacherId = true){
        $class = SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($data->class_id);
        $section = SmSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($data->section_id);
        $subject = SmSubject::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($data->subject_id);
        $academic_year = SmAcademicYear::find($data->academic_id);

        return @$class->class_name. '('.@$section->section_name. ')-'.@$subject->subject_name.'-'.@$academic_year->year;
    }
}
