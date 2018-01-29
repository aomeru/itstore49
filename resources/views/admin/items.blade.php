@extends('layouts.admin')
@section('title', 'Items')
@section('page_title') <i class="fa fa-sitemaps mr10"></i>Items @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Items</li>
</ol>
@endsection

@section('content')

	@if($reorder)
		<p class="alert alert-danger mb50 font-22x text-center">Some inventory items needs to be reordered.</p>
	@endif

	<div class="card">

		<div class="card-block">
			@if(in_array(Auth::user()->username,$create_allow))
			<div class="mb10">
				<div class="pull-right">
					<button class="btn btn-primary btn-sm no-margin" title="Add new department" data-toggle="modal" data-target="#add-item-modal"><i class="fa fa-plus"></i></button>
				</div>
				<div class="clearfix"></div>
			</div>
			@endif

			@if ($list->count() == 0)
				<p class="alert alert-info">No item record found.</p>
			@else

				<div class="table-responsive">

					<table id="dept-table" class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="50">

						<thead>
							<tr class="active">
								<th>#</th>
								<th>Title</th>
								<th>Type</th>
								<th>Processor</th>
								<th>Descrip</th>
								<th class="text-center">Total</th>
								<th class="text-center" title="Allocated / Available">Al/Av</th>
								<th class="text-center" title="Reorder?">Reorder</th>
								@if(in_array(Auth::user()->username,$delete_allow))
									<th>Added by</th>
									<th>Created</th>
									<th>Updated</th>
								@endif

								@if(in_array(Auth::user()->username,$delete_allow) || in_array(Auth::user()->username,$edit_allow))
									<th class="text-center">Actions</th>
								@endif
							</tr>
						</thead>

						<tbody>

							@php $row_count = 1 @endphp

							@foreach($list as $item)

								<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-item-id="{{Crypt::encrypt($item->id)}}" data-item-title="{{$item->title}}" data-item-type="{{$item->type}}" data-item-descrip="{{$item->descrip}}" data-item-processor="{{$item->processor}}">
									<td>{{ $row_count }}</td>
									<td>{{ $item->title }}</td>
									<td>{{ $item->type }}</td>
									<td>{!! $item->processor == null ? '<span class="c-999">N/A</span>' : $item->processor !!}</td>
									<td>{!! $item->descrip == null ? '<span class="c-999">N/A</span>' : $item->descrip !!}</td>
									<td class="text-center">{{ $item->inventory->count() }}</td>
									<td class="text-center">
										<?php
											$inventory = $item->inventory == null ? 0 : $item->inventory->count();
											$allocated = 0;
											foreach ($item->inventory as $val) {
												if($val->allocation != null) $allocated += 1;
											}
											$avail = $inventory - $allocated;
										?>
										{{ $allocated }} / {{ $avail }}
									</td>
									<td class="text-center">
										{!! $avail <= config('app.rlevel') ? '<span class="c-f00">Yes</span>' : '<span class="c-090">No</span>' !!}
									</td>
									@if(in_array(Auth::user()->username,$delete_allow))
										<td>{{$item->user->firstname.' '.$item->user->lastname}}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->updated_at))}}</td>
									@endif

									@if(in_array(Auth::user()->username,$delete_allow) || in_array(Auth::user()->username,$edit_allow))
										<td class="text-center">
											<button class="btn btn-primary btn-sm" title="Edit {{ $item->title }}" data-toggle="modal" data-target="#edit-item-modal"><i class="fa fa-pencil"></i></button>
											<button class="btn btn-danger btn-sm" title="Delete {{ $item->title }}" data-toggle="modal" data-target="#delete-item-modal" @if(!in_array(Auth::user()->username,$delete_allow)) disabled @endif><i class="fa fa-trash"></i></button>
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
<div class="modal fade" id="add-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add Item</h4>
				</div>

				<div class="modal-body">
					<div class="form-group input_field_sections">
						<label for="item-name" class="form-control-label text-center sr-only">Name</label>

						<input type="text" id="item-name" class="form-control" placeholder="Enter item title" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9&'- ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces, hypen, apostrophe  and &amp;">
					</div>

					<div class="form-group input_field_sections">
						<label for="item-type" class="form-control-label text-center sr-only">Type</label>

						<select id="item-type" class="form-control chzn-select">
							<option value="">Select Type</option>
							@foreach($item_types as $item_type)
								<option value="{{$item_type}}">{{$item_type}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group input_field_sections">
						<label for="item-processor" class="form-control-label text-center sr-only">Processor</label>

						<select id="item-processor" class="form-control chzn-select">
							<option value="">Select Processor</option>
							@foreach($item_processor as $item_pro)
								<option value="{{$item_pro}}">{{$item_pro}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group input_field_sections">
						<label for="item-descrip" class="form-control-label text-center sr-only">Description</label>

						<textarea id="item-descrip" class="form-control" maxlength="150" placeholder="Item description"></textarea>
					</div>
				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<button class="btn-success btn btn-block" id='add-item-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$edit_allow))
<div class="modal fade" id="edit-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">
			<form method="post">

				<div class="modal-header mh-override">
					<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Item</h4>
				</div>

				<div class="modal-body">

					<div class="form-group input_field_sections">
						<label for="item-name-edit" class="form-control-label text-center sr-only">Name</label>

						<input type="text" id="item-name-edit" class="form-control" placeholder="Enter item title" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9&'- ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces, hypen, apostrophe  and &amp;">
					</div>

					<div class="form-group input_field_sectionss">
						<label for="item-type-edit" class="form-control-label text-center sr-only">Type</label>

						<select id="item-type-edit" class="form-control chzn-selectt">
							<option value="">Select Type</option>
							@foreach($item_types as $item_type)
								<option value="{{$item_type}}">{{$item_type}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group input_field_sectionss">
						<label for="item-processor-edit" class="form-control-label text-center sr-only">Processor</label>

						<select id="item-processor-edit" class="form-control chzn-selectt">
							<option value="">Select Processor</option>
							@foreach($item_processor as $item_pro)
								<option value="{{$item_pro}}">{{$item_pro}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group input_field_sections">
						<label for="item-descrip-edit" class="form-control-label text-center sr-only">Description</label>

						<textarea id="item-descrip-edit" class="form-control" maxlength="150" placeholder="Item description"></textarea>
					</div>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="item-row-id">
							<input type="hidden" id="item-id-edit">
							<button class="btn-success btn btn-block" id='update-item-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endif

@if(in_array(Auth::user()->username,$delete_allow))
<div class="modal fade" id="delete-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog w300" role="document">
		<div class="modal-content">

				<div class="modal-body">

					<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" item?</p>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="item-row-id-delete">
							<input type="hidden" id="item-id-delete">
							<button class="btn-danger btn btn-block" id='delete-item-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
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


		$(document).on('click', '#add-item-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				item_name = $("#item-name").val(),
				item_type = $("#item-type").val(),
				item_processor = $("#item-processor").val(),
				item_descrip = $("#item-descrip").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.items.add')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					item_name: item_name,
					item_type: item_type,
					item_processor: item_processor,
					item_descrip: item_descrip,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-dept-modal').modal('hide');
					window.location.href = "{{route('admin.items')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#edit-item-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				title = tr.data('item-title'),
				type = tr.data('item-type'),
				processor = tr.data('item-processor'),
				descrip = tr.data('item-descrip'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			//console.log(title + " " + type + " " + processor + " " + descrip);

			$("#item-name-edit").val(title);
			$("#item-type-edit").val(type);
			$("#item-processor-edit").val(processor);
			$("#item-descrip-edit").val(descrip);
			$("#item-id-edit").val(item_id);
			$("#item-row-id").val(hrid);

		});

		$(document).on('click', '#update-item-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				item_id = $("#item-id-edit").val(),
				item_name = $("#item-name-edit").val(),
				item_type = $("#item-type-edit").val(),
				item_processor = $("#item-processor-edit").val(),
				item_descrip = $("#item-descrip-edit").val(),
				load_element = "#row-" + $("#item-row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.items.update')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					item_id: item_id,
					item_name: item_name,
					item_type: item_type,
					item_processor: item_processor,
					item_descrip: item_descrip,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-item-modal').modal('hide');
					$(load_element).data('item-title',item_name);
					$(load_element).data('item-type',item_type);
					$(load_element).data('item-processor',item_processor);
					$(load_element).data('item-descrip',item_descrip);
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

		$('#delete-item-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('item-title'),
				hrid = tr.data('hrid'),
				item_id = tr.data('item-id');

			$("#delete-title").text(delete_title);
			$("#item-id-delete").val(item_id);
			$("#item-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-item-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				item_id = $('#item-id-delete').val(),

				remove_element = '#row-' + $("#item-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.items.delete')}}";

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
					$('#delete-item-modal').modal('hide');
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
