<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Chat\Entities\Conversation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatEvent implements ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets;

    public $message;

    public function __construct(Conversation $message)
    {
        $this->message = $message;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('single-chat.'.$this->message->to_id);
    }
}
