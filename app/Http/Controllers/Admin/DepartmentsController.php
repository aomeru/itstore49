<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Models\Unit;
use App\Models\Department;
use App\Models\Allocation;
use App\Models\Task;
use Session;
use Crypt;
use Illuminate\Support\Facades\Validator;
//use Request;
use Auth;


class DepartmentsController extends Controller
{
    use CommonTrait;

    protected $delete_allow = array('Developer','Administrator');
    protected $edit_allow = array('Developer','Administrator','Editor');

    public function index() {

		$this->log(Auth::user()->id, 'Opened the departments and units page.', Request()->path());

        return view('admin.departments.index', [
            'depts' => Department::orderby('title')->get(),
            'units' => Unit::orderby('title')->get(),
            'nav' => 'departments-and-units',
			'edit_allow' => $this->edit_allow,
			'delete_allow' => $this->delete_allow,
        ]);

    }


    public function storeDept(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to create a department', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$rules = array(
			'dept_name' => 'required|regex:/^([a-zA-Z&\', ]+)$/|unique:departments,title',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$item = new Department();
		$item->title = ucwords($r->dept_name);

		if($item->save()) { $this->log(Auth::user()->id, 'Created '.$item->title.' department with id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Department added'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteDept(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a department', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$dept = Department::find($id);

        if($dept == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This department does not exist.']]), 400);

		if($dept->units->count() > 0) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete '.$dept->title.' sub-units first.']]), 400);

		$did = $dept->id;
		$dtitle = $dept->title;

		if($dept->delete()){ $this->log(Auth::user()->id, 'Deleted '.$dtitle.' department with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Department deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function updateDept(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update a department', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->dept_id);
		$dept = Department::find($id);

		if($dept == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This department does not exist.']]), 400);


		$rules = array(
			'dept_name' => 'required|regex:/^([a-zA-Z&\', ]+)$/|unique:departments,title,'.$dept->id,
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$ptitle = $dept->title;
		$dept->title = ucwords($r->dept_name);

		if($dept->update()) { $this->log(Auth::user()->id, 'Updated department title from '.$ptitle.' to '.$dept->title.' with id .'.$dept->id, $r->path()); return response()->json(array('success' => true, 'message' => $dept->title.' department updated'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function storeUnit(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to create a unit', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		if($r->unit_dept_id == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please select a department.']]), 400);

		$id = Crypt::decrypt($r->unit_dept_id);
		$dept = Department::find($id);

		if($dept == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['The department you selected does not exist.']]), 400);

		$rules = array(
			'unit_name' => 'required|regex:/^([a-zA-Z&\', ]+)$/|unique:units,title',
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$item = new Unit();
		$item->title = ucwords($r->unit_name);
		$item->department_id = $dept->id;

		if($item->save()){ $this->log(Auth::user()->id, 'Created '.$item->title.' unit with id .'.$item->id, $r->path()); return response()->json(array('success' => true, 'message' => 'Unit created'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function deleteUnit(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->delete_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to delete a unit', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$id = Crypt::decrypt($r->item_id);
		$unit = Unit::find($id);

        if($unit == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This unit does not exist.']]), 400);

		if($unit->users->count() > 0) return response()->json(array('success' => false, 'errors' => ['errors' => ['Please delete '.$unit->title.' users first.']]), 400);

		$did = $unit->id;
		$dtitle = $unit->title;

		if($unit->delete()){ $this->log(Auth::user()->id, 'Deleted '.$dtitle.' unit with id .'.$did, $r->path()); return response()->json(array('success' => true, 'message' => 'Unit deleted'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


	public function updateUnit(Request $r)
	{
		if(!in_array(Auth::user()->role->title,$this->edit_allow))
		{
			$this->log(Auth::user()->id, 'RESTRICTED! Tried to update a unit', $r->path());
			return response()->json(array('success' => false, 'errors' => ['errors' => ['WARNING!!! YOU DO NOT HAVE ACCESS TO CARRY OUT THIS PROCESS']]), 400);
		}

		$unit_id = Crypt::decrypt($r->unit_id);
		$unit = Unit::find($unit_id);

		$dept = Department::where('title',$r->dept_title)->first();

		if($unit == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This unit does not exist.']]), 400);
		if($dept == null) return response()->json(array('success' => false, 'errors' => ['errors' => ['This department does not exist.']]), 400);

		$rules = array(
			'unit_name' => 'required|regex:/^([a-zA-Z&\', ]+)$/|unique:units,title,'.$unit->id,
		);
		$validator = Validator::make($r->all(), $rules);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 400);
		}

		$putitle = $unit->title;
		$pdtitle = $unit->department->title;

		$unit->title = ucwords($r->unit_name);
		$unit->department_id = $dept->id;

		if($unit->update()) {$this->log(Auth::user()->id, 'Updated unit title from '.$putitle.' to '.$unit->title.' and parent deparment from '.$putitle.' to '.$unit->department->title, $r->path()); return response()->json(array('success' => true, 'message' => $unit->title.' unit updated'), 200);}

		return response()->json(array('success' => false, 'errors' => ['errors' => ['Oops, something went wrong please try again']]), 400);
	}


    public function showDept($id) {

		$id = Crypt::decrypt($id);
		$dept = Department::find($id);

		if($dept == null)
		{
			Session::put('error','This department does not exist');
			return redirect()->back();
		}

		$units = array();
		foreach($dept->units as $unit)
		{
			array_push($units,$unit->id);
		}

		$alls = Allocation::whereHas('user', function($u) use ($units){
			$u->whereIn('unit_id',$units);
		})->get();

		$iss = Task::whereHas('client', function($u) use ($units){
			$u->whereIn('unit_id',$units);
		})->get();

		$this->log(Auth::user()->id, 'Opened the '.$dept->title.' department page.', Request()->path());

        return view('admin.departments.show', [
            'dept' => $dept,
            'alls' => $alls,
			'iss' => $iss,
            'nav' => 'departments-and-units',
			'edit_allow' => $this->edit_allow,
			'delete_allow' => $this->delete_allow,
        ]);

    }


	public function showUnit($id) {

		$id = Crypt::decrypt($id);
		$unit = Unit::find($id);

		if($unit == null)
		{
			Session::put('error','This unit does not exist');
			return redirect()->back();
		}

		$alls = Allocation::whereHas('user', function($u) use ($unit){
			$u->where('unit_id',$unit->id);
		})->get();

		$iss = Task::whereHas('client', function($u) use ($unit){
			$u->where('unit_id',$unit->id);
		})->get();

		$this->log(Auth::user()->id, 'Opened the '.$unit->title.' unit page.', Request()->path());

        return view('admin.departments.showUnit', [
            'unit' => $unit,
            'alls' => $alls,
            'iss' => $iss,
            'nav' => 'departments-and-units',
			'edit_allow' => $this->edit_allow,
			'delete_allow' => $this->delete_allow,
        ]);

    }
}
