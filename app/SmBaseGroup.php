<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmBaseGroup extends Model
{
	use HasFactory;
    public function baseSetups(){
		return $this->hasmany('App\SmBaseSetup', 'base_group_id');
	} 
}
