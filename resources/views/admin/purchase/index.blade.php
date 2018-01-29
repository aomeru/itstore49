@extends('layouts.admin')
@section('title', 'Purchase Order')
@section('page_title') <i class="fa fa-usd mr10"></i>Purchase Order @endSection



@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Purchase Order</li>
</ol>
@endsection


@section('content')

	<?php
	$bd = config('app.bd'.config('app.bdyear'));
	$loss = false;
	$cb = $bd - $exp;
	if($cb <= 0) $loss = true;
	?>

	<div class="row mb40 text-center">
		<div class="col-sm-2 col-6 xs-mb20">
			<div class="card card-default">
				<div class="card-block padding-10">
					<span class="font-20x c-333">{{config('app.bdyear')}} Budget</span>
					<h1 class="xs-font-20x no-bottom-margin font-600"><s>N</s>{{number_format($bd)}}</h1>
				</div>
			</div>
		</div>

		<div class="col-sm-2 col-6 xs-mb20">
			<div class="card card-default">
				<div class="card-block padding-10">
					<span class="font-20x c-333">Expenditure</span>
					<h1 class="xs-font-20x no-bottom-margin font-600"><s>N</s>{{number_format($exp)}}</h1>
				</div>
			</div>
		</div>

		<div class="col-sm-2 col-6 xs-mb20">
			<div class="card card-{{ $loss == true ? 'danger' : 'success' }}">
				<div class="card-block padding-10">
					<span class="font-20x c-fff">{{ $loss == true ? 'Deficit' : 'Budget Left' }}</span>
					<h1 class="xs-font-20x no-bottom-margin font-600 c-fff"><s>N</s>{{number_format($bd - $exp)}}</h1>
				</div>
			</div>
		</div>
	</div>
	

	<div class="card mb50">

		<div class="card-block">
			@if(in_array(Auth::user()->username,$create_allow))
				<div class="mb10">
					<div class="pull-right">
						<a href="{{ route('admin.po.add') }}" class="btn btn-primary btn-sm no-margin" title="Assign new task"><i class="fa fa-plus"></i></a>
					</div>
					<div class="clearfix"></div>
				</div>
			@endif


			@if ($list->count() == 0)

				<p class="alert alert-info">No purchase order record found.</p>

			@else

				<div class="table-responsive">

					<table class="table table-bordered table-hover nowrap data-table" width="100%" data-page-length="50">

						<thead>
							<tr class="active">
								<th>#</th>
								<th>Title</th>
								<th class="text-center" title="Purchase Order">PO</th>
								<th class="text-center" title="Purchase Delivery Note">DN</th>
								<th class="text-center" title="Purchase Invoice">IN</th>
								<th>Added By</th>
								<th>Total</th>
								<th>Created</th>
								@if(in_array(Auth::user()->username,$edit_allow)) <th class="text-right">Actions</th> @endif
							</tr>
						</thead>

						<tbody>

							@php $row_count = 1 @endphp

							@foreach($list as $item)

								<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-id="{{Crypt::encrypt($item->id)}}" data-title="{{$item->title}}">

									<td>{{ $row_count }}</td>

									<td><u><a href="{{route('admin.po.show', Crypt::encrypt($item->id))}}" class="c-06f">{{$item->title}}</a></u> <span class="ml5 badge badge-primary font-no-bold">{{ $item->log == null ? 0 : $item->log->count() }}</span></td>

									{{--  <td class="text-center">{!! $item->po == null ? '<em class="c-999">Null</em>' : '<a href="'.route('showfile','storage/purchase/'.$item->po).'" title="View '.$item->title.' purchase order" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</td>  --}}
									
									<td class="text-center">{!! $item->po == null ? '<em class="c-999">Null</em>' : '<a href="'.asset('storage/purchase/'.$item->po).'" title="View '.$item->title.' purchase order" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</td>
									
									<td class="text-center">{!! $item->dn == null ? '<em class="c-999">Null</em>' : '<a href="'.asset('storage/purchase/'.$item->dn).'" title="View '.$item->title.' delivery note" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</td>
									
									<td class="text-center">{!! $item->inv == null ? '<em class="c-999">Null</em>' : '<a href="'.asset('storage/purchase/'.$item->inv).'" title="View '.$item->title.' invoice" target="_blank"><i class="fa fa-2x fa-file-image-o c-900"></i></a>' !!}</td>

									<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->user_id))}}" class="c-06f">{{$item->user->firstname.' '.$item->user->lastname}}</a></u></td>

									<td>{!! $item->total == null ? '<em class="c-999">Null</em>' : '<s>N</s>'.number_format($item->total) !!}</td>

									<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>

									@if(in_array(Auth::user()->username,$edit_allow))
										<td class="text-right">

											<a href="{{ route('admin.po.edit', Crypt::encrypt($item->id)) }}" class="btn btn-primary btn-sm no-margin" title="Edit {{ $item->title }} purchase order"><i class="fa fa-pencil"></i></a>
											
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
								<input type="hidden" id="po-row-id-delete">
								<input type="hidden" id="po-id-delete">
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
			"order": [[ 0 , "desc" ]]
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


		$('#delete-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				delete_title = tr.data('title'),
				hrid = tr.data('hrid'),
				task_id = tr.data('id');

			$("#delete-title").text(delete_title);
			$("#po-id-delete").val(task_id);
			$("#po-row-id-delete").val(hrid);
		});

		$(document).on('click', '#delete-btn', function(e){
			e.preventDefault();
			var btn = $(this),
				btn_text = btn.html(),
				po_id = $('#po-id-delete').val(),
				remove_element = '#row-' + $("#po-row-id-delete").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.po.delete')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					po_id: po_id,
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

	});
</script>
@endSection
