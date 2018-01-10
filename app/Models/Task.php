<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Task extends Model
{
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function comments()
	{
		return $this->hasMany(Comment::class);
	}


}
