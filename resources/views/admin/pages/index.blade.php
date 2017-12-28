@extends('layouts.admin')
@section('title', 'Pages')
@section('page_title') <i class="fa fa-file-o mr10"></i>Pages @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Pages</li>
</ol>
@endsection

@section('content')

<div class="card">
	<div class="card-header bgc-070">
		<h4 class="text-uppercase font-600 no-padding no-margin c-fff"><strong>Page List</strong></h4>
	</div>
	
	<div class="card-block">
		<div class="mb10">
			<div class="pull-right">
				<a href="{{route('create.page')}}" class="btn btn-primary btn-sm no-margin" title="Add new page"><i class="fa fa-plus"></i></a>
			</div>
			<div class="clearfix"></div>
		</div>
		
		@if (count($list) == 0)
		<p class="alert alert-info">No page record found yet.</p>
		@else

		<div class="table-responsive">
			<table id="pages-table" class="table table-bordered table-hover" width="100%" data-page-length="50">

				<thead>
					<tr class="active">
						<th>#</th>
						<th>Title</th>
						<th>Slug</th>
						<th>Parent</th>
						<th>Icon</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>

				<tbody>

					@php

					$row_count = 1

					@endphp

					@foreach($list as $page)

					<tr>
						<td>{{ $row_count }}</td>
						<td>{{ $page->title }}</td>

						<?php
						if($page->type == 'subpage')
						{
							$parent_link = App\Models\Page::select('slug','title')->where('id',$page->type_id)->first();
							echo '<td>'.$page->slug.' <span class="fontp9x c-999">(/admin/'.$page->slug.'/)</span></td>';
						}
						else
						{
							echo '<td>'.$page->slug.' <span class="fontp9x c-999">(/admin/'.$page->slug.'/)</span></td>';
						}
						?>

						<td>
							@if(isset($parent_link))
							{{ $parent_link->title }}
							@else
							<em class="c-999">Null</em>							
							@endif
						</td>

						<td class="text-center font125x"><i class="fa fa-{{ $page->icon }}"></i></td>
						
						<td class="text-center">
							<a href="{{ route('edit.page', $page->id) }}" title="Edit {{ $page->title }} page" class="btn btn-default btn-sm">
								<i class="fa fa-pencil"></i>
							</a>

							<a href="{{ route('delete.page', $page->id) }}" title="Delete {{ $page->title }} page" class="btn btn-danger btn-sm">
								<i class="fa fa-trash"></i>
							</a>
						</td>
						@php

						unset($parent_link);

						@endphp
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
		
		$('#pages-table').DataTable( {
			"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			"order": [[ 0, "asc" ]]
		});
		$(".dataTables_wrapper").removeClass("form-inline");
	});
</script>
@endSection