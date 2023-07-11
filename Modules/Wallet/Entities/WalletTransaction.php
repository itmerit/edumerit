<?php

namespace Modules\Wallet\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletTransactionFactory::new();
    }

    public function userName(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
