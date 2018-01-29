@extends('layouts.admin')
@section('title', $task->title.' Task')
@section('page_title') <i class="fa fa-th-large mr10"></i>{{$task->title}} @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item h-padding-5"><a href="{{route('admin.tasks')}}">Tasks</a></li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">{{$task->title}}</li>
</ol>
@endsection

@section('content')

	<div id="loadDiv">
		<div class="row">

			<div class="col-sm-4 xs-mb20">
				<div class="card">

					<div class="card-block">

						<dl class="row">
							<dt class="col-3">Owner</dt>
							<dd class="col-9"><u><a href="{{route('admin.users.show', Crypt::encrypt($task->user_id))}}" class="c-06f">{{$task->user->firstname.' '.$task->user->lastname}}</a></u></dd>

							<dt class="col-3">Inventory</dt>
							<dd class="col-9">@if($task->inventory == null) <em class="c-999">Null</em> @else {{$task->inventory->item->title.' / '.$task->inventory->serial_no}} @endif</dd>

							<dt class="col-3">Client</dt>
							<dd class="col-9">
								@if(in_array(Auth::user()->role->title, $edit_allow)) <u><a href="{{route('admin.users.show', Crypt::encrypt($task->client_id))}}" class="c-06f">{{$task->client->firstname.' '.$task->client->lastname}}</a></u> @else {{$task->client->firstname.' '.$task->client->lastname}} @endif
							</dd>

							<dt class="col-3">Department</dt>
							<dd class="col-9">
								@if($task->client->unit == null) <em class="c-999">Null</em> @else {{ $task->client->unit->title.' / '.$task->client->unit->department->title }} @endif
							</dd>

							<dt class="col-3">Assigned By</dt>
							<dd class="col-9">{{$task->assigned->firstname.' '.$task->assigned->lastname}}</dd>

							<dt class="col-3">Date</dt>
							<dd class="col-9">{{date('d-m-y, g:ia', strtotime($task->created_at))}}</dd>

							<dt class="col-3">Actions</dt>
							<dd class="col-9"><span class="ml5 badge badge-primary font-no-bold">{{ $task->comments == null ? 0 : $task->comments->count() }}</span></dd>

							<dt class="col-3">Last Action</dt>
							<dd class="col-9">{{date('d-m-y, g:ia', strtotime($task->created_at))}}</dd>

							<dt class="col-3">Status</dt>
							<dd class="col-9">
								@if ($task->status == 'opened')

									<span class="badge badge-warning padding-5 font-no-bold">Open</span>

								@elseif ($task->status == 'unresolved')

									<span class="badge badge-danger padding-5 font-no-bold">Unresolved</span>

								@else

									<span class="badge badge-success padding-5 font-no-bold">Closed</span>

								@endif
							</dd>
						</dl>
					</div>

				</div>
			</div>

			<div class="col-sm-8">

				<div class="card">

					<div class="card-header bgc-070">
						<h4 class="font-600 text-center no-padding no-margin text-uppercase c-fff">Actions</h4>
					</div>


					<div class="card-block">

						@if($task->status != 'closed')
							<div class="mb10">
								<div class="pull-right">
									<button class="btn btn-primary btn-sm no-margin" title="Add action point" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus"></i></button>
								</div>
								<div class="clearfix"></div>
							</div>
						@else
							<p class="alert alert-info">Action point cannot be added to this task as it has been marked closed.</p>
						@endif


						@if ($actions->count() == 0)

							<p class="alert alert-info">There is no action for this task.</p>

						@else

							<ul class="list-group mb15">
								@foreach ($actions as $act)
									<li class="list-group-item list-group-item-action flex-column align-items-start v-padding-20" id="row-{{$act->id}}" data-hrid="{{$act->id}}" data-id={{Crypt::encrypt($act->id)}} data-comment="{{$act->comment}}" data-status="{{$act->status}}">
										<div class="d-flex w-100 justify-content-between">
											<h4 style="color: inherit !important">
												<span class="c-06f">{{'#'.$act->id.': '}}</span>
												{{$act->user->firstname.' '.$act->user->lastname}}
												@if ($act->status == 'pending')
													<span class="badge badge-primary padding-5 font-no-bold ml20 h-padding-10">Pending</span>
												@elseif ($act->status == 'reassigned')
													<span class="badge badge-warning padding-5 font-no-bold ml20 h-padding-10">Reassigned</span>
												@elseif ($act->status == 'escalated')
													<span class="badge badge-danger padding-5 font-no-bold ml20 h-padding-10">Escalated</span>
												@else
													<span class="badge badge-success padding-5 font-no-bold ml20 h-padding-10">Completed</span>
												@endif
											</h4>
											<span>{{date('d-m-y, g:ia', strtotime($act->updated_at))}}</span>
										</div>
										<div class="row d-flex w-100 justify-content-between">
											<div class="col-10">
												<p class="mb0 c-666">
													{{$act->comment}}
												</p>
											</div>
											<div class="col-2 text-right">
												@if($act->autogen == 0 && $act->task->status != 'closed')
													@if(Auth::user()->id == $act->user_id) <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-pencil"></i></button> @endif
													@if(in_array(Auth::user()->role->title,$delete_allow)) <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal"><i class="fa fa-trash"></i></button> @endif
												@endif
											</div>
										</div>
									</li>
								@endforeach
							</ul>

							{{$actions->links('vendor.pagination.bootstrap-4')}}
						@endif

					</div>

				</div>

			</div>

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
						<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add Action Point</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="action-status" class="form-control-label text-center sr-onlyy">Status</label>

							<select id="action-status" class="form-control chzn-select">
								@foreach($action_status as $status)
									<option value="{{$status}}">{{ucfirst($status)}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<textarea id="action-comment" class="form-control h120" required placeholder="Enter action point" cols="40"></textarea>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" value="{{Crypt::encrypt($task->id)}}" id="task-id">
								<button class="btn-success btn btn-block" id='add-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
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

						<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" comment?</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="com-row-id-delete">
								<input type="hidden" id="com-id-delete">
								<button class="btn-danger btn btn-block" id='delete-btn' type="submit" role="button"><i class="fa fa-trash mr5"></i>Delete</button>
							</div>
						</div>
					</div>
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
						<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Action Point</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="action-status-edit" class="form-control-label text-center sr-onlyy">Status</label>

							<select id="action-status-edit" class="form-control chzn-selectt">
								@foreach($action_status as $status)
									<option value="{{$status}}">{{ucfirst($status)}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<textarea id="action-comment-edit" class="form-control h120" required placeholder="Enter action point" cols="40"></textarea>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="com-id-edit">
								<input type="hidden" id="row-id">
								<input type="hidden" value="{{Crypt::encrypt($task->id)}}" id="task-id-edit">
								<button class="btn-success btn btn-block" id='edit-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
							</div>
						</div>
					</div>
				</form>
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
			"order": [[ 0, "asc" ]]
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
				status = $("#action-status").val(),
				comment = $("#action-comment").val(),
				task_id = $("#task-id").val(),
				load_element = "#actions",
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.add.com')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					comment: comment,
					task_id: task_id,
					status: status,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-modal').modal('hide');
					window.location.href = "{{route('admin.tasks.show', Crypt::encrypt($task->id))}}";
					//$(load_element).load(location.href + " "+ load_element +">*","");
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
				li = btn.closest('li'),
				delete_title = li.data('hrid'),
				hrid = li.data('hrid'),
				com_id = li.data('id');

			$("#delete-title").text(delete_title);
			$("#com-id-delete").val(com_id);
			$("#com-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				com_id = $('#com-id-delete').val(),
				remove_element = '#row-' + $("#com-row-id-delete").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.delete.com')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					com_id: com_id,
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

		$('#edit-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				li = btn.closest('li'),
				status = li.data('status'),
				comment = li.data('comment'),
				com_id = li.data('id'),
				hrid = li.data('hrid');

			$("#action-status-edit").val(status);
			$("#action-comment-edit").val(comment);
			$("#com-id-edit").val(com_id);
			$("#row-id").val(hrid);
		});

		$(document).on('click', '#edit-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				status = $("#action-status-edit").val(),
				comment = $("#action-comment-edit").val(),
				com_id = $("#com-id-edit").val(),
				load_element = "#row-" + $("#row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.tasks.update.com')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					status: status,
					comment: comment,
					com_id: com_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-modal').modal('hide');
					$(load_element).data('status',status);
					$(load_element).data('comment',comment);
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

	});
</script>
@endSection
