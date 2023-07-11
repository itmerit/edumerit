<?php
namespace Modules\Chat\Repositories;

use App\Models\User;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\GroupMessageRecipient;
use Modules\Chat\Entities\GroupMessageRemove;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Notifications\InvitationNotification;


class ConversationRepository
{

    protected $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }


    public function oneToOneDeleteNotAuthor(Conversation $conversation)
    {
        $conversation->update([
            'deleted_by_to' => 1
        ]);
        return true;
    }

    public function oneToOneDeleteByAuthor(Conversation $conversation)
    {
        $conversation->delete();
        return true;
    }

    public function groupMessageDelete(GroupMessageRecipient $thread)
    {
        if($thread->user_id == auth()->id()){
            $thread->conversation()->delete();
            return $thread->delete();
        }

        GroupMessageRemove::create([
            'user_id' => auth()->id(),
            'group_message_recipient_id' => $thread->id
        ]);
        return true;
    }
}
