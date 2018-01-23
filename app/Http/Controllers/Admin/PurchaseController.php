<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\AclTrait;

use App\Models\Purchase;

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
        dd('purchase page');
    }
}
