<?php

namespace App\Traits;

use App\SmParent;
use App\SmStaff;
use App\SmStudent;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Entities\Status;

trait UserChatMethods{

    public function getAvatarUrlAttribute()
    {
         if (\Request::is('chat/*') || \Request::is('api/chat/*')) {
            if ($this->role_id){
                // return $this->getProfileAttribute();
                if($this->role_id == 2){
                    $student = SmStudent::whereUserId($this->id)->first();
                    return $student->student_photo ?? 'public/chat/images/spondon-icon.png';
                }elseif($this->role_id == 3){
                    $parent = SmParent::whereUserId($this->id)->first();
                    return $parent->guardians_photo ?? 'public/chat/images/spondon-icon.png';
                }else{
                    $staff = $this->staff;
                    return $staff->staff_photo ?? 'public/chat/images/spondon-icon.png';
                }
             }
        }
        return 'public/chat/images/spondon-icon.png';

    
       
    }

    public function activeStatus()
    {
        return $this->hasOne(Status::class)->withDefault();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'chat_group_users', 'user_id', 'group_id');
    }

    public function connectedWithLoggedInUser()
    {
        return Invitation::where('to', auth()->id())->where('from', $this->id)
            ->orWhere(function ($query){
                $query->where('from', auth()->id());
                $query->where('to', $this->id);
            })->exists();
    }

    public function activeConnectionWithLoggedInUser()
    {
        return Invitation::where('to', auth()->id())->where('from', $this->id)
            ->orWhere(function ($query){
                $query->where('from', auth()->id());
                $query->where('to', $this->id);
            })->where('status', 1)->exists();
    }

    public function connectionPending()
    {
        return Invitation::where(function ($query){
            $query->where('to', auth()->id());
            $query->where('from', $this->id);
            $query->where('status', 0);
        })
            ->orWhere(function ($query){
                $query->where('from', auth()->id());
                $query->where('to', $this->id);
                $query->where('status', 0);
            })
            ->exists();
    }

    public function connectionSuccess()
    {
        return Invitation::where(function ($query){
            $query->where('to', auth()->id());
            $query->where('from', $this->id);
            $query->where('status', 1);
        })
            ->orWhere(function ($query){
                $query->where('from', auth()->id());
                $query->where('to', $this->id);
                $query->where('status', 1);
            })
            ->exists();
    }

    public function connectionBlocked()
    {
        return Invitation::where(function ($query){
            $query->where('to', auth()->id());
            $query->where('from', $this->id);
            $query->where('status', 2);
        })
            ->orWhere(function ($query){
                $query->where('from', auth()->id());
                $query->where('to', $this->id);
                $query->where('status', 2);
            })
            ->exists();
    }

    public function blockedByMe()
    {
        return BlockUser::where('block_by', auth()->id())->where('block_to', $this->id)->exists();
    }

    public function getBlockedByMeAttribute()
    {
        if (\Request::is('chat/*')) {
            return $this->blockedByMe();
        }
        return false;

    }

    public function ownConversations()
    {
        return $this->hasMany(Conversation::class, 'from_id')->orderBy('created_at','desc');
    }

    public function oppositeConversations()
    {
        return $this->hasMany(Conversation::class, 'to_id')->orderBy('created_at','desc');
    }

    public function allConversations() 
    {
        $first = $this->ownConversations()->latest()->get();
        $second = $this->oppositeConversations()->latest()->get();
        $data = $first->merge($second);

        return $data->sortBy('created_at');
    }

    public function userSpecificConversation($userId)
    {
        return $conversation =  Conversation::with('forwardFrom','reply','fromUser','toUser')->where(function ($query) use($userId){
            $query->where('from_id', $userId);
            $query->where('to_id', auth()->id());
        })->orWhere(function($query) use ($userId){
            $query->where('from_id', auth()->id());
            $query->where('to_id', $userId);
        })
            ->get()
            ->sortByDesc('created_at')
            ->take(20)->toArray();

    }

    public function userSpecificConversationCollection($userId)
    {
        return Conversation::with('forwardFrom','reply','fromUser','toUser')->where(function ($query) use($userId){
            $query->where('from_id', $userId);
            $query->where('to_id', auth()->id());
        })/*->orWhere(function($query) use ($userId){
            $query->where('from_id', auth()->id());
            $query->where('to_id', $userId);
        })*/->get();

    }

    public function userSpecificConversationForLoadMore($userId, $ids)
    {
        $conversation =  Conversation::with('forwardFrom','reply','fromUser','toUser')
            ->where(function ($query) use($userId){
                $query->where('from_id', $userId);
                $query->where('to_id', auth()->id());
            })->orWhere(function($query) use ($userId){
                $query->where('from_id', auth()->id());
                $query->where('to_id', $userId);
            })
            ->get()
            ->sortByDesc('created_at');

        return $conversation->filter(function ($value, $key) use ($ids){
            return !in_array($value->id, json_decode($ids));
        })->take(20);
    }

}