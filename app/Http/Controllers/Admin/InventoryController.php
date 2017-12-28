<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Models\Item;
use App\Models\Inventory;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
use Auth;

class InventoryController extends Controller
{
    use CommonTrait;

    protected $delete_allow = array('Developer','Administrator');
    protected $edit_allow = array('Developer','Administrator','Editor');

    public function index()
    {

		$this->log(Auth::user()->id, 'Opened the items page.', Request()->path());

        return view('admin.inventory', [
            'invs' => Inventory::orderby('serial_no')->get(),
            'items' => Item::orderby('title')->get(),
            'nav' => 'inventory',
            'edit_allow' => $this->edit_allow,
            'delete_allow' => $this->delete_allow,
        ]);

    }


    public function store(Request $r)
	{
        if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to add an inventory', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$rules = array(
			'serial_no' => 'required|regex:/^([a-zA-Z0-9- ]+)$/|unique:inventories,serial_no',
			'item_type' => 'required|exists:items,title',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$item = new Inventory();
		$item->serial_no = strtoupper($r->serial_no);
		$item->item_id = Item::where('title',$r->item_type)->value('id');
		$item->user_id = Auth::user()->id;

		if($item->save()) { $this->log(Auth::user()->id, 'Added inventory with serial number "'.$item->serial_no.'" and id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Inventory Added'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function update(Request $r)
	{
        if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update an Inventory', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->inv_id);
		$item = Inventory::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item was not found in our inventory.']]), 400);

		$rules = array(
			'serial_no' => 'required|regex:/^([a-zA-Z0-9- ]+)$/|unique:inventories,serial_no,'.$item->id,
			'item_type' => 'required|exists:items,title',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$psn = $item->serial_no;
		$ptype = $item->item->title;

		$item->serial_no = strtoupper($r->serial_no);
		$item->item_id = Item::where('title',$r->item_type)->value('id');

		if($item->update())
		{
			$this->log(Auth::user()->id,
				'Updated inventory item serial-no from "'.$psn.'" to "'.$item->serial_no.'",
				and from "'.$ptype.'" type to "'.$item->item->title.'",
				with id .'.$item->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => 'Inventory item updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete an inventory item', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->inv_id);
		$item = Inventory::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item was not found in our inventory.']]), 400);

        if($item->allocation != null) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete inventory allocation first']]), 400);

		$did = $item->id;
		$dsn = $item->serial_no;
		$ditem = $item->item->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$ditem.'" with serial number "'.$dsn.'" from the inventory which had id: '.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Inventory item deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


}
