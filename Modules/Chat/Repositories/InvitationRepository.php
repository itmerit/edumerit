<?php
namespace Modules\Chat\Repositories;

use App\Models\User;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Notifications\InvitationNotification;


class InvitationRepository
{
    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function invitationCreate($to, $status = 0)
    {
        return Invitation::create([
            'from' => auth()->id(),
            'to' => $to,
            'status' => $status
        ]);
    }

    public function myRequest()
    {
        return Invitation::with('requestTo','requestFrom')
            ->where('from', auth()->id())
            ->where('status', 0)
            ->get();
    }

    public function peopleRequest()
    {
        return Invitation::with('requestTo','requestFrom')
            ->where('to', auth()->id())
            ->where('status', 0)
            ->get();
    }

    public function invitationUpdate($type, $id)
    {
        $invitation = Invitation::findOrFail($id);
        if ($invitation->to == auth()->id() && $invitation->status == 0){
            switch ($type) {
                case "accept":
                    $invitation->update([
                        'status' => 1
                    ]);

                    Conversation::create([
                        'from_id' => auth()->id(),
                        'to_id' => $invitation->from,
                        'message' => "Hello, Now you can chat with me!",
                        'status' => 1,
                        'message_type' => 0,
                        'initial' => 1,
                    ]);
                    User::find($invitation->from)->notify(new InvitationNotification($invitation,auth()->user()->first_name.' accept your invitation'));
                    break;
                case "reject":
                    $invitation->delete();
                    break;
            }
            return true;
        }
        return false;
    }

    public function getAllConnectedUsers()
    {
        $to = Invitation::where('status', 1)->where('from', auth()->id())->pluck('to')->toArray();
        $from = Invitation::where('status', 1)->where('to', auth()->id())->pluck('from')->toArray();

        $blocks = $this->blocksResult();

        $users =  User::with('ownConversations','oppositeConversations','activeStatus','roles','staff')
            ->whereIn('id', array_merge($to, $from))
            ->get();

        // ==InfixEdu==

        if(app('general_settings')->get('chat_open') == 'no') {
            if (app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'no') {
                foreach ($users as $index => $user) {
                    if (auth()->user()->roles->id == 4) {
                        $user->roles->id === 3 ? $users->forget($index) : '';
                    }
                }
            }
        }

        // ==End InfixEdu==


        foreach ($users as $user){
            $last = $user->userSpecificConversationCollection($user->id)->sortByDesc('created_at')->last();
            if ($last){
                $user->custom_order = $last->id;
                if ($last->message_type == 0){
                    $user->last_message = $last->message;
                }else{
                    $user->last_message = 'Attachment';
                }
            }else{
                $user->last_message = '';
            }
        }

        return $users->sortByDesc(function ($user){
            return $user->custom_order;
        })->values();


    }

    public function blocksResult()
    {
        $blocks = BlockUser::where('block_by', auth()->id())->pluck('block_to')->toArray();
        $blocksReverse = BlockUser::where('block_to', auth()->id())->pluck('block_by')->toArray();

        return array_merge($blocks, $blocksReverse);
    }

}
