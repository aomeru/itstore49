@extends('layouts.admin')
@section('title', 'Allocation')
@section('page_title') <i class="fa fa-sitemaps mr10"></i>Allocation @endSection


@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Allocation</li>
</ol>
@endsection

@section('content')

	<div class="data_tables">

		<div class="card">

			<div class="card-block">

				@if ($list->count() == 0)
					<p class="alert alert-info">No allocation record found.</p>
				@else

					<div class="table-responsive">

						<div class="">
							<div class="pull-sm-right">
								<div class="row">
									<div class="col-6">
										<div class="tools pull-sm-right"></div>
									</div>
									<div class="col-6 text-right">
										@if(in_array(Auth::user()->username,$create_allow))
										<button class="btn btn-primary btn-sm no-margin" title="Add new allocation" data-toggle="modal" data-target="#add-all-modal"><i class="fa fa-plus"></i></button>
										@endif
									</div>
								</div>
							</div>
						</div>

						<table id="sample_1" class="data-table table table-striped table-bordered table-hover nowrap" width="100%" data-page-length="10">

							<thead>
								<tr class="active">
									<th>#</th>
									<th>Serial No</th>
									<th>Item</th>
									<th>Recipient</th>
									<th>Department</th>
									<th class="text-center">Approval</th>
									<th>Added By</th>
									<th>Date</th>
									@if(in_array(Auth::user()->username,$edit_allow))
										<th class="text-center">Actions</th>
									@endif
								</tr>
							</thead>

							<tbody>

								@php $row_count = 1 @endphp

								@foreach($list as $item)

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
										<td class="text-center">
											{!! $item->approval == null ? '<em class="c-999">Null</em>' : '<i class="fa fa-2x fa-file-image-o c-900"></i>' !!}
										</td>
										<td>{{ $item->allocated->firstname.' '.$item->allocated->lastname }}</td>
										<td>{{date('d-m-y, g:ia', strtotime($item->created_at))}}</td>

										@if(in_array(Auth::user()->username,$edit_allow))
											<td class="text-center">
												<button class="btn btn-primary btn-sm" title="Edit #{{ $item->id }} allocation entry" data-toggle="modal" data-target="#edit-all-modal"><i class="fa fa-pencil"></i></button>
												<button class="btn btn-danger btn-sm" title="Delete #{{ $item->id }} allocation entry" data-toggle="modal" data-target="#delete-all-modal" @if(!in_array(Auth::user()->username,$delete_allow)) disabled @endif><i class="fa fa-trash"></i></button>
											</td>
										@endif

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



@endSection




@section('page_footer')

	@if(in_array(Auth::user()->username,$create_allow))

	<div class="modal fade" id="add-all-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">
				<form method="post" enctype="multipart/form-data">

				    <div class="modal-header mh-override">
	                    <h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Allocate Item</h4>
				    </div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
	                        <label for="serial-no" class="form-control-label text-center sr-only">Item</label>

							<select id="serial-no" class="form-control chzn-select">
								<option value="">Select Item</option>
								@foreach($finvs as $finv)
									<option value="{{$finv->serial_no}}">{{$finv->serial_no}}</option>
								@endforeach
							</select>
	                    </div>

						<div class="form-group input_field_sections">
	                        <label for="email" class="form-control-label text-center sr-only">Processor</label>

							<select id="email" class="form-control chzn-select">
								<option value="">Select User</option>
								@foreach($users as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}}</option>
								@endforeach
							</select>
	                    </div>


						<div class="form-group input_field_sections">
	                        <label for="approval" class="form-control-label text-center srr-only">Approval</label>
							<input type="file" id="approval" class="form-control">
	                    </div>


						<span id="helpText" class="help-text font-12x c-999 text-center">
							Items or employee not found should be added first to the approciate table before trying to allocate item.
						</span>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<button class="btn-success btn btn-block" id='add-all-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Allocate</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	@endif


	@if(in_array(Auth::user()->username,$edit_allow))

	<div class="modal fade" id="edit-all-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">
				<form method="post">

					<div class="modal-header mh-override">
						<h4 class="modal-title no-padding no-margin text-uppercase font-600 text-center c-070">Edit Item</h4>
					</div>

					<div class="modal-body">

						<div class="form-group input_field_sections">
							<label for="serial-no-edit" class="form-control-label text-center sr-only">Item</label>

							<select id="serial-no-edit" class="form-control chzn-selectt">
								<option value="">Select Item</option>
								@foreach($invs as $inv)
									<option value="{{$inv->serial_no}}">{{$inv->serial_no}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group input_field_sections">
							<label for="email-edit" class="form-control-label text-center sr-only">Processor</label>

							<select id="email-edit" class="form-control chzn-selectt">
								<option value="">Select User</option>
								@foreach($users as $user)
									<option value="{{$user->email}}">{{$user->firstname.' '.$user->lastname}}</option>
								@endforeach
							</select>
						</div>

						<span id="helpText" class="help-text font-12x c-999 text-center">
							Items or employee not found should be added first to the approciate table before trying to allocate item.
						</span>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-default btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="item-row-id">
								<input type="hidden" id="item-id-edit">
								<button class="btn-success btn btn-block" id='update-all-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	@endif


	@if(in_array(Auth::user()->username,$delete_allow))

	<div class="modal fade" id="delete-all-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">

					<div class="modal-body">

						<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>" allocation record?</p>

					</div>

					<div class="modal-footer mh-override">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close"><i class="fa fa-times mr5"></i>Cancel</button>
							</div>
							<div class="col-6">
								<input type="hidden" id="item-row-id-delete">
								<input type="hidden" id="item-id-delete">
								<button class="btn-danger btn btn-block" id='delete-all-btn' type="submit" role="button"><i class="fa fa-check mr5"></i>Delete</button>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>

	@endif

@endsection




@section('footer')

	<script>

		'use strict';

		var TableAdvanced = function() {
		    var initTable1 = function() {
		        var table = $('#sample_1');
		        table.DataTable({
		            dom: "Bflr<'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
					//order: [[ 0, "asc" ]],
		            buttons: [
		                'copy', 'csv', 'print'
		            ]
		        });
		        var tableWrapper = $('#sample_1_wrapper');
		        tableWrapper.find('.dataTables_length select').select2();
		    }


		    return {
		        init: function() {
		            if (!jQuery().dataTable) {
		                return;
		            }
		            initTable1();
		        }
		    };
		}();

		$(function(){
			TableAdvanced.init();
			$(".dataTables_scrollHeadInner .table").addClass("table-responsive");
			$(".dataTables_wrapper .dt-buttons .btn").addClass('btn-primary btn-inverse').removeClass('btn-default');
			$(".dataTables_wrapper").removeClass("form-inline");
		});

	</script>


	<script>
		$(function(){
			'use strict';

			//TableAdvanced.init();

			// $('.data-table').DataTable( {
			// 	"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			// 	"order": [[ 0, "asc" ]],
			// 	"buttons": [
			// 		'copy', 'csv', 'print'
			// 	]
			// });
			// $(".dataTables_wrapper").removeClass("form-inline");

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


			$(document).on('click', '#add-all-btn', function(e){

				e.preventDefault();

				var btn = $(this),
				btn_text = btn.html(),
				serial_no = $("#serial-no").val(),
				email = $("#email").val(),
				approval = $("#approval").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.all.add')}}";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						serial_no: serial_no,
						email: email,
						approval: approval,
						_token: token
					},
					beforeSend: function () {
						btn.html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {
						btn.html(btn_text);
						pnotify_alert('success', response.message);
						$('#add-all-modal').modal('hide');
						window.location.href = "{{route('admin.all')}}";
					},
					error: function(jqXHR, exception){
						btn.html(btn_text);
						$('.process-loading-two').toggleClass('add-loading');
						var error = getErrorMessage(jqXHR, exception);
						pnotify_alert('error', error);
					}
				});
			});

			$('#edit-all-modal').on('show.bs.modal', function (e) {
				var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				serial_no = tr.data('serial-no'),
				email = tr.data('email'),
				hrid = tr.data('hrid'),
				item_id = tr.data('id');

				//console.log(title + " " + type + " " + processor + " " + descrip);

				$("#serial-no-edit").val(serial_no);
				$("#email-edit").val(email);
				$("#item-id-edit").val(item_id);
				$("#item-row-id").val(hrid);

			});

			$(document).on('click', '#update-all-btn', function(e){

				e.preventDefault();

				var btn = $(this),
				btn_text = btn.html(),
				item_id = $("#item-id-edit").val(),
				serial_no = $("#serial-no-edit").val(),
				email = $("#email-edit").val(),
				load_element = "#row-" + $("#item-row-id").val(),
				token ='{{ Session::token() }}',
				url = "{{route('admin.all.update')}}";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						item_id: item_id,
						serial_no: serial_no,
						email: email,
						_token: token
					},
					beforeSend: function () {
						btn.html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {
						btn.html(btn_text);
						pnotify_alert('success', response.message);
						$('#edit-all-modal').modal('hide');
						$(load_element).data('serial-no',serial_no);
						$(load_element).data('email',email);
						$(load_element).load(location.href + " "+ load_element +">*","");
					},
					error: function(jqXHR, exception){
						btn.html(btn_text);
						$('.process-loading-two').toggleClass('add-loading');
						var error = getErrorMessage(jqXHR, exception);
						pnotify_alert('error', error);
					}
				});
			});

			$('#delete-all-modal').on('show.bs.modal', function (e) {
				var btn = $(e.relatedTarget),
				tr = btn.closest('tr'),
				hrid = tr.data('hrid'),
				item_id = tr.data('id'),
				delete_title = "#" + hrid;

				$("#delete-title").text(delete_title);
				$("#item-id-delete").val(item_id);
				$("#item-row-id-delete").val(hrid);
			});

			$(document).on('click', '#delete-all-btn', function(e){
				e.preventDefault();
				var btn = $(this),
				btn_text = btn.html(),
				item_id = $('#item-id-delete').val(),

				remove_element = '#row-' + $("#item-row-id-delete").val(),
				load_element = '#loadDiv',
				token ='{{ Session::token() }}',
				url = "{{route('admin.all.delete')}}";

				$.ajax({
					type: "POST",
					url: url,
					data: {
						item_id: item_id,
						_token: token
					},
					beforeSend: function () {
						btn.html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {
						btn.html(btn_text);
						$('#delete-all-modal').modal('hide');
						pnotify_alert('success', response.message);
						$(remove_element).remove();
						//$(load_element).load(location.href + " "+ load_element +">*","");
					},
					error: function(jqXHR, exception){
						btn.html(btn_text);
						$('.process-loading-two').toggleClass('add-loading');
						var error = getErrorMessage(jqXHR, exception);
						pnotify_alert('error', error);
					}
				});
			});

		});
	</script>

@endSection
