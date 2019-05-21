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
								<h3 class="m-subheader__title ">Edit User</h3>
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
													<input type="text" class="form-control m-input" name="empid" value="{{$user->emp_id}}" disabled>
													<input type="hidden" class="form-control m-input" name="encempid" value="<?php echo UserFunction::encrypt($user->emp_id); ?>">
												</div>
												<div class="col-md-6">
													<label>Type:</label>
													<?php
													$optionArr = "Staff,Manager";
													$valueArr = "staff,manager";
													echo UserFunction::buildcbsort("usertype", $optionArr, $valueArr, $user->usertype, "form-control m-input"); ?>
												</div>
											</div>
											<div class="form-group m-form__group row staff-type">
												<div class="col-md-6">
													<label>Active:</label>
													<?php
													$optionArr = "Yes,No";
													$valueArr = "0,1";
													echo UserFunction::buildcbsort("status", $optionArr, $valueArr, $user->status, "form-control m-input"); ?>
												</div>
											</div>
											<?php if(session("usertype") == "manager"){ ?>
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
											<?php } ?>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>First Name:</label>
													<input type="text" class="form-control m-input" name="fname" value="{{$user->first_name}}">
												</div>
												<div class="col-md-6">
													<label>Last Name:</label>
													<input type="text" class="form-control m-input" name="lname" value="{{$user->last_name}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Nickname:</label>
													<input type="text" class="form-control m-input" name="nname" value="{{$user->nickname}}">
												</div>
												<div class="col-md-6">
													<label>Date of Birth:</label>
													<input type="text" class="form-control m-input" name="dob" value="{{$user->fmtdob}}">
												</div>
											</div>
											<div class="form-group m-form__group row staff-type">
												<div class="col-md-6">
													<label>Department:</label>
													<input type="text" class="form-control m-input" name="dept" value="{{$user->dept}}">
												</div>
												<div class="col-md-6">
													<label>Part Time:</label>
													<?php
													$optionArr = "No,Yes";
													$valueArr = "0,1";
													echo UserFunction::buildcbsort("parttime", $optionArr, $valueArr, $user->part_time, "form-control m-input"); ?>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Address:</label>
													<input type="text" class="form-control m-input" name="address1" value="{{$user->street_add1}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<input type="text" class="form-control m-input" name="address2" value="{{$user->street_add2}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Zip Code:</label>
													<input type="text" class="form-control m-input" name="zipcode" value="{{$user->zip_code}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>City:</label>
													<input type="text" class="form-control m-input" name="city" value="{{$user->city}}">
												</div>
												<div class="col-md-6">
													<label>State:</label>
													<?php
													$optionArr = "KUALA LUMPUR, JOHOR, KEDAH, KELANTAN, MELAKA, NEGERI SEMBILAN, PAHANG, PENANG, PERAK, PERLIS, SABAH, SARAWAK, SELANGOR, TERRENGGANU, LABUAN, PUTRAJAYA";
													$valueArr = "KUALA LUMPUR, JOHOR, KEDAH, KELANTAN, MELAKA, NEGERI SEMBILAN, PAHANG, PENANG, PERAK, PERLIS, SABAH, SARAWAK, SELANGOR, TERRENGGANU, LABUAN, PUTRAJAYA";
													echo UserFunction::buildcbsort("state", $optionArr, $valueArr, $user->state, "form-control m-input"); ?>
													<!-- <input type="text" class="form-control m-input" name="state" value=""> -->
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Marital Status:</label>
													<?php
													$optionArr = "MARRIED,SINGLE";
													$valueArr = "MARRIED,SINGLE";
													echo UserFunction::buildcbsort("maritalstatus", $optionArr, $valueArr, $user->marital_status, "form-control m-input"); ?>
													<!-- <input type="text" class="form-control m-input" name="maritalstatus" value=""> -->
												</div>
												<div class="col-md-6">
													<label>IC Number:</label>
													<input type="text" class="form-control m-input" name="ssn" value="{{$user->ssn}}">
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Created By:</label>
													<input type="text" class="form-control m-input" name="createdby" value="{{$user->createdby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Created Date:</label>
													<input type="text" class="form-control m-input" name="createddate" value="{{$user->fmcreated_date}}" disabled>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<div class="col-md-6">
													<label>Updated By:</label>
													<input type="text" class="form-control m-input" name="modifiedby" value="{{$user->modifiedby}}" disabled>
												</div>
												<div class="col-md-6">
													<label>Last Updated:</label>
													<input type="text" class="form-control m-input" name="modifiedbydate" value="{{$user->fmmodified_date}}" disabled>
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
<script type="text/javascript">
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
		$.validator.addMethod("notEqualNull", function(value, element) {
			return this.optional(element) || value != "null";
		}, "Please select Type.");
		//Validate form using jquery.validation
		$("#submitForm").validate({
				//Normalizer is for trimming whitespace due to required rule no longer ignore whitespace
				rules: {
					type:	{
							required: true,
							normalizer: function(value) {
								 return $.trim(value);
									},
							"notEqualNull": true
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
						 "nospace": true,
						 remote: {
							 url: "<?php echo route("checknnameedit"); ?>",
							 type: "post",
							 data: { "encempid": function() { return $("input[name=encempid]").val();}  }
						 }
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
					ssn: {
						required: true,
						normalizer: function(value) {
							 return $.trim(value);
						},
						digits: true,
					}
				},
				messages: {
					nname: {
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

		function submitForm(form)
		{
			$.ajax({
				type:'Post',
				url:"{{route('useredit.post')}}",
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
		//Check type on load
		if($("#usertype").val()=="staff")
		{
			$(".staff-type").show();
		}
		else {
			$(".staff-type").hide();
		}
		//Change Type
		$("#usertype").on("change", function(){
			if($("#usertype").val()=="staff")
			{
				$(".staff-type").show();
			}
			else {
				$(".staff-type").hide();
			}
		});
	});
</script>
@endsection
