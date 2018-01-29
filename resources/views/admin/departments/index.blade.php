@extends('layouts.admin')
@section('title', 'Departments &amp; Units')
@section('page_title') <i class="fa fa-th-large mr10"></i>Departments &amp; Units @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Departments &amp; Units</li>
</ol>
@endsection

@section('content')

	<div id="loadDiv" class="row">

		<div class="col-sm-6">
			<div class="card">
				<div class="card-header bgc-070">
					<h4 class="text-uppercase font-600 no-padding no-margin c-fff">Departments</h4>
				</div>

				<div class="card-block">
					@if(in_array(Auth::user()->username,$dcreate_allow))
					<div class="mb10">
						<div class="pull-right">
							<button class="btn btn-primary btn-sm no-margin" title="Add new department" data-toggle="modal" data-target="#add-dept-modal"><i class="fa fa-plus"></i></button>
						</div>
						<div class="clearfix"></div>
					</div>
					@endif

					@if ($depts->count() == 0)
						<p class="alert alert-info">No department record found.</p>
					@else

						<div class="table-responsive">

							<table id="dept-table" class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="10">

								<thead>
									<tr class="active">
										<th>#</th>
										<th>Title</th>
										<th class="text-center">Units</th>
										<th class="text-center">Staff</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>

								<tbody>

									@php $row_count = 1 @endphp

									@foreach($depts as $item)

										<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-item-id="{{Crypt::encrypt($item->id)}}" data-item-title="{{$item->title}}">
											<td>{{ $row_count }}</td>
											<td>
												<u><a href="{{route('admin.depts.show', Crypt::encrypt($item->id))}}" class="c-06f">{{ $item->title }}</a></u>
											</td>
											<td class="text-center">{{ $item->units->count() }}</td>
											<td class="text-center">
												<?php
												$ds_count = 0;
												foreach($item->units as $un)
												{
													$ds_count += $un->users->count();
												}
												?>
												{{$ds_count}}
											</td>
											<td class="text-center">
												<button class="edit-dept-btn btn btn-primary btn-sm" title="Edit {{ $item->title }}" data-toggle="modal" data-target="#edit-dept-modal"><i class="fa fa-pencil"></i></button>
												<button class="btn btn-danger btn-sm" title="Delete {{ $item->title }}" data-toggle="modal" data-target="#delete-dept-modal" @if(!in_array(Auth::user()->username,$ddelete_allow)) disabled @endif><i class="fa fa-trash"></i></button>
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

		</div>

		<div class="col-sm-6">
			<div class="card">
				<div class="card-header bgc-070">
					<h4 class="text-uppercase font-600 no-padding no-margin c-fff">Units</h4>
				</div>

				<div class="card-block">
					@if(in_array(Auth::user()->username,$ucreate_allow))
					<div class="mb10">
						<div class="pull-right">
							<button class="btn btn-primary btn-sm no-margin" title="Add new sub unit" data-toggle="modal" data-target="#add-unit-modal"><i class="fa fa-plus"></i></button>
						</div>
						<div class="clearfix"></div>
					</div>
					@endif

					@if ($units->count() == 0)
						<p class="alert alert-info">No sub unit record found.</p>
					@else

						<div class="table-responsive">

							<table id="unit-table" class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="10">

								<thead>
									<tr class="actiive">
										<th>#</th>
										<th>Title</th>
										<th>Department</th>
										<th class="text-center">Staff</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>

								<tbody>

									@php $row_count = 1 @endphp

									@foreach($units as $unit)

										<tr id="urow-{{$unit->id}}" data-hrid="{{$unit->id}}" data-item-id="{{Crypt::encrypt($unit->id)}}" data-item-title="{{$unit->title}}" data-item-dtitle="{{$unit->department->title}}">
											<td>{{ $row_count }}</td>
											<td><u><a href="{{route('admin.depts.show.unit', Crypt::encrypt($unit->id))}}" class="c-06f">{{ $unit->title }}</a></u></td>
											<td>{{ $unit->department->title }}</td>
											<td class="text-center">{{ $unit->users->count() }}</td>
											<td class="text-center">
												<button class="btn btn-primary btn-sm" title="Edit {{ $unit->title }}" data-toggle="modal" data-target="#edit-unit-modal"><i class="fa fa-pencil"></i></button>
												<button class="btn btn-danger btn-sm" title="Delete {{ $unit->title }}" data-toggle="modal" data-target="#delete-unit-modal" @if(!in_array(Auth::user()->username,$udelete_allow)) disabled @endif><i class="fa fa-trash"></i></button>
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
		</div>

	</div>

@endSection




@section('page_footer')

@if(in_array(Auth::user()->username,$dcreate_allow))
<div class="modal fade" id="add-dept-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add Department</h4>
				</div>

				<div class="modal-body">
					<div class="form-group input_field_sections">
						<label for="dept_name" class="form-control-label text-center">Department Name</label>

						<input type="text" name="dept_name" id="dept_name" class="form-control" value="{{ Request::old('dept_name') }}" placeholder="Enter departmental title" data-validation="custom required" data-validation-regexp="^([a-zA-Z&' ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces and &amp;">
					</div>
				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<button class="btn-success btn btn-block" id='add-dept-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$ddelete_allow))
<div class="modal fade" id="delete-dept-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">

				<div class="modal-body">

					<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" department?</p>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="dept-row-id-delete">
							<input type="hidden" id="dept-id-delete">
							<button class="btn-danger btn btn-block" id='delete-dept-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$dedit_allow))
<div class="modal fade" id="edit-dept-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Department</h4>
				</div>

				<div class="modal-body">
					<div class="form-group input_field_sections">
						<label for="dept-name-edit" class="form-control-label text-center">Department Name</label>

						<input type="text" id="dept-name-edit" class="form-control" placeholder="Enter departmental title" data-validation="custom required" data-validation-regexp="^([a-zA-Z&' ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces and &amp;">
					</div>
				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="dept-row-id">
							<input type="hidden" id="dept-id-edit">
							<button class="btn-success btn btn-block" id='update-dept-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif


@if(in_array(Auth::user()->username,$ucreate_allow))
<div class="modal fade" id="add-unit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add Sub Unit</h4>
				</div>

				<div class="modal-body">
					<div class="form-group input_field_sections">
						<label for="unit-name" class="form-control-label text-center">Unit Name</label>

						<input type="text" id="unit-name" class="form-control" placeholder="Enter sub unit name" data-validation="custom required" data-validation-regexp="^([a-zA-Z&' ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces and &amp;">
					</div>

					<div class="form-group input_field_sections">
						<label for="unit-dept-id" class="form-control-label text-center">Department Name</label>

						<select id="unit-dept-id" class="form-control chzn-select">
							<option value="">Select Department</option>
							@foreach($depts as $dept)
								<option value="{{Crypt::encrypt($dept->id)}}">{{$dept->title}}</option>
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
							<button class="btn-success btn btn-block" id='add-unit-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$uedit_allow))
<div class="modal fade" id="edit-unit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Sub Unit</h4>
				</div>

				<div class="modal-body">
					<div class="form-group input_field_sections">
						<label for="unit-name-edit" class="form-control-label text-center">Unit Name</label>

						<input type="text" id="unit-name-edit" class="form-control" placeholder="Enter sub unit name" data-validation="custom required" data-validation-regexp="^([a-zA-Z&' ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces and &amp;">
					</div>

					<div class="form-group inputt_field_sections">
						<label for="unit-dept-title-edit" class="form-control-label text-center">Department Name</label>

						<select id="unit-dept-title-edit" class="form-control chznn-select">
							<option value="">Select Department</option>
							@foreach($depts as $dept)
								<option value="{{$dept->title}}">{{$dept->title}}</option>
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
							<input type="hidden" id="unit-row-id">
							<input type="hidden" id="unit-id-edit">
							<button class="btn-success btn btn-block" id='update-unit-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$udelete_allow))
<div class="modal fade" id="delete-unit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">

				<div class="modal-body">

					<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-unit-title" class="c-06f"></span>" unit?</p>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="unit-row-id-delete">
							<input type="hidden" id="unit-id-delete">
							<button class="btn-danger btn btn-block" id='delete-unit-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
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
			"order": [[ 1, "asc" ]]
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


		$(document).on('click', '#add-dept-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				dept_name = $("#dept_name").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.add')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					dept_name: dept_name,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-dept-modal').modal('hide');
					window.location.href = "{{route('admin.depts')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#delete-dept-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('item-title'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			$("#delete-title").text(delete_title);
			$("#dept-id-delete").val(item_id);
			$("#dept-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-dept-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				item_id = $('#dept-id-delete').val(),

				remove_element = '#row-' + $("#dept-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.delete')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					item_id: item_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#delete-dept-modal').modal('hide');
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

		$('#edit-dept-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				title = tr.data('item-title'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			console.log(title);

			$("#dept-name-edit").val(title);
			$("#dept-id-edit").val(item_id);
			$("#dept-row-id").val(hrid);
		});

		$(document).on('click', '#update-dept-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				dept_name = $("#dept-name-edit").val(),
				dept_id = $("#dept-id-edit").val(),
				load_element = "#row-" + $("#dept-row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.update')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					dept_name: dept_name,
					dept_id: dept_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-dept-modal').modal('hide');
					$(load_element).data('item-title',dept_name);
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




		$(document).on('click', '#add-unit-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				unit_name = $("#unit-name").val(),
				unit_dept_id = $("#unit-dept-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.add.unit')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					unit_name: unit_name,
					unit_dept_id: unit_dept_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-unit-modal').modal('hide');
					window.location.href = "{{route('admin.depts')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#edit-unit-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				title = tr.data('item-title'),
				dtitle = tr.data('item-dtitle'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			$("#unit-name-edit").val(title);
			$("#unit-id-edit").val(item_id);
			$("#unit-row-id").val(hrid);
			$("#unit-dept-title-edit").val(dtitle);

			console.log($("#unit-dept-title-edit").val());
		});

		$(document).on('click', '#update-unit-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				unit_name = $("#unit-name-edit").val(),
				unit_id = $("#unit-id-edit").val(),
				dept_title = $("#unit-dept-title-edit").val(),
				load_element = "#urow-" + $("#unit-row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.update.unit')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					unit_name: unit_name,
					dept_title: dept_title,
					unit_id: unit_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-unit-modal').modal('hide');
					$(load_element).data('item-title',unit_name);
					$(load_element).data('item-dtitle',dept_title);
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

		$('#delete-unit-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_unit_title = tr.data('item-title'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			$("#delete-unit-title").text(delete_unit_title);
			$("#unit-id-delete").val(item_id);
			$("#unit-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-unit-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				item_id = $('#unit-id-delete').val(),

				remove_element = '#urow-' + $("#unit-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.depts.delete.unit')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					item_id: item_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#delete-unit-modal').modal('hide');
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

	});
</script>
@endSection
