<?php

namespace Modules\Chat\Services;

use App\Models\User;
use Carbon\Carbon;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\GroupMessageRecipient;
use Modules\Chat\Entities\Notification;
use Modules\Chat\Notifications\GroupMessageNotification;
use Modules\Chat\Notifications\MessageNotification;
use Modules\Chat\Repositories\UserRepository;
use Modules\Chat\Repositories\GroupRepository;
use Modules\Chat\Repositories\ConversationRepository;

class ConversationService
{
    protected $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function oneToOneDelete($data){

        $conversation = Conversation::find($data['conversation_id']);
        if ($conversation->forMe() || $conversation->fromMe()){
            if ($conversation->forMe()){
                return $this->conversationRepository->oneToOneDeleteNotAuthor($conversation);
            }else{
                return $this->conversationRepository->oneToOneDeleteByAuthor($conversation);
            }
        }
        return false;
    }

    public function groupMessageDelete($data){

        $thread = GroupMessageRecipient::with('group.users','conversation')->find($data['thread_id']);

        if ($thread->group->users->contains('id',auth()->id())){
            return $this->conversationRepository->groupMessageDelete($thread);
        }
        return false;
    }

    public function readAllNotification($user)
    {
        if($user){
            $notifications = Notification::where('type', MessageNotification::class)
                ->where('notifiable_id', auth()->id())
                ->where('read_at', null)
                ->get();
            foreach ($notifications as $notification){
                if ($notification->data->thread->from_id == $user->id && $notification->data->thread->to_id == auth()->id()){
                    $notification->update([
                        'read_at' => Carbon::now()
                    ]);
                }
            }
        }
    }

    public function readAllNotificationGroup($groupId)
    {
        $notifications = Notification::where('type', GroupMessageNotification::class)
            ->where('notifiable_id', auth()->id())
            ->where('read_at', null)
            ->get();
        foreach ($notifications as $notification){
            if (optional(optional($notification->data)->group)->id == $groupId){
                $notification->update([
                    'read_at' => Carbon::now()
                ]);
            }
        }
    }

}
