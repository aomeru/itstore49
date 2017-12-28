<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Unit extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
