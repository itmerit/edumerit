<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPromotionGroupDisable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sectionId, $classId;

    public function __construct($sectionId, $classId)
    {
        $this->sectionId = $sectionId;
        $this->classId = $classId;
    }
}
