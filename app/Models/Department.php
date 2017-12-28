<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function units()
	{
		return $this->hasMany(Unit::class);
	}
}
