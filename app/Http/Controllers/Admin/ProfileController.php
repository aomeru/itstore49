<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Hash;


class ProfileController extends Controller
{

	public function updateProfile(Request $r)
	{
		$rules = array(
			'username' => 'bail|required|unique:users,username,'.Auth::user()->id,
			'email' => 'bail|required|email|unique:users,email,'.Auth::user()->id,
			'first_name' => 'required|alpha_dash|min:3|max:50',
			'last_name' => 'required|alpha_dash|min:3|max:50',
			'phone' => 'required|min:14|max:14|regex:/^([0-9+]{14})$/|unique:users,phone,'.Auth::user()->id,
			'gender' => 'required|in:male,female',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}
		if(!Auth::check())
		{
			return response()->json([
				'success' => false,
				'errors' => ['errors' => ['Please refresh your page, your login session has expired.']]
			], 400);
		}
		$user = Auth::user();
		$user->first_name = $r->first_name;
		$user->last_name = $r->last_name;
		$user->email = $r->email;
		$user->phone = $r->phone;
		$user->username = $r->username;
		$user->gender = $r->gender;

		if($user->update())
		{
			return response()->json(['success' => true], 200);
		}
		return response()->json([
			'success' => false,
			'errors' => ['errors' => ['Unable to update profile at this time, please try again later.']]
		], 400);
	}


	public function storePassword(Request $r)
	{
		$rules = array(
			'old_password' => 'required',
			'password_confirmation' => 'required|min:6',
			'password' => 'required|min:6|same:password_confirmation',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		if(!Hash::check($r['old_password'], Auth::User()->password))
		{
			return response()->json([
				'success' => false,
				'errors' => ['errors' => ['Incorrect old password']]
			], 400);
		}

		$user = Auth::User();
		$user->password = bcrypt($r['password_confirmation']);

		if($user->save())
		{
			Auth::logout();
			Session::flush();
			Session::put('home_flash', 'Password updated please login to access your account.');
			return response()->json(['success' => true], 200);
		}
		return response()->json([
			'success' => false,
			'errors' => ['errors' => ['Unable to update your password at this time, please try again later.']]
		], 400);
	}

}
