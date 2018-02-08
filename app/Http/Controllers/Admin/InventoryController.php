<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\AclTrait;

use App\Models\Item;
use App\Models\Inventory;
use App\Models\Ilog;
use App\Models\Purchase;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
use Auth;

class InventoryController extends Controller
{
    use CommonTrait;
    use AclTrait;

    protected $create_allow;
	protected $edit_allow;
    protected $view_allow;
    protected $delete_allow;
    protected $show_allow;
	
	protected $lcreate_allow;
	protected $ledit_allow;
    protected $lview_allow;
    protected $ldelete_allow;
    protected $lshow_allow;

	public function __construct()
	{
		$this->create_allow = $this->acl['inventory']['create'];
		$this->edit_allow = $this->acl['inventory']['edit'];
	    $this->view_allow = $this->acl['inventory']['view'];
	    $this->delete_allow = $this->acl['inventory']['delete'];
	    $this->show_allow = $this->acl['inventory']['show'];
		
		$this->lcreate_allow = $this->acl['ilog']['create'];
		$this->ledit_allow = $this->acl['ilog']['edit'];
	    $this->lview_allow = $this->acl['ilog']['view'];
	    $this->ldelete_allow = $this->acl['ilog']['delete'];
	    $this->lshow_allow = $this->acl['ilog']['show'];
	}


    public function index()
    {

		$this->log(Auth::user()->id, 'Opened the items page.', Request()->path());

        return view('admin.inventory.index', [
            'invs' => Inventory::orderby('serial_no')->get(),
            'items' => Item::orderby('title')->get(),
            'po' => Purchase::orderby('title')->get(),
            'nav' => 'inventory',
            'create_allow' => $this->create_allow,
            'edit_allow' => $this->edit_allow,
            'view_allow' => $this->view_allow,
            'delete_allow' => $this->delete_allow,
            'show_allow' => $this->show_allow,
        ]);

    }


    public function store(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->create_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to add an inventory', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		if($r->po_title != null)
		{
			$po = Purchase::where('title',$r->po_title)->first();
			if($po == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase order does not exist.']]), 400);
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
		if($r->po_title != null) $item->purchase_id = $po->id;
		$item->user_id = Auth::user()->id;

		if($item->save()) { $this->log(Auth::user()->id, 'Added inventory with serial number "'.$item->serial_no.'" and id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Inventory Added'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function update(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update an Inventory', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->inv_id);
		$item = Inventory::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item was not found in our inventory.']]), 400);

		if($r->po_title != null)
		{
			$po = Purchase::where('title',$r->po_title)->first();
			if($po == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase order does not exist.']]), 400);
		}

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
		$ppo = $item->purchase != null ? 'from '.$item->purchase->title : 'new';

		$item->serial_no = strtoupper($r->serial_no);
		$item->item_id = Item::where('title',$r->item_type)->value('id');
		if($r->po_title != null) $item->purchase_id = $po->id; else $item->purchase_id = null;

		if($item->update())
		{
			$this->log(Auth::user()->id,
				'Updated inventory item serial-no from "'.$psn.'" to "'.$item->serial_no.'", 
				from "'.$ptype.'" type to "'.$item->item->title.'",
				and "'.$ppo.'" purchase order to "'.$item->purchase->title.'", 
				with id .'.$item->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => 'Inventory item updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->delete_allow))
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


	public function show($id)
	{

		$id = Crypt::decrypt($id);
		$item = Inventory::find($id);

		if($item == null)
		{
			Session::put('error','This inventory does not exist');
			return redirect()->back();
		}

		$this->log(Auth::user()->id, 'Opened the '.$item->item->title.' (#'.$item->serial_no.') inventory page.', Request()->path());

        return view('admin.inventory.show', [
            'item' => $item,
			'nav' => 'inventory',
			'create_allow' => $this->lcreate_allow,
            'edit_allow' => $this->ledit_allow,
            'view_allow' => $this->lview_allow,
			'delete_allow' => $this->ldelete_allow,
			'show_allow' => $this->lshow_allow,
        ]);

	}


	public function storeLog(Request $r)
	{
		$id = Crypt::decrypt($r->item_id);
		$inv = Inventory::find($id);

		if($inv == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This inventory item does not exist']]), 400);

		if(!in_array(Auth::user()->username, $this->lcreate_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to add log comment on "#'.$inv->serial_no.' ('.$inv->item->title.')".', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$rules = array(
			'comment' => 'required',
		);

		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$log = new Ilog();
		$log->inventory_id = $inv->id;
		$log->comment = $r->comment;
		$log->user_id = Auth::user()->id;

		if($log->save()) {
			$this->log(Auth::user()->id, 'Added log comment for inventory item "#'.$inv->serial_no.' ('.$inv->item->title.')" with id: '.$log->id, $r->path());
			return response()->json(array('success' => true, 'message' => 'Inventory log added'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function updateLog(Request $r)
	{
		$id = Crypt::decrypt($r->com_id);
		$item = Ilog::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This log item does not exist.']]), 400);

		if(!in_array(Auth::user()->username, $this->ledit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to edit inventory log comment on "#'.$item->inventory->serial_no.' ('.$item->inventory->item->title.')".', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		if(Auth::user()->id != $item->user_id)
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to edit inventory log item for "#'.$item->inventory->serial_no.' ('.$item->inventory->item->title.')" not owned.', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['This inventory log item is not owned by you']]), 400);
		}

		$rules = array(
			'comment' => 'required',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$pcomment = $item->comment;
		$item->comment = $r->comment;

		if($item->update())
		{
			$this->log(Auth::user()->id, 'Updated inventory log comment from "'.$pcomment.'" to "'.$item->comment.'", on log id .'.$item->id, $r->path());
			return response()->json(array('success' => true, 'message' => '#'.$item->inventory->serial_no.' inventory log item updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteLog(Request $r)
	{
		$id = Crypt::decrypt($r->com_id);
		$item = Ilog::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This comment does not exist.']]), 400);

		if(!in_array(Auth::user()->username, $this->ldelete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete inventory log comment on "#'.$item->inventory->serial_no.' ('.$item->inventory->item->title.')".', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$did = $item->id;
		$dtitle = $item->inventory->item->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" inventory log comment with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Inventory log comment deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


}
