<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Chat\Database\factories\GroupMessageRecipientFactory;

class GroupMessageRecipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chat_group_message_recipients';

    protected $fillable = [
        'user_id', 'conversation_id', 'group_id', 'read_at',
    ];

    protected static function newFactory()
    {
        return GroupMessageRecipientFactory::new();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function removeMessages()
    {
        return $this->hasMany(GroupMessageRemove::class, 'group_message_recipient_id');
    }

    public function byMe()
    {
        return $this->user_id === auth()->id();
    }
}
