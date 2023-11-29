<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassTeacherGetAllStudent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $assign_class_teacher, $class_teacher, $type;

    public function __construct($assign_class_teacher, $class_teacher, $type = 'store')
    {
        $this->assign_class_teacher = $assign_class_teacher;
        $this->class_teacher = $class_teacher;
        $this->type = $type;
    }
}
