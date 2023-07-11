<?php

use App\Models\InvitationType;
use Modules\Chat\Entities\GroupUser;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Entities\Status;

if (!function_exists('userStatusChange')) {
    function userStatusChange($userId, $status)
    {
        $current = Status::firstOrNew(
            ['user_id' => $userId],
            ['user_id' => $userId,]
        );

        $current->status = $status;
        $current->save();
    }
}

if (!function_exists('createGroupPermission')) {
    function createGroupPermission()
    {
        $role_id = auth()->user()->role_id;
        if ($role_id == 1) return true;
        if ($role_id == 3) return false;

        if ($role_id == 2){
            if(app('general_settings')->get('chat_can_make_group')== 'yes'){
                return true;
            }
            return false;
        }

        if(app('general_settings')->get('chat_teacher_staff_can_make_group')== 'yes'){
            return true;
        }
        return false;

    }
}


if (!function_exists('createGroupUser')) {
    function createGroupUser($group, $user_id, $role=1, $invited_by=null)
    {
        GroupUser::updateOrCreate([
            'user_id' => $user_id,
            'group_id' => $group->id,
        ],
        [
            'user_id' => $user_id,
            'group_id' => $group->id,
            'added_by' => $invited_by ?? $group->created_by,
            'role' => $role
        ]);

        $exist = Invitation::where('from', $group->created_by)->where('to', $user_id)->first();
        if (is_null($exist) && $group->created_by != $user_id){
            $invitation = Invitation::updateOrCreate([
                'from' => $invited_by ?? $group->created_by,
                'to' => $user_id,
            ],
            [
                'from' => $invited_by ?? $group->created_by,
                'to' => $user_id,
                'status' => 1
            ]);

            InvitationType::updateOrCreate([
                'invitation_id' => $invitation->id,
            ],
            [
                'invitation_id' => $invitation->id,
                'type' => 'class-teacher',
                'section_id' => $group->section_id,
                'class_teacher_id' => $invited_by ?? $group->created_by,
            ]);
        }
    }
}



if (!function_exists('removeGroupUser')) {
    function removeGroupUser($group, $user_id)
    {
        
        GroupUser::where([
            'user_id' => $user_id,
            'group_id' => $group->id
        ])->delete();

        $invitation = Invitation::where('from', $group->created_by)->where('to', $user_id)->first();
        if ($invitation){       
            InvitationType::create([
                'invitation_id' => $invitation->id
            ])->delete();
            $invitation->delete();
        }
    }
}

