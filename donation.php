<?php include "header.php"; ?>
<div class="row">
	<div class="panel-group col-md-4">
		<header class="panel panel-primary">
			<div class="panel-heading">Enter Bill Details</div>
                <div class="panel-body">
					<div style="max-height:600px;overflow-y:scroll;height:600px"  class="panel-body">
						<div class="form-group">
							<label for="bill-date">Bill Date</label>
							<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="bill-date">
						</div>
						<div class="form-group">
							<label for="pno">Name</label>
							<div class="input-group">
							    <div class="input-group-addon">
							      	<select id="gen">
							      		<option value="">Select</option>
							      		<option value="Mr.">Mr.</option>
							      		<option value="Ms.">Ms.</option>
							      		<option value="M/s.">M/s.</option>
							      	</select>
							    </div>
							    <input type="hidden" value="0" id="person-id">
							   	<input type="text" class="form-control" id="name">
							   	<div class="name-resp-cont"><table></table></div>
							</div>
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
							<label for="mob">Mobile</label>
							<input type="text" class="form-control" id="mob">
						</div>
						<div class="form-group">
							<label for="rank">Rank</label>
							<input type="text" class="form-control" id="rank">
						</div>
						<div class="form-group">
							<label for="upto">Up to</label>
							<input type="date" class="form-control" id="upto">
						</div>
						<div class="form-group">
							<label for="mem-fee">Membership Fee (<i class='fa fa-inr'></i>)</label>
							<input type="text" value="0" class="form-control" id="mem-fee">
						</div>
						<div class="form-group">
							<label for="donation">Donation/Unsecured Loan/Other (<i class='fa fa-inr'></i>)</label>
							<input type="text" value="0" class="form-control" id="amt">
						</div>
						<div class="form-group">
							<label for="pno">DD/NEFT/RTGS/Cheque Number</label>
							<div class="input-group">
							    <input type="text" class="form-control" id="pno">
							    <div class="input-group-addon">
							      	<select id="mop">
							      		<option value="">Select</option>
							      		<option value="Cash">Cash</option>
							      		<option value="DD">DD</option>
							      		<option value="RTGS">RTGS</option>
							      		<option value="NEFT">NEFT</option>
							      		<option value="Cheque">Cheque</option>
							      	</select>
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label for="pdate">Date</label>
							<input type="date" class="form-control" id="pdate">
						</div>
						<div class="form-group">
							<label for="mem-image">Image</label>
							<form enctype="multipart/form-data"><input type="file" class="form-control" id="mem-image" accept="image/*"></form>
						</div>
						<div class="ine-status"></div>
						<input type="button" class="btn btn-success btn-block" value="Submit" id="sub-btn">
					</div>
                </div>
		</header>
	</div>
	<div class="panel-group col-md-8" id="accordion">
			<div class="panel panel-primary">
				<h4 class="panel-title">
					<button type="button" class="btn btn-primary btn-block" id="collapse-button" data-parent="#accordion" data-toggle="collapse" href="#bill-preview"><i class="fa fa-user" aria-hidden="true"></i>Bill Preview</button>
				</h4>
				<div id="bill-preview" class="panel-collapse collapse in">
					<div style="padding:30px;" id="panel-body" class="panel-body">
						<div class="prev">
						
							<div style="float:left;width:85%;text-align:center">
								<p style="float:left;width:20%;">PAN No.: AAFTA1692P</p>
								<p style="float:right;width:80%;text-align:center">GOVERNMENT REGD. CELL NCT DELHI (INDIA)&nbsp;&nbsp;<strong> A REGD. NGO UNDER INDIAN T.ACT.-1882 </strong></p>
								<h2 style="margin-bottom: 0;"><strong><b class="main-heading">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL &#174;</b></strong></h2>
								<p style="text-align:center;font-size: 12px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
								AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250)<br/>
								Website: aiacacc.com, Email: aiacacc@gmail.com
								</strong></p>
							</div>
							
							<div class="rec" style="float:right;width:15%;">
								<p style="margin:5px 0 0 0;">RECEIPT No.</p>
								<p><strong class="rec-no">
									<script>
										$.post("ajax-req-handler.php", { key: "generate-receipt-number" }, function(data){ $(".rec .rec-no").html(data); });
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
											$(".rec #date").text(convertDate($("#bill-date").val()));
											$("#bill-date").change(function(){
												$(".rec #date").text(convertDate($("#bill-date").val()));
											});
										</script>
									</strong>
								</div>
							</div>
							
							<div style="margin-top:20px;" class="blanks b1">
								<div>Recieved With Thanks From </div> <div class="d2"></div> &nbsp; <strong class="name">  </strong>
							</div>
							
							<div class="blanks b2">
								Address &nbsp;&nbsp; <strong class="add">  </strong>
							</div>
							
							<div class="blanks b3">
								<strong class="add2">  </strong> <div class="d3">City</div><strong class="s3 city"></strong> <div>State</div> <strong class="state"> </strong> <div class="d2">Mob</div><strong class="s2 mob"></strong>
							</div>
							
							<div class="blanks b4">
								<div>Rank</div> &nbsp; <strong class="rank">  </strong> <div class="d2">Up To</div> &nbsp; <strong class=" s2 upto">  </strong>
							</div>
							
							<div class="blanks b5">
								<div>on Account Membership Donation </div> <strong class="mem-fee"></strong> <strong class="s2 mem-fee-words"></strong><div class="d2">&</div>
							</div>
							
							<div class="blanks b6">
								<div>Voluntarily Donated/Unsecured Loan/Other</div> <strong class="amount s2"> </strong> <strong class="amt-words">  </strong>
							</div>
							
							<div class="blanks b7">
								 &nbsp;<div>Total Amount</div> <strong class="total"> </strong> <div class="d2"> By </div> <div class="mop"></div> &nbsp; <strong class="pno s2">  </strong> <div class="d3">Dated</div> <strong  class="pdate s3"> </strong>
							</div>
						<!--<div class="blanks b8">
								<div> By </div> <div class="mop"></div> &nbsp; <strong class="pno">  </strong> <div class="d2">Dated</div> <strong  class="date s2"> </strong>
							</div>-->
							
								<table style="margin: 20px 0 0 0;" class="bill-bottom ">
									<tr>
										<td class="amt">
												<div class="rupee-sym" ><i class="fa fa-inr"></i></div>
												<div style="min-width:100px" class="rupee-amt"></div>
										</td>
										
										<td style="border:none;padding:0;padding-left:60px;" class="bank-det">
											<div>BANK: State Bank Of India</div>
											<div>A/C No.: 35627823093</div>
											<div>IFSC Code.: SBIN0011466</div>
										</td>
										
										<td style="font-size:15px;font-style:italic;border:none;padding:0;" class="sign">
											<u>Recieved By</u>
											<div>Signature....................................................</div>
										</td>
									</tr>
								</table>
						</div>
					<!--<button class="btn btn-primary print" type="button">Print</button>-->
					</div>
				</div>
			</div>
			
			<div class="panel panel-primary">
				<h4 class="panel-title">
					<button type="button" class="btn btn-primary btn-block" id="collapse-button"  data-parent="#accordion" data-toggle="collapse" href="#all-bills"> <i class="fa fa-server" aria-hidden="true"></i>View Bills</button>
				</h4>
				<div id="all-bills" class="panel-collapse collapse">
					<div style="max-height:600px;overflow-y:scroll;overflow-x:hidden;height:600px" id="panel-body" class="panel-body">
						
						<input type="text" class="form-control search-bar" placeholder="Enter Reciept Number, Name, Mobile, Address, City, State, DD/NEFT/RTGS/Cheque, Date">
						<table style="margin-top:10px;">
							<script>
								$.post("ajax-req-handler.php", {
									key: "Load-alla-bills"
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
<script src="functions.js" type="text/javascript"></script>
<script>
	$("#mem-image").change(function() {
	  readURL(this);
	});
	$("#gen").change(function(){
		$(".b1 .d2").text($(this).val());
	});
	$("#name").keyup(function(){
		$(".name").text($(this).val());
		if($(this).val() == ""){
			$(".name-resp-cont").hide();
		}
		else{
			$(".name-resp-cont").show();
		}
		$.post("ajax-req-handler.php", { key: "find-existing-entries", val: $(this).val() }, function(data){ $(".name-resp-cont table").html(data); });
	});
	$("#add").keyup(function(){
		addLength = $(this).val().length;
		if(addLength < 65){
			$(".add").text($(this).val());
		} 
		else{
			$(".add").text($(this).val().substring(0,64));
			$(".add2").text("-"+$(this).val().substr(64));
		} 
	});
	$("#city").keyup(function(){
		$(".city").text($(this).val());
	});
	$("#state").keyup(function(){
		$(".state").text("("+$(this).val()+")");
	});
	$("#mob").keyup(function(){
		$(".mob").text($(this).val());
	});
	$("#rank").keyup(function(){
		$(".rank").text($(this).val());
	});
	$("#upto").change(function(){
		$(".upto").text(convertDate($(this).val()));
	});
	$("#mem-fee").keyup(function(){
		var num = $(this).val();
		$(".mem-fee").html("<i class='fa fa-inr'></i> "+num+ "/-");
		$(".mem-fee-words").text(capitalLetter(inWords(num))+" Rupees");
		$("#donation").val("0");
		$(".rupee-amt").text(num+"/-");
	});
	$("#donation").keyup(function(){
		$(".donation").text($(this).val());
	});
	$("#amt-words").keyup(function(){
		$(".amt-words").text($(this).val());
	});
	$("#amt").keyup(function(){
		var num = $(this).val();
		var tot = parseInt(num) + parseInt($("#mem-fee").val());
		$(".amount").html("<i class='fa fa-inr'></i> "+num+ "/-");
		$(".amt-words").text(capitalLetter(inWords(num))+" Rupees");
		$(".total").html( "<i class='fa fa-inr'></i> "+tot+"/-" );
		$(".rupee-amt").text(tot+"/-");
	});
	$("#pno").keyup(function(){
		$(".pno").text($(this).val());
	});
	$("#date").change(function(){
		$(".date").text(convertDate($(this).val()));
	});
	$("#pdate").change(function(){
		$(".pdate").text(convertDate($(this).val()));
	});
	$("#mop").change(function(){
		$(".mop").text($(this).val());
	});
	$(".search-bar").keyup(function(){
		if($(this).val() == "") $.post("ajax-req-handler.php", { key: "Load-alla-bills" }, function(data){ $("#all-bills table").html(data); });
		else $.post("ajax-req-handler.php", {key:"search-bill", val:$(this).val()}, function(data){ $("#all-bills table").html(data); });
	});
	    $("#sub-btn").click(function(){  
	    	if($("#mem-image").val() == ""){
	    		alert("Please Select an image!");
	    	}
	    	else{
	    		$("#sub-btn").hide();  
		    	var key = "Insert-records-to-db";
				var	billDate = $("#bill-date").val();
				var	personId = $("#person-id").val();
				var	name = $("#name").val();
				var	add = $("#add").val();
				var	city = $("#city").val();
				var	state = $("#state").val();
				var	mob = $("#mob").val();
				var	rank = $("#rank").val();
				var	upto = $("#upto").val();
				var	memFee = $("#mem-fee").val();
				var	amount = $("#amt").val();
				var	pno = $("#pno").val();
				var	pdate = $("#pdate").val();
				var	mop =  $("#mop").val();
			   	var file_data = $('#mem-image').prop('files')[0];   
			    var form_data = new FormData();     
			    form_data.append('key', key);  
			    form_data.append('billDate', billDate);  
			    form_data.append('personId', personId);  
			    form_data.append('name', name);  
			    form_data.append('add', add);  
			    form_data.append('city', city);  
			    form_data.append('state', state);  
			    form_data.append('mob', mob);  
			    form_data.append('rank', rank);  
			    form_data.append('upto', upto);  
			    form_data.append('memFee', memFee);  
			    form_data.append('amount', amount);  
			    form_data.append('pno', pno);  
			    form_data.append('pdate', pdate);  
			    form_data.append('mop', mop);  
			    form_data.append('file', file_data); 
				$.ajax({
			        url: 'ajax-req-handler.php', // point to server-side PHP script 
			        dataType: 'text',  // what to expect back from the PHP script, if anything
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: form_data,                         
			        type: 'post',
			        success: function(data){
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
										$(".prev").print({
											globalStyles: true,
											mediaPrint: false,
											stylesheet: null,
											noPrintSelector: ".no-print",
											iframe: true,
											append: $(".prev"),
											prepend: null,
											manuallyCopyFormValues: true,
											deferred: $.Deferred(),
											timeout: 750,
											title: null,
											doctype: '<!doctype html>'
										});
										}
									}
								},
							content: data,
							contentLoaded: function(data, status, xhr){
								this.setContentAppend('<br>Status: ' + status);
							}
						});
			        }
			    });
			}
		});
	$(".print").click(function(){
		$(".prev").print({
        	globalStyles: true,
        	mediaPrint: true,
        	stylesheet: null,
        	noPrintSelector: ".no-print",
        	iframe: true,
        	append: $(".prev"),
        	prepend: null,
        	manuallyCopyFormValues: true,
        	deferred: $.Deferred(),
        	timeout: 750,
        	title: null,
        	doctype: '<!doctype html>'
		});
	});
</script>
<?php include "footer.php"; ?>