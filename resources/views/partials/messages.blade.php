<div>
	@if(Session::has('access_denied'))
	<p class="alert alert-danger text-center mb20" role="alert">
		<i class="fa fa-lock ml5"></i> {!! session('access_denied') !!}
	</p>
	@endif

	@if(Session::has('error'))
    <p class="alert alert-danger text-center mb20" role="alert">
		<i class="fa fa-times ml5"></i> {!! session('error') !!}
	</p>
	@endif

	@if(Session::has('error_array'))
	@foreach(Session::get('error_array') as $ea_item)
	<p class="alert alert-danger text-center mb20" role="alert">
		<i class="fa fa-times ml5"></i> {!! $ea_item !!}
	</p>
	@endforeach
	@endif


	@if(session('success'))
    <p class="alert alert-success text-center mb20" role="alert">
		<i class="fa fa-check ml5"></i> {!! session('success') !!}
	</p>
	@endif
</div>
<?php
session()->forget('access_denied');
session()->forget('home_flash');
session()->forget('error');
session()->forget('error_array');
session()->forget('success');
?>
