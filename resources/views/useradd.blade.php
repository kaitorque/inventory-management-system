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
								<h3 class="m-subheader__title ">Add User</h3>
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
													<label>Employee ID:</label>
													<input type="text" class="form-control m-input" name="empid" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Password:</label>
													<input type="password" class="form-control m-input" name="pass" value="">
												</div>
												<div class="col-md-6">
													<label>Confirm Password:</label>
													<input type="password" class="form-control m-input" name="cpass" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>First Name:</label>
													<input type="text" class="form-control m-input" name="fname" value="">
												</div>
												<div class="col-md-6">
													<label>Last Name:</label>
													<input type="text" class="form-control m-input" name="lname" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Nickname:</label>
													<input type="text" class="form-control m-input" name="nname" value="">
												</div>
												<div class="col-md-6">
													<label>Date of Birth:</label>
													<input type="text" class="form-control m-input" name="dob" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Address:</label>
													<input type="text" class="form-control m-input" name="address1" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<input type="text" class="form-control m-input" name="address2" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Zip Code:</label>
													<input type="text" class="form-control m-input" name="zipcode" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>City:</label>
													<input type="text" class="form-control m-input" name="city" value="">
												</div>
												<div class="col-md-6">
													<label>State:</label>
													<input type="text" class="form-control m-input" name="state" value="">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Marital Status:</label>
													<input type="text" class="form-control m-input" name="maritalstatus" value="">
												</div>
												<div class="col-md-6">
													<label>IC Number:</label>
													<input type="text" class="form-control m-input" name="ssn" value="">
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
		//Datepicker
		$('input[name=dob]').datepicker({
    	format: "dd/mm/yyyy"
  	});
		//Custom Validator
		jQuery.validator.addMethod("nospace", function(value, element) {
      return value.indexOf(" ") < 0 && value != "";
    }, "Space are not allowed.");
		//Validate form using jquery.validation
		$("#submitForm").validate({
				//Normalizer is for trimming whitespace due to required rule no longer ignore whitespace
				rules: {
					empid: {
							 required: true,
							 digits: true,
							 normalizer: function(value) {
									return $.trim(value);
									 },
							 remote: {
								 url: "<?php echo route("checkempid"); ?>",
								 type: "post",
							 }
					},
					fname: {
						 required: true,
						 normalizer: function(value) {
								return $.trim(value);
						 }
					},
					lname: {
						 required: true,
						 normalizer: function(value) {
								return $.trim(value);
						 }
					},
					nname: {
						 required: true,
						 normalizer: function(value) {
								return $.trim(value);
						 },
						 "nospace": true
					},
					dob: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					address1: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					city: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					state: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					zipcode: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					maritalstatus: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					pass: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					cpass: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					},
					ssn: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						}
					}
				},
				messages: {
					empid: {
						remote: jQuery.validator.format("{0} is already taken.")
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
	});
</script>
@endsection
