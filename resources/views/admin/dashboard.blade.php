@extends('layouts.admin')
@section('title','Dashboard')
@section('page_title')
<i class="fa fa-home mr10"></i> Dashboard
@endsection

@section('content')


<p class="font-600 text-right">
	Welcome back {{Auth::user()->firstname}}
</p>

<div class="row">

	<div class="col-sm-12">

		




        <div class="card v-margin-50">
        	<div class="card-block">

        	</div>
        </div>








		<div class="row mb20">
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>xyz</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">hello everyone</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>xyz</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">hello everyone</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>xyz</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">hello everyone</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>xyz</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">hello everyone</h3>
					</div>
				</div>
			</div>
		</div>









		<div class="row">
			<div class="col-sm-6">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070">
						<h5 class="font-600 text-center no-padding no-margin text-uppercase c-fff">dsjlkjdskjds</h5>
					</div>
					<div class="card-block text-center">

						jnkdhkfjsdhijs

					</div>
				</div>

			</div>


			<div class="col-sm-6">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070"><h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">hjdsjdsbjds</h5></div>
					<div class="card-block text-center">

						<p class="mb0">
							;dskjkdhsshkkjjksd
						</p>

					</div>
				</div>

			</div>

		</div>






		<div class="row">

			<div class="col-sm-4">
				<div class="card mb30 xs-mb20">
					<div class="card-block text-center">
						<p class="no-bottom-margin"><em>dsddssd</em></p>
						<h1 class="font3x font-600 c-2c5 no-bottom-margin">dssdsd</h1>
					</div>
				</div>
			</div>

			<div class="col-sm-8">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070">
						<h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">dsbskjdhsdj</h5>
					</div>
					<div class="card-block">


					</div>
				</div>
			</div>

		</div>

	</div>
</div>
@endSection




@section('page_footer')

	<div class="modal fade" id="invite-modal" tabindex="-1" role="dialog" aria-labelledby="">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<form action="" method="post">

					<div class="modal-header">
						<h4 class="modal-title no-padding no-bottom-margin text-uppercase font-600">jdcnjhdf</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<textarea id="emails" class="form-control input_field_sections" name="emails" required placeholder="Enter emails" rows="5" cols="40"></textarea>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn-default btn" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>

						{{csrf_field()}}
						<button class="btn-success btn" type="submit" role="button"><i class="fa fa-check mr5"></i>Send</button>
					</div>
				</form>

			</div>
		</div>
	</div>

@endSection





@section('footer')

	<script type="text/javascript">

		$(function(){
			'use strict';

			$('.data-table').DataTable( {
				"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
				"order": [[ 0, "desc" ]]
			});
			$(".dataTables_wrapper").removeClass("form-inline");

		});

	</script>


	<script>

	</script>

@endSection
