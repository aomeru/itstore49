<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Auth;
use Session;
use App\User;
use Request;

use App\Traits\CommonTrait;

class DashboardController extends Controller
{
    use CommonTrait;

	public function index()
	{

		return view('admin.dashboard');
	}


	private function check_allow($user)
	{
		if(Auth::user()->role->title == 'Agent' && $user->role->title != 'User') return false;
		if(Auth::user()->role->title == 'Administrator' && $user->role->title == 'Developer') return false;
		if(Auth::user()->role->title == 'Administrator' && $user->role->title == 'G') return false;
		if(Auth::user()->role->title == 'Administrator' && $user->role->title == 'Administrator')
		{
			if($user->id != Auth::user()->id) return false;
		}
		return true;
	}


	public function logout()
	{
        $this->log(Auth::user()->id, 'Logged out', Request::path());
		Auth::logout();
		Session::flush();
		return redirect()->route('home');
	}


	public function test()
	{
		return view('test');
	}


    public function process(Request $r)
    {

    }
}
