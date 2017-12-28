<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	public function role()
	{
		return $this->belongsTo(Roles::class);
	}

	public function page()
	{
		return $this->belongsTo(Pages::class);
	}
}
