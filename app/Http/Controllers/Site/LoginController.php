<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;


class LoginController extends Controller
{
	use CommonTrait;

    public function index()
	{
		if(Auth::check())
		{
			Auth::logout();
			Session::flush();
		}

		return view('site.login');
	}




	public function post(Request $r)
	{

		$user = User::where('email',$r->email)->first();

		if($user == null)
		{
			Session::put('error','This account does not exist on '.config('app.name'));
			return redirect()->back();
		}


		$limited = array('Staff','Department','Organization');
		if(in_array($user->role->title, $limited))
		{
			Session::put('error','You are not authorized to access this platform');
			$this->log($user->id, 'Tried to access the IT store without authorization', $r->path());
			return redirect()->back();
		}


		if($user->password == null)
		{
			Session::put('error','You need to create a password for your account.');
			$this->log($user->id, 'Authorized staff triggered the create password process', $r->path());
			return redirect()->route('create.pass');
		}


		if(Auth::attempt(['email' => $r['email'], 'password' => $r['password']]))
		{
			if(Auth::user()->status == 'blocked') {
				$this->log(Auth::user()->id, 'Tried to access the IT store with a blocked account', $r->path());
				Auth::logout();
				Session::put('error','Access denied! Your account has been bloced, please contact the administrator.');
				return redirect()->back();
			}

			$this->setMenu();
			$this->log(Auth::user()->id, 'Logged in', $r->path());
			return redirect()->route('admin.dashboard');
		}

		Session::put('error','Invalid Email and/or Password');
		return redirect()->back();
	}




	public function createPassword()
	{
		if(Auth::check())
		{
			Auth::logout();
			Session::flush();
		}

		return view('site.createpass');
	}




    public function storePassword(Request $r)
    {
		$this->validate($r, [
			'email' => 'bail|required|email',
			'password_confirmation' => 'required|min:6',
			'password' => 'required|min:6|same:password_confirmation',
		]);

        $user = User::where('email',$r->email)->first();

		if($user == null)
		{
			Session::put('error','This account does not exist on '.config('app.name'));
			return redirect()->back();
		}

		if($user->role->title == 'Staff')
		{
			Session::put('error','You are not authorized to access this platform');
			$this->log($user->id, 'Tried to access the IT store without authorization', $r->path());
			return redirect()->back();
		}



		if($user->password != null)
		{
			Session::put('error','Your account already has a password, please contact an administrator to reset it.');
			$this->log($user->id, 'Tried to replace own account password', $r->path());
			return redirect()->back();
		}


		if($user->status == 'blocked')
		{
			$this->log($user->id, 'Tried to change blocked own account password', $r->path());
			Session::put('error','Access denied! Your account has been bloced, please contact the administrator.');
			return redirect()->back();
		}


        $user->password = bcrypt($r['password_confirmation']);
		$user->status = 'active';

		if($user->update())
		{
			$this->log($user->id, 'Changed password for own account', $r->path());
			Session::put('success','Password successfully created please login.');
			return redirect()->route('login');
		}


		Session::put('error','Request unsuccessful.');
		return redirect()->back();

    }

}
