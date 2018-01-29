<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Auth;
use Session;
use App\User;
use App\Models\Inventory;
use App\Models\Allocation;
use App\Models\Log;
use App\Models\Task;
use App\Models\Item;

use App\Traits\CommonTrait;

class DashboardController extends Controller
{
    use CommonTrait;

	protected $delete_allow = array('Developer','Administrator');
    protected $edit_allow = array('Developer','Administrator','Editor');
	protected $task_allow = array('Developer','Supervisor');


	public function index()
	{

		return view('admin.dashboard', [
			'logs' => Log::where('user_id',Auth::user()->id)->orderby('created_at','desc')->limit(5)->get(),
			'tasks' => in_array(Auth::user()->role->title, $this->task_allow) ? Task::orderby('created_at','desc')->limit(5)->get() : Task::where('user_id',Auth::user()->id)->orderby('created_at','desc')->limit(5)->get(),
			'task_allow' => $this->task_allow,
			'ftask' => Task::where('status','closed')->count(),
			'tall' => Allocation::count(),
			'rall' => Allocation::limit(10)->get(),
			'tinv' => Inventory::count(),
			'ttask' => Task::count(),
			'reorder' => $this->check_inv(),
		]);
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
        $this->log(Auth::user()->id, 'Logged out', Request()->path());
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


	public function logs()
    {
		return view('admin.logs', ['logs' => Log::get(), 'nav' => 'logs']);
    }
}
