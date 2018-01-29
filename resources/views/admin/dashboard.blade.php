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

		<div class="row mb20 text-center">
			<div class="col-sm-2 col-6 xs-mb20">
				<div class="card card-primary">
					<div class="card-block padding-10">
						<span class="font-20x c-fff">Tasks</span>
						<h1 class="xs-font-20x no-bottom-margin font-600">{{$ttask}} ({{$ftask}})</h1>
					</div>
				</div>
			</div>

			<div class="col-sm-2 col-6 xs-mb20">
				<div class="card card-primary">
					<div class="card-block padding-10">
						<span class="font-20x c-fff">Inventory</span>
						<h1 class="xs-font-20x no-bottom-margin font-600">{{$tinv}}</h1>
					</div>
				</div>
			</div>

			<div class="col-sm-2 col-6 xs-mb20">
				<div class="card card-primary">
					<div class="card-block padding-10">
						<span class="font-20x c-fff">Allocation</span>
						<h1 class="xs-font-20x no-bottom-margin font-600">{{$tall}}</h1>
					</div>
				</div>
			</div>

			<div class="col-sm-2 col-6 xs-mb20">
				<div class="card card-{{ $reorder ? 'danger' : 'success'}}">
					<div class="card-block padding-10">
						<span class="font-20x c-fff">Reorder?</span>
						<h1 class="xs-font-20x no-bottom-margin font-600 c-fff">{{$reorder ? 'Yes' : 'No'}}</h1>
					</div>
				</div>
			</div>
		</div>



		@if($reorder)
			<p class="alert alert-danger mb50 font-22x">Some inventory items needs to be reordered. <a href="{{ route('admin.items') }}" class="alert-link" title="View inventory items">Click here to see items.</a></p>
		@endif



		<div class="row">
			<div class="col-sm-6">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070">
						<h5 class="font-600 text-center no-padding no-margin text-uppercase c-fff">Tasks</h5>
					</div>
					<div class="card-block">

						@if ($tasks->count() == 0)

							<p class="alert alert-info">No task record found.</p>

						@else

							<div class="table-responsive">

								<table class="table table-bordered table-hover nowrap data-table" width="100%" data-page-length="10">

									<thead>
										<tr class="active">
											<th>#</th>
											<th>Title</th>
											<th>Type</th>
											<th>Client</th>
											<th>Inventory</th>
											@if(in_array(Auth::user()->role->title, $task_allow)) <th>Owner</th> @endif
											<th>Status</th>
											<th>Created</th>
										</tr>
									</thead>

									<tbody>

										@php $row_count = 1 @endphp

										@foreach($tasks as $item)

											<tr>

												<td><u><a href="{{route('admin.tasks.show', Crypt::encrypt($item->id))}}" class="c-06f">{{$item->id}}</a></u></td>

												<td>{{$item->title}} <span class="ml5 badge badge-primary font-no-bold">{{ $item->comments == null ? 0 : $item->comments->count() }}</span></td>

												<td>{{$item->type}}</td>

												<td>{{$item->client->firstname.' '.$item->client->lastname}}</td>

												<td>
													@if($item->inventory == null) <em class="c-999">Null</em> @else {{$item->inventory->item->title.' / '.$item->inventory->serial_no}} @endif
												</td>

												@if(in_array(Auth::user()->role->title, $task_allow)) <td>{{$item->user->firstname.' '.$item->user->lastname}}</td> @endif

												<td class="text-center">
													@if ($item->status == 'opened')

														<span class="badge badge-warning font-no-bold padding-5 d-block">Open</span>

													@elseif ($item->status == 'unresolved')

														<span class="badge badge-danger font-no-bold padding-5 d-block">Unresolved</span>

													@else

														<span class="badge badge-success font-no-bold padding-5 d-block">Closed</span>

													@endif
												</td>

												<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>

											</tr>

											@php $row_count++ @endphp

										@endforeach

									</tbody>

								</table>

							</div>

						@endif

					</div>
				</div>

			</div>


			<div class="col-sm-6">
				<div class="card mb50 xs-mb30">
					<div class="card-header bgc-070"><h5 class="no-margin no-padding text-center font-600 text-uppercase c-fff">Recent Activities</h5></div>
					<div class="card-block">

						@if ($logs->count() == 0)
							<p class="alert alert-info">No recent activity on your account.</p>
						@else
							<ul class="list-group">
								@foreach ($logs as $item)
									<li class="list-group-item v-padding-20">
										<p class="mb0">
											{{ $item->descrip }}
											<br><span class="font-12x c-999">{{date('d-m-y, g:ia', strtotime($item->created_at))}}</span>
										</p>
									</li>
								@endforeach
							</ul>
						@endif

					</div>
				</div>

			</div>

		</div>



		<div class="card">

			<div class="card-header bgc-070">
				<h5 class="font-600 text-center no-padding no-margin text-uppercase c-fff">Recent Allocations</h5>
			</div>

			<div class="card-block">

				@if ($rall->count() == 0)
					<p class="alert alert-info">No allocation record found.</p>
				@else

					<div class="table-responsive">

						<table class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="10">

							<thead>
								<tr class="active">
									<th>#</th>
									<th>Serial No</th>
									<th>Item</th>
									<th>Recipient</th>
									<th>Department</th>
									<th>Added By</th>
									<th>Date</th>
								</tr>
							</thead>

							<tbody>

								@php $row_count = 1 @endphp

								@foreach($rall as $item)

									<tr id="row-{{$item->id}}" data-hrid="{{$item->id}}" data-id="{{Crypt::encrypt($item->id)}}" data-serial-no="{{$item->inventory->serial_no}}" data-email="{{$item->user->email}}">
										<td>{{ $row_count }}</td>
										<td>{{ $item->inventory->serial_no }}</td>
										<td>{{ $item->inventory->item->title }}</td>
										<td><u><a href="{{route('admin.users.show', Crypt::encrypt($item->user->id))}}" class="c-06f">{{ $item->user->firstname.' '.$item->user->lastname }}</a></u></td>
										<td>
											@if ($item->user->unit != null)
												<u><a href="{{route('admin.depts.show.unit', Crypt::encrypt($item->user->unit->id))}}" class="c-06f">{{ $item->user->unit->title }}</a></u>
												<span class="c-999"> / </span>
												<u><a href="{{route('admin.depts.show', Crypt::encrypt($item->user->unit->department_id))}}" class="c-06f">{{ $item->user->unit->department->title }}</a></u>
											@else
												<span class="c-999">Null</span>
											@endif
										</td>
										<td>{{ $item->allocated->firstname.' '.$item->allocated->lastname }}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>

									</tr>

									@php $row_count++ @endphp

								@endforeach

							</tbody>

						</table>
					</div>

				@endif

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

@endSection
