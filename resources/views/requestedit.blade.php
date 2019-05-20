@extends('layouts.wrapper')
@section('pluginstyle')
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" />
@endsection
@section('style')
<style>
.m-form .form-control-feedback{
	color: #f4516c;
}
</style>
@endsection
@section('content')
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">Edit Request</h3>
							</div>
						</div>
					</div>

					<!-- END: Subheader -->
					<div class="m-content">

						<!--Begin::Section-->
						<div class="row">
							<div class="col-lg-12">
								<form id="submitForm" class="m-form m-form--fit m-form--label-align-right">
									<div class="m-portlet m-portlet--mobile">
										<div class="m-portlet__body">
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Request ID:</label>
													<input type="text" class="form-control m-input" name="rid" value="{{$requests[0]->request_id}}" disabled>
													<input type="hidden" class="form-control m-input" name="encrid" value="<?php echo UserFunction::encrypt($requests[0]->request_id); ?>">
												</div>
												<div class="col-md-6">
													<label>Status:</label>
													<input type="text" class="form-control m-input" name="status" value="{{$requests[0]->status}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Created By:</label>
													<input type="text" class="form-control m-input" name="createdby" value="{{$requests[0]->createdby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Created Date:</label>
													<input type="text" class="form-control m-input" name="createddate" value="{{$requests[0]->fmcreated_date}}" disabled>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Updated By:</label>
													<input type="text" class="form-control m-input" name="modifiedby" value="{{$requests[0]->modifiedby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Last Updated:</label>
													<input type="text" class="form-control m-input" name="modifiedbydate" value="{{$requests[0]->fmmodified_date}}" disabled>
												</div>
											</div>
											<table class="table table-striped table-bordered table-hover table-checkable" id="itemTable">
												<thead>
													<tr>
														<th>No.</th>
														<th>Product ID</th>
														<th>Category</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Quantity</th>
														<th>Cost</th>
														<th>Total</th>
													</tr>
												</thead>
												<tbody>
										<?php $num = 1;
													foreach($requests as $item)
													{ ?>
														<tr>
															<td>{{$num}}</td>
															<td>{{$item->product_id}}</td>
															<td>{{$item->category}}</td>
															<td>{{$item->brand}}</td>
															<td>{{$item->model}}</td>
															<td>{{$item->qty}}</td>
															<td>{{$item->original_cost}}</td>
															<td>{{$item->total}}</td>
														</tr>
										<?php } ?>
												</tbody>
												<tfoot>
							            <tr>
						                <th colspan="7" style="text-align:right">Total:</th>
														<th></th>
							            </tr>
								        </tfoot>
											</table>
										</div>
										<div class="m-portlet__foot m-portlet__foot--fit">
											<div class="m-form__actions d-flex justify-content-center">
												<button type="submit" class="btn btn-primary btn-submit mx-2">Submit</button>
												<button type="reset" class="btn btn-secondary mx-2">Cancel</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!--End::Section-->
					</div>
@endsection
@section('plugin')
<script type="text/javascript" src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}"></script>
@endsection
@section('script')
<!-- Example: <script></script> -->
@endsection
@section('ready')
<script>
	$(document).ready(function(){
		//Set header to csrf token
		$.ajaxSetup({
			headers: {
					'X-CSRF-TOKEN': $('input[name="_token"]').val()
					}
		});
		//Datatable Declaration
		var tablelist = $("#itemTable").DataTable({
			"paging": false,
			"searching": false,
			scrollY:"false",
			scrollX:true,
			scrollCollapse:true,
			"columnDefs": [ {
		        "searchable": false,
		        "orderable": false,
		        "targets": 0,
						// "render": function (data, type, full, meta) {
            //     return meta.settings._iDisplayStart + meta.row + 1;
            // }
						// "render": function ( data, type, full, meta ) {
						//     return  meta.row + 1;
						// }
		    } ],
		  "ordering": false,
		  "oLanguage": {
					 "sSearch": "Filter:"
				 },
		  // "drawCallback": function( settings ) {
			//
    	// },
			"footerCallback": function ( row, data, start, end, display ) {
			    var api = this.api(), data;

			    // Remove the formatting to get integer data for summation
			    var intVal = function ( i ) {
			        return typeof i === 'string' ?
			            i.replace(/[\$,]/g, '')*1 :
			            typeof i === 'number' ?
			                i : 0;
			    };

			    // Total over all pages
			    total = api
			        .column( 7 )
			        .data()
			        .reduce( function (a, b) {
			            return intVal(a) + intVal(b);
			        }, 0 );

			    // Total over this page
			    // pageTotal = api
			    //     .column( 7, { page: 'current'} )
			    //     .data()
			    //     .reduce( function (a, b) {
			    //         return intVal(a) + intVal(b);
			    //     }, 0 );

			    // Update footer
			    $( api.column( 7 ).footer() ).html(
			        total
			    );
			}
		});
		tablelist.draw();
		//Custom Validator
		jQuery.validator.addMethod("nospace", function(value, element) {
      return value.indexOf(" ") < 0 && value != "";
    }, "Space are not allowed.");
		$.validator.addMethod("notEqualNull", function(value, element) {
			return this.optional(element) || value != "null";
		}, "Please select Type.");
		//Validate form using jquery.validation
		$("#submitForm").validate({
				//Normalizer is for trimming whitespace due to required rule no longer ignore whitespace
				rules: {
					status:	{
							required: true,
							normalizer: function(value) {
								 return $.trim(value);
									},
					},
				},
				invalidHandler: function(event, validator) {
					swal({
						title:"",
						text:"There are some errors in your form. Please correct them.",
						type:"error",
						confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
					});
				 },
				submitHandler: function(form) {
					//For spinner animation and disabled button
					$(form).find(".btn-submit").addClass("m-loader m-loader--success m-loader--right").prop("disabled", true);
					submitForm(form);
					//Prevent form submit
					return false;
				}
		});

		function submitForm(form)
		{
			// var data = tablelist.$('input').serializeArray();
			// data.append({ "rid": $("input[name=rid]").val() ,"status": $("input[name=status]").val() });
			$.ajax({
				type:'Post',
				url:"{{route('requestedit.post')}}",
				data: $(form).serialize(),
				dataType: "json",
				success: function(data) {
					if(data.success)
					{
						swal({
							title:"",
							text:data.response,
							type:"success",
							confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
						});
						$(form).find(".btn-submit").removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
					}
					else
					{
						swal({
							title:"",
							//Only display first error return by the array
							text:data.response[0],
							type:"error",
							confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
						});
						//Stop spinner and disabled on button
						$(form).find(".btn-submit").removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
					}
				},
				error: function(jqXHR, exception){
						swal({
							title:"",
							text:"Error Code: "+jqXHR.status+"-"+jqXHR.statusText,
							type:"error",
							confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
						});
						$(form).find(".btn-submit").removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
					}
			});
		}
	});
</script>
@endsection
