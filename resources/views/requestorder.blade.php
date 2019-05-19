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
								<center><h3 class="m-subheader__title ">Order application</h3></center>
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
											<div class="form-group m-form__group">
												<label>Employee ID:</label>
												<p class="form-control-static">112233@example</p>
											</div>
											<div class="form-group m-form__group">
												<label>Employee Name:</label>
												<p class="form-control-static">MUHAMMAD FIRDAUS BIN JAMDI@example</p>
											</div>
												<div class="m-portlet">
													<div class="m-portlet__body">
														<table class="table table-bordered">
															<tr>
																<td style="width: 30%">Item request</td>
																<td>
																	<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#m_modal_4">Enter Item</button>
																</td>
															</tr>
													</table>
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

									<!-- MODAL CLASS FOR REQUEST ITEM -->
									<div class="modal fade" id="m_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Enter Item to request:</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
												<form> 	<!-- Form inside modal -->
														<div class="form-group">
															<label class="form-control-label">Enter Item ID:</label>
															<input type="number" class="form-control" id="" placeholder="Item ID" name="Item_ID">
														</div>
														<!--
														.
														.
														. -->

													</form>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-accent">Submit item</button>
												</div>
											</div>
										</div>
									</div>

									<!-- END MODAL -->

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
