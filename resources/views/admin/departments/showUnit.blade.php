@extends('layouts.admin')
@section('title', $unit->title.' Allocations')
@section('page_title') <i class="fa fa-th-large mr10"></i>{{$unit->title}} @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item h-padding-5"><a href="{{route('admin.depts')}}">Departments &amp; Units</a></li>
	<li class="breadcrumb-item h-padding-5"><a href="{{route('admin.depts.show', Crypt::encrypt($unit->department_id))}}">{{$unit->department->title}}</a></li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">{{$unit->title}}</li>
</ol>
@endsection

@section('content')

	<div class="card">

		<div class="card-header bgc-070">
			<h4 class="font-600 text-center no-padding no-margin text-uppercase c-fff">{{$unit->title}} Allocations</h4>
		</div>


		<div class="card-block">

			@if ($alls->count() == 0)

				<p class="alert alert-info">There are no allocations to this unit.</p>

			@else

				<div class="table-responsive">

					<table class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="25">

						<thead>
							<tr class="active">
								<th>#</th>
								<th>Serial No</th>
								<th>Item</th>
								<th>Recipient</th>
								<th>Date</th>
							</tr>
						</thead>

						<tbody>

							@php $row_count = 1 @endphp

							@foreach($alls as $item)

								<tr>
									<td>{{ $row_count }}</td>
									<td>{{ $item->inventory->serial_no }}</td>
									<td>{{ $item->inventory->item->title }}</td>
									<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->user->id))}}" class="c-06f">{{ $item->user->firstname.' '.$item->user->lastname }}</a></u></td>
									<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>
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

	});
</script>
@endSection
