@extends('layouts.admin')
@section('title', 'Profile')
@section('page_title') <i class="fa fa-user mr10"></i>My Profile @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Profile</li>
</ol>
@endsection


@section('content')
@if(!$bank_com)
<div class="alert alert-danger" role="alert">
	<h4 class="text-center no-margin">You need to update your bank information to activate your pledge and auto match. </h4>
</div>
@endif


<div class="row mb20 text-center">
    <div class="col-6 col-sm-4">
        <div class="card card-success">
            <div class="card-block padding-10">
                <p class="no-bottom-margin c-fff"><span class="font-600 mr5">CODE:</span>{{Auth::user()->customer_code}}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="card card-danger">
            <div class="card-block padding-10">
                <p class="no-bottom-margin c-fff"><span class="font-600 mr5">PIN:</span> <span class="pin">*****</span> <span class="ml5" id="view-pin"><i class="fa fa-eye"></i></span></p>
            </div>
        </div>
    </div>
</div>


<div class="card" id="loadDiv">

	<div class="card-block">
		<ul class="nav nav-tabs " role="tablist">
			<li role="presentation" class="nav-item @if($bank_com) active @endif"><a href="#profile" aria-controls="home" role="tab" data-toggle="tab" class="nav-link font-600">Profile</a></li>

			<li role="presentation" class="nav-item @if(!$bank_com) active @endif"><a href="#bank" aria-controls="messages" role="tab" data-toggle="tab" class="nav-link font-600">Bank</a></li>
		</ul>

		<div class="mt10">
			<div class="tab-content font-16x">

				<div role="tabpanel" class="tab-pane @if($bank_com) active @endif" id="profile">
					<div class="row">

						<div class="col-sm-6 xs-mb15">
							<div class="card">
								<div class="card-header bgc-070">
									<h5 class="font-600 no-margin no-padding text-center text-uppercase card-title c-fff">Edit Profile</h5>
								</div>
								<div class="card-block get-loading">
									<form action="" method="post" class="fo">
										<div class="form-group input_field_sections {{ $errors->has('username') ? 'has-error' : '' }}">
											<label for="username" class="form-control-label ">Username <span class="rfd">*</span></label>

											<input type="text" name="username" id="username"  value="{{Auth::user()->username}}" class="form-control" data-validation="custom" data-validation-regexp="^([a-zA-Z]{3,50})$" data-validation-error-msg="Please enter a username with at least 3 characters" value="{{ Request::old('username') }}" placeholder="Enter your username">
											<span class="font-14x">{{ $errors->has('username') ? $errors->first('username') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('first_name') ? 'has-error' : '' }}">
											<label for="firstname" class="form-control-label ">Firstname <span class="rfd">*</span></label>

											<input type="text" name="first_name" id="firstname"  value="{{Auth::user()->first_name}}" class="form-control" data-validation="custom" data-validation-regexp="^([a-zA-Z]{3,50})$" data-validation-error-msg="Please enter a firstname with at least 3 characters" value="{{ Request::old('first_name') }}" placeholder="Enter your firstname">
											<span class="font-14x">{{ $errors->has('first_name') ? $errors->first('first_name') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('last_name') ? 'has-error' : '' }}">
											<label for="lastname" class="form-control-label ">Lastname <span class="rfd">*</span></label>

											<input type="text" name="last_name" id="lastname"  value="{{Auth::user()->last_name}}" class="form-control" data-validation="custom" data-validation-regexp="^([a-zA-Z]{3,50})$" data-validation-error-msg="Please enter a lastname with at least 3 characters" placeholder="Enter your lastname">
											<span class="font-14x">{{ $errors->has('last_name') ? $errors->first('last_name') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('gender') ? 'has-error' : '' }}">
											<label for="gender" class="form-control-label ">Gender <span class="rfd">*</span></label>

											<select name="gender" id="gender" class="form-control chzn-select">
												<option value="male" @if(Auth::user()->gender == 'male') selected @endif>Male</option>
												<option value="female" @if(Auth::user()->gender == 'female') selected @endif>Female</option>
											</select>

											<span class="font-14x c-c00">{{ $errors->has('gender') ? $errors->first('gender') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('email') ? 'has-error' : '' }}">
											<label for="email" class="form-control-label">Email <span class="rfd">*</span></label>

											<input type="email" name="email" id="email"  class="form-control" data-validation="email" data-validation-error-msg="Please enter a valid email address" value="{{Auth::user()->email}}" placeholder="Enter email address">
											<span id="validate-email" style="color:red" class="font-14x">
												<span>{{ $errors->has('email') ? $errors->first('email') : '' }}</span>
											</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('phone') ? 'has-error' : '' }}">
											<label for="phone" class="form-control-label">Phone Number <span class="rfd">*</span></label>

											<input type="text" name="phone" id="phone" class="form-control" value="{{ Auth::user()->phone }}" maxlength="14" data-validation="custom" data-validation-regexp="^([0-9+]{14})$" data-validation-error-msg="Please enter a valid phone number including +234" placeholder="Enter phone number">
											<span id="validate-phone" style="color:red" class="font-14x">
												<span>{{ $errors->has('phone') ? $errors->first('phone') : '' }}</span>
											</span>
										</div>

										<div class="form-group input_field_sections">
											{{csrf_field()}}
											<button role="button" id="update-profile-button" type="button" class="btn btn-primary btn-block font-600"><i class="fa fa-check mr5"></i>Save Changes</button>
										</div>
									</form>
									<p class="alert message-box text-center padding-10 no-bottom-margin no-border"></p>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="card">
								<div class="card-header bgc-070">
									<h5 class="font-600 no-margin no-padding text-center text-uppercase card-title c-fff">Update Password</h5>
								</div>
								<div class="card-block get-loading">
									<form action="" method="post" class="fo">
										<div class="form-group input_field_sections">
											<label for="old_password" class="form-control-label sr-only">Old Password</label>
											<input 
												   type="password" 
												   class="form-control" 
												   id="old-password" 
												   name="old_password" 
												   required="required" 
												   placeholder="Old password"
												   >
										</div>

										<div class="form-group input_field_sections">
											<label for="password_confirmation" class="form-control-label sr-only">New Password</label>
											<input 
												   type="password" 
												   class="form-control" 
												   id="password-confirmation" 
												   name="password_confirmation" 
												   required="required" 
												   placeholder="New password"
												   data-validation="length" 
												   data-validation-length="min6" 
												   data-validation-error-msg="Password must be at least 8 characters"
												   >
										</div>

										<div class="form-group input_field_sections">
											<label for="password" class="form-control-label sr-only">Confirm New Password</label>
											<input 
												   type="password" 
												   class="form-control" 
												   id="password" 
												   name="password" 
												   required="required" 
												   placeholder="Confirm new password"
												   data-validation="confirmation"
												   data-validation-confirm="password_confirmation"
												   data-validation-error-msg="Password doesn't match first entry"
												   >
										</div>

										<div class="form-group input_field_sections">
											<span class="show form-text v-margin-10 font-12x c-999">Changing your password automatically logs you out of your account, be sure to remember the one you change it to.</span>
											<button role="button" id="update-password-button" type="button" class="btn btn-primary btn-block font-600"><i class="fa fa-check mr5"></i>Update Password</button>
										</div>
									</form>
									<p class="alert message-box text-center padding-10 no-bottom-margin no-border"></p>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div role="tabpanel" class="tab-pane @if(!$bank_com) active @endif" id="bank">
					<div class="row">
						<div class="col-sm-6 xs-mb15">
							<div class="card">
								<div class="card-header bgc-070">
									<h5 class="font-600 no-margin no-padding text-center text-uppercase card-title c-fff">Bank Information</h5>
								</div>
								<table class="table">
									<tr>
										<th>Bank</th>
										<td class="text-right c-2c5">{!! Auth::user()->bank_name != null ? Auth::user()->bank_name : '<em class="c-999">Null</em>' !!}</td>
									</tr>
									<tr>
										<th>Account Name</th>
										<td class="text-right c-2c5">{!! Auth::user()->account_name != null ? Auth::user()->account_name : '<em class="c-999">Null</em>' !!}</td>
									</tr>
									<tr>
										<th>Account Number</th>
										<td class="text-right c-2c5">{!! Auth::user()->account_no != null ? Auth::user()->account_no : '<em class="c-999">Null</em>' !!}</td>
									</tr>
								</table>
								<div class="card-footer text-center font-600 c-f00"></div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="card">
								<div class="card-header bgc-070">
									<h5 class="no-margin no-padding font-600 text-center text-uppercase card-title c-fff">Update Bank Information</h5>
								</div>
								<div class="card-block">

									<form action="{{route('admin.update.bank')}}" method="post" class="fo">

										<div class="form-group input_field_sections {{ $errors->has('bank_name') ? 'has-error' : '' }}">
											<label for="bank-name" class="form-control-label ">Bank Name <span class="rfd">*</span></label>

											<select name="bank_name" id="bank-name" class="form-control chzn-select">
												@foreach(config('app.banks') as $b)
												<option value="{{$b}}" @if(Auth::user()->bank_name == $b) selected @endif>{{$b}}</option>
												@endforeach
											</select>

											<span class="font-14x c-c00">{{ $errors->has('bank_name') ? $errors->first('bank_name') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('account_name') ? 'has-error' : '' }}">
											<label for="account-name" class="form-control-label ">Account Name <span class="rfd">*</span></label>
											<input type="text" name="account_name" id="account-name"  class="form-control" required value="{{Auth::user()->account_name}}" placeholder="Enter account name">
											<span class="font-14x c-c00">{{ $errors->has('account_name') ? $errors->first('account_name') : '' }}</span>
										</div>

										<div class="form-group input_field_sections {{ $errors->has('account_no') ? 'has-error' : '' }}">
											<label for="account-no" class="form-control-label ">Account Number <span class="rfd">*</span></label>
											<input type="text" name="account_no" id="account-no"  class="form-control" required value="{{Auth::user()->account_no}}" placeholder="Enter account no">
											<span class="font-14x c-c00">{{ $errors->has('account_no') ? $errors->first('account_no') : '' }}</span>
										</div>

										<div class="form-group input_field_sections">
											{{csrf_field()}}
											<button role="button" type="submit" class="btn btn-success btn-block font-600"><i class="fa fa-check mr5"></i>Update</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


@endSection




@section('footer')

<script>
	$(function(){		
		$("#dob").datepicker();
		
		$.validate();
		
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
		
		$(document).on('click', '#update-profile-button', function(e){

			e.preventDefault();

			var button = $(this),
				button_text = button.html(),
				load_element = '#loadDiv',
				form = button.closest('form'),
				url = "{{route('admin.update.profile')}}",
				token = '{{ Session::token() }}';

			$.ajax({
				type: 'POST',
				url: url,
				data: {
					first_name: $('#firstname').val(),
					last_name: $('#lastname').val(),
					username: $('#username').val(),
					phone: $('#phone').val(),
					gender: $('#gender').val(),
					email: $('#email').val(),					
					_token: token
				},
				beforeSend: function(){
					button.html('<i class="fa fa-spinner fa-spin fa-mr10"></i> Processing');
				},
				success: function(returnData){
					button.html(button_text);
					pnotify_alert('success', 'Changes Saved');
					$(load_element).load(location.href + " "+ load_element +">*","");
				},
				error: function(jqXHR, exception){
					button.html(button_text);
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});

		});
		
		$(document).on('click', '#update-password-button', function(e){

			e.preventDefault();

			var button = $(this),
				button_text = button.html(),
				form = button.closest('form'),
				url = "{{route('admin.update.password')}}",
				token = '{{ Session::token() }}';

			$.ajax({
				type: 'POST',
				url: url,
				data: {
					old_password: $('#old-password').val(),
					password_confirmation: $('#password-confirmation').val(),
					password: $('#password').val(),
					_token: token
				},
				beforeSend: function(){
					button.html('<i class="fa fa-spinner fa-spin fa-mr10"></i> Processing');
				},
				success: function(returnData){
					button.html(button_text);
					pnotify_alert('success', 'Password changed please login.');
					window.location.href = "{{route('home')}}";
				},
				error: function(jqXHR, exception){
					button.html(button_text);
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});

		});
		
		$(document).on('click', '#update-contact-btn', function(e){

			e.preventDefault();

			var button = $(this),
				button_text = button.html(),
				form = button.closest('form'),
				load_element = '#loadDiv',
				url = "{{route('admin.update.contact')}}",
				token = '{{ Session::token() }}';

			$.ajax({
				type: 'POST',
				url: url,
				data: {
					email: $('#email').val(),
					phone: $('#phone').val(),
					facebook: $('#facebook').val(),
					twitter: $('#twitter').val(),
					_token: token
				},
				beforeSend: function(){
					button.html('<i class="fa fa-spinner fa-spin fa-mr10"></i> Processing');
				},
				success: function(returnData){
					button.html(button_text);
					pnotify_alert('success', 'Contact details updated Saved');
					$(load_element).load(location.href + " "+ load_element +">*","");
				},
				error: function(jqXHR, exception){
					button.html(button_text);
					var error = getErrorMessage(jqXHR, exception);
					pnotify_alert('error', error);
				}
			});
		});
		
	});
</script>
@endSection