<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\AclTrait;

use App\Models\Allocation;
use App\Models\Inventory;
use App\User;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
use Auth;

class AllocationController extends Controller
{
	use CommonTrait;
	use AclTrait;

    protected $create_allow;
	protected $edit_allow;
    protected $view_allow;
    protected $delete_allow;
    protected $show_allow;

	public function __construct()
	{
		$this->create_allow = $this->acl['allocation']['create'];
		$this->edit_allow = $this->acl['allocation']['edit'];
	    $this->view_allow = $this->acl['allocation']['view'];
	    $this->delete_allow = $this->acl['allocation']['delete'];
	    $this->show_allow = $this->acl['allocation']['show'];
	}

	
    public function index()
    {
        $this->log(Auth::user()->id, 'Opened the allocation page.', Request()->path());

        return view('admin.allocation', [
            'list' => Allocation::orderby('created_at','desc')->get(),
            'invs' => Inventory::orderby('serial_no')->get(),
            'finvs' => Inventory::has('allocation',0)->orderby('serial_no')->get(),
            'users' => User::orderby('firstname')->get(),
            'nav' => 'allocation',
            'create_allow' => $this->create_allow,
            'edit_allow' => $this->edit_allow,
            'delete_allow' => $this->delete_allow,
        ]);
    }


    public function store(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to add an allocation', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

        if ($r->hasFile('approval')) return response()->json(array('success' => false, 'errors' => ['errors' => ['Has File']]), 400); else return response()->json(array('success' => false, 'errors' => ['errors' => ['No File '.$r->approval]]), 400);


		$rules = array(
			'serial_no' => 'required|exists:inventories',
			'email' => 'required|exists:users',
			'approval' => 'nullable',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$inv = Inventory::where('serial_no',$r->serial_no)->first();

		if($inv->allocation != null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item has already been allocated']]), 400);

		$item = new Allocation();
		$item->inventory_id = $inv->id;
		$item->user_id = User::where('email',$r->email)->value('id');
		$item->added_by = Auth::user()->id;

		if($item->save()) { $this->log(Auth::user()->id, 'Allocated item with serial number: #"'.$item->inventory->serial_no.' ('.$item->inventory->item->title.')" to user "'.$item->user->firstname.' '.$item->user->lastname.'" with allocation ID: .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Allocation Added'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function update(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update an Allocation entry', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$item = Allocation::find($id);
		$user = User::where('email',$r->email)->first();
		$inv = Inventory::where('serial_no',$r->serial_no)->first();

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This Allocation entry does not exists']]), 400);

		if($user == null || $inv == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['The selected item and/or user record does not exist']]), 400);

		$er = Allocation::where('id',$item->id)->where('inventory_id','<>',$inv->id)->first();

		if($er != null) return response()->json(array('success' => false, 'errors' => ['errors' => ['The item has already been allocated '.$er->id]]), 400);


		$pinv = $item->inventory_id;
		$puser = $item->user->firstname.' '.$item->user->lastname;

		$item->user_id = $user->id;
		$item->inventory_id = $inv->id;

		if($item->update())
		{
			$this->log(Auth::user()->id,
				'Updated allocation record entry with inventory ID from "'.$pinv.'" to "'.$item->inventory_id.'",
				and from "'.$puser.'" user to "'.$item->user->firstname.' '.$item->user->lastname.'",
				on id .'.$item->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => 'Allocation record updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete an allocation record', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$item = Allocation::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This allocation record does not exist.']]), 400);

		$did = $item->id;
		$duser = $item->user->firstname.' '.$item->user->lastname;
		$ditem = $item->inventory_id;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "#'.$did.'" allocation, of inventory with id: "'.$ditem.'" for "'.$duser.'"', $r->path()); return response()->json(array('success' => true, 'message' => 'Item deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}
}
