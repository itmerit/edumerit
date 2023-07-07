<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\Entities\Conversation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\GroupMessageRecipient;

class GroupChatEvent implements ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets;

    public $group, $thread, $conversation, $user;

    public function __construct(Group $group, GroupMessageRecipient $thread, Conversation $conversation, $user)
    {
        $this->group = $group;
        $this->thread = $thread;
        $this->conversation = $conversation;
        $this->user = $user;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('group-chat.'.$this->group->id);
    }
}
