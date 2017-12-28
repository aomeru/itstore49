@extends('layouts.admin')
@section('title', 'Permissions')
@section('page_title') <i class="fa fa-lock mr10"></i>Permissions @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Permissions</li>
</ol>
@endsection


@section('content')
<div class="card">
	<div class="card-header bgc-070">
		<h4 class="text-uppercase font-600 no-padding no-margin c-fff"><strong>Permissions List</strong></h4>
	</div>

	<div class="card-block">
		<div class="mb10">
			<div class="pull-right">
				<a href="{{route('assign.perm')}}" class="btn btn-primary btn-sm no-margin" title="Add new permission"><i class="fa fa-plus"></i></a>
			</div>
			<div class="clearfix"></div>
		</div>

		@if (count($role_list) == 0)
		<p class="alert alert-info">No permissions recorded yet.</p>
		@else

		<div class="table-responsive">
			<table id="permissions-table" class="table table-bordered table-hover" width="100%" data-paging="false">

				<thead>
					<tr class="active">
						<th>#</th>
						<th>Role</th>
						<th>Allowed Pages</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>

				<tbody>

					@php

					$row_count = 1

					@endphp


					@foreach($role_list as $role)


					@php

					$perm_key = $role->id

					@endphp


					<tr>
						<td>{{ $row_count }}</td>
						<td>{{ $role->title }}</td>
						<td>
							@php

							$a_c = 1;
							$page_list = ''

							@endphp

							@while($a_c <= count($perm_list[$role->id]))

							@php

							$page_list .= $perm_list[$role->id][$a_c - 1].', ';

							$a_c++

							@endphp


							@endwhile

							@php

							echo substr($page_list,0,-2)

							@endphp

							</td>
						<td class="text-right font15x xs-font125x">
							<a href="{{ route('edit.perm', $role->id) }}" title="Edit {{ $role->title }} permissions" class="btn btn-primary btn-sm">
								<i class="fa fa-pencil"></i>
							</a>
						</td>
					</tr>


					@php

					$row_count++

					@endphp


					@endforeach

				</tbody>

			</table>
		</div>

		@endif

	</div>

</div>

<div class="clearfix"></div>

@endSection




@section('footer')
<script>
	$(function(){
		'use strict';

		$('#permissions-table').DataTable( {
			"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			"order": [[ 0, "asc" ]]
		});
		$(".dataTables_wrapper").removeClass("form-inline");
	});
</script>
@endSection
