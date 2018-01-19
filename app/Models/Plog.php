<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Plog extends Model
{
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
