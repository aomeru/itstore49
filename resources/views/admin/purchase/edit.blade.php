@extends('layouts.admin')
@section('title', 'Edit '.$item->title.' Purchase Order')
@section('page_title') <i class="fa fa-usd mr10"></i>Edit {{$item->title}} Purchase Order @endSection



@section('bc')
<ol class="breadcrumb float-right nav_breadcrumb_top_align" style="margin-bottom: 0">
	<li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.dashboard')}}">
			Dashboard
		</a>
    </li>
    <li class="breadcrumb-item h-padding-5">
		<a href="{{route('admin.po')}}">
			Purchase Order
		</a>
	</li>
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Edit "{{$item->title}}"</li>
</ol>
@endsection


@section('content')



<div class="row">
	
    <div class="col-sm-6 offset-3">
        
        <div class="card v-margin-50">
            
			<div class="card-header bgc-070">
                <h4 class="card-title no-margin no-padding text-center font-600 text-uppercase c-fff">Edit Purchase Order</h4>
            </div>
			
			
			<div class="card-block">
				
				<div class="row">

					<div class="col-8">
							
						<form method="post" enctype="multipart/form-data" action="{{ route('admin.po.update', Crypt::encrypt($item->id)) }}">
							
							<p>Fields with <span class="rfd">*</span> are important.</p>

							<div class="form-group input_field_sections {{ $errors->has('po_title') ? 'has-error' : '' }}">
								<label for="po-title" class="form-control-label text-center sr-onlyy">PO Title <span class="rfd">*</span></label>
				
								<input type="text" name="po_title" class="form-control" placeholder="Enter purchase order title" data-validation="required"  data-validation-error-msg="Please enter a title for this purchase order" value="{{ $item->title }}">
								
								<span class="font-14x">{{ $errors->has('po_title') ? $errors->first('po_title') : '' }}</span>
							</div>
				
							<div class="form-group input_field_sections {{ $errors->has('po_pofile') ? 'has-error' : '' }}">
								<label for="po-pofile" class="form-control-label text-center sr-onlyy">Purchase Order</label>
				
								<input type="file" name="po_pofile" class="form-control" accept=".pdf" data-validation="mime" data-validation-optional="true" data-validation-allowing="pdf"   data-validation-error-msg-mime="File formats must be in PDF formats">
								
								<span class="font-14x">{{ $errors->has('po_pofile') ? $errors->first('po_pofile') : '' }}</span>
							</div>
				
							<div class="form-group input_field_sections {{ $errors->has('po_total') ? 'has-error' : '' }}">
								<label for="po-total" class="form-control-label text-center sr-onlyy">PO Total</label>
				
								<div class="input-group">
									<div class="input-group-addon">$</div>
									
									<input type="text" name="po_total" class="form-control" data-validation="number" data-validation-optional="true" data-validation-error-msg-mime="Purchase Order total must be numeric" placeholder="Enter purchase order total cost" @if($item->total != null) value="{{ $item->total }}" @endif>
								</div>
								
								<span class="font-14x">{{ $errors->has('po_total') ? $errors->first('po_total') : '' }}</span>
							</div>
				
							<div class="form-group input_field_sections {{ $errors->has('po_dnfile') ? 'has-error' : '' }}">
								<label for="po-dnfile" class="form-control-label text-center sr-onlyy">Delivery Note</label>
				
								<input type="file" name="po_dnfile" class="form-control" accept=".pdf" data-validation="mime" data-validation-optional="true" data-validation-allowing="pdf"   data-validation-error-msg-mime="File formats must be in PDF formats" value="{{ Request::old('po_dnfile') }}">
								
								<span class="font-14x">{{ $errors->has('po_dnfile') ? $errors->first('po_dnfile') : '' }}</span>
							</div>
				
							<div class="form-group input_field_sections {{ $errors->has('po_invfile') ? 'has-error' : '' }}">
								<label for="po-invfile" class="form-control-label text-center sr-onlyy">Invoice</label>
				
								<input type="file" name="po_invfile" class="form-control" accept=".pdf" data-validation="mime" data-validation-optional="true" data-validation-allowing="pdf"   data-validation-error-msg-mime="File formats must be in PDF formats" value="{{ $item->total }}">
								
								<span class="font-14x">{{ $errors->has('po_invfile') ? $errors->first('po_invfile') : '' }}</span>
							</div>

							{{csrf_field()}}

							<button class="btn btn-success btn-block padding-10" type="submit"><i class="fa fa-check mr5"></i>Update</button>
							
						</form>

					</div>

					<div class="col-4 text-center sm-pt50 h-padding-15 ">

						<div class="bgc-f5 padding-20" data-poid="{{ Crypt::encrypt($item->id) }}">
							
							<p id="po-row-po">
								<strong>Current Purchase Order</strong><br>
								{!! $item->po == null ? '<em class="c-999">Null</em>' : '<a class="d-bllock mtt20" href="'.asset('storage/purchase/'.$item->po).'" title="View '.$item->title.' purchase order" target="_blank"><i class="fa fa-3x fa-file-image-o c-900"></i></a><button role="button" class="btn btn-danger btn-sm ml10" data-toggle="modal" data-target="#delete-modal" data-option="po" data-title="'.$item->title.' purchase order file"><i class="fa fa-trash"></i></button>' !!}
							</p>

							<hr>

							<p id="po-row-dn">
								<strong>Current Delivery Note</strong><br>
								{!! $item->dn == null ? '<em class="c-999">Null</em>' : '<a class="d-bllock mtt20" href="'.asset('storage/purchase/'.$item->dn).'" title="View '.$item->title.' delivery note" target="_blank"><i class="fa fa-3x fa-file-image-o c-900"></i></a><button role="button" class="btn btn-danger btn-sm ml10" data-toggle="modal" data-target="#delete-modal" data-option="dn" data-title="'.$item->title.' delivery note file"><i class="fa fa-trash"></i></button>' !!}
							</p>

							<hr>

							<p class="mb0" id="po-row-inv">
								<strong>Current Invoice</strong><br>
								{!! $item->inv == null ? '<em class="c-999">Null</em>' : '<a class="d-bllock mtt20" href="'.asset('storage/purchase/'.$item->inv).'" title="View '.$item->title.' invoice" target="_blank"><i class="fa fa-3x fa-file-image-o c-900"></i></a><button role="button" class="btn btn-danger btn-sm ml10" data-toggle="modal" data-target="#delete-modal" data-option="inv" data-title="'.$item->title.' invoice file"><i class="fa fa-trash"></i></button>' !!}
							</p>

						</div>

					</div>
				</div>
		
			</div>

        </div>

	</div>

</div>

@endSection



@section('page_footer')

	<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog w300" role="document">
			<div class="modal-content">

				<div class="modal-body">

					<p class="text-center font-18x no-bottom-margin">Are you sure you want to delete "<span id="delete-title" class="c-06f"></span>"</p>

				</div>

				<div class="modal-footer mh-override">
					<div class="row">
						<div class="col-6">
							<button type="button" class="btn-primary btn btn-block" data-dismiss="modal" aria-label="Close">
								<i class="fa fa-times mr5"></i>Cancel</button>
						</div>
						<div class="col-6">
							<input type="hidden" id="po-row-id-delete">
							<input type="hidden" id="po-id-delete">
							<input type="hidden" id="file-option">
							<button class="btn-danger btn btn-block" id='delete-btn' type="submit" role="button">
								<i class="fa fa-trash mr5"></i>Delete File</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection



@section('footer')
<script>
	$(function(){
		'use strict';

		$.validate();

		$('.data-table').DataTable( {
			"dom": "<'row'<'col-md-6 col-12'l><'col-md-6 col-12'f>r><'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
			"order": [[ 0 , "asc" ]]
		});
		$(".dataTables_wrapper").removeClass("form-inline");

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

		$('#delete-modal').on('show.bs.modal', function (e) {
			var btn = $(e.relatedTarget),
				option = btn.data('option'),
				delete_title = btn.data('title'),
				div = btn.closest('div'),
				poid = div.data('poid');

			//console.log(option);

			$("#delete-title").text(delete_title);
			$("#file-option").val(option);
			$("#po-id-delete").val(poid);
			//$("#po-row-id-delete").val("po-row-" + option);
		});

		$(document).on('click', '#delete-btn', function (e) {
			
			e.preventDefault();

			var btn = $(this),
				btn_text = btn.html(),
				poid = $('#po-id-delete').val(),
				option = $('#file-option').val(),
				load_element = '#po-row-' + $("#file-option").val(),
				token = '{{ Session::token() }}',
				url = "{{route('admin.po.delete.file')}}";

			$.ajax({
				type: "POST",
				url: url,
				data: {
					poid: poid,
					option: option,
					_token: token
				},
				beforeSend: function () {
					btn.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function (response) {
					btn.html(btn_text);
					$('#delete-modal').modal('hide');
					pnotify_alert('success', response.message);
					$(load_element).load(location.href + " " + load_element + ">*", "");
				},
				error: function (jqXHR, exception) {
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
