@extends('layouts.admin')

@section('title', $item->title.' Purchase Order')

@section('page_title')
	<i class="fa fa-th mr10"></i>#{{$item->title}}
@endSection


@section('bc')
	<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
		<li class="breadcrumb-item h-padding-5">
			<a href="{{route('admin.dashboard')}}">
				Dashboard
			</a>
		</li>
		<li class="breadcrumb-item h-padding-5">
			<a href="{{route('admin.po')}}">Purchase Order</a>
		</li>
		<li class="breadcrumb-item active h-padding-5 no-right-padding">{{$item->title}}</li>
	</ol>
@endsection


@section('content')

	<div id="loadDiv">

		<div class="row">

			<div class="col-sm-4 xs-mb20">
				
				<div class="card">

					<div class="card-block">

						<dl class="row mb0">
							<dt class="col-4">Title</dt>
							<dd class="col-8">{{$item->title}}</dd>

							<dt class="col-4">Total</dt>
							<dd class="col-8">{!! $item->total == null ? '<em class="c-999">Null</em>' : '<s>N</s>'.number_format($item->total) !!}</dd>

							<dt class="col-4">Purchase Order</dt>
							<dd class="col-8">{!! $item->po == null ? '<em class="c-999">Null</em>' : '<a class="d-block" href="'.asset('storage/purchase/'.$item->po).'" title="View '.$item->title.' purchase order" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</dd>

							<dt class="col-4">Delivery Note</dt>
							<dd class="col-8">{!! $item->dn == null ? '<em class="c-999">Null</em>' : '<a class="d-block" href="'.asset('storage/purchase/'.$item->dn).'" title="View '.$item->title.' delivery note" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</dd>

							<dt class="col-4">Invoice</dt>
							<dd class="col-8">{!! $item->inv == null ? '<em class="c-999">Null</em>' : '<a class="d-block" href="'.asset('storage/purchase/'.$item->inv).'" title="View '.$item->title.' invoice" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</dd>

							<dt class="col-4">Added By</dt>
							<dd class="col-8">{{$item->user->firstname.' '.$item->user->lastname}}</dd>

							<dt class="col-4">Comments</dt>
							<dd class="col-8"><span class="ml5 badge badge-primary font-no-bold">{{ $item->log == null ? 0 : $item->log->count() }}</span></dd>

							<dt class="col-4">Date</dt>
							<dd class="col-8">{{date('d-m-y, g:ia', strtotime($item->created_at))}}</dd>

							<dt class="col-4">PO Date</dt>
							<dd class="col-8">{{date('d-m-y, g:ia', strtotime($item->po_at))}}</dd>

							<dt class="col-4">DN Date</dt>
							<dd class="col-8">{{date('d-m-y, g:ia', strtotime($item->dn_at))}}</dd>

							<dt class="col-4">Inv Date</dt>
							<dd class="col-8">{{date('d-m-y, g:ia', strtotime($item->inv_at))}}</dd>
						</dl>

					</div>

				</div>

			</div>

			<div class="col-sm-8">

				<div class="card">

					<div class="card-header bgc-070">
						<h4 class="font-600 text-center no-padding no-margin text-uppercase c-fff">Purchase Order Log</h4>
					</div>


					<div class="card-block">

						@if(in_array(Auth::user()->username,$create_allow))
							<div class="mb10">
								<div class="pull-right">
									<button class="btn btn-primary btn-sm no-margin" title="Add new purchase order comment" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus"></i></button>
								</div>
								<div class="clearfix"></div>
							</div>
						@endif

						@if(!in_array(Auth::user()->username,$show_allow))

							<p class="alert alert-info">{{ config('app.default_pdm') }}</p>

						@elseif ($item->log->count() == 0)

							<p class="alert alert-info">There is no comment log for this purchase order.</p>

						@else

							<div class="table-responsive">

								<table class="data-table table table-striped table-borderedd table-hover nowrapp" width="100%" data-page-length="20">

									<thead>
										<tr class="active">
											<th>Author</th>
											<th>Comment</th>
										</tr>
									</thead>

									<tbody>

										@foreach ($item->log()->orderby('created_at','desc')->get() as $log)

											<tr id="row-{{$log->id}}" data-hrid="{{$log->id}}" data-id="{{Crypt::encrypt($log->id)}}" data-com="{{$log->comment}}">

												<td>
													<span class="font-bold">{{$log->user->firstname.' '.$log->user->lastname}}</span> <br>
													<span>{{date('d-m-y, g:ia', strtotime($log->created_at))}}</span>
												</td>

												<td>
													<span class="c-06f">{{'#'.$log->id.': '}}</span> {{$log->comment}} <br><br>
													@if(in_array(Auth::user()->username, $edit_allow) && Auth::user()->id == $log->user_id)
														<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-pencil"></i></button>
													@endif

													@if(in_array(Auth::user()->username, $delete_allow))
														<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal"><i class="fa fa-trash"></i></button>
													@endif
												</td>

											</tr>

										@endforeach

									</tbody>

								</table>
							</div>

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
						<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Purchase Order Comment</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<textarea id="po-comment" class="form-control h120" required placeholder="Enter purchase order comment" cols="40"></textarea>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close">
									<i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" value="{{Crypt::encrypt($item->id)}}" id="po-id">
								<button class="btn-success btn btn-block" id='add-btn' type="submit" role="button">
									<i class="fa fa-check mr5"></i>Add</button>
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

					<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" purchase order log item?</p>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close">
								<i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="com-row-id-delete">
							<input type="hidden" id="com-id-delete">
							<button class="btn-danger btn btn-block" id='delete-btn' type="submit" role="button">
								<i class="fa fa-trash mr5"></i>Delete</button>
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
						<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Purchase Order Log Comment</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<textarea id="com-edit" class="form-control h120" required placeholder="Enter action point" cols="40"></textarea>
						</div>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close">
									<i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="com-id-edit">
								<input type="hidden" id="row-id">
								<button class="btn-success btn btn-block" id='edit-btn' type="submit" role="button">
									<i class="fa fa-check mr5"></i>Update</button>
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
		$(function () {
			'use strict';

			$('.data-table').DataTable({
				"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
				"order": [
					//[0, "asc"]
				]
			});
			$(".dataTables_wrapper").removeClass("form-inline");

			function getErrorMessage(jqXHR, exception) {
				var msg = '';
				if (jqXHR.responseJSON) {
					var errors = (jqXHR.responseJSON.errors);
					$.each(errors, function (key, value) {
						msg = value[0];
					})
				} else if (jqXHR['errors']) {
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

			function pnotify_alert(type, text) {
				var icon = 'fa-times';
				if (type == 'success') {
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

			$(document).on('click', '#add-btn', function (e) {

				e.preventDefault();

				var btn = $(this),
					btn_text = btn.html(),
					comment = $("#po-comment").val(),
					item_id = $("#po-id").val(),
					token = '{{ Session::token() }}',
					url = "{{route('admin.po.add.log')}}";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						comment: comment,
						item_id: item_id,
						_token: token
					},
					beforeSend: function () {
						btn.html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function (response) {
						btn.html(btn_text);
						pnotify_alert('success', response.message);
						$('#add-modal').modal('hide');
						window.location.href = "{{route('admin.po.show', Crypt::encrypt($item->id))}}";
						//$(load_element).load(location.href + " "+ load_element +">*","");
					},
					error: function (jqXHR, exception) {
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
					delete_title = tr.data('hrid'),
					hrid = tr.data('hrid'),
					com_id = tr.data('id');

				$("#delete-title").text(delete_title);
				$("#com-id-delete").val(com_id);
				$("#com-row-id-delete").val(hrid);
			});

			$(document).on('click', '#delete-btn', function (e) {
				e.preventDefault();
				var btn = $(this),
					btn_text = btn.html(),
					com_id = $('#com-id-delete').val(),
					remove_element = '#row-' + $("#com-row-id-delete").val(),
					token = '{{ Session::token() }}',
					url = "{{route('admin.po.delete.log')}}";

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
					success: function (response) {
						btn.html(btn_text);
						$('#delete-modal').modal('hide');
						pnotify_alert('success', response.message);
						$(remove_element).remove();
					},
					error: function (jqXHR, exception) {
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
					status = tr.data('status'),
					com = tr.data('com'),
					com_id = tr.data('id'),
					hrid = tr.data('hrid');

				$("#com-edit").val(com);
				$("#com-id-edit").val(com_id);
				$("#row-id").val(hrid);
			});

			$(document).on('click', '#edit-btn', function (e) {

				e.preventDefault();

				var btn = $(this),
					btn_text = btn.html(),
					comment = $("#com-edit").val(),
					com_id = $("#com-id-edit").val(),
					load_element = "#row-" + $("#row-id").val(),
					token = '{{ Session::token() }}',
					url = "{{route('admin.po.update.log')}}";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						comment: comment,
						com_id: com_id,
						_token: token
					},
					beforeSend: function () {
						btn.html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function (response) {
						btn.html(btn_text);
						pnotify_alert('success', response.message);
						$('#edit-modal').modal('hide');
						$(load_element).data('comment', comment);
						$(load_element).load(location.href + " " + load_element + ">*", "");
					},
					error: function (jqXHR, exception) {
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