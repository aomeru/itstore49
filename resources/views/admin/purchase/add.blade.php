@extends('layouts.admin')
@section('title', 'Add Purchase Order')
@section('page_title') <i class="fa fa-usd mr10"></i>Add Purchase Order @endSection



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
	<li class="breadcrumb-item active h-padding-5 no-right-padding">Add</li>
</ol>
@endsection


@section('content')



<div class="row">
    <div class="col-sm-4 offset-4">
        
        <div class="card v-margin-50">
            
			<div class="card-header bgc-070">
                <h4 class="card-title no-margin no-padding text-center font-600 text-uppercase c-fff">Add Purchase Order</h4>
            </div>
            
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.po.add.store') }}">
                
                <div class="card-block">

					<p>Fields with <span class="rfd">*</span> are important.</p>

                    <div class="form-group input_field_sections {{ $errors->has('po_title') ? 'has-error' : '' }}">
                        <label for="po-title" class="form-control-label text-center sr-onlyy">PO Title <span class="rfd">*</span></label>
        
						<input type="text" name="po_title" class="form-control" placeholder="Enter purchase order title" data-validation="required"  data-validation-error-msg="Please enter a title for this purchase order" value="{{ Request::old('po_title') }}">
						
						<span class="font-14x">{{ $errors->has('po_title') ? $errors->first('po_title') : '' }}</span>
                    </div>
        
                    <div class="form-group input_field_sections {{ $errors->has('po_pofile') ? 'has-error' : '' }}">
                        <label for="po-pofile" class="form-control-label text-center sr-onlyy">Purchase Order</label>
        
						<input type="file" name="po_pofile" class="form-control" accept=".pdf" data-validation="mime" data-validation-optional="true" data-validation-allowing="pdf"   data-validation-error-msg-mime="File formats must be in PDF formats" value="{{ Request::old('po_pofile') }}">
						
						<span class="font-14x">{{ $errors->has('po_pofile') ? $errors->first('po_pofile') : '' }}</span>
                    </div>
        
                    <div class="form-group input_field_sections {{ $errors->has('po_total') ? 'has-error' : '' }}">
                        <label for="po-total" class="form-control-label text-center sr-onlyy">PO Total</label>
        
                        <div class="input-group">
                            <div class="input-group-addon">$</div>
                            
                            <input type="text" name="po_total" class="form-control" data-validation="number" data-validation-optional="true" data-validation-error-msg-mime="Purchase Order total must be numeric" placeholder="Enter purchase order total cost" value="{{ Request::old('po_total') }}">
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
        
						<input type="file" name="po_invfile" class="form-control" accept=".pdf" data-validation="mime" data-validation-optional="true" data-validation-allowing="pdf"   data-validation-error-msg-mime="File formats must be in PDF formats" value="{{ Request::old('po_invfile') }}">
						
						<span class="font-14x">{{ $errors->has('po_invfile') ? $errors->first('po_invfile') : '' }}</span>
                    </div>
            
                </div> 

                <div class="card-footer">

					{{csrf_field()}}

					<button class="btn btn-success btn-block padding-10" type="submit"><i class="fa fa-check mr5"></i>Add</button>
                   
                </div>

            </form>

        </div>

	</div>

</div>

@endSection



@section('page_footer')



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

	});
</script>
@endSection
