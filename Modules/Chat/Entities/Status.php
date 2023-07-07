<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $table = 'chat_statuses';

    protected $fillable = [
        'user_id',
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\StatusFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isInactive()
    {
        return $this->status == 0;
    }

    public function isActive()
    {
        return $this->status == 1;
    }

    public function isAway()
    {
        return $this->status == 2;
    }

    public function isBusy()
    {
        return $this->status == 3;
    }
}
