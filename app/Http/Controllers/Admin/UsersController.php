<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

use App\Models\Role;
use App\Models\Unit;
use App\Models\Allocation;

use App\Traits\CommonTrait;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use Crypt;


class UsersController extends Controller
{
	use CommonTrait;

	protected $delete_allow = array('Developer');
    protected $edit_allow = array('Developer','Administrator');

    public function index()
    {
		$this->log(Auth::user()->id, 'Opened the users page.', Request()->path());

        return view('admin.users.index', [
			'nav' => 'users',
			'list' => User::orderBy('firstname')->get(),
			'roles' => Role::orderBy('title')->get(),
			'units' => Unit::orderBy('title')->get(),
			'delete_allow' => $this->delete_allow,
		]);
    }


	public function store(Request $r)
	{

		$rules = array(
			'staff_id' => 'nullable|regex:/^([a-zA-Z0-9-]*)$/|unique:users,staff_id',
			'role_id' => 'required|exists:roles,title',
			'firstname' => 'required|regex:/^([a-zA-Z0-9-]+)$/',
			'lastname' => 'required|regex:/^([a-zA-Z0-9-]+)$/',
			'email' => 'nullable|email|unique:users,email',
			'gender' => 'required|in:male,female',
			'unit_id' => 'nullable|exists:units,title',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$user = new User();
		$user->role_id = Role::where('title',$r->role_id)->value('id');
		$user->staff_id = $r->staff_id;
		$user->firstname = ucfirst($r->firstname);
		$user->lastname = ucfirst($r->lastname);
		$user->email = $r->email;
		$user->gender = $r->gender;
		$user->unit_id = Unit::where('title',$r->unit_id)->value('id');

		if($user->save()) { $this->log(Auth::user()->id, 'Added user "'.$user->firstname.' '.$user->lastname.'" user ID .'.$user->id, $r->path()); return response()->json(array('success' => true, 'message' => 'User added'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function update(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update a user account', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->user_id);
		$user = User::find($id);

		if($user->role->title == 'Developer' && Auth::user()->role->title != 'Developer')
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update Developer account', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		if($user == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This user does not exist.']]), 400);

		$rules = array(
			'staff_id' => 'nullable|regex:/^([a-zA-Z0-9-]*)$/|unique:users,staff_id,'.$user->id,
			'role_id' => 'required|exists:roles,title',
			'unit_id' => 'nullable|exists:units,title',
			'firstname' => 'required|regex:/^([a-zA-Z0-9-]+)$/',
			'lastname' => 'required|regex:/^([a-zA-Z0-9-]+)$/',
			'email' => 'nullable|email|unique:users,email,'.$user->id,
			'gender' => 'required|in:male,female',
			'status' => 'required|in:active,inactive,blocked',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$pfirstname = $user->firstname;
		$plastname = $user->lastname;
		$pemail = $user->email;
		$pgender = $user->gender;
		$prole = $user->role->title;
		$punit = $user->unit == null ? '' : $user->unit->title;
		$psid = $user->staff_id;
		$pstatus = $user->status;

		$user->firstname = $r->firstname;
		$user->lastname = $r->lastname;
		$user->email = $r->email;
		$user->gender = $r->gender;
		$user->role_id = Role::where('title',$r->role_id)->value('id');
		$user->unit_id = Unit::where('title',$r->unit_id)->value('id');
		$user->staff_id = $r->staff_id;
		$user->status = $r->status;

		if($user->update())
		{
			$this->log(Auth::user()->id,
				'Updated user account; firstname from "'.$pfirstname.'" to "'.$user->firstname.'",
				lastname from "'.$plastname.'" to "'.$user->lastname.'",
				email from "'.$pemail.'" to "'.$user->email.'",
				gender from "'.$pgender.'" to "'.$user->gender.'",
				account status from "'.$pstatus.'" to "'.$user->status.'",
				staff ID from "'.$psid.'" to "'.$user->staff_id.'",
				role from "'.$prole.'" to "'.$user->role->title.'",
				unit from "'.$punit.'" to unit with ID "'.$user->unit_id.'",
				on user id .'.$user->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => $user->firstname.' account updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a user account', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->user_id);
		$item = User::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This user does not exist.']]), 400);

		if($item->allocations->count() > 0) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete user allocations first']]), 400);

		$did = $item->id;
		$dtitle = $item->firstname.' '.$item->lastname;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" user account with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => $dtitle.' user account deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function show($id) {

		$id = Crypt::decrypt($id);
		$user = User::find($id);

		if($user == null)
		{
			Session::put('error','This user does not exist');
			return redirect()->back();
		}

		$alls = Allocation::where('user_id', $user->id)->get();

		$this->log(Auth::user()->id, 'Opened '.$user->firstname.' '.$user->lastname.' allocation page.', Request()->path());

        return view('admin.users.show', [
            'user' => $user,
            'alls' => $alls,
            'nav' => 'users',
			'edit_allow' => $this->edit_allow,
			'delete_allow' => $this->delete_allow,
        ]);

    }


	public function resetPassword(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to reset a user account password', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->user_id);
		$item = User::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This user does not exist.']]), 400);

		if($item->role->title == 'Developer' && Auth::user()->role->title != 'Developer')
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to reset Developer account password', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$item->password = '';

		if($item->update()){ $this->log(Auth::user()->id, 'Carried out password reset for account "'.$item->firstname.' '.$item->lastname.'" user account with id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Password reset successful'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


}
