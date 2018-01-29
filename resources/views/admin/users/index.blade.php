@extends('layouts.admin')
@section('title', 'Users')
@section('page_title') <i class="fa fa-user mr10"></i>Users @endSection



@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Users</li>
</ol>
@endsection


@section('content')

<div class="card mb50">

	<div class="card-block">
		@if(in_array(Auth::user()->username,$create_allow))
		<div class="mb10">
			<div class="pull-right">
				<button class="btn btn-primary btn-sm no-margin" title="Add new user" data-toggle="modal" data-target="#add-user-modal"><i class="fa fa-plus"></i></button>
			</div>
			<div class="clearfix"></div>
		</div>
		@endif

		@if ($list->count() == 0)

			<p class="alert alert-info">No user record found.</p>

		@else

			<div class="table-responsive">

				<table class="table table-bordered table-hover nowrap data-table" width="100%" data-page-length="50">

					<thead>
						<tr class="active">
							<th>#</th>
							<th>ID</th>
							<th>User</th>
							<th class="text-center">Gender</th>
							<th>Email</th>
							<th>Role</th>
							<th>Department</th>
							<th class="text-center">Issues</th>
							<th class="text-center">Allocation</th>
							<th class="text-center">Status</th>
							<th class="text-right">Actions</th>
						</tr>
					</thead>

					<tbody>

						@php $row_count = 1 @endphp

						@foreach($list as $item)

							<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-id="{{Crypt::encrypt($item->id)}}" data-firstname="{{$item->firstname}}" data-lastname="{{$item->lastname}}" data-staff-id="{{$item->staff_id}}" data-role-id="{{$item->username}}" data-unit-id="{{$item->unit == null ? '' : $item->unit->title}}" data-gender="{{$item->gender}}" data-email="{{$item->email}}" data-status="{{$item->status}}">

								<td>{{ $row_count }}</td>

								<td>{!! $item->staff_id == null ? '<span class="c-999">N/A</span>' : $item->staff_id !!}</td>

								<td>{!! $item->firstname == null ? '<span class="c-999">N/A</span>' : $item->firstname.' '.$item->lastname !!}</td>

								<td class="text-center"><span class="sr-only">{{$item->gender}} </span><i class="fa fa-{{$item->gender}}"></i></td>

								<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->id))}}" class="c-06f">{{$item->email}}</a></u></td>

								<td>{{$item->username}}</td>

								<td>{!! $item->unit == null ? '<span class="c-999">N/A</span>' : $item->unit->department->title.' <span class="c-999 v-padding-5">/</span> '.$item->unit->title !!}</td>

								<td class="text-center">{{$item->issues->count()}} ({{$item->issues()->where('status','closed')->count()}})</td>

								<td class="text-center">{{$item->allocations->count()}}</td>

								<td class="text-center">{!! $item->status == 'active' ? '<span class="c-2c5">'.$item->status.'</span>' : '<span class="c-f00">'.$item->status.'</span>' !!}</td>

								<td class="text-right">

									<button class="btn btn-primary btn-sm" title="Edit {{ $item->firstname }}" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-pencil"></i></button>
									<button class="btn btn-primary btn-sm" title="Reset {{ $item->firstname }} password" data-toggle="modal" data-target="#rpass-modal"><i class="fa fa-refresh"></i></button>
									<button class="btn btn-danger btn-sm" title="Delete {{ $item->firstname }}" data-toggle="modal" data-target="#delete-user-modal" @if(!in_array(Auth::user()->username,$delete_allow)) disabled @endif><i class="fa fa-trash"></i></button>

								</td>

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
	<div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w450" role="document">
			<div class="modal-content">
				<form method="post">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add User</h4>
				    </div>

					<div class="modal-body">

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
			                        <label for="staff-id" class="form-control-label text-center sr-onlyy">Staff ID</label>

									<input type="text" id="staff-id" class="form-control" placeholder="Enter staff ID" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9]*)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
			                    </div>
							</div>
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="role-id" class="form-control-label text-center sr-onlyy">Role</label>

									<select id="role-id" class="form-control chzn-select">
										<option value="">Select Role</option>
										@foreach($roles as $item)
											<option value="{{$item->title}}">{{$item->title}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="firstname" class="form-control-label text-center sr-onlyy">Firstname <span class="rfd">*</span></label>

									<input type="text" id="firstname" class="form-control" placeholder="Enter firstname" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]+)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
								</div>
							</div>

							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="lastname" class="form-control-label text-center sr-onlyy">Lastname <span class="rfd">*</span></label>

									<input type="text" id="lastname" class="form-control" placeholder="Enter lastname" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]+)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
								</div>
							</div>
						</div>

						<div class="form-group input_field_sections">
							<label for="email" class="form-control-label sr-onlyy">Email</label>

							<input type="email" id="email" class="form-control" data-validation="email" data-validation-error-msg="Please enter a valid email address" placeholder="Enter email address">
						</div>

						<div class="form-group input_field_sections">
							<label for="gender" class="form-control-label text-center sr-onlyy">Gender</label>

							<select id="gender" class="form-control chzn-select">
								<option value="male">Male</option>
								<option value="female">Female</option>
							</select>
						</div>



						<div class="form-group input_field_sections">
	                        <label for="unit-id" class="form-control-label text-center sr-onlyy">Unit / Department</label>

							<select id="unit-id" class="form-control chzn-select">
								<option value="">Select Unit</option>
								@foreach($units as $item)
									<option value="{{$item->title}}">{{$item->title}} / {{$item->department->title}}</option>
								@endforeach
							</select>
	                    </div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<button class="btn-success btn btn-block" id='add-user-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endif

@if(in_array(Auth::user()->username,$edit_allow))
	<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w450" role="document">
			<div class="modal-content">
				<form method="post">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Update User Account</h4>
				    </div>

					<div class="modal-body">

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
			                        <label for="staff-id-edit" class="form-control-label text-center sr-onlyy">Staff ID</label>

									<input type="text" id="staff-id-edit" class="form-control" placeholder="Enter staff ID" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]*)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
			                    </div>
							</div>
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="role-id-edit" class="form-control-label text-center sr-onlyy">Role</label>

									<select id="role-id-edit" class="form-control chzn-selectt">
										<option value="">Select Role</option>
										@foreach($roles as $item)
											<option value="{{$item->title}}">{{$item->title}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="firstname-edit" class="form-control-label text-center sr-onlyy">Firstname <span class="rfd">*</span></label>

									<input type="text" id="firstname-edit" class="form-control" placeholder="Enter firstname" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]+)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
								</div>
							</div>

							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="lastname-edit" class="form-control-label text-center sr-onlyy">Lastname <span class="rfd">*</span></label>

									<input type="text" id="lastname-edit" class="form-control" placeholder="Enter lastname" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]+)$" data-validation-error-msg="Please use aplhanumeric characters only and hypen">
								</div>
							</div>
						</div>

						<div class="form-group input_field_sections">
							<label for="email-edit" class="form-control-label sr-onlyy">Email</label>

							<input type="email" id="email-edit" class="form-control" data-validation="email" data-validation-error-msg="Please enter a valid email address" placeholder="Enter email address">
						</div>

						<div class="form-group input_field_sections">
							<div class="form-group input_field_sections">
								<label for="unit-id-edit" class="form-control-label text-center sr-onlyy">Unit / Department</label>

								<select id="unit-id-edit" class="form-control chzn-selectt">
									<option value="">Select Unit</option>
									@foreach($units as $item)
										<option value="{{$item->title}}">{{$item->title}} / {{$item->department->title}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="row">
							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="gender-edit" class="form-control-label text-center sr-onlyy">Gender</label>

									<select id="gender-edit" class="form-control chzn-selectt">
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group input_field_sections">
									<label for="status-edit" class="form-control-label text-center sr-onlyy">Status</label>

									<select id="status-edit" class="form-control chzn-selectt">
										<option value="">Select Status</option>
										<option value="active">Active</option>
										<option value="inactive">Inactive</option>
										<option value="blocked">Blocked</option>
									</select>
								</div>
							</div>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-danger btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="user-id-edit">
								<input type="hidden" id="row-id">
								<button class="btn-success btn btn-block" id='edit-user-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="rpass-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">

					<div class="modal-body">

						<p class="text-center font-18x no-bottom-margin">Confirm password reset for "<span id="rpass-title" class="c-06f"></span>"</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="rpass-row-id">
								<input type="hidden" id="rpass-id">
								<button class="btn-danger btn btn-block" id='rpass-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Yes, Reset</button>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
@endif

@if(in_array(Auth::user()->username,$delete_allow))
	<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">

					<div class="modal-body">

						<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" user account?</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="user-row-id-delete">
								<input type="hidden" id="user-id-delete">
								<button class="btn-danger btn btn-block" id='delete-user-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
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

		$(document).on('click', '#add-user-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				role_id = $("#role-id").val(),
				staff_id = $("#staff-id").val(),
				firstname = $("#firstname").val(),
				lastname = $("#lastname").val(),
				email = $("#email").val(),
				gender = $("#gender").val(),
				unit_id = $("#unit-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.users.add')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					role_id: role_id,
					staff_id: staff_id,
					firstname: firstname,
					lastname: lastname,
					email: email,
					gender: gender,
					unit_id: unit_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-user-modal').modal('hide');
					window.location.href = "{{route('admin.users')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#edit-user-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				firstname = tr.data('firstname'),
				lastname = tr.data('lastname'),
				staff_id = tr.data('staff-id'),
				role_id = tr.data('role-id'),
				unit_id = tr.data('unit-id'),
				email = tr.data('email'),
				gender = tr.data('gender'),
				user_id = tr.data('id'),
				status = tr.data('status'),
				hrid = tr.data('hrid');

			// console.log(user_id);

			//console.log(title + " " + type + " " + processor + " " + descrip);

			$("#email-edit").val(email);
			$("#gender-edit").val(gender);
			$("#firstname-edit").val(firstname);
			$("#lastname-edit").val(lastname);
			$("#staff-id-edit").val(staff_id);
			$("#role-id-edit").val(role_id);
			$("#unit-id-edit").val(unit_id);
			// $("#unit-id-edit").chosen("val",unit_id);
			$("#user-id-edit").val(user_id);
			$("#status-edit").val(status);
			$("#row-id").val(hrid);

			//console.log($("#unit-id-edit").val());

		});

		$(document).on('click', '#edit-user-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				role_id = $("#role-id-edit").val(),
				staff_id = $("#staff-id-edit").val(),
				firstname = $("#firstname-edit").val(),
				lastname = $("#lastname-edit").val(),
				email = $("#email-edit").val(),
				gender = $("#gender-edit").val(),
				unit_id = $("#unit-id-edit").val(),
				user_id = $("#user-id-edit").val(),
				status = $("#status-edit").val(),
				load_element = "#row-" + $("#row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.users.update')}}";

			// console.log(user_id);

			$.ajax({
				type: "POST",
				url: url,
				data: {
					role_id: role_id,
					staff_id: staff_id,
					unit_id: unit_id,
					firstname: firstname,
					lastname: lastname,
					email: email,
					gender: gender,
					user_id: user_id,
					status: status,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-user-modal').modal('hide');
					$(load_element).data('role-id',role_id);
					$(load_element).data('unit-id',unit_id);
					$(load_element).data('staff-id',staff_id);
					$(load_element).data('firstname',firstname);
					$(load_element).data('lastname',lastname);
					$(load_element).data('email',email);
					$(load_element).data('gender',gender);
					$(load_element).data('status',status);
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

		$('#delete-user-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('firstname') + ' ' + tr.data('lastname'),
				hrid = tr.data('hrid'),
				user_id = tr.data('id');

			console.log(user_id);

			$("#delete-title").text(delete_title);
			$("#user-id-delete").val(user_id);
			$("#user-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-user-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				user_id = $('#user-id-delete').val(),

				remove_element = '#row-' + $("#user-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.users.delete')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					user_id: user_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#delete-user-modal').modal('hide');
					pnotify_alert('success', response.message);
					$(remove_element).remove();
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

		$('#rpass-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				rpass_title = tr.data('firstname') + ' ' + tr.data('lastname'),
				hrid = tr.data('hrid'),
				user_id = tr.data('id');

			console.log(user_id);

			$("#rpass-title").text(rpass_title);
			$("#rpass-id").val(user_id);
			$("#rpass-row-id").val(hrid);
		});

		$(document).on('click', '#rpass-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				user_id = $('#rpass-id').val(),

				remove_element = '#row-' + $("#rpass-row-id").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.users.rpass')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					user_id: user_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#rpass-modal').modal('hide');
					pnotify_alert('success', response.message);
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
