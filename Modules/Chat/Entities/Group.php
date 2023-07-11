<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Chat\Database\factories\GroupFactory;
use Ramsey\Uuid\Uuid;

class Group extends Model
{
    use HasFactory;

    protected $table = 'chat_groups';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    protected $guarded = [
        'id'
    ];

    protected static function newFactory()
    {
        return GroupFactory::new();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_group_users', 'group_id', 'user_id')
            ->wherePivot('deleted_at', '=', null)
            ->withPivot(['deleted_at', 'created_at']);
    }

    public function threads()
    {
        return $this->hasMany(GroupMessageRecipient::class, 'group_id');
    }
}
