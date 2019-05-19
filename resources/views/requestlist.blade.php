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
								<h3 class="m-subheader__title ">Request List</h3>
							</div>
							<!-- <div>
								<a class="btn m-btn-pill btn-primary" href="{{route('useradd')}}">Add User</a>
							</div> -->
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
														<th>Request ID</th>
														<th>Request Date</th>
														<th>Employee Name</th>
														<th>Quantity requested (Overall)</th>
														<th>View all</th>
														<!-- <th>Warranty</th>
														<th>Created_by</th>
														<th>Created_date</th>
														<th>Modified_by</th>
														<th>Modified_date</th>
														<th>Description</th> -->

													</tr>
												</thead>
												<tbody>
													<?php
														$num = 1;

														foreach($requestlist as $item)
														{ ?>
															<tr class="list-clickable" data-href="{{UserFunction::encrypt('$item->request_id')}}">
																<td><?php echo $num; ?></td>
																<td><?php echo "{$item->request_id}"; ?></td>
																<td><?php echo "{$item->dates}"; ?></td>
																<td><?php echo "{$item->nickname}"; ?></td>
																<td><?php echo ""; ?></td>


																<!-- <td><?php //echo "{$item->warranty_month}"; ?></td>
																<td><?php //echo "{$item->created_by}"; ?></td>
																<td><?php //echo "{$item->created_date}"; ?></td>
																<td><?php //echo "{$item->modified_by}"; ?></td>
																<td><?php //echo "{$item->modified_date}"; ?></td>
																<td><?php //echo "{$item->description}"; ?></td> -->

																<td>
																	<?php
																		if(session('request_id') != $item->request_id)
																		{ ?>
																			<a href="" class="btn btn-sm btn-info">View</a>
															<?php }
																	?>
																</td>
															</tr>
											<?php		$num++;
														}
													?>
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
		//Clickable list
		$("#userTable").on("dblclick", ".list-clickable", function(){
			var link = $(this).data('href');
			window.location.href = "{{route('useredit')}}?q="+link;
		});
	});
</script>
@endsection
