<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Inventory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function allocation()
	{
		return $this->hasOne(Allocation::class);
	}
}
