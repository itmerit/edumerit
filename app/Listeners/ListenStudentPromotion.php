<?php

namespace App\Listeners;

use App\Events\StudentPromotion;
use App\SmAssignSubject;
use App\SmClass;
use App\SmSection;
use App\SmSubject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Invitation;

class ListenStudentPromotion
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
     * @param  StudentPromotion  $event
     * @return void
     */
    public function handle(StudentPromotion $event)
    {
        $student_info = json_decode($event->student_promotion->student_info);

        $subjects = SmAssignSubject::where('section_id',$event->student_promotion->previous_section_id)->get();

        foreach (array_unique($subjects->pluck('teacher_id')->toArray()) as $id){
            $exist = BlockUser::where(function ($query) use ($student_info, $id){
                    $query->where('block_to',$student_info->user_id);
                    $query->where('block_by', $id);
                })
                ->orWhere(function($q) use ($student_info, $id){
                    $q->where('block_by', $student_info->user_id);
                    $q->where('block_to', $id);
                })->first();

            if (is_null($exist)){
                BlockUser::create([
                    'block_by' => $id,
                    'block_to' => $student_info->user_id,
                ]);
            }
        }
    }
}
