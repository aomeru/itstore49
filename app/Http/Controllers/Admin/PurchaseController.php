<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Traits\CommonTrait;
use App\Traits\AclTrait;

use App\Models\Purchase;
use App\Models\Plog;

use Session;
use Auth;
use Crypt;
use Storage;

class PurchaseController extends Controller
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
		$this->create_allow = $this->acl['purchase']['create'];
		$this->edit_allow = $this->acl['purchase']['edit'];
	    $this->view_allow = $this->acl['purchase']['view'];
	    $this->delete_allow = $this->acl['purchase']['delete'];
	    $this->show_allow = $this->acl['purchase']['show'];
		
		$this->lcreate_allow = $this->acl['plog']['create'];
		$this->ledit_allow = $this->acl['plog']['edit'];
	    $this->lview_allow = $this->acl['plog']['view'];
	    $this->ldelete_allow = $this->acl['plog']['delete'];
	    $this->lshow_allow = $this->acl['plog']['show'];
	}


    public function index()
    {
        $this->log(Auth::user()->id, 'Opened the purchase order page.', Request()->path());

        return view('admin.purchase.index', [
            'list' => Purchase::get(),
            'exp' => Purchase::sum('total'),
            'nav' => 'purchase-order',
            'create_allow' => $this->create_allow,
            'edit_allow' => $this->edit_allow,
            'view_allow' => $this->view_allow,
            'delete_allow' => $this->delete_allow,
            'show_allow' => $this->show_allow,
        ]);
    }


    public function create()
    {

        if(!in_array(Auth::user()->username,$this->create_allow))
        {
            $this->log(Auth::user()->id, 'RESTRICTED! Tried to access the create purchase order page', Request()->path());
            $this->ad();
            return redirect()->back();
        }

        $this->log(Auth::user()->id, 'Opened the purchase order page.', Request()->path());

        return view('admin.purchase.add', ['nav' => 'purchase-order']);
    }


    public function store(Request $r)
	{

		if(!in_array(Auth::user()->username,$this->create_allow))
        {
            $this->log(Auth::user()->id, 'RESTRICTED! Tried to create a purchase order page', Request()->path());
            $this->ad();
            return redirect()->route('admin.po');
        }

       $this->validate($r, [
			'po_title' => 'required|unique:purchases,title',
			'po_total' => 'nullable|numeric',
			'po_pofile' => 'nullable|mimes:pdf',
			'po_dnfile' => 'nullable|mimes:pdf',
			'po_invfile' => 'nullable|mimes:pdf',
		]);

		$item = new Purchase();
		$pofile = null;
		$dnfile = null;
		$invfile = null;
		$item->title = $r->po_title;
		$item->total = $r->po_total;
		$item->user_id = Auth::user()->id;

		$list = array('po','dn','inv');
		$title = $this->clean(ucwords($r->po_title));
		
		foreach($list as $a)
		{
			if($r->hasFile('po_'.$a.'file'))
			{
				$v = 'po_'.$a.'file';
				$d = $a.'_at';
				$e = $r->file($v)->extension();
				$t = $title.'_'.$a.'_'.str_shuffle(time()).'.'.$e;
				$r->$v->storeAs('public/purchase',$t);
				$item->$a = $t;
				$item->$d = $this->get_time();
			}
		}

		if($item->save()) { 
			$this->log(Auth::user()->id, 'Added purchase order entry "'.$item->title.'" and id .'.$item->id, $r->path());
			Session::put('success','Purchase order added');
			return redirect()->route('admin.po');
		}

		Session::put('error','Oops, an error occured by try again');
		return redirect()->back();
	}


	public function showFile($file)
	{
		return response()->file($file);
	}


	public function edit($id)
    {

        if(!in_array(Auth::user()->username,$this->edit_allow))
        {
            $this->log(Auth::user()->id, 'RESTRICTED! Tried to edit a purchase order page', Request()->path());
            $this->ad();
            return redirect()->back();
		}
		
		$id = Crypt::decrypt($id);
		$item = Purchase::find($id);

		if($item == null)
		{
			Session::put('error','This purchase order record does not exist');
			return  redirect()->back();
		}

        $this->log(Auth::user()->id, 'Opened the "'.$item->title.'" purchase order page.', Request()->path());

        return view('admin.purchase.edit', ['item' => $item, 'nav' => 'purchase-order']);
	}
	

	public function update(Request $r, $id)
	{

		$id = Crypt::decrypt($id);
		$item = Purchase::find($id);

		if($item == null)
		{
			Session::put('error','This purchase order record does not exist');
			return  redirect()->back();
		}

		if(!in_array(Auth::user()->username,$this->edit_allow))
        {
            $this->log(Auth::user()->id, 'RESTRICTED! Tried to update a purchase order record', Request()->path());
            $this->ad();
            return redirect()->route('admin.po');
        }

        $this->validate($r, [
			'po_title' => 'required|unique:purchases,title',
			'po_total' => 'nullable|numeric',
			'po_pofile' => 'nullable|mimes:pdf',
			'po_dnfile' => 'nullable|mimes:pdf',
			'po_invfile' => 'nullable|mimes:pdf',
		]);


		$ptitle = $item->title;
		$npo = false;
		$ndn = false;
		$ninv = false;


		
		$item->title = $r->po_title;
		$item->total = $r->po_total;

		$list = array('po','dn','inv');
		$title = $this->clean(ucwords($r->po_title));
		
		foreach($list as $a)
		{
			if($r->hasFile('po_'.$a.'file'))
			{
				if($item->$a != null)
				{
					Storage::delete('public/purchase/'.$item->$a);
				}

				$v = 'po_'.$a.'file';
				$n = 'n'.$a;
				$d = $a.'_at';
				$e = $r->file($v)->extension();
				$t = $title.'_'.$a.'_'.str_shuffle(time()).'.'.$e;
				$r->$v->storeAs('public/purchase',$t);
				$item->$a = $t;
				$item->$d = $this->get_time();
				$$n = true;
			}
		}

		if($item->update()) {
			$msg = 'Updated purchase order entry from "'.$ptitle.'" to "'.$item->title.'"';
			if($npo) $msg .= ', updated the purchase order file';
			if($ndn) $msg .= ', updated the delivery note file';
			if($ninv) $msg .= ', updated the invoice file';
			$msg .= '; on ID: '.$item->id;

			$this->log(Auth::user()->id, $msg, $r->path());
			Session::put('success','Purchase order updated');
			return redirect()->route('admin.po');
		}

		Session::put('error','Oops, an error occured by try again');
		return redirect()->back();
	}


	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a purchase order item', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->po_id);
		$item = Purchase::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase order item does not exist']]), 400);

        if($item->plog != null) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete '.$item->title.' purchase order log records first']]), 400);

		$did = $item->id;
		$ditem = $item->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$ditem.'" purchase order which had id: '.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Purchase order deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function show($id)
	{

		if(!in_array(Auth::user()->username,$this->lshow_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to access the purchase order page', Request()->path());
			 $this->ad();
            return redirect()->back();
		}


		$id = Crypt::decrypt($id);
		$item = Purchase::find($id);

		if($item == null)
		{
			Session::put('error','This purchase order item does not exist');
			return redirect()->back();
		}

		$this->log(Auth::user()->id, 'Opened the '.$item->title.' purchase order page.', Request()->path());

        return view('admin.purchase.show', [
            'item' => $item,
			'nav' => 'purchase-order',
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
		$po = Purchase::find($id);

		if($po == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase order item does not exist']]), 400);

		if(!in_array(Auth::user()->username, $this->lcreate_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to add log comment on "'.$po->title.' ('.$inv->item->title.')"', $r->path());
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

		$log = new Plog();
		$log->purchase_id = $po->id;
		$log->comment = $r->comment;
		$log->user_id = Auth::user()->id;

		if($log->save()) {
			$this->log(Auth::user()->id, 'Added log comment for purchase order item "'.$po->title.'" with id: '.$log->id, $r->path());
			return response()->json(array('success' => true, 'message' => 'Purchase order log added'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function updateLog(Request $r)
	{
		$id = Crypt::decrypt($r->com_id);
		$item = Plog::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This log item does not exist.']]), 400);

		if(!in_array(Auth::user()->username, $this->ledit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to edit purchase order log comment on "'.$item->purchase->title.'".', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		if(Auth::user()->id != $item->user_id)
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to edit purchase order log item for "'.$item->purchase->title.'" not owned.', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase log item is not owned by you']]), 400);
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
			$this->log(Auth::user()->id, 'Updated purchase order log comment from "'.$pcomment.'" to "'.$item->comment.'", on log id .'.$item->id, $r->path());
			return response()->json(array('success' => true, 'message' => $item->purchase->title.' purchase order log item updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteLog(Request $r)
	{
		$id = Crypt::decrypt($r->com_id);
		$item = Plog::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This comment does not exist.']]), 400);

		if(!in_array(Auth::user()->username, $this->ldelete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete purchase order log comment on "'.$item->purchase->title.'".', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$did = $item->id;
		$dtitle = $item->purchase->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" purchase order log comment with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Purchase order log comment deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteFile(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->ldelete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a purchase order file', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->poid);
		$item = Purchase::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This purchase order item does not exist']]), 400);

		$option = $r->option;
		switch($option)
		{
			case 'po':
			$title = 'Purchase Order';
			break;

			case 'dn':
			$title = 'Delivery Note';
			break;

			case 'inv':
			$title = 'Invoice';
			break;
		}

        Storage::delete('public/purchase/'.$item->$option);

		$did = $item->id;
		$ditem = $item->title.' purchase order '.$title;

		$item->$option = null;

		if($item->update()){ $this->log(Auth::user()->id, 'Deleted "'.$ditem.'"; with id: '.$did, $r->path()); return response()->json(array('success' => true, 'message' => $title.' file deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}
}
