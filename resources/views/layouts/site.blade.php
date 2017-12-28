<?php
$linkpre = '';
$active = 'active';
$path = \Request::path();
if($path != "/")
{
	$pathdata = explode('/',$path);
	if(!in_array('home',$pathdata))
	{
		$linkpre = '/home';
		$active = '';
	}
}
//if(isset($nav) && $nav == 'home') $hpon = true; else $hpon = false;
//$hpon = false;
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">

		<title>@yield('title') {{config('app.name')}}</title>
		<!--IE Compatibility modes-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!--Mobile first-->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />

		<link href="https://fonts.googleapis.com/css?family=Exo+2|Grand+Hotel|Raleway" rel="stylesheet">

		<link type="text/css" rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}" />

		<link type="text/css" rel="stylesheet" href="{{asset('css/select2.min.css')}}" />

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

		<link href="{{ asset('slick/slick.css') }}" rel="stylesheet" type="text/css">

		<link href="{{ asset('slick/slick-theme.css') }}" rel="stylesheet" type="text/css">

		<link type="text/css" rel="stylesheet" href="{{asset('css/site.css')}}" />
		@yield('page_styles')
	</head>

	<body class="body">


		@yield('content')


		<footer class="v-padding-50 md-v-padding-100 xs-v-padding-20 bgc-111 c-999">

			<p class="no-bottom-margin text-center">&copy; {{date('Y')}} {{config('app.name')}}. <br class="visible-xs-inline">All rights reserved.

			<div class="container">
			    <div class="row">
			        <div class="col-xs-6">
                        </p>
			        </div>
			        <div class="col-xs-6">
                        <p class="no-bottom-margin text-right">

                        </p>
			        </div>
			    </div>
				<div class="text-center">


				</div>
			</div>
		</footer>

		@yield('page_footer')

		<script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>

		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<script src="{{asset('js/bootstrap.min.js')}}" type="text/javascript"></script>

		<script src="{{asset('js/jquery.scrollUp.js')}}" type="text/javascript"></script>

		<script src="{{asset('js/jquery.sticky.js')}}" type="text/javascript"></script>

		<script src="{{asset('js/parallax.min.js')}}" type="text/javascript"></script>

		<script src="{{asset('js/select2.min.js')}}" type="text/javascript"></script>

		<script src="{{ asset('js/jquery.countdown.min.js') }}" type="text/javascript"></script>

		<script src="{{ asset('slick/slick.min.js') }}" type="text/javascript"></script>

		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

		<script type="text/javascript" src="{{asset('js/jquery-easing.js')}}"></script>

		<script type="text/javascript" src="{{asset('js/clipboard.min.js')}}"></script>

		<script src="{{ asset('js/screen.js') }}" type="text/javascript"></script>

		@yield('page_scripts')




		<script>
			$(function() {



			});
		</script>

		@yield('footer')

	</body>

</html>
