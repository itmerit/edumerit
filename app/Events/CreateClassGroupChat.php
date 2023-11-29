<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateClassGroupChat
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $assign_subject;

    public function __construct($assign_subject)
    {
        $this->assign_subject = $assign_subject;
    }

}
