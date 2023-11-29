<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPromotion
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student_promotion, $student;
    public function __construct($student_promotion, $student)
    {
        $this->student_promotion = $student_promotion;
        $this->student = $student;
    }
}
