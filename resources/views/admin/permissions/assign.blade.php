@extends('layouts.admin')
@section('title', 'Assign Permissions')
@section('page_title') <i class="fa fa-lock mr10"></i>Permissions @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="{{route('admin.perm')}}">Permissions</a></li>
	<li class="breadcrumb-item active">Assign</li>
</ol>
@endsection


@section('content')
<div class="card bg-white">

	<div class="card-header bgc-070">
		<h4 class="text-uppercase no-padding no-margin font-600 c-fff"><strong>Assign Permissions</strong></h4>
	</div>

	<div class="card-block">
		<div class="row v-padding-30">
			<div class="col-sm-6 offset-sm-3">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<i class="fa fa-times"></i> Some fields needs your attention.
				</div>
				@endif

				<form action="{{ route('store.perm') }}" method="post" class="form-override">

					<div class="form-group input_field_sections {{ $errors->has('roles') ? 'has-error' : '' }}">

						<label for="roles">Roles <span class="rfd">*</span></label>

						<select name="roles[]" id="roles" multiple class="form-control chzn-select">

							@foreach($roles as $role)

							<option value="{{ $role->id }}">{{ $role->title }}</option>

							@endforeach

						</select>

						<span class="f-error">{{ $errors->has('roles') ? $errors->first('roles') : '' }}</span>

					</div>


					<div class="form-group input_field_sections {{ $errors->has('pages') ? 'has-error' : '' }}">

						<label for="pages">Pages <span class="rfd">*</span></label>

						<select name="pages[]" id="pages" multiple class="form-control chzn-select">

							@foreach($pages as $page)

							<option value="{{ $page->id }}">{{ $page->title }}</option>

							@endforeach

						</select>

						<span class="f-error">{{ $errors->has('pages') ? $errors->first('pages') : '' }}</span>

					</div>


					<div class="form-group input_field_sections">
						{{ csrf_field() }}
						<input type="submit" class="form-control text-uppercase btn btn-primary font-600" value="Assign">
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

		$.validate({ validateOnBlur: false });

	});

</script>

@endSection
