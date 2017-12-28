@extends('layouts.admin')
@section('title','Dashboard')
@section('page_title')
<i class="fa fa-home mr10"></i> Dashboard
@endsection

@section('content')
<?php
if($testimonial) $dpt = true; else $dpt = false;
?>

<p class="font-600">
	Welcome back Admin {{Auth::user()->username}}
</p>

<div class="row">

	<div class="col-sm-12">

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


        {{-- <div class="hidden-sm-up mb20">
            <p class="text-center mb10">Quick links</p>
            <div class="row">
                <div class="col-6"><a href="{{route('get-help')}}" class="btn btn-block btn-success"><i class="fa fa-heart-o mr5"></i>Get Help</a></div>
                <div class="col-6"><a href="{{route('provide-help')}}" class="btn btn-block btn-success"><i class="fa fa-heart mr5"></i>Provide Help</a></div>
            </div>
        </div> --}}




        <div class="card v-margin-50">
        	<div class="card-block">
        		{{-- <a href="{{route('refresh.ref')}}" class="btn btn-success mr5">Update Ref</a> --}}
        		<a href="{{route('fix.tlog')}}" class="btn btn-success mr5">Fix Tlog</a>
        		<button data-toggle="modal" data-target="#invite-modal" class="btn btn-primary mr5">Send Invite</button>
        	</div>
        </div>





		<div class="row mb20">
			<div class="col-sm-4 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Total Users</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($users)}}</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Active Users</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($aUsers)}}</h3>
					</div>
				</div>
			</div>
			{{-- <div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Pledged Users</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($list)}}</h3>
					</div>
				</div>
			</div> --}}
			<div class="col-sm-4 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Suspended Users</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($sUsers)}}</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb20">
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Completed Pledges</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($ccount)}}</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Pending Payments</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($pcount)}}</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Waitlist</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{number_format($pwcount)}}</h3>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card card-success">
					<div class="card-block padding-10 c-fff">
						<span>Total Transaction</span>
						<h3 class="xs-font-20x no-bottom-margin font-600 c-fff">₦{{number_format($total_t)}}</h3>
					</div>
				</div>
			</div>
		</div>

		{{-- <div class="row mb20">
			<div class="col-sm-3 col-6 xs-mb20">
				<div class="card">
					<div class="card-block padding-10">
						<span>Testimonials</span>
						<h3 class="xs-font-20x no-bottom-margin font-600">{{$testimonial}}</h3>
					</div>
				</div>
			</div>
		</div> --}}


		<div class="card mb20">
			<div class="card-header bgc-070">
				<h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">Pledges Cash Chart</h5>
			</div>
			<div class="card-block">
				<div style="width:100%;">
					<canvas id="canvas" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select: none;"></canvas>
				</div>
			</div>
		</div>






		<div class="row">
			@if($pphs > 0)
			<div class="col-sm-{{$pghs > 0 ? 6 : 12}}">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070">
						<h5 class="font-600 text-center no-padding no-margin text-uppercase c-fff">Pending PH</h5>
					</div>
					<div class="card-block text-center">

						<p class="mb0"><span class="c-f00">You have {{$pphs}} payment(s) to make,</span> <br> <a href="{{route('provide-help')}}"><u>Click here for details.</u></a></p>

					</div>
				</div>

			</div>
			@endif


			@if($pghs > 0)
			<div class="col-sm-{{$pphs > 0 ? 6 : 12}}">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070"><h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">Pending GH</h5></div>
					<div class="card-block text-center">

						<p class="mb0"><span class="c-f00">You have {{$pghs}} payment(s) to receive,</span> <br> <a href="{{route('get-help')}}"><u>Click here for details.</u></a></p>

					</div>
				</div>

			</div>
			@endif

		</div>

		<div class="row">

			<div class="col-sm-4">
				<div class="card mb30 xs-mb20">
					<div class="card-block text-center">
						<p class="no-bottom-margin"><em>Bonus</em></p>
						<h1 class="font3x font-600 c-2c5 no-bottom-margin">₦{{number_format(Auth::user()->bonus)}}</h1>
					</div>
				</div>
			</div>

			<div class="col-sm-8">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070">
						<h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">Your Referrals</h5>
					</div>
					<div class="card-block">
						@if($refs->count() > 0)

						<div class="table-responsive">
							<table id="datatable-responsive" class="table table-bordered table-hover data-table nowrap" width="100%" data-page-length="25">
								<thead>
									<tr class="active">
										<th>Name</th>
										<th>Joined</th>
										<th>Status</th>
										<th>Pledge</th>
										<th>Bonus</th>
									</tr>
								</thead>

								<tbody>
									@foreach($refs as $item)
									<tr>
										<?php
										$p = $item->user->phs->first();
										?>
										<td>{{ $item->user->first_name.' '.$item->user->last_name }} / <i class="fa fa-phone ml5 c-06f"></i><a href="tel:0{{substr($item->user->phone, 4)}}" class="c-06f">{{$item->user->phone}}</a></td>
										<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($item->user->created_at))->diffForHumans() }}</td>
										<td>{!! $item->user->status == 'active' ? '<span class="c-2c5">'.$item->user->status.'</span>' : '<span class="c-f00">'.$item->user->status.'</span>' !!}</td>
										<td>@if($p != null) ₦{{ number_format($p->cash) }} @endif</td>
										<td>
											@if($p == null)

											<em>No Bonus</em>

											@else

											@if($item->user->payer->count() > 0 && $p->cash == $p->cash_p)

											<?php
											$matches = $p->matches;
											$check = 0;
											foreach($matches as $m)
											{
												if($m->confirm == '1') $check +=1;
											}
											if($check == $matches->count())
											{
												$bonus = $p->cash > 49000 ? config('app.referral_bonus_max') : config('app.referral_bonus_min');
											} else $bonus = 'No Bonus';
											?>

											₦{{number_format($bonus)}}

											@else

											<em>No Bonus</em>

											@endif

											@endif
										</td>
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>

						@endif

						<p class="text-center">
							<strong class="c-070">Referral Bonuses</strong> <br>
							You earn <span class="c-2c5">₦{{config('app.referral_bonus_min')}}</span> when your referral pledges between <span class="c-2c5">₦{{number_format(10000)}}</span> and <span class="c-2c5">₦{{number_format(25000)}}</span>, <br>
							<span class="c-2c5">₦{{number_format(config('app.referral_bonus_max'))}}</span> when your referral pledges <span class="c-2c5">₦{{number_format(26000)}}</span> and above.
							{{-- <span class="c-2c5">₦{{number_format(config('app.referral_bonus_max'))}}</span> when your referral pledges <span class="c-2c5">₦{{number_format(50000)}}</span> and above. --}}
						</p>

						<p class="mb0 text-center"><strong>Link: </strong><span id="reflink"><u class="c-06f">{{ route('gref', [Auth::user()->refcode]) }}</u></span> <button id="refcopy" class="btn btn-primary btn-sm ml5" data-clipboard-target="#reflink"><i class="fa fa-clipboard mr5"></i>Copy</button></p>

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
			<form action="{{route('send.two.week.mail')}}" method="post">

				<div class="modal-header">
					<h4 class="modal-title no-padding no-bottom-margin text-uppercase font-600">Invite Members</h4>
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

		new Clipboard('#refcopy');

		$('.data-table').DataTable( {
			"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			"order": [[ 0, "desc" ]]
		});
		$(".dataTables_wrapper").removeClass("form-inline");

		$('[data-countdown]').each(function() {
			var $this = $(this), finalDate = $(this).data('countdown');
			$this.countdown(finalDate, function(event) {
				$this.html(event.strftime('%-Ih:%-Mm:%Ss'));
			});
		});

	});
</script>

<script>
	var config = {
		type: 'line',
		data: {
			labels: [{!!$cash_str!!}],
			datasets: [{
				label: "Total Participants",
				backgroundColor: window.chartColors.dhs,
				borderColor: window.chartColors.dh,
				data: [{{$par_str}}],
				fill: true,
			}]
		},
		options: {
			responsive: true,
			title:{
				display:false,
				text:'Pledges Cash Chart'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Cash'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Participants'
					},
					ticks: {
						min: 0,
						max: {{$max_cash}},
						// forces step size to be 5 units
						stepSize: 10
					}
				}]
			}
		}
	};
	window.onload = function() {
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx, config);
	};
	var colorNames = Object.keys(window.chartColors);
</script>
@endSection
