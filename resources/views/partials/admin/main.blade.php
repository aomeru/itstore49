<div id="content" class="bg-container">

	<header class="head no-top-margin bgc-fff" style="border: none !important; margin-top: -1px  !important;">
		<div class="main-bar">
			<div class="row no-gutters">
				<div class="col-sm-6 col-12">
					<h3 class="nav_top_align font-600 m-t-10 m-b-10 c-070">@yield('page_title')</h3>
				</div>
				<div class="col-sm-6 col-12 hidden-xs-down c-333">
					@yield('bc')
				</div>
			</div>
		</div>
	</header>

	<div class="outer">
		<div class="inner bg-light lter bg-container">
			@include('partials.messages')
			@yield('content')
		</div>
	</div>


	<div class="outer mt50 bgc-333 v-padding-15 xs-mb0">
		<div class="inner bg-dark bg-container">

			<div class="row hidden-xs-down c-ccc">
				<div class="col-6">&copy; {{date('Y')}} {{config('app.name')}}. All rights reserved.</div>
				<div class="col-6 text-right">
					Site by <span class="c-fff">IT Unit</span>
				</div>
			</div>

			<div class="hidden-sm-up text-center c-ccc">
                <p class="mb10">&copy; {{date('Y')}} {{config('app.name')}}. All rights reserved.</p>
                <p class="mb10">Site by <span class="c-fff">IT Unit</span></p>
            </div>
		</div>
	</div>

</div>
