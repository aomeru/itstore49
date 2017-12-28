<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function role()
	{
		return $this->belongsTo(Models\Role::class);
	}

	public function unit()
	{
		return $this->belongsTo(Models\Unit::class);
	}

	public function logs()
	{
		return $this->hasMany(Models\Log::class);
	}

    public function allocations()
	{
		return $this->hasMany(Models\Allocation::class);
	}



}
