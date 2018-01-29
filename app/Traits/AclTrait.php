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
		'allocation' => [
			'create' => ['itsupport','enta'],
			'edit' => ['itsupport','enta'],
			'view' => ['itsupport'],
			'delete' => ['itsupport',],
			'show' => [],
		],
		'comment' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'department' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
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
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'log' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
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
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'unit' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
		'user' => [
			'create' => ['itsupport'],
			'edit' => ['itsupport'],
			'view' => ['itsupport'],
			'delete' => ['itsupport'],
			'show' => ['itsupport'],
		],
	];

}
