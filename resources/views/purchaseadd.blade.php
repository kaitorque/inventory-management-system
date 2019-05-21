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
								<h3 class="m-subheader__title ">Add Purchases</h3>
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
													<label>Purchase ID:</label>
													<input type="text" class="form-control m-input" name="puid" value="">
												</div>
												<div class="col-md-6">
													<label>Description:</label>
													<textarea type="text" class="form-control m-input" name="desc"></textarea>
												</div>
											</div>
											<div class="form-group m-form__group d-flex justify-content-center">
												<div class="col-md-6">
													<input type="text" class="form-control m-input" name="productid" value="">
												</div>
												<button id="quickadd" type="button" class="btn btn-info mx-2">Quick Add</button>
												<button id="addBtn" type="button" class="btn btn-success mx-2">Add Product</button>
											</div>
											<table class="table table-striped table-bordered table-hover table-checkable" id="itemTable">
												<thead>
													<tr>
														<th>No.</th>
														<th>Item</th>
														<th>Quantity</th>
														<th>Price</th>
														<th>Tax(%)</th>
														<th>Discount</th>
														<th>Total</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
							            <tr>
						                <th colspan="6" style="text-align:right">Total:</th>
														<th colspan="2"></th>
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

					<div class="modal fade" id="invListModal" tabindex="-1" role="dialog" aria-labelledby="List of Employee Modal" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">List of Inventory</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form class="m-form m-form--fit m--margin-bottom-20" id="invListForm">
										<div class="row m--margin-bottom-20">
											<div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
												<label>Product ID:</label>
												<input type="text" class="form-control m-input" name="mpid" placeholder="Product ID" data-col-index="0">
											</div>
											<div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
												<label>Category:</label>
												<input type="text" class="form-control m-input" name="mcategory" placeholder="Category" data-col-index="1">
											</div>
											<div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
												<label>Brand:</label>
												<input type="text" class="form-control m-input" name="mbrand" placeholder="Brand" data-col-index="1">
											</div>
											<div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
												<label>Model:</label>
												<input type="text" class="form-control m-input" name="mmodel" placeholder="Model" data-col-index="1">
											</div>
										</div>
										<div class="m-separator m-separator--md m-separator--dashed"></div>
										<div class="row">
											<div class="col-lg-12">
												<div class="p-2 d-flex justify-content-center">
													<button type="button" class="btn btn-brand m-btn m-btn--icon btn-search">
														<span>
															<i class="la la-search"></i>
															<span>Search</span>
														</span>
													</button>
													&nbsp;&nbsp;
													<button type="reset" class="btn btn-secondary m-btn m-btn--icon">
														<span>
															<i class="la la-close"></i>
															<span>Reset</span>
														</span>
													</button>
												</div>
											</div>
										</div>
										<div class="m-separator m-separator--md m-separator--dashed"></div>
										<table class="table table-striped table-bordered table-hover table-checkable" id="invListTable">
											<thead>
												<tr>
													<th>No.</th>
													<th>Product Id</th>
													<th>Category</th>
													<th>Brand</th>
													<th>Model</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary btn-submit">Submit</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
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
		//Item ID array
		var itemArr = [];
		//Open modal trigger ajax employee
		$("#addBtn").click(function(){
			$("#invListModal").modal("show");
			getinvList();
			selectedArr = [];
		});
		//Resize row column header everytime modal is open
		$('#invListModal').on('shown.bs.modal', function (e) {
			$($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
		});
		//DataTable declaration
		var invList = $("#invListTable").DataTable({
			scrollY:"50vh",
			scrollX:true,
			scrollCollapse:true,
			"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
			"order": [[ 1, 'asc' ]]
		});
		//Datatable numbering
		invList.on( 'order.dt search.dt', function () {
        invList.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
		//Ajax get employee list
		var selectedArr = [];
		function getinvList()
		{
			$("#invListModal").find(".btn-search").addClass("m-loader m-loader--light m-loader--right").prop("disabled", true);
			var form = $("#invListForm");
			invList.clear().draw();
			$.ajax({
				type:'POST',
				url:"{{route('invlist')}}",
				data: $(form).serialize(),
				dataType: "json",
				success:function(data){
					if(data.success)
					{
						for(var i=0; i<data.data.length; i++)
						{
							var row = invList.row.add([
								"",
								data.data[i].product_id,
								data.data[i].category,
								data.data[i].brand,
								data.data[i].model,
							]);
							row.nodes().to$().attr('data-id', data.data[i].product_id).attr('data-cost', data.data[i].original_cost)
							.attr('data-price', data.data[i].retail_price);
							if(selectedArr.includes(parseInt(data.data[i].product_id)))
							{
								row.nodes().to$().addClass("selected");
							}
						}
						invList.draw();
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
					$('#invListModal').find(".btn-search").removeClass("m-loader m-loader--light m-loader--right").prop("disabled", false);
				},
				error: function(jqXHR, exception){
					swal({
						title:"",
						text:"Error Code: "+jqXHR.status+"-"+jqXHR.statusText,
						type:"error",
						confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
					});
				  $('#invListModal').find(".btn-search").removeClass("m-loader m-loader--light m-loader--right").prop("disabled", false);
				}
			});
		}
		//Button search
		$("#invListModal").on("click", ".btn-search", function(){
			getinvList();
		});
		//Employee selector
			$('#invListTable tbody').on('click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
							$(this).removeClass('selected');
							selectedArr = {};
					}
					else {
							invList.$('tr.selected').removeClass('selected');
							$(this).addClass('selected');
							selectedArr = { pid: $(this).data("id"), category: invList.row(this).data()[2], brand: invList.row(this).data()[3], model: invList.row(this).data()[4], cost: $(this).data("cost"), price: $(this).data("price")};
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
			        .column( 6 )
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
			    $( api.column( 6 ).footer() ).html(
			        total.toFixed(2)
			    );
			}
		});
		//Datatable numbering
		function renumberingList()
		{
			var num = 1;
			tablelist.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
			    tablelist.cell(this, 0).data(num);
					num++;
			});
		}
		// tablelist.on( 'order.dt search.dt', function () {
	  //     tablelist.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	  //         cell.innerHTML = i+1;
	  //     } );
	  // } ).draw();
		//Submit selected employee
		$("#invListModal").on("click", ".btn-submit", function(){
			$("#invListModal").modal("hide");
			if(!$.isEmptyObject(selectedArr))
			{
				if(!itemArr.includes(selectedArr.pid))
				{
					itemArr.push(selectedArr.pid);
					var row2 = tablelist.row.add([
						"",
						`${selectedArr.category} ${selectedArr.brand} ${selectedArr.model}`,
						`<input type="number" class="row-quantity" name="qty[]" value="1"><input type="hidden" name="pid[]" value="`+selectedArr.pid+`">`,
						`<input type="text" class="row-price" name="price[]" value="${selectedArr.price}">`,
						`<input type="text" class="row-tax" name="tax[]" value="0">`,
						`<input type="text" class="row-discount" name="discount[]" value="0">`,
						selectedArr.price,
						`<button type="button" class="btn btn-sm btn-danger btn-remove">Remove</button>`,
					]);
					renumberingList();
					tablelist.draw();
				}
			}
		});
		$("#itemTable").on("change", ".row-quantity,.row-price,.row-tax,.row-discount", function(){
			var thistr = $(this).closest("tr");
			var qty = thistr.find(".row-quantity").val();
			var price = parseFloat(thistr.find(".row-price").val());
			var tax = parseFloat(thistr.find(".row-tax").val());
			var discount = parseFloat(thistr.find(".row-discount").val());
			var cell = tablelist.cell(thistr, 6);
			cell.data((qty*(price-discount))*(tax+100)/100);
			cell.invalidate();
			// tablelist.row(thistr).data()[7] = cost * $(this).val();
			// var row3 = tablelist.row(thistr);
			// var columnrow = row3.data()[7];
			// columnrow = cost * $(this).val();
			// columnrow.invalidate();
			// row3.invalidate();
			tablelist.draw();
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
					puid: {
							 required: true,
							 digits: true,
							 normalizer: function(value) {
									return $.trim(value);
									 },
							 remote: {
								 url: "<?php echo route("checkpuid"); ?>",
								 type: "post",
							 }
					}
				},
				messages: {
					puid: {
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
			// var data = tablelist.$('input').serializeArray();
			// data.append({ "rid": $("input[name=rid]").val() ,"status": $("input[name=status]").val() });
			$.ajax({
				type:'Post',
				url:"{{route('purchaseadd.post')}}",
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
						}).then((result) => {
							//if user click ok, it will redirect the user
							if (result.value) {
								window.location.href=("{{route('purchaseadd')}}");
							}
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

		$("#itemTable").on("click", ".btn-remove", function(){

			tablelist.row($(this).closest("tr")).remove();
			renumberingList()
			tablelist.draw();
		});

		$("#quickadd").on("click", function(){
				var thisbtn = $(this);
				thisbtn.addClass("m-loader m-loader--success m-loader--right").prop("disabled", true);
				var productid = $("input[name=productid]").val();
			$.ajax({
				type:'Post',
				url:"{{route('purchasequick')}}",
				data: { productid: productid},
				dataType: "json",
				success: function(data) {
					if(data.success)
					{
						if(!itemArr.includes(data.data.product_id))
						{
							itemArr.push(data.data.product_id);
							var row2 = tablelist.row.add([
								"",
								`${data.data.category} ${data.data.brand} ${data.data.model}`,
								`<input type="number" class="row-quantity" name="qty[]" value="1"><input type="hidden" name="pid[]" value="`+data.data.product_id+`">`,
								`<input type="text" class="row-price" name="price[]" value="${data.data.retail_price}">`,
								`<input type="text" class="row-tax" name="tax[]" value="0">`,
								`<input type="text" class="row-discount" name="discount[]" value="0">`,
								data.data.retail_price,
								`<button type="button" class="btn btn-sm btn-danger btn-remove">Remove</button>`,
							]);
							renumberingList();
							tablelist.draw();
						}
						else
						{
							var qty = $("#itemTable").find("input[name^=pid][value="+data.data.product_id+"]").closest("tr").find("input[name^=qty]").val(function(i, oldval) {
						    return ++oldval;
							});
							qty.trigger("change");
						}
						thisbtn.removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
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
						thisbtn.removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
					}
				},
				error: function(jqXHR, exception){
						swal({
							title:"",
							text:"Error Code: "+jqXHR.status+"-"+jqXHR.statusText,
							type:"error",
							confirmButtonClass:"btn btn-secondary m-btn m-btn--wide"
						});
						thisbtn.removeClass("m-loader m-loader--success m-loader--right").prop("disabled", false);
					}
			});
		})
	});
</script>
@endsection
