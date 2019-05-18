@extends('layouts.wrapper')
@section('pluginstyle')
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" />
@endsection
@section('style')
<!-- Example: <style></style> -->
@endsection
@section('content')
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">User List</h3>
							</div>
							<div>
								<a class="btn m-btn--pill btn-primary" href="{{route('useradd')}}">Add User</a>
							</div>
						</div>
					</div>

					<!-- END: Subheader -->
					<div class="m-content">

						<!--Begin::Section-->
						<div class="row">
							<div class="col-lg-12">
								<div class="m-portlet m-portlet--mobile">
									<div class="m-portlet__body">
										<table class="table table-striped table-bordered table-hover table-checkable" id="userTable">
												<thead>
													<tr>
														<th>No.</th>
														<th>Fullname</th>
														<th>Nickname</th>
														<th>Employee ID</th>
														<th>Last Updated</th>
														<th>Updated By</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
									</div>
								</div>
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
<script type="text/javascript">
	$(document).ready(function(){
		//Set header to csrf token
		$.ajaxSetup({
			headers: {
					'X-CSRF-TOKEN': $('input[name="_token"]').val()
					}
		});
		//Datatable Declaration
		var tablelist = $("#userTable").DataTable({
			scrollY:"false",
			scrollX:true,
			scrollCollapse:true,
			"columnDefs": [ {
		        "searchable": false,
		        "orderable": false,
		        "targets": 0
		    } ],
		  "ordering": false,
		  "oLanguage": {
					 "sSearch": "Filter:"
				 }
		});
		//Datatable numbering
		tablelist.on( 'order.dt search.dt', function () {
	      tablelist.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	          cell.innerHTML = i+1;
	      } );
	  } ).draw();
		//Load data to datatables
		function loaddata()
		{
			$.ajax({
					type:'POST',
					url:"{{route('userlist.post')}}",
					// data: $("#searchListForm").serialize(),
					dataType: "json",
					success:function(data){
						mApp.unblockPage();
						tablelist.clear();
						for(var i=0; i<data.data.length; i++)
						{
	              var row = tablelist.row.add(["",
	                                  `${data.data[i].first_name} ${data.data[i].last_name}`,
	                                  data.data[i].nickname,
	                                  data.data[i].emp_id,
																		data.data[i].fmcreated_date,
	                                  data.data[i].ncreated_by,
	                                  `<button type="button" class="btn btn-sm btn-danger btn-delete `+(data.data[i].emp_id == {{session('empid')}} ? "d-none" : "")+`">Delete</button>`]);
								row.nodes().to$().attr('data-link', data.data[i].link).addClass('list-clickable');
						}
	          tablelist.draw();
					},
					error: function(jqXHR, exception){
						swal({
							title:"",
							text:"Error Code: "+jqXHR.status+"-"+jqXHR.statusText,
							type:"error",
							confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
						});
						mApp.unblockPage();
					}
				});
		}
		loaddata();
		//Clickable list
		$("#userTable").on("dblclick", ".list-clickable", function(){
			var link = $(this).data('link');
			window.location.href = "{{route('useredit')}}?q="+link;
		});
		//Delete item
		$("#userTable").on("click", ".btn-delete", function(e){
			e.preventDefault();
			swal({
				title:"Are you sure?",
				text:"You won't be able to revert this!",
				type:"warning",
				showCancelButton: true,
				confirmButtonClass:"btn btn-danger m-btn m-btn--wide"
			}).then((result) => {
				if (result.value) {
					mApp.blockPage({
							overlayColor: "#000000",
							type: "loader",
							state: "success",
							message: "Please wait..."
					});
					var thisbutton = $(this);
					var delid = $(this).closest("tr").data("link");
					$.ajax({
						type:'POST',
						url:"{{route('userdel')}}",
						data: {delid},
						dataType: "json",
						success:function(data){
							if(data.success)
							{
								swal({
									title:"",
									text:data.response,
									type:"success",
									confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
								}).then((result) => {
				// 						thisbutton.parent().remove();
											tablelist.row( thisbutton.closest('tr') ).remove().draw();
				// 						renumberingList();
								});
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
							}
							mApp.unblockPage();
						},
						error: function(jqXHR, exception){
							swal({
								title:"",
								text:"Error Code: "+jqXHR.status+"-"+jqXHR.statusText,
								type:"error",
								confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
							});
							mApp.unblockPage();
						}
					});
				}
			});
		});
	});
</script>
@endsection
