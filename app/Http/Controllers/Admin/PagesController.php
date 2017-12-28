<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Role;
use App\Models\Permission;
use App\Traits\CommonTrait;
use Auth;
use Session;
use DB;

class PagesController extends Controller
{
	use CommonTrait;
	
	public function pageIndex()
	{
		return view('admin.pages.index', ['nav' => 'pages', 'list' => Page::orderby('title','asc')->get()]);
	}
	
	
	public function createPage()
	{
		return view('admin.pages.add', ['nav' => 'pages']);
	}
	
	
	public function loadPages()
	{
		$list = Page::select('id','title')->where('type','page')->get();
		if(count($list) > 0)
		{
			echo '
			<div class="form-group input_field_sections"><label for="type_id" class="control-label">Parent Page <span class="rfd">*</span></label><select name="type_id" id="type_id" class="form-control chzn-select">';
			foreach($list as $page)
			{
				echo '<option value="'.$page->id.'">'.$page->title.'</option>';
			}
			echo '</select></div>';
		}
		else echo '<p class="alert alert-danger v-margin-15 text-center">No page to assign subpage to, please select page type instead</p>';
	}
	
	
	public function storePage(Request $request)
	{

		$this->validate($request, [
			'title' => 'required|regex:/^([a-zA-Z0-9 &]+)$/|max:50',
			'slug' => 'required|alpha_dash|regex:/^([a-zA-Z0-9-]+)$/|max:50|unique:pages',
			'icon' => 'required|alpha_dash|regex:/^([a-zA-Z0-9-]+)$/',
			'type' => 'required|in:page,subpage',
		]);

		$page = new Page();
		$page->title = ucwords($request['title']);
		$page->slug = strtolower($request['slug']);
		$page->icon = strtolower($request['icon']);
		$page->type = $request['type'];

		if(isset($request['type_id']))
		{
			$this->validate($request, [
				'type_id' => 'required|exists:pages,id',
			]);
			$page->type_id = $request['type_id'];
		}

		if($page->save())
		{
			$dev_role = Role::where('title','Developer')->first();
			$perm = new Permission();
			$perm->page_id = $page->id;
			$perm->role_id = $dev_role->id;
			$perm->save();
			
			$this->setMenu();
			$request->session()->put('success', 'Page added');
			return redirect()->route('admin.pages');
		}

		Session::put('error', 'Unable to add page.');
		return redirect()->back();
	}
	
	
	public function deletePage($id)
	{
		$data = Page::where('id',$id)->first();

		if($data != null)
		{
			$page_id = $data->id;
			$page_title = $data->title;
			if($data->delete())
			{
				$subpages = Page::where('type_id',$id)->get();
				if($subpages->count() > 0)
				{
					foreach($subpages as $s)
					{
						$page_id = $s->id;
						$page_title = $s->title;
						$s->delete();
					}
				}
				$this->setMenu();
				Session::put('success', 'Page deleted.');
				return redirect()->route('admin.pages');
			}

			Session::put('error', 'Unable to delete page.');
			return redirect()->route('admin.pages');
		}

		Session::put('error', 'The page you are trying to delete does not exist.');
		return redirect()->route('admin.pages');
	}
	
	
	public function editPage($id)
	{
		$page = Page::where('id',$id)->first();
		if($page == null)
		{
			Session::put('error','Page you are trying to edit does not exist!');
			return redirect()->back();
		}
		$page_list = Page::where('type','page')->get();
		return view('admin.pages.edit', [
			'nav' => 'pages',
			'id' => $id,
			'page' => $page,
			'page_list' => $page_list
		]);
	}


	public function updatePage(Request $request, $id)
	{
		$page = Page::where('id',$id)->first();
		if($page == null)
		{
			Session::put('error','Page you are trying to edit does not exist!');
			return redirect()->route('admin.pages');
		}
		$this->validate($request, [
			'title' => 'required|regex:/^([a-zA-Z0-9 &]+)$/|max:50',
			'slug' => 'required|alpha_dash|regex:/^([a-zA-Z0-9-]+)$/|max:50|unique:pages,slug,'.$id,
			'icon' => 'required|alpha_dash|regex:/^([a-zA-Z0-9-]+)$/',
			'type' => 'required|in:page,subpage',
		]);

		$page->title = ucwords($request['title']);
		$page->slug = strtolower($request['slug']);
		$page->icon = strtolower($request['icon']);
		$page->type = $request['type'];

		if($request['type'] == 'subpage' && isset($request['type_id']) && !empty($request['type_id']))
		{
			$this->validate($request, [
				'type_id' => 'required|exists:pages,id',
			]);
			$page->type_id = $request['type_id'];
		} else $page->type_id = 0;

		if($page->save())
		{
			$this->setMenu();
			Session::put('success', 'Page updated.');
			return redirect()->route('admin.pages');
		}

		Session::put('error', 'Unable to update page infromation.');
		return redirect()->back();
	}
	
	
	
	
	
	
	public function permissionsIndex()
	{
		$role_list = Role::has('permissions')
			->orderby('title','asc')
			->get();

		$perm_key = array();
		$allowed_pages = array();

		foreach($role_list as $role)
		{
			array_push($perm_key, $role->id);
			$pages = Permission::select('page_id')
				->where('role_id',$role->id)
				->get();
			$role_pages = array();
			foreach($pages as $rp)
			{
				$page = Page::select('title')->where('id',$rp->page_id)->first();
				array_push($role_pages, $page->title);
			}
			sort($role_pages);
			array_push($allowed_pages, $role_pages);
		}
		$perm_list = array_combine($perm_key, $allowed_pages);
		return view('admin.permissions.index', ['nav' => 'permissions', 'role_list' => $role_list, 'perm_list' => $perm_list]);
	}
	
	
	public function assignPerm()
	{
		$roles = Role::select('id','title')->where('title','<>','User')->get();
		$pages = Page::select('id','title')->get();
		return view('admin.permissions.assign', ['nav' => 'permissions', 'roles' => $roles, 'pages' => $pages]);
	}


	public function storePerm(Request $request)
	{
		if(count($request['roles']) == 0  || count($request['pages']) == 0){
			Session::put('error', 'You must enter at least one role and page');
			return redirect()->back();
		}
		$this->validate($request, [
			'roles.*' => 'required|exists:roles,id',
			'pages.*' => 'required|exists:pages,id',
		]);

		$perm_error = false;
		foreach($request['roles'] as $role)
		{
			foreach($request['pages'] as $page)
			{
				if($this->perm_exists($role, $page))
				{
					$perm = new Permission();
					$perm->role_id = $role;
					$perm->page_id = $page;
					$perm->save() ? $perm_error = false : $perm_error = true;
				}
			}
		}

		if(!$perm_error)
		{
			Session::put('success', 'Role permissions assigned.');
			return redirect()->route('admin.perm');
		}

		Session::put('error', 'Unable to assign role permission(s).');
		return redirect()->back();
	}
	
	
	protected function perm_exists($role_id, $page_id)
	{
		$perm_exist = Permission::where('role_id',$role_id)->where('page_id',$page_id)->first();
		if($perm_exist == null) return true; else return false;
	}
	
	
	public function editPerm($id)
	{
		$role = Role::where('id',$id)->first();
		$pages = Page::select('id','title')->get();
		$assigned_pages = $this->getAssignedPages($role->id);
		return view('admin.permissions.edit', [
			'nav' => 'permissions',
			'role' => $role,
			'pages' => $pages,
			'assigned_pages' => $assigned_pages,
		]);
	}


	protected function getAssignedPages($role_id)
	{
		$apages = Permission::select('page_id')->where('role_id',$role_id)->get();
		$assigned_pages = array();
		foreach($apages as $a)
		{
			array_push($assigned_pages, $a->page_id);
		}
		return $assigned_pages;
	}


	public function updatePerm(Request $request, $id)
	{
		$this->validate($request, [
			'pages.*' => 'required|exists:pages,id',
		]);
		$perm_error = false;
		$assigned_pages = $this->getAssignedPages($id);

		foreach($assigned_pages as $old)
		{
			if(!in_array($old, $request['pages']))
			{
				$data = Permission::where('page_id',$old)->where('role_id',$id)->first();
				$data->delete();
			}
		}

		foreach($request['pages'] as $page)
		{
			if($this->perm_exists($id, $page))
			{
				$perm = new Permission();
				$perm->role_id = $id;
				$perm->page_id = $page;
				$perm->save() ? $perm_error = false : $perm_error = true;
			}

		}

		if(!$perm_error)
		{
			Session::put('success', 'Role permissions updated.');
			return redirect()->route('admin.perm');
		}

		Session::put('error', 'Unable to update role permissions.');
		return redirect()->back();
	}
}
?>

