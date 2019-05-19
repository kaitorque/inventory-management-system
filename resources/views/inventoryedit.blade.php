@extends('layouts.wrapper')
@section('pluginstyle')
<!-- Example: <link rel="stylesheet" type="text/css" href="" /> -->
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
								<h3 class="m-subheader__title ">Edit Product</h3>
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
													<label>Product ID:</label>
													<input type="text" class="form-control m-input" name="pid" value="{{$product->product_id}}" disabled>
													<input type="hidden" class="form-control m-input" name="encpid" value="<?php echo UserFunction::encrypt($product->product_id); ?>">
												</div>
												<div class="col-md-6">
													<label>Quantity:</label>
													<input type="text" class="form-control m-input" name="quantity" value="{{$product->quantity}}" disabled>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Original Cost:</label>
													<input type="text" class="form-control m-input" name="cost" value="{{number_format($product->original_cost, 2)}}">
												</div>
												<div class="col-md-6">
													<label>Retail Price:</label>
													<input type="text" class="form-control m-input" name="price" value="{{number_format($product->retail_price, 2)}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Category:</label>
													<input type="text" class="form-control m-input" name="category" value="{{$product->category}}">
												</div>
												<div class="col-md-6">
													<label>Brand:</label>
													<input type="text" class="form-control m-input" name="brand" value="{{$product->brand}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Model:</label>
													<input type="text" class="form-control m-input" name="model" value="{{$product->model}}">
												</div>
												<div class="col-md-6">
													<label>Groups:</label>
													<input type="text" class="form-control m-input" name="groups" value="{{$product->groups}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Warranty(Month):</label>
													<input type="text" class="form-control m-input" name="warranty" value="{{$product->warranty_month}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Description:</label>
													<textarea class="form-control m-input" name="desc">{{$product->description}}</textarea>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Created By:</label>
													<input type="text" class="form-control m-input" name="createdby" value="{{$product->createdby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Created Date:</label>
													<input type="text" class="form-control m-input" name="createddate" value="{{$product->fmcreated_date}}" disabled>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Updated By:</label>
													<input type="text" class="form-control m-input" name="modifiedby" value="{{$product->modifiedby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Last Updated:</label>
													<input type="text" class="form-control m-input" name="modifiedbydate" value="{{$product->fmmodified_date}}" disabled>
												</div>
											</div>
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
<!-- Example: <script type="text/javascript" src=""></script> -->
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
					cost:	{
							required: true,
							normalizer: function(value) {
								 return $.trim(value);
									},
							number: true
					},
					price: {
						 required: true,
						 normalizer: function(value) {
								return $.trim(value);
						 },
						 number: true
					},
					category: {
						 required: true,
						 normalizer: function(value) {
								return $.trim(value);
						 }
					},
					brand: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					model: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					warranty: {
						digits: true
					}
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
			$.ajax({
				type:'Post',
				url:"{{route('inventoryedit.post')}}",
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
