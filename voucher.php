<?php include "header.php"; ?>
<div class="row voucher">
	<div class="panel-group col-md-4">
		<header class="panel panel-primary">
			<div class="panel-heading">Enter Voucher Details</div>
                <div class="panel-body">
					<div style="max-height:600px;overflow-y:scroll;height:600px"  class="panel-body">
						<div class="form-group">
							<label for="voucher-date">Voucher Date</label>
							<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="voucher-date">
						</div>
						<div class="form-group">
							<label for="name">Client Name</label>
								<input type="hidden" value="0" id="person-id">
							   	<input type="text" class="form-control" id="name">
							   	<div class="name-resp-cont"><table></table></div>
						</div>
						<div class="form-group">
							<label for="mob">Contact Number</label>
							<input type="text" class="form-control" id="mob">
						</div>
						<div class="form-group">
							<label for="add">Address</label>
							<input type="text" class="form-control" id="add">
						</div>
						<div class="form-group">
							<label for="city">City</label>
							<input type="text" class="form-control" id="city">
						</div>
						<div class="form-group">
							<label for="state">State</label>
							<input type="text" class="form-control" id="state">
						</div>
						<div class="form-group">
							<label for="chnum">Cheque Number</label>
							<input type="text" class="form-control" id="chnum">
						</div>
						<div class="form-group">
							<label for="pdec">Payment Description</label>
							<textarea class="form-control" rows=10 id="pdesc"></textarea>
						</div>
						<div class="form-group">
							<label for="amount">Amount (<i class='fa fa-inr'></i>)</label>
							<input type="text" value="0" class="form-control" id="amount">
						</div>
						<div class="form-group">
							<label class="radio-inline"><input type="radio" value="Cash" name="type" id="type">Cash</label>
							<label class="radio-inline"><input type="radio" value="Cheque" name="type" id="type">Cheque</label>
						</div>
						<button type="button" class="btn btn-success btn-block" id="sub-btn">Submit</button> 
					</div>
                </div>
		</header>
	</div>	
	<div class="panel-group col-md-8" id="accordion">
			<div class="panel panel-primary">
				<h4 class="panel-title">
					<button type="button" class="btn btn-primary btn-block" id="collapse-button" data-parent="#accordion" data-toggle="collapse" href="#voucher"><i class="fa fa-user" aria-hidden="true"></i>Voucher Preview</button>
				</h4>
				<div id="voucher" class="panel-collapse collapse in">
					<div id="panel-body" class="panel-body">
						<div class="voucher-prev">
							
							<div style="float:left;width:85%;text-align:center">
								<p style="float:left;width:20%;">PAN No.: AAFTA1692P</p>
								<p style="float:right;width:80%;text-align:center">GOVERNMENT REGD. CELL NCT DELHI (INDIA)&nbsp;&nbsp;<strong> A REGD. NGO UNDER INDIAN T.ACT.-1882 </strong></p>
								<h2 style="margin-bottom: 0;"><strong><b class="main-heading">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL &#174;</b></strong></h2>
								<p style="text-align:center;font-size: 10px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
								AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250)<br/>
								Website: aiacacc.com, Email: aiacacc@gmail.com
								</strong></p>
							</div>
							
							<div class="rec" style="float:right;width:15%;">
								<p style="margin:5px 0 0 0;">Voucher No.</p>
								<p><strong class="rec-no">
									<script>
										$.post("ajax-req-handler.php", { key: "generate-voucher-number" }, function(data){ $(".rec .rec-no").html(data); });
									</script>
								</strong></p>
								<div class="date-label">&#9734;&#9734;&#9734;DATE&#9734;&#9734;&#9734;</div>
								<div style="text-align:center;border-bottom:2px dotted;font-size:18px;">
									<strong id="date">
										<script>
											function convertDate(inputFormat) {
											  	function pad(s) { return (s < 10) ? '0' + s : s; }
											  	var d = new Date(inputFormat);
											  	return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
											}
											$(".rec #date").text(convertDate($("#voucher-date").val()));
											$("#voucher-date").change(function(){
												$(".rec #date").text(convertDate($("#voucher-date").val()));
											});
										</script>
									</strong>
								</div>
							</div>

							<div class="row voucher-main">
								<div class="col-print-7 left">
									<div class="left-top">
										<div class="person-det vb1 vb">
											<div class="cname lb">Client Name</div>
											<div class="name-dash d"></div>
										</div>
										<div class="person-det vb2 vb">
											<div class="mob lb">Mobile</div>
											<div class="mob-dash d"></div>
										</div>
										<div class="person-det vb3 vb">
											<div class="add lb">Address</div>
											<div class="add-dash d"></div>
										</div>
										<div class="person-det vb4 vb">
											<div class="city lb">City</div>
											<div class="city-dash d"></div>
										</div>
										<div class="person-det vb5 vb">
											<div class="state lb">State</div>
											<div class="state-dash d"></div>
										</div>
										<div class="person-det vb6 vb">
											<div class="cno lb">Cheque Number</div>
											<div class="chnum-dash d"></div>
										</div>
									</div>

									<div class="left-bottom">
										<div class="heading">Payment Description/ Details:</div>
										<div class="d1 d pdesc1"></div>
										<div class="d2 d pdesc2"></div>
										<div class="d3 d pdesc3"></div>
										<div class="d4 d pdesc4"></div>
									</div>
								</div>

								<div class="col-print-5 right">
									<div class="right-top">
										<div class="amount">
											<div class="amount-dig">
												<div class="sym"><i class="fa fa-inr"></i></div>
												<div class="dig"></div>
											</div>
											<div class="amt-words">
												<div>In Words</div>
												<div class="d d1"></div>
												<div class="d d2"></div>
												<div class="d d3"></div>
											</div>
										</div>

										<div class="rec-det">
											<div class="mop">
												<div class="cmark"></div>
												<div class="cash">Cash</div>
												<div class="chmark"></div>
												<div class="cheque">Cheque</div>
											</div>
										</div>
										
										<div class="signature">
											<div class="vrec">Reciever Sig.</div>
											<div class="prep">Prepared By</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<h4 class="panel-title">
					<button type="button" class="btn btn-primary btn-block" id="collapse-button"  data-parent="#accordion" data-toggle="collapse" href="#all-bills"> <i class="fa fa-server" aria-hidden="true"></i>View Bills</button>
				</h4>
				<div id="all-bills" class="panel-collapse collapse">
					<div style="max-height:600px;overflow-y:scroll;overflow-x:hidden;height:600px" id="panel-body" class="panel-body">
						
						<input type="text" class="form-control search-bar" placeholder="Enter Reciept Number, Name, Address, State, DD/NEFT/RTGS/Cheque, Date">
						<table style="margin-top:10px;">
							<script>
								$.post("ajax-req-handler.php", {
									key: "Load-all-vouchers"
								}, function(data){
									$("#all-bills table").html(data);
								});
							</script>
						</table>
					</div>
				</div>
			</div>
    </div>	
</div>
<button class="btn btn-primary print" type="button">Print</button>
<script type="text/javascript">
	$("#name").keyup(function(){
		$(".name-dash").text($(this).val());
		$(".name").text($(this).val());
		if($(this).val() == ""){
			$(".name-resp-cont").hide();
		}
		else{
			$(".name-resp-cont").show();
		}
		$.post("ajax-req-handler.php", { key: "find-existing-entries", val: $(this).val() }, function(data){ $(".name-resp-cont table").html(data); });
	});

	$("#mob").keyup(function(){
		$(".mob-dash").text($(this).val());
	});
	$("#add").keyup(function(){
		$(".add-dash").text($(this).val());
	});
	$("#city").keyup(function(){
		$(".city-dash").text($(this).val());
	});
	$("#state").keyup(function(){
		$(".state-dash").text($(this).val());
	});
	$("#chnum").keyup(function(){
		$(".chnum-dash").text($(this).val());
	});
	$("#pdesc").keyup(function(){
		pdescLength = $(this).val().length;
		if(pdescLength == 0 || $(this).val() == ""){
			$(".pdesc1").text("");
			$(".pdesc2").text("");
			$(".pdesc3").text("");
			$(".pdesc4").text("");
		}
		if(pdescLength < 50){
			$(".pdesc1").text($(this).val());
		} 
		else if(pdescLength < 100){
			$(".pdesc1").text($(this).val().substring(0,50));
			$(".pdesc2").text($(this).val().substr(50));
		} 
		else if( pdescLength < 150 ){
			$(".pdesc1").text($(this).val().substring(0,49));
			$(".pdesc2").text($(this).val().substring(49,99));
			$(".pdesc3").text($(this).val().substring(99,150));
		}
		else if( pdescLength < 200 ){
			$(".pdesc1").text($(this).val().substring(0,49));
			$(".pdesc2").text($(this).val().substring(49,99));
			$(".pdesc3").text($(this).val().substring(99,149));
			$(".pdesc4").text($(this).val().substring(149,200));
		}
	});
	$("#amount").keyup(function(){
		$(".voucher-main .amount .amount-dig .dig").text($(this).val()+"/-");
		$(".voucher-main .amount .amt-words .d1").text(capitalLetter(inWords($(this).val()))+" Rupees");
	});

	$(".rec-det .date .dash").html("<strong>"+convertDate($("#voucher-date").val())+"</strong>");
	$("#voucher-date").change(function(){
		$(".rec-det .date .dash").html("<strong>"+convertDate($(this).val())+"</strong>");
	});
	$("input:radio[name='type']").change(function(){
		if($(this).val() == "Cash"){
			$(".rec-det .mop .cmark").html('<i class="fa fa-check"></i>');
			$(".rec-det .mop .chmark").html("");
		}
		else{
			$(".rec-det .mop .chmark").html('<i class="fa fa-check"></i>');
			$(".rec-det .mop .cmark").html("");
		}
	});
	$("#sub-btn").click(function(){
		$("#sub-btn").hide();
		$.post("ajax-req-handler.php", {
			key: "insert-voucher-details",
			date: $("#voucher-date").val(),
			personId: $("#person-id").val(),
			name: $("#name").val(),
			mob: $("#mob").val(),
			add: $("#add").val(),
			city: $("#city").val(),
			state: $("#state").val(),
			chnum: $("#chnum").val(),
			pdesc: $("textarea#pdesc").val(),
			amount: $("#amount").val(),
			mop: $("input:radio[name='type']:checked").val()
		}, function(data){
			$.confirm({
				title: "Okay", 
				type: 'green',
				typeAnimated: true,
				columnClass: 'col-md-8 col-md-offset-2',
				buttons: {
					OK: {
						text: 'OK',
						action: function(){
							
						}
					},
					OK: {
						text: 'Done',
						action: function(){
							location.reload();
						}
					},
					PRINT: {
						text: 'PRINT',
						action: function(){
							$.print(".voucher-prev");
									
						}
					}
				},
				content: data,
				contentLoaded: function(data, status, xhr){
					this.setContentAppend('<br>Status: ' + status);
				}
			});
		});
	});
	$(".search-bar").keyup(function(){
		if($(this).val() != "")
			$.post("ajax-req-handler.php", { key: "search-voucher", val: $(this).val() }, function(data){ $("#all-bills table").html(data); });
		else
			$.post("ajax-req-handler.php", { key: "Load-all-vouchers" }, function(data){ $("#all-bills table").html(data); });
	});
	$(".print").click(function(){
		$.print(".voucher-prev");
	});
</script>
<?php include "footer.php"; ?> 