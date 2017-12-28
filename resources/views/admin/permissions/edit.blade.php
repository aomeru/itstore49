@extends('layouts.admin')
@section('title', 'Edit Permissions')
@section('page_title') <i class="fa fa-lock mr10"></i>Permissions @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="{{route('admin.perm')}}">Permissions</a></li>
	<li class="breadcrumb-item active">Edit</li>
</ol>
@endsection


@section('content')
<div class="card">
	<div class="card-header bgc-070">
		<h4 class="text-uppercase no-padding no-margin font-600 c-fff">Edit Permissions: {{ $role->title }} Role</h4>
	</div>

	<div class="card-block">
		<div class="row v-padding-50 xs-v-padding-20">
			<div class="col-sm-6 offset-sm-3">

				@if (count($errors) > 0)

				<p class="msg-error">
					<i class="fa fa-times"></i> Some fields needs your attention.
				</p>

				@endif

				<form action="{{ route('update.perm', $role->id) }}" method="post" class="form-override">

					<p>Update permissions for <strong>"{{ $role->title }} Role"</strong></p>

					<div class="form-group input_field_sections {{ $errors->has('pages') ? 'has-error' : '' }}">

						<label for="pages">Assigned Pages <span class="rfd">*</span></label>

						<select name="pages[]" id="pages" multiple class="form-control chzn-select">

							@foreach($pages as $page)

							<option value="{{ $page->id }}" @if(in_array($page->id, $assigned_pages)) selected @endif>{{ $page->title }}</option>

							@endforeach

						</select>

						<span class="f-error">{{ $errors->has('pages') ? $errors->first('pages') : '' }}</span>

					</div>


					<div class="form-group input_field_sections">
						{{ csrf_field() }}
						<button type="submit" class="text-uppercase font-600 btn btn-success btn-block"><i class="fa fa-check mr10"></i>Save Changes</button>
					</div>

				</form>

			</div>
		</div>

	</div>

</div>

@endSection


@section('footer')

<script>

	$(function () {
		'use strict';

		$.validate({
			validateOnBlur: false
		});
	});

</script>

@endSection
