<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use App\Models\Roles;
use App\Traits\CommonTrait;
use App\Models\Page;
use App\Models\Permission;
use Illuminate\Support\Facades\Session;

class IsAdminMiddleware
{

	use CommonTrait;

	protected $ignore_list = array('admin/logout', 'admin/dashboard', 'admin/process', 'admin/test');

    public function handle($request, Closure $next)
    {
		if(!Auth::check())
		{
			return redirect()->route('home');
		}


		$limited = array('Staff','Department','Organization');
		if(in_array(Auth::user()->role->title, $limited))
		{
			$this->log(Auth::user()->id, 'Tried to access the IT store without authorization', $request->path());
			Auth::logout();
			Session::flush();
			return redirect()->route('home');
		}



		//dd('yes');



		$path = $request->path();
		$pathdata = explode('/',$path);


		if(!in_array($request->path(), $this->ignore_list))
		{
			$logged_in_role = Auth::User()->role_id;

			if(!$this->checkPage($request, $logged_in_role, $pathdata))
			{
				$this->log(Auth::user()->id, 'Tried to access a page tthe IT store without authorization', $request->path());
				Session::put('access_denied', 'YOU ARE NOT AUTHORIZED TO ACCESS THE REQUESTED PAGE.');
				return redirect()->route('admin.dashboard');
			}

		}
        return $next($request);
    }






	protected function checkPage($request, $logged_in_role, $pathdata)
	{
		//return true;

		if(count($pathdata) < 2) return true;

		$page = Page::where('slug',$pathdata[1])->first();
		if($page == null) return false;

		$exist = Permission::where('page_id',$page->id)->where('role_id',$logged_in_role)->first();

		return $exist != null ? true : false;
	}

}
