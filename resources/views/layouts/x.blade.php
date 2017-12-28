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

		<title>{{config('app.name')}} @yield('title')</title>
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

	<body>

		<nav id="vh-navbar" class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle pull-left collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<i class="fa fa-bars"></i>
					</button>
                    <a class="navbar-brand xs-ml50" href="#"><span><img src="{{asset('images/brand-name-w.png')}}" alt="Brand logo"></span></a>
				</div>

				<div id="navbar" class="collapse navbar-collapse">

					<ul class="nav navbar-nav navbar-right">
						<li class="@if(isset($nav) && $nav == 'home') active @endif"><a href="{{route('home')}}">Home</a></li>
						<li class="@if(isset($nav) && $nav == 'about') active @endif"><a href="{{route('about')}}">About</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Collections<span class="caret ml10"></span></a>
							<ul class="dropdown-menu xs-pl15">
								<li class="@if(isset($nav) && $nav == 'fcollections') active @endif"><a href="">Men</a></li>
								<li class="@if(isset($nav) && $nav == 'mcollections') active @endif"><a href="">Women</a></li>
							</ul>
						</li>
						<li class="@if(isset($nav) && $nav == 'beauty') active @endif"><a href="">Beauty</a></li>
						<li class="@if(isset($nav) && $nav == 'press') active @endif"><a href="">Press</a></li>
						<li class="@if(isset($nav) && $nav == 'stockist') active @endif"><a href="">Stockist</a></li>
						<li class="@if(isset($nav) && $nav == 'contact') active @endif"><a href="">Contact</a></li>
					</ul>

				</div>
			</div>
		</nav>


		@yield('content')


		<footer class="v-padding-50 md-v-padding-100 xs-v-padding-20 bgc-111 c-999">
			<div class="container">
			    <div class="row">
			        <div class="col-xs-6">
						<p class="no-bottom-margin">&copy; {{date('Y')}} {{config('app.name')}}. <br class="visible-xs-inline">All rights reserved.
                        </p>
			        </div>
			        <div class="col-xs-6">
                        <p class="no-bottom-margin text-right">
                            @if(config('app.telegram')) <a href="{{config('app.tg_link')}}" target="_blank" class="mr10 c-070"><i class="fa fa-telegram fa-2x"></i></a> @endif
                            @if(config('app.facebook')) <a href="{{config('app.fb_link')}}" target="_blank" class="mr10 c-070"><i class="fa fa-facebook fa-2x"></i></a> @endif
                            @if(config('app.twitter')) <a href="{{config('app.tw_link')}}" target="_blank" class="mr10 c-070"><i class="fa fa-twitter fa-2x"></i></a> @endif
                            @if(config('app.instagram')) <a href="{{config('app.ig_link')}}" target="_blank" class="mr10 c-070"><i class="fa fa-instagram fa-2x"></i></a> @endif
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
