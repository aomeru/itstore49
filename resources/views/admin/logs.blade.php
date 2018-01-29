@extends('layouts.admin')
@section('title', 'Logs')
@section('page_title') <i class="fa fa-barcode mr10"></i>Logs @endSection



@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Logs</li>
</ol>
@endsection


@section('content')

<div class="card mb50">

	<div class="card-block">

		@if ($logs->count() == 0)

			<p class="alert alert-info">No task record found.</p>

		@else

			<div class="table-responsive">

				<table class="table table-bordered table-hover nowrrap data-table" width="100%" data-page-length="50">

					<thead>
						<tr class="active">
							<th>#</th>
							<th>User</th>
							<th>Page</th>
							<th>Log</th>
							<th>Logged</th>
						</tr>
					</thead>

					<tbody>

						@php $row_count = 1 @endphp

						@foreach($logs as $item)

							<tr>

								<td>{{ $row_count }}</td>

								<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->user_id))}}" class="c-06f">{{$item->user->firstname.' '.$item->user->lastname}}</a></u></td>

								<td>
									<?php
									$i = $item->page_url;
									$i = strlen($i) > 20 ? substr($i,0,20).'...' : $i;
									?>
									{{$i}}
								</td>

								<td style="word-wrap: inherit !important">{{$item->descrip}}</td>

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

	});
</script>
@endSection
