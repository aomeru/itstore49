@extends('layouts.admin')
@section('title', 'Inventory')
@section('page_title') <i class="fa fa-th mr10"></i>Inventory @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Inventory</li>
</ol>
@endsection

@section('content')

	<button class="btn btn-primary mb20" id="summary-heading" data-toggle="collapse" data-target="#summary-block" aria-expanded="false" aria-controls="summary-block">Summary</button>

	<div id="summary-block" class="collapse mb20" aria-labelledby="summary-heading">

		<div class="card">

			<div class="card-block">
				<div class="row">
					@foreach($items as $i)
						<div class="col-6 col-sm-3 mb20">
							<div class="card card-primary">
								<div class="card-block padding-10">
									<h4 class="c-07e card-title font-600 margin-bottom-10">{{$i->title}}</h4>
									<?php
									$inventory = $i->inventory == null ? 0 : $i->inventory->count();
									$allocated = 0;
									foreach ($i->inventory as $val) {
										if($val->allocation != null) $allocated += 1;
									}
									$l = $inventory - $allocated;
									?>
									<div class="row">
										<div class="col-6">
											<p class="no-bottom-margin">Total: {{ $inventory }}</p>
									<p class="no-bottom-margin">Allocated: {{ $allocated }}</p>
										</div>
										<div class="col-6">
											<p class="no-bottom-margin">Available: {{ $l }}</p>
											<p class="no-bottom-margin">Reorder?: {!! $l <= config('app.rlevel') ? '<span class="c-f00">Yes</span>' : '<span class="c-fff">No</span>' !!}</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>

			<div class="card-footer padding-5"></div>
		</div>

	</div>



	<div class="card">

		<div class="card-block">
			@if(in_array(Auth::user()->username,$create_allow))
				<div class="mb10">
					<div class="pull-right">
						<button class="btn btn-primary btn-sm no-margin" title="Add new inventory" data-toggle="modal" data-target="#add-inv-modal"><i class="fa fa-plus"></i></button>
					</div>
					<div class="clearfix"></div>
				</div>
			@endif

			@if ($invs->count() == 0)
				<p class="alert alert-info">No inventory record found.</p>
			@else

				<div class="table-responsive">

					<table id="inv-table" class="data-table table table-striped table-bordered table-hover nowrapp" width="100%" data-page-length="10">

						<thead>
							<tr class="active">
								<th>#</th>
								<th>Title</th>
								<th>Type</th>
								<th>Processor</th>
								<th>PO</th>
								<th class="text-center">Allocated</th>
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

							@foreach($invs as $item)

								<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-id="{{Crypt::encrypt($item->id)}}" data-purchase-title="{{$item->purchase == null ? '' : $item->purchase->title}}" data-item-type="{{$item->item->title}}" data-serial-no="{{$item->serial_no}}">
									<td>
										<u><a href="{{route('admin.inv.show', Crypt::encrypt($item->id))}}" class="c-06f">{{ $item->serial_no }}</a></u>
									</td>
									<td>{{ $item->item->title }}</td>
									<td>{{ $item->item->type }}</td>
									<td>{!! $item->item->processor == null ? '<span class="c-999">N/A</span>' : $item->item->processor !!}</td>
									<td>{!! $item->purchase == null ? '<span class="c-999">Null</span>' : $item->purchase->title !!}</td>
									<td class="text-center">{!!$item->allocation == null ? '<span class="c-f00">No</span>' : '<span class="c-2c5">Yes</span>'!!}</td>
									@if(in_array(Auth::user()->username,$delete_allow))
										<td>{{$item->user->firstname.' '.$item->user->lastname}}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->updated_at))}}</td>
									@endif

									@if(in_array(Auth::user()->username,$delete_allow) || in_array(Auth::user()->username,$edit_allow))
										<td class="text-center">
											<button class="btn btn-primary btn-sm" title="Edit {{ $item->serial_no }}" data-toggle="modal" data-target="#edit-inv-modal"><i class="fa fa-pencil"></i></button>
											<button class="btn btn-danger btn-sm" title="Delete {{ $item->serial_no }}" data-toggle="modal" data-target="#delete-inv-modal" @if(!in_array(Auth::user()->username,$delete_allow)) disabled @endif><i class="fa fa-trash"></i></button>
										</td>
									@endif

								</tr>

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
		<div class="modal fade" id="add-inv-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog w300" role="document">
				<div class="modal-content">
					<form method="post">

						<div class="modal-header mh-override">
							<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Add Inventory</h4>
						</div>

						<div class="modal-body">

							<div class="form-group input_field_sections">
								<label for="serial-no" class="form-control-label text-center sr-only">Serial No</label>

								<input type="text" id="serial-no" class="form-control" placeholder="Enter device serial number" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9&- ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces &amp; hypen">
							</div>

							<div class="form-group input_field_sections">
								<label for="item-type" class="form-control-label text-center sr-only">Type</label>

								<select id="item-type" class="form-control chzn-select">
									<option value="">Select Item Type</option>
									@foreach($items as $item)
										<option value="{{$item->title}}">{{$item->title}}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group input_field_sections">
								<label for="purchase-title" class="form-control-label text-center sr-only">Purchase Order</label>

								<select id="purchase-title" class="form-control chzn-select">
									<option value="">Select Purchase Order</option>
									@foreach($po as $p)
										<option value="{{$p->title}}">{{$p->title}}</option>
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
									<button class="btn-success btn btn-block" id='add-inv-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Add</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endif

	@if(in_array(Auth::user()->username,$edit_allow))
		<div class="modal fade" id="edit-inv-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog w300" role="document">
				<div class="modal-content">
					<form method="post">

						<div class="modal-header mh-override">
							<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Inventory</h4>
						</div>

						<div class="modal-body">

							<div class="form-group input_field_sections">
								<label for="serial-no-edit" class="form-control-label text-center sr-only">Serial No</label>

								<input type="text" id="serial-no-edit" class="form-control" placeholder="Enter device serial number" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9&- ]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces &amp; hypen">
							</div>

							<div class="form-group input_field_sections">
								<label for="item-type-edit" class="form-control-label text-center sr-only">Type</label>

								<select id="item-type-edit" class="form-control chzn-selectt">
									<option value="">Select Item Type</option>
									@foreach($items as $item)
										<option value="{{$item->title}}">{{$item->title}}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group input_field_sections">
								<label for="purchase-title-edit" class="form-control-label text-center sr-only">Purchase Order</label>

								<select id="purchase-title-edit" class="form-control chzn-selectt">
									<option value="">None</option>
									@foreach($po as $p)
										<option value="{{$p->title}}">{{$p->title}}</option>
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
									<input type="hidden" id="inv-row-id">
									<input type="hidden" id="inv-id-edit">
									<button class="btn-success btn btn-block" id='update-inv-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endif

	@if(in_array(Auth::user()->username,$delete_allow))
		<div class="modal fade" id="delete-inv-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog w300" role="document">
				<div class="modal-content">

					<div class="modal-body">

						<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" from the inventory?</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="inv-row-id-delete">
								<input type="hidden" id="inv-id-delete">
								<button class="btn-danger btn btn-block" id='delete-inv-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
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

		$(document).on('click', '#add-inv-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				serial_no = $("#serial-no").val(),
				item_type = $("#item-type").val(),
				po_title = $("#purchase-title").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.inv.add')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					serial_no: serial_no,
					item_type: item_type,
					po_title: po_title,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#add-inv-modal').modal('hide');
					window.location.href = "{{route('admin.inv')}}";
				},
				error: function(jqXHR, exception){
					btn.html(btn_text);
					$('.process-loading-two').toggleClass('add-loading');
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});

		$('#edit-inv-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				serial_no = tr.data('serial-no'),
				item_type = tr.data('item-type'),
				po_title = tr.data('purchase-title'),
				hrid = tr.data('hrid'),
				inv_id = tr.data('id');

			$("#serial-no-edit").val(serial_no);
			$("#item-type-edit").val(item_type);
			$("#purchase-title-edit").val(po_title);
			$("#inv-id-edit").val(inv_id);
			$("#inv-row-id").val(hrid);

		});

		$(document).on('click', '#update-inv-btn', function(e){

			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				serial_no = $("#serial-no-edit").val(),
				item_type = $("#item-type-edit").val(),
				po_title = $("#purchase-title-edit").val(),
				inv_id = $("#inv-id-edit").val(),
				load_element = "#row-" + $("#inv-row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.inv.update')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					serial_no: serial_no,
					item_type: item_type,
					po_title: po_title,
					inv_id: inv_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					pnotify_alert('success', response.message);
					$('#edit-inv-modal').modal('hide');
					$(load_element).data('serial-no',serial_no);
					$(load_element).data('item-type',item_type);
					$(load_element).data('purchase-title',po_title);
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

		$('#delete-inv-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('item-type') + " " + tr.data('serial-no'),
				hrid = tr.data('hrid'),
				inv_id = tr.data('id');

			$("#delete-title").text(delete_title);
			$("#inv-id-delete").val(inv_id);
			$("#inv-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-inv-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				inv_id = $('#inv-id-delete').val(),

				remove_element = '#row-' + $("#inv-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.inv.delete')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					inv_id: inv_id,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					btn.html(btn_text);
					$('#delete-inv-modal').modal('hide');
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
