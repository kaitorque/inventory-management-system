<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Metronic | Login Page - 2</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

		<!--end::Web font -->

		<!--begin::Global Theme Styles -->
		<link href="{{asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="../../../assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="{{asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="../../../assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Global Theme Styles -->
		<link rel="shortcut icon" href="{{asset('assets/demo/default/media/img/logo/favicon.ico')}}" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1"  style="background-image: url({{asset('assets/app/media/img//bg/bg-1.jpg')}});">
				<div class="m-grid__item m-grid__item--fluid m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo">
							<a href="#">
								<img src="{{asset('assets/app/media/img/logos/logo-1.png')}}">
							</a>
						</div>
						<div class="m-login__signin">
							<div class="m-login__head">
								<h3 class="m-login__title">Sign In</h3>
							</div>
							<form class="m-login__form m-form" action="{{route('login.post')}}" id="loginForm">
								@csrf
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="text" placeholder="Employee ID" name="empid">
								</div>
								<div class="form-group m-form__group">
									<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
								</div>
								<div class="m-login__form-action">
									<button type="submit" class="btn btn-submit btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">Sign In</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!--begin::Global Theme Bundle -->
		<script src="{{asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts -->
		<!-- <script src="{{asset('assets/snippets/custom/pages/user/login.js')}}" type="text/javascript"></script> -->
		<script type="text/javascript">
			$(document).ready(function(){
				$.ajaxSetup({
					headers: {
							'X-CSRF-TOKEN': $('input[name="_token"]').val()
							}
				});
				$("#loginForm").validate({
						//Normalizer is for trimming whitespace due to required rule no longer ignore whitespace
						rules: {
							empid: {
								 required: true,
								 normalizer: function(value) {
										return $.trim(value);
								 },
								 digits: true
							},
							password: {
								 required: true,
								 normalizer: function(value) {
										return $.trim(value);
								 }
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
						url:"{{route('login.post')}}",
						data: $(form).serialize(),
						dataType: "json",
						success: function(data) {
							if(data.success)
							{
								// swal({
			            // title:"",
			            // text:data.response,
			            // type:"success",
			            // confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
			          // }).then((result) => {
									//if user click ok, it will redirect the user
			            // if (result.value) {
			              window.location.replace("{{route('home')}}");
			            // }
			          // });
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
		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>
