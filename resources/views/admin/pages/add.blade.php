@extends('layouts.admin')
@section('title', 'Add Page')
@section('page_title') <i class="fa fa-file-o mr10"></i>Pages @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="{{route('admin.pages')}}">Pages</a></li>
	<li class="breadcrumb-item active">Add</li>
</ol>
@endsection


@section('content')

<div class="card">

	<div class="card-header bgc-070">
		<h4 class="text-uppercase no-padding no-margin font-600 c-fff"><strong>Add Page</strong></h4>
	</div>

	<div class="card-block">
		<div class="row v-padding-20">
			<div class="col-sm-8 offset-sm-2">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<i class="fa fa-times"></i> Some fields needs your attention.
				</div>
				@endif

				<form action="{{ route('store.page') }}" method="post" class="fo">
					<div class="row">
						<div class="col-6">
							<div class="form-group input_field_sections {{ $errors->has('title') ? 'has-error' : '' }}">
								<label for="title">Page Title <span class="rfd">*</span></label>

								<input type="text" name="title" id="title" class="form-control" value="{{ Request::old('title') }}" placeholder="Enter title" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9 &]+)$" data-validation-error-msg="Please use aplhanumeric characters only, with spaces and &amp;">

								<span class="f-error">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group input_field_sections {{ $errors->has('slug') ? 'has-error' : '' }}">
								<label for="lastname">Slug <span class="rfd">*</span></label>

								<input type="text" name="slug" id="slug" class="form-control" placeholder="Enter slug" value="{{ Request::old('slug') }}" data-validation="custom required" data-validation-regexp="^([a-zA-Z0-9-]+)$" data-validation-error-msg="Slug cannot contain leading or preceeding slashes(\/), no-spaces, only hypens allowed" >

								<span class="f-error">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-6">
							<div class="form-group input_field_sections {{ $errors->has('icon') ? 'has-error' : '' }}">
								<label for="icon">Icon <span class="rfd">*</span></label>
								<input
									   type="text"
									   name="icon"
									   class="form-control"
									   placeholder="Enter icon tag without 'fa-'"
									   value="{{ Request::old('icon') }}"
									   data-validation="custom"
									   data-validation-regexp="^([a-zA-Z0-9-]+)$"
									   data-validation-error-msg="Icons must be the string after 'fa-'/'.fa-'"
									   >

								<span class="f-error">{{ $errors->has('icon') ? $errors->first('icon') : '' }}</span>

								<span id="helpBlock" class="help-block" style="">
									<em>Use 'circle' or 'square' if not sure. Visit <a href="http://fontawesome.bootstrapcheatsheets.com/" target="_blank"><u>Font Awesome</u></a> for icons types</em>
								</span>

							</div>
						</div>

						<div class="col-6">
							<div class="form-group {{ $errors->has('type') ? 'has-error' : '' }} input_field_sections">
								<label for="type" class="control-label">Type <span class="rfd">*</span></label>

								<select name="type" id="type" class="form-control chzn-select" style="width: 100%;">
									<option value="page">Page</option>
									<option value="subpage">Subpage</option>
								</select>

								<span class="f-error">{{ $errors->has('type') ? $errors->first('type') : '' }}</span>
							</div>
						</div>
					</div>

					<div id="select-parent-page"></div>

					<div class="form-group input_field_sections">
						{{csrf_field()}}
						<input type="submit" class="form-control text-uppercase btn btn-primary font-600" value="Add">
					</div>

				</form>

			</div>
		</div>


		<div class="clearfix"></div>

	</div>

</div>

@endSection


@section('footer')

<script>

	$(function () {
		'use strict';

		$.validate();

		function composeLink() {
			var slug = $('#title').val();
			slug = slug.toLowerCase().replace(/  /g, ' ').replace(/ /g, '-').replace(/&/g, 'and');
			$('#slug').val(slug);
		}

		composeLink();

		$('#title').on('keyup', function () {
			composeLink();
		}).on('focusout', function () {
			composeLink();
		});

		$('#type').on('change', function () {
			var ptype = $(this).val();
			if(ptype == 'subpage'){
				$('#select-parent-page').load('{{ route('load.pages') }}');
			} else {
				$('#select-parent-page').html('');
			}
		});

	});

</script>

@endSection
