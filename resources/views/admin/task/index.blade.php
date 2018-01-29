@extends('layouts.admin')
@section('title', 'Tasks')
@section('page_title') <i class="fa fa-tasks mr10"></i>Tasks @endSection



@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Tasks</li>
</ol>
@endsection


@section('content')

<div class="card mb50">

	<div class="card-block">
		@if(in_array(Auth::user()->username,$edit_allow))
			<div class="mb10">
				<div class="pull-right">
					<button class="btn btn-primary btn-sm no-margin" title="Assign new task" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus"></i></button>
				</div>
				<div class="clearfix"></div>
			</div>
		@endif


		@if ($list->count() == 0)

			<p class="alert alert-info">No task record found.</p>

		@else

			<div class="table-responsive">

				<table class="table table-bordered table-hover nowrap data-table" width="100%" data-page-length="50">

					<thead>
						<tr class="active">
							<th>#</th>
							<th>Task ID</th>
							<th>Title</th>
							<th>Type</th>
							<th>Client</th>
							<th>Inventory</th>
							<th>Owner</th>
							<th>Status</th>
							<th>Created</th>
							@if(in_array(Auth::user()->username,$edit_allow)) <th class="text-right">Actions</th> @endif
						</tr>
					</thead>

					<tbody>

						@php $row_count = 1 @endphp

						@foreach($list as $item)

							<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-id="{{Crypt::encrypt($item->id)}}" data-title="{{$item->title}}" data-type="{{$item->type}}" data-client="{{$item->client->email}}" data-owner="{{$item->user->email}}" data-serial-no="{!!$item->inventory == null ? '' : $item->inventory->serial_no!!}" data-status="{{$item->status}}">

								<td>{{ $row_count }}</td>

								<td><u><a href="{{route('admin.tasks.show', Crypt::encrypt($item->id))}}" class="c-06f">{{$item->id}}</a></u></td>

								<td>{{$item->title}} <span class="ml5 badge badge-primary font-no-bold">{{ $item->comments == null ? 0 : $item->comments->count() }}</span></td>

								<td>{{$item->type}}</td>

								<td>
									@if(in_array(Auth::user()->username, $edit_allow)) <u><a href="{{route('admin.users.show', Crypt::encrypt($item->client_id))}}" class="c-06f">{{$item->client->firstname.' '.$item->client->lastname}}</a></u> @else {{$item->client->firstname.' '.$item->client->lastname}} @endif
								</td>

								<td>
									@if($item->inventory == null) <em class="c-999">Null</em> @else {{$item->inventory->item->title.' / '.$item->inventory->serial_no}} @endif
								</td>

								<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->user_id))}}" class="c-06f">{{$item->user->firstname.' '.$item->user->lastname}}</a></u></td>

								<td class="text-center">
									@if ($item->status == 'opened')

										<span class="badge badge-warning font-no-bold padding-5 d-block">Open</span>

									@elseif ($item->status == 'unresolved')

										<span class="badge badge-danger font-no-bold padding-5 d-block">Unresolved</span>

									@else

										<span class="badge badge-success font-no-bold padding-5 d-block">Closed</span>

									@endif
								</td>

								<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>

								@if(in_array(Auth::user()->username,$edit_allow))
									<td class="text-right">

										<button class="btn btn-primary btn-sm" title="Edit {{ $item->title }} task" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-default btn-sm" title="Reassign {{ $item->title }} task" data-toggle="modal" data-target="#rass-modal"><i class="fa fa-refresh"></i></button>
										<button class="btn btn-danger btn-sm" title="Delete {{ $item->title }} task" data-toggle="modal" data-target="#delete-modal" @if(!in_array(Auth::user()->username,$delete_allow)) disabled @endif><i class="fa fa-trash"></i></button>

									</td>
								@endif

							</tr>

							@php $row_count++ @endphp

						@endforeach

					</tbody>

				</table>

			</div>

		@endif

	</div>

</div>

@endSection



@section('page_footer')
@if(in_array(Auth::user()->username,$create_allow))
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w450" role="document">
			<div class="modal-content">
				<form method="post">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Assign Task</h4>
				    </div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="task-title" class="form-control-label text-center sr-onlyy">Task Title</label>

							<input type="text" id="task-title" class="form-control" placeholder="Enter task title" data-validation="required"  data-validation-error-msg="Please enter a title for this task">
						</div>

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="task-type" class="form-control-label text-center sr-onlyy">Type</label>

									<select id="task-type" class="form-control chzn-select">
										<option value="">Select Task Type</option>
										@foreach($task_types as $type)
											<option value="{{$type}}">{{$type}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="task-status" class="form-control-label text-center sr-onlyy">Status</label>

									<select id="task-status" class="form-control chzn-select">
										<option value="">Select Task Status</option>
										@foreach($task_status as $status)
											<option value="{{$status}}">{{ucfirst($status)}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="form-group input_field_sections">
							<label for="task-owner" class="form-control-label text-center sr-onlyy">Assign To</label>

							<select id="task-owner" class="form-control chzn-select">
								<option value="">Assign to ...</option>
								@foreach($users as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}} / {{ $user->tasks == null ? '(0)' : '('.$user->tasks()->where('status','<>','resolved')->count().')' }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<label for="task-client" class="form-control-label text-center sr-onlyy">Client</label>

							<select id="task-client" class="form-control chzn-select">
								<option value="">Select Client ...</option>
								@foreach($staff as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}} / {{ $user->issues == null ? '(0)' : '('.$user->issues()->where('status','<>','resolved')->count().')' }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<label for="task-serial-no" class="form-control-label text-center sr-onlyy">Inventory</label>

							<select id="task-serial-no" class="form-control chzn-select">
								<option value="">Select Inventory ...</option>
								@foreach($invs as $inv)
									<option value="{{$inv->serial_no}}">{{$inv->serial_no}} / {{ $inv->item->title }}</option>
								@endforeach
							</select>

						</div>

						<span class="mt20 c-999 font-14x help-text" id="helpText">Leave the above field blank if the task isn't inventory related</span>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<button class="btn-success btn btn-block" id='add-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Assign</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endif

@if(in_array(Auth::user()->username,$edit_allow))
	<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w450" role="document">
			<div class="modal-content">
				<form method="post">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Task</h4>
				    </div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="task-title-edit" class="form-control-label text-center sr-onlyy">Task Title</label>

							<input type="text" id="task-title-edit" class="form-control" placeholder="Enter task title" data-validation="required"  data-validation-error-msg="Please enter a title for this task">
						</div>

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="task-type" class="form-control-label text-center sr-onlyy">Type</label>

									<select id="task-type-edit" class="form-control chzn-selectt">
										<option value="">Select Task Type</option>
										@foreach($task_types as $type)
											<option value="{{$type}}">{{$type}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="task-status-edit" class="form-control-label text-center sr-onlyy">Status</label>

									<select id="task-status-edit" class="form-control chzn-selectt">
										<option value="">Select Task Status</option>
										@foreach($task_status as $status)
											<option value="{{$status}}">{{ucfirst($status)}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="form-group input_field_sections">
							<label for="task-client-edit" class="form-control-label text-center sr-onlyy">Client</label>

							<select id="task-client-edit" class="form-control chzn-selectt">
								<option value="">Select Client ...</option>
								@foreach($staff as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}} / {{ $user->issues == null ? '(0)' : '('.$user->issues()->where('status','<>','resolved')->count().')' }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<label for="task-serial-no-edit" class="form-control-label text-center sr-onlyy">Inventory</label>

							<select id="task-serial-no-edit" class="form-control chzn-selectt">
								<option value="">Select Inventory ...</option>
								@foreach($invs as $inv)
									<option value="{{$inv->serial_no}}">{{$inv->serial_no}} / {{ $inv->item->title }}</option>
								@endforeach
							</select>
						</div>

						<span class="mt20 c-999 font-14x help-text" id="helpText">Leave the above field blank if the task isn't inventory related</span>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-danger btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="task-id-edit">
								<input type="hidden" id="row-id">
								<button class="btn-success btn btn-block" id='edit-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="rass-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w450" role="document">
			<div class="modal-content">
				<form method="post">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Reassign Task</h4>
				    </div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="task-owner-rass" class="form-control-label text-center sr-onlyy">Reassign To</label>

							<select id="task-owner-rass" class="form-control chzn-selectt">
								<option value="">Reassign to ...</option>
								@foreach($users as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}} / {{ $user->tasks == null ? '(0)' : '('.$user->tasks()->where('status','<>','resolved')->count().')' }}</option>
								@endforeach
							</select>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-danger btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="task-id-rass">
								<input type="hidden" id="row-id-rass">
								<button class="btn-success btn btn-block" id='rass-btn' type="submit" role="button"><i class="fa fa-refresh mr5"></i>Reassign</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endif

@if(in_array(Auth::user()->username,$delete_allow))
	<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">

					<div class="modal-body">

						<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" task?</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="task-row-id-delete">
								<input type="hidden" id="task-id-delete">
								<button class="btn-danger btn btn-block" id='delete-btn' type="submit" role="button"><i class="fa fa-trash mr5"></i>Delete</button>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
@endif

@endsection



@section('footer')
<script>
	$(function(){
		'use strict';

		$('.data-table').DataTable( {
			"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			"order": [[ 0 , "asc" ]]
		});
		$(".dataTables_wrapper").removeClass("form-inline");

		function getErrorMessage(jqXHR, exception)
		{
			var msg = '';
			if (jqXHR.responseJSON) {
				var errors = (jqXHR.responseJSON.errors);
				$.each(errors, function(key, value){
					msg = value[0];
				})
			} else if(jqXHR['errors']) {
				msg = jqXHR['errors'];
			} else if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network. <br>Please Contact Support Team.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]. <br>Please Contact Support Team.';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500]. <br>Please Contact Support Team.\n' + jqXHR.responseText;
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed. <br>Please Contact Support Team.';
			} else if (exception === 'timeout') {
				msg = 'Time out error';
			} else if (exception === 'abort') {
				msg = 'Request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			return msg;
		}

		function pnotify_alert(type, text)
		{
			var icon = 'fa-times';
			if(type == 'success'){
				icon = 'fa-check'
			}

			new PNotify({
				addclass: 'font-16x text-center',
				title: false,
				text: text,
				type: type,
				hide: true,
				icon: 'fa ' + icon + ' font-18x',
				delay: 5000,
				styling: 'bootstrap3',
				nonblock: {
					nonblock: true,
					nonblock_opacity: .5,
				}
			});
		}

		$(document).on('click', '#add-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				title = $("#task-title").val(),
				type = $("#task-type").val(),
				status = $("#task-status").val(),
				owner = $("#task-owner").val(),
				client = $("#task-client").val(),
				serial_no = $("#task-serial-no").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.add')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					title: title,
					type: type,
					status: status,
					owner: owner,
					client: client,
					serial_no: serial_no,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-modal').modal('hide');
					window.location.href = "{{route('admin.tasks')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#edit-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				title = tr.data('title'),
				type = tr.data('type'),
				status = tr.data('status'),
				client = tr.data('client'),
				serial_no = tr.data('serial-no'),
				task_id = tr.data('id'),
				hrid = tr.data('hrid');

			console.log(serial_no);

			//console.log(title + " " + type + " " + processor + " " + descrip);

			$("#task-title-edit").val(title);
			$("#task-type-edit").val(type);
			$("#task-status-edit").val(status);
			$("#task-client-edit").val(client);
			$("#task-serial-no-edit").val(serial_no);
			$("#task-id-edit").val(task_id);
			$("#row-id").val(hrid);

			//console.log($("#task-serial-no-edit").html());

		});

		$(document).on('click', '#edit-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				title = $("#task-title-edit").val(),
				type = $("#task-type-edit").val(),
				status = $("#task-status-edit").val(),
				client = $("#task-client-edit").val(),
				serial_no = $("#task-serial-no-edit").val(),
				task_id = $("#task-id-edit").val(),
				load_element = "#row-" + $("#row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.update')}}";

			// console.log(user_id);

			$.ajax({
				type: "POST",
				url: url,
				data: {
					title: title,
					status: status,
					type: type,
					client: client,
					serial_no: serial_no,
					task_id: task_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-modal').modal('hide');
					$(load_element).data('title',title);
					$(load_element).data('type',type);
					$(load_element).data('status',status);
					$(load_element).data('client',client);
					$(load_element).data('serial_no',serial_no);
					$(load_element).load(location.href + " "+ load_element +">*","");
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#delete-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('title'),
				hrid = tr.data('hrid'),
				task_id = tr.data('id');

			$("#delete-title").text(delete_title);
			$("#task-id-delete").val(task_id);
			$("#task-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				task_id = $('#task-id-delete').val(),
				remove_element = '#row-' + $("#task-row-id-delete").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.delete')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					task_id: task_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#delete-modal').modal('hide');
					pnotify_alert('success', response.message);
					$(remove_element).remove();
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#rass-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				owner = tr.data('owner'),
				task_id = tr.data('id'),
				hrid = tr.data('hrid');

			$("#task-owner-rass").val(owner);
			$("#task-id-rass").val(task_id);
			$("#row-id-rass").val(hrid);
		});

		$(document).on('click', '#rass-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				task_id = $('#task-id-rass').val(),
				owner = $('#task-owner-rass').val(),
				load_element = "#row-" + $("#row-id-rass").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.rass')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					owner: owner,
					task_id: task_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#rass-modal').modal('hide');
					$(load_element).data('owner',owner);
					pnotify_alert('success', response.message);
					$(load_element).load(location.href + " "+ load_element +">*","");
					//$(load_element).load(location.href + "#task-owner-rass>*","");
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

	});
</script>
@endSection
