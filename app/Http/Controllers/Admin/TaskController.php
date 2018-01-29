<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\AclTrait;

use App\Models\Task;
use App\Models\Comment;
use App\Models\Inventory;
use App\User;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
use Auth;

class TaskController extends Controller
{
    use CommonTrait;
	use AclTrait;

    protected $owners = array('Developer','Supervisor','Administrator','Editor','Manager');
    protected $task_types = array('Repairs','License','Network Connectivity','Application Support');
    protected $task_status = array('opened','unresolved','closed');
    protected $action_status = array('pending','reassigned','escalated','completed');
	protected $statust = '';
	protected $astatust = '';
	protected $typet = '';


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
        sort($this->task_types);

		$tt = '';
		$ts = '';
		$tas = '';

		foreach ($this->task_types as $v) { $tt .= $v.','; }
		foreach ($this->task_status as $v) { $ts .= $v.','; }
		foreach ($this->action_status as $v) { $tas .= $v.','; }

		$this->typet = substr($tt,0,-1);
		$this->statust = substr($ts,0,-1);
		$this->astatust = substr($tas,0,-1);

		$this->create_allow = $this->acl['task']['create'];
		$this->edit_allow = $this->acl['task']['edit'];
	    $this->view_allow = $this->acl['task']['view'];
	    $this->delete_allow = $this->acl['task']['delete'];
	    $this->show_allow = $this->acl['task']['show'];
		
		$this->lcreate_allow = $this->acl['comment']['create'];
		$this->ledit_allow = $this->acl['comment']['edit'];
	    $this->lview_allow = $this->acl['comment']['view'];
	    $this->ldelete_allow = $this->acl['comment']['delete'];
	    $this->lshow_allow = $this->acl['comment']['show'];
    }


    public function index()
    {
        $this->log(Auth::user()->id, 'Opened the task page.', Request()->path());

		$list = in_array(Auth::user()->username,$this->edit_allow) ? Task::orderby('created_at','desc')->get() : Task::where('user_id',Auth::user()->id)->orderby('created_at','desc')->get();

        return view('admin.task.index', [
            'list' => $list,
            'users' => User::whereHas('role', function($r){
				$r->whereIn('title',$this->owners);
			})->orderby('firstname')->get(),
            'staff' => User::orderby('firstname')->get(),
            'invs' => Inventory::orderby('serial_no')->get(),
            'nav' => 'tasks',
            'task_types' => $this->task_types,
			'task_status' => $this->task_status,
			'create_allow' => $this->create_allow,
            'edit_allow' => $this->edit_allow,
            'view_allow' => $this->view_allow,
            'delete_allow' => $this->delete_allow,
            'show_allow' => $this->show_allow,
        ]);
    }


    public function store(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to assign a task', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$rules = array(
			'title' => 'required',
			'type' => 'in:'.$this->typet,
			'status' => 'in:'.$this->statust,
			'owner' => 'email|exists:users,email',
			'client' => 'email|exists:users,email',
			'serial_no' => 'nullable|exists:inventories,serial_no',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$item = new Task();
		$item->title = ucwords($r->title);
		$item->type = ucwords($r->type);
		$item->status = $r->status;
		$item->user_id = User::where('email',$r->owner)->value('id');
		$item->client_id = User::where('email',$r->client)->value('id');
		if($r->serial_no != null) $item->inventory_id = Inventory::where('serial_no',$r->serial_no)->value('id');
		$item->assigned_by = Auth::user()->id;

		if($item->save()) {
			$com = new Comment();
			$com->task_id = $item->id;
			$com->comment = 'Task opened and assigned to '.$item->user->firstname.' '.$item->user->lastname;
			$com->autogen = 1;
			$com->user_id = Auth::user()->id;
			$com->save();

			$this->log(Auth::user()->id, 'Assigned task "'.$item->title.'" item with id .'.$item->id.' to "'.$item->user->firstname.' '.$item->lastname.'"', $r->path());

			return response()->json(array('success' => true, 'message' => 'Task assigned'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function update(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update a task', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->task_id);
		$item = Task::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This task does not exist.']]), 400);

		$rules = array(
			'title' => 'required',
			'type' => 'in:'.$this->typet,
			'status' => 'in:'.$this->statust,
			'client' => 'email|exists:users,email',
			'serial_no' => 'nullable|exists:inventories,serial_no',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}


		if($r->status == 'closed')
		{
			$last_com = $item->comments()->orderBy('created_at','desc')->first();
			if($last_com->status != 'completed') return response()->json(array('success' => false, 'errors' => ['errors' => ['You cannot update task status to closed until you have entered a "completed" status action point for this task.']]), 400);
		}

		$ptitle = $item->title;
		$ptype = $item->type;
		$pstatus = $item->status;
		$pclient = $item->client->firstname.' '.$item->client->lastname;
		$pserialno = $item->serial_no;

		$item->title = ucwords($r->title);
		$item->type = ucwords($r->type);
		$item->status = $r->status;
		$item->client_id = User::where('email',$r->client)->value('id');
		$item->inventory_id = $r->serial_no != null ? Inventory::where('serial_no',$r->serial_no)->value('id') : null;

		if($item->update())
		{
			if($item->status != $pstatus)
			{
				$com = new Comment();
				$com->task_id = $item->id;
				$com->comment = 'Task updated and status changed to '.$item->status;
				$com->user_id = Auth::user()->id;
				$com->autogen = 1;
				$com->save();
			}

			$this->log(Auth::user()->id,
				'Updated task title from "'.$ptitle.'" to "'.$item->title.'",
				from "'.$ptype.'" type to "'.$item->type.'" type,
				from "'.$pstatus.'" status to "'.$item->status.'",
				from "'.$pclient.'" client to "'.$item->client->firstname.' '.$item->client->lastname.'",
				from "'.$pserialno.'" serial number to "'.$item->serial_no.'",
				on task id .'.$item->id,
				$r->path());
			return response()->json(array('success' => true, 'message' => $item->title.' task updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function rass(Request $r)
	{
        if(!in_array(Auth::user()->username,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to reassign a task', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->task_id);
		$item = Task::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This task does not exist.']]), 400);

		if($item->status == 'closed') return response()->json(array('success' => false, 'errors' => ['errors' => ['This task is already marked as closed, you cannot reassign it']]), 400);

		$rules = array(
			'owner' => 'email|exists:users,email',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$powner = $item->user->firstname.' '.$item->user->lastname;

		$item->user_id = User::where('email',$r->owner)->value('id');

		if($item->update())
		{
			$com = new Comment();
			$com->task_id = $item->id;
			$com->comment = 'Task reassigned to '.$item->user->firstname.' '.$item->lastname;
			$com->user_id = Auth::user()->id;
			$com->status = 'reassigned';
			$com->autogen = 1;
			$com->save();

			$this->log(Auth::user()->id, 'Reassigned task owner from "'.$powner.'" to "'.$item->firstame.' '.$item->lastname.'", on task id .'.$item->id, $r->path());
			return response()->json(array('success' => true, 'message' => $item->title.' task reassigned'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}

	public function delete(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a task', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->task_id);
		$item = Task::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This task does not exist.']]), 400);

		if($item->status == 'closed') return response()->json(array('success' => false, 'errors' => ['errors' => ['This task is already marked as closed, you cannot delete it']]), 400);

		$did = $item->id;
		$dtitle = $item->title;

		$item->comments()->delete();
		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" task with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Task deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function show($id)
	{

		if(!in_array(Auth::user()->username,$this->lshow_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to access a department page', Request()->path());
			$this->ad();
            return redirect()->back();
		}

		$id = Crypt::decrypt($id);
		$task = Task::find($id);

		if($task == null)
		{
			Session::put('error','This task does not exist');
			return redirect()->back();
		}

		if(Auth::user()->role->title != 'Developer')
		{
			if(Auth::user()->id != $task->user_id)
			{
				Session::put('error','This task is not assigned to you.');
				$this->log(Auth::user()->id, 'RESTRICTED! Tried to access "'.$task->title.' ('.$task->id.')" task not owned.', Request()->path());
				return redirect()->route('admin.tasks');
			}
		}

		$faid = $task->comments()->first();

		$this->log(Auth::user()->id, 'Opened the "'.$task->title.' ('.$task->id.')" task page.', Request()->path());

        return view('admin.task.show', [
            'task' => $task,
			'actions' => Comment::where('task_id',$task->id)->orderby('created_at','desc')->paginate(10),
            'nav' => 'tasks',
			'faid' => $faid->id,
			'create_allow' => $this->lcreate_allow,
            'edit_allow' => $this->ledit_allow,
            'view_allow' => $this->lview_allow,
			'delete_allow' => $this->ldelete_allow,
			'show_allow' => $this->lshow_allow,
			'action_status' => $this->action_status,
        ]);

    }


	public function storeComment(Request $r)
	{
		$id = Crypt::decrypt($r->task_id);
		$task = Task::find($id);

		if($task == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This task does not exist']]), 400);

		if($task->status == 'closed') return response()->json(array('success' => false, 'errors' => ['errors' => ['Action point cannot be added to this task as it has been marked closed']]), 400);

		if(!in_array(Auth::user()->username,$this->lcreate_allow))
		{
			if(Auth::user()->id != $task->user_id)
			{
				$this->log(Auth::user()->id, 'RESTRICTED! Tried to add action point on "'.$task->title.' ('.$task->id.')" task not owned.', $r->path());
				return response()->json(array('success' => false, 'errors' => ['errors' => ['This task is not assigned to you']]), 400);
			}

			//$this->log(Auth::user()->id, 'RESTRICTED! Tried to add action point on "'.$task->title.' ('.$task->id.')" task.', $r->path());
			//return response()->json(array('success' => false, 'errors' => ['errors' => ['You don\'t have permission to add action point to this task']]), 400);
		}

		$rules = array(
			'comment' => 'required',
			'status' => 'in:'.$this->astatust,
		);

		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$com = new Comment();
		$com->task_id = $task->id;
		$com->status = $r->status;
		$com->comment = $r->comment;
		$com->user_id = Auth::user()->id;

		if($com->save()) {
			$this->log(Auth::user()->id, 'Added an action point to the task "'.$com->title.'" with id: '.$com->id, $r->path());
			if($com->status == 'completed')
			{
				$task->status = 'closed';
				if($task->update())
				{
					$this->log(Auth::user()->id, 'Completed task action for "'.$task->title.'" issue with id: '.$task->id, $r->path());
				}
			}
			return response()->json(array('success' => true, 'message' => 'Comment added'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function updateComment(Request $r)
	{
		$id = Crypt::decrypt($r->com_id);
		$item = Comment::find($id);

		if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This comment does not exist.']]), 400);

		if($item->task->status == 'closed') return response()->json(array('success' => false, 'errors' => ['errors' => ['Action point cannot be edited for this task as it has been marked closed']]), 400);

		if(Auth::user()->id != $item->user_id)
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to edit action point on "'.$item->task->title.' (#'.$item->id.' action point)" task not owned.', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['This action point is not owned by you']]), 400);
		}

		$rules = array(
			'comment' => 'required',
			'status' => 'in:'.$this->astatust,
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$pstatus = $item->status;
		$pcomment = $item->comment;

		$item->status = $r->status;
		$item->comment = $r->comment;

		if($item->update())
		{
			// if($item->status == 'completed')
			// {
			// 	$item->task->status = 'closed';
			// 	if($item->task->update())
			// 	{
			// 		$this->log(Auth::user()->id, 'Completed task action for "'.$item->task->title.'" issue with id: '.$item->task->id, $r->path());
			// 	}
			// }

			$this->log(Auth::user()->id, 'Updated task action point comment from "'.$pcomment.'" to "'.$item->comment.'", on comment id .'.$item->id, $r->path());
			return response()->json(array('success' => true, 'message' => $item->task->title.' task action point updated'), 200);
		}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteComment(Request $r)
	{
		if(!in_array(Auth::user()->username,$this->ldelete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a task comment', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->com_id);
		$item = Comment::find($id);

        if($item == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This comment does not exist.']]), 400);

		if($item->autogen == 1) return response()->json(array('success' => false, 'errors' => ['errors' => ['This action point was automatically generated and cannot be deleted']]), 400);

		if($item->task->status == 'closed') return response()->json(array('success' => false, 'errors' => ['errors' => ['This task is already marked as closed, you cannot delete an action point']]), 400);

		$did = $item->id;
		$dtitle = $item->task->title;

		if($item->delete()){ $this->log(Auth::user()->id, 'Deleted "'.$dtitle.'" task action point with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Task action point deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}

}
