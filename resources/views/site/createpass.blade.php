@extends('layouts.site')
@section('title','Create Password - ')

@section('content')

<div class="v-padding-100 md-v-padding-200 xs-v-padding-50 xs-pb200" style="background-color: rgba(0,0,0,0.90)">
	<div class="container">
		<div class="row">

			<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

				<form action="{{route('store.pass')}}" method="post" class="padding-15 fonb fonb-alt br10 bgc-ffrf">

					<div class="row hidden-xs mb20">
						<div class="col-xs-6 col-xs-offset-3">
							<img src="{{asset('images/brand-name-w.png')}}" class="img-responsive center-block" alt="">
						</div>
					</div>



					<p class="msg-box text-center no-bottom" role="alert"></p>




					@include('partials.messages')




					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">

						<label for="email" class="control-label c-ccc">Email <span class="rfd">*</span></label>

						<div class="input-group">

							<div class="input-group-addon"><i class="fa fa-user-circle"></i></div>

							<input type="email" name="email" id="login-email"  class="form-control" data-validation="email" data-validation-error-msg="Please enter a valid email address" value="{{Request::old('email')}}" placeholder="Email address">

						</div>

						<span id="validate-email" style="color:red" class="font-14x">
							<span>{{ $errors->has('email') ? $errors->first('email') : '' }}</span>
						</span>

					</div>




					<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">

						<label for="password_confirmation" class="control-label c-ccc">Password <span class="rfd">*</span></label>

						<div class="input-group">

							<div class="input-group-addon"><i class="fa fa-lock"></i></div>

							<input type="password" class="form-control" id="login-password" name="password_confirmation" required="required" placeholder="Create password" data-validation="length" data-validation-length="min6" data-validation-error-msg="Your password must be 6 characters or more">

						</div>

						<span class="font-14x">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation') : '' }}</span>
					</div>




					<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">

						<label for="password" class="control-label c-ccc">Confirm Password <span class="rfd">*</span></label>

						<div class="input-group">

							<div class="input-group-addon"><i class="fa fa-lock"></i></div>

							<input type="password" class="form-control" id="login-password" name="password" required="required" placeholder="Confirm password" data-validation="confirmation" data-validation-error-msg="Your passwords do not match">

						</div>

						<span class="font-14x">{{ $errors->has('password') ? $errors->first('password') : '' }}</span>
					</div>


					<div class="form-group no-bottom-margin">
						{{csrf_field()}}
						<button type="submit" class="btn btn-success btn-lg btn-block bgc-333 c-fff no-border">Create Password</button>
					</div>


				</form>

			</div>

		</div>

	</div>
</div>

@endSection


@section('page_footer')



@endSection


@section('page_scripts')

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

@endSection


@section('footer')



<script>
	$(function(){
		$.validate({
			modules : 'security',
		});

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

		function cdis(){
			if($('#login-email').val().length > 0 && $('#login-password').val().length > 0) {
				$('#login-btn').removeAttr('disabled');
			}
		}

		cdis();

		$(document).on('change', '#login-email', function(){ cdis(); });
		$(document).on('change', '#login-password', function(){ cdis(); });

		var btn_loading = '<i class="fa fa-spinner fa-spin mr5"></i> Processing',
			text_check = '<i class="fa fa-check mr5"></i>';


		$(document).on('click', '#login-btn', function(e){
			e.preventDefault();

			var btn = $(this),
				btn_text = btn.text(),
				success_text = text_check + ' Welcome',
				message = btn.closest('form').find('.msg-box'),
				url = "{{route('post.login')}}",
				token = '{{ Session::token() }}';

			$.ajax({
				type: 'POST',
				url: url,
				data: {
					email: $('#login-email').val(),
					password: $('#login-password').val(),
					_token: token
				},
				beforeSend: function(){
					btn.html(btn_loading);
					btn.attr('disabled',true);
                    message.addClass('no-bottom-margin').removeClass('alert alert-danger alert-success').html('');
				},
				success: function(response){
					var redir = response.redir,
						redir_url = "{{url(':redir')}}",
						redir_url = redir_url.replace(':redir', redir);
					btn.remove();
                    message.removeClass('alert-danger no-bottom-margin').addClass('alert alert-success').html(success_text);
					window.location.href = redir;
				},
				error: function(jqXHR, exception){
					btn.attr('disabled',false);
					btn.html(btn_text);
					var error = getErrorMessage(jqXHR, exception);
					message.removeClass('no-bottom-margin').addClass('alert alert-danger').html('<i class="fa fa-times mr5"></i>' + error);
				}
			});
		});



	});
</script>
@endSection
