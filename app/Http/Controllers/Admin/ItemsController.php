<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Traits\AclTrait;
use App\Models\Item;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
use Auth;

class ItemsController extends Controller
{
    use CommonTrait;
    use AclTrait;

    protected $create_allow;
	protected $edit_allow;
    protected $view_allow;
    protected $delete_allow;
    protected $show_allow;

    protected $item_types = array('Laptop','Monitor','Desktop','Workstation','Wireless Keyboard & Mouse','Keyboard','Mouse','Printer','Stand','Accessories','Plotter','Toner','Mobile Phone','Docking Station');
    protected $item_processor = array('None','Core i5','Core i3','Core i7');
	protected $typet = '';
	protected $prot = '';

    public function __construct()
    {
		$this->create_allow = $this->acl['item']['create'];
		$this->edit_allow = $this->acl['item']['edit'];
	    $this->view_allow = $this->acl['item']['view'];
	    $this->delete_allow = $this->acl['item']['delete'];
		$this->show_allow = $this->acl['item']['show'];
		
        sort($this->item_types);
        sort($this->item_processor);

		$tt = '';
		$tp = '';

		foreach ($this->item_types as $v) { $tt .= $v.','; }
		foreach ($this->item_processor as $v) { $tp .= $v.','; }

		$this->typet = substr($tt,0,-1);
		$this->prot = substr($tp,0,-1);
    }


    public function index()
    {

		$this->log(Auth::user()->id, 'Opened the items page.', Request()->path());

        return view('admin.items', [
            'list' => Item::orderby('title')->get(),
            'nav' => 'items',
            'item_types' => $this->item_types,
			'item_processor' => $this->item_processor,
			'reorder' => $this->check_inv(),

			'create_allow' => $this->create_allow,
            'edit_allow' => $this->edit_allow,
            'view_allow' => $this->view_allow,
            'delete_allow' => $this->delete_allow,
            'show_allow' => $this->show_allow,
        ]);

    }


    public function storeItem(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to create an Item', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$rules = array(
			'item_name' => 'required|regex:/^([a-zA-Z0-9&-\' ]+)$/|unique:items,title',
			'item_type' => 'required',
			'item_descrip' => 'max:150',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		if(!in_array($r->item_type,$this->item_types)) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please select an Item type that is listed.']]), 400);
		if(!empty($item_processor) && !in_array($r->item_processor,$this->item_processor)) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please select an Item processor that is listed.']]), 400);

		$item = new Item();
		$item->title = ucwords($r->item_name);
		$item->type = ucwords($r->item_type);

		if($r->item_processor == 'none' || empty($r->item_processor)) $pro = ''; else $pro = ucwords($r->item_processor);
		$item->processor = $pro;
		$item->descrip = ucfirst($r->item_descrip);
        $item->user_id = Auth::user()->id;

		if($item->save()) { $this->log(Auth::user()->id, 'Created "'.$item->title.'" item with id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Item created'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


    public function updateItem(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update an Item', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$item = Item::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item does not exist.']]), 400);

		$rules = array(
			'item_name' => 'required|regex:/^([a-zA-Z0-9&-\' ]+)$/|unique:items,title,'.$item->id,
			'item_type' => 'required',
			'item_descrip' => 'max:150',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		if(!in_array($r->item_type,$this->item_types)) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please select an Item type that is listed.']]), 400);
		if(!empty($item_processor) && !in_array($r->item_processor,$this->item_processor)) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please select an Item processor that is listed.']]), 400);

        $ptitle = $item->title;
        $ptype = $item->type;
        $ppro = $item->processor;
        $pdescrip = $item->descrip;

		$item->title = ucwords($r->item_name);
		$item->type = ucwords($r->item_type);
		if($r->item_processor == 'none' || empty($r->item_processor)) $pro = ''; else $pro = ucfirst($r->item_processor);
		$item->processor = $pro;
		$item->descrip = ucwords($r->item_descrip);

		if($item->update())
		{
			$this->log(Auth::user()->id,
				'Updated item title from "'.$ptitle.'" to "'.$item->title.'",
				from "'.$ptype.'" type to "'.$item->type.'" type,
				from "'.$ppro.'" processor to "'.$item->processor.'" processor,
				from "'.$pdescrip.'" description to "'.$item->descrip.'" description,
				with id .'.$item->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => $item->title.' item updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteItem(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete an item', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$item = Item::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This item does not exist.']]), 400);

		if($item->inventory->count() > 0) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete '.$item->title.' inventory first.']]), 400);

		$did = $item->id;
		$dtitle = $item->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" item with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Item deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}
}
