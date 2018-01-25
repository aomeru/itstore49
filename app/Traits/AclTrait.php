<?php
namespace App\Traits;

use Auth;
use App\User;
use App\Models\Log;
use App\Models\Page;
use App\Models\Permission;
use Session;

trait AclTrait
{

	protected $acl = [
		'itsupportocation' => [
			'create' => ['itsupport','enta'],
			'edit' => ['itsupport','enta'],
			'view' => ['itsupport'],
			'delete' => ['itsupport',],
			'show' => [],
		],
		'comment' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
		'department' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
		'ilog' => [
			'create' => ['itsupport','enta','cukaigwe'],
			'edit' => ['itsupport','enta','cukaigwe'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'inventory' => [
			'create' => ['itsupport','enta','cukaigwe'],
			'edit' => ['itsupport','enta','cukaigwe'],
			'view' => ['itsupport'],
			'delete' => ['itsupport',],
			'show' => ['itsupport'],
		],
		'item' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
		'log' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
		'plog' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'purchase' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'task' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
		'unit' => [
			'create' => [],
			'edit' => [],
			'view' => [],
			'delete' => [],
			'show' => [],
		],
	];

}
