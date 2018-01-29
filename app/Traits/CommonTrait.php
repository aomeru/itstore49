<?php
namespace App\Traits;

use Auth;
use App\User;
use App\Models\Log;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Item;

use Session;

trait CommonTrait
{
	
	public function get_time()
	{
		$date = new \DateTime();
		return $date->format('Y-m-d H:i:s');
	}




	protected function setMenu()
	{
		$pages = Permission::where('role_id',Auth::user()->role_id)
			->join('pages','permissions.page_id','=','pages.id')
			->select('pages.id','pages.title','pages.slug','pages.icon')
			->where('pages.type','page')
			->orderby('pages.title')
			->get();

		$allowed_pages = array();
		$allowed_subpages_role = array();

		foreach($pages as $page)
		{
			$subpages = Page::where('type_id',$page->id)->orderby('title')->get();

			array_push($allowed_pages, ([
				'id' => $page->id,
				'title' => $page->title,
				'slug' => $page->slug,
				'icon' => $page->icon,
				'subpages' => $subpages,
			]));
		}
		session(['allowed_pages' => $allowed_pages]);
	}




	public function allowed_records()
	{

	}




	public function generate_code($table, $col, $l)
	{
		switch($l)
		{
			case 4:
				$mi = 1000;
				$mx = 9999;
				break;

			case 8:
				$mi = 10000000;
				$mx = 99999999;
				break;

			case 10:
				$mi = 1000;
				$mx = 9999;
				break;
		}

		switch($table)
		{
			case 'users':
				if($l == 4)
				{
					$val = rand($mi, $mx);
				} elseif($l == 8) {
					do{
						$val = rand($mi, $mx);
						$data = User::where($col, $val)->get();
					}while(!$data->isEmpty());
				} else {
					do{
						$val = str_random(10);
						$data = User::where($col, $val)->get();
					}while(!$data->isEmpty());
				}
				break;

			case 'log':
				do{
					$val = strtoupper('DH'.rand($mi, $mx).str_random(4));
					$data = Tlog::where($col, $val)->get();
				}while(!$data->isEmpty());
				break;
		}
		return $val;
	}



	public function check_exist($table, $col, $val)
	{

	}




	public function log($user_id, $descrip, $path)
	{
		$log = new Log();
		$log->user_id = $user_id;
		$log->page_url = $path;
		$log->descrip = $descrip;
		$log->save();
	}




	public function ad()
	{
		Session::put('access_denied', 'YOU ARE NOT AUTHORIZED TO ACCESS THE REQUESTED PAGE.');
	}


	public function clean($string)
	{
		$string = str_replace(' ', '_', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}



	private function check_inv()
	{
		$check = false;
		$list = Item::all();
		foreach($list as $item)
		{
			$inventory = $item->inventory == null ? 0 : $item->inventory->count();
			$allocated = 0;
			foreach ($item->inventory as $val) {
				if($val->allocation != null) $allocated += 1;
			}
			$avail = $inventory - $allocated;
			if($avail <= config('app.rlevel')) { $check = true; break;}
		}
		return $check;
	}


}
