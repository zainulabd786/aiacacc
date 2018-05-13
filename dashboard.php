<?php include "header.php"; ?> 
<div class="row transactions">
	<a href="#">
		<div class="col-md-3 card card-primary members-card">
			<div class="num-head">
				<div class="num">0</div>
				<div class="head">Members</div>
			</div>
			<div class="sym"><i class="fa fa-user"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "find-number-of-members" }, function(data){ $(".members-card .num").text(data); });
			</script>
		</div>
	</a>
	<a href="transactions.php#cash-transactions">
		<div class="col-md-3 card card-success cash-card">
			<div class="num-head">
				<div class="num"><i class="fa fa-inr"></i>0/-</div>
				<div class="head">Cash Balance</div>
			</div>
			<div class="sym"><i class="fa fa-inr"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "Fetch-Cash-Balance" }, function(data){ $(".cash-card .num").html(data); });
			</script>
		</div>
	</a>
	<a href="transactions.php#bank-transactions">
		<div class="col-md-3 card card-primary bank-card">
			<div class="num-head">
				<div class="num"><i class="fa fa-inr"></i>0/-</div>
				<div class="head">Bank Balance</div>
			</div>
			<div class="sym"><i class="fa fa-bank"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "Fetch-Bank-Balance" }, function(data){ $(".bank-card .num").html(data); });
			</script>
		</div>
	</a>
	<a href="transactions.php#all">
		<div class="col-md-1 card card-danger don-card">
			<div class="num-head">
				<div class="num"><i class="fa fa-inr"></i>0/-</div>
				<div class="head" style="font-size: 16px;">Donations/Unsecured Loan/Other</div>
			</div>
			<div class="sym"><i class="fa fa-gift"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "find-total-donation" }, function(data){ $(".don-card .num").html(data); });
			</script>
		</div>
	</a>
</div>

<div class="row transactions">
	<a href="transactions.php#all">
		<div class="col-md-6 card card-success income-card">
			<div class="num-head">
				<div class="num">0</div>
				<div class="head">Today's Income</div>
			</div>
			<div class="sym"><i class="fa fa-inr"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "find-today's-income" }, function(data){ $(".income-card .num").html(data); });
			</script>
		</div>
	</a>

	<a href="transactions.php#all">
		<div style="width: 47.2%" class="col-md-6 card card-danger expense-card">
			<div class="num-head">
				<div class="num"><i class="fa fa-inr"></i>0/-</div>
				<div class="head">Today's Expense</div>
			</div>
			<div class="sym"><i class="fa fa-inr"></i></div>
			<script type="text/javascript">
				$.post("ajax-req-handler.php", { key: "find-today's-expense" }, function(data){ $(".expense-card .num").html(data); });
			</script>
		</div>
	</a>
</div>

<div class="row">

	<div class="col-md-6">
		<div class="panel-group row">
			<header class="panel panel-default col-md-12 exp-mem">
				<div class="panel-heading">Expiring Members</div>
	                <div class="panel-body">
						<div class="panel-body">
							<table></table>
							<script type="text/javascript">
								$.post("ajax-req-handler.php", { key: "get-expiring-members" }, function(data){ $(".exp-mem table").html(data); });
							</script>
						</div>
	                </div>
			</header>
		</div>

		<div style="margin-top: 10px" class="panel-group row expired-members">
			<header class="panel panel-default">
				<div class="panel-heading">Expired Memberships</div>
	                <div class="panel-body">
						<div class="panel-body">
							<table>
								<script type="text/javascript">
									$.post("ajax-req-handler.php", { key: "get-expired-members" }, function(data){ $(".expired-members table").html(data); });
								</script>
							</table>
						</div>
	                </div>
			</header>
			
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel-group send-msg">
			<header class="panel panel-default">
				<div class="panel-heading">Send Text Message</div>
	                <div class="panel-body">
						<div class="panel-body">
							<div class="alerts"></div>
							<!--<div class="form-group">
								<label class="radio-inline">Select Language:</label>
								<label class="radio-inline"><input type="radio" value="hin" name="lang">Hindi</label>
								<label class="radio-inline"><input type="radio" value="eng" name="lang">English</label>
							</div>-->
							<div class="form-group sendto">
								<label class="radio-inline">Send to:</label>
								<label class="radio-inline"><input type="radio" value="all" name="sendto">All</label>
								<label class="radio-inline"><input type="radio" value="mem" name="sendto">Members</label>
								<label class="radio-inline"><input type="radio" value="ind" name="sendto">Individuals</label>
							</div>
							<div class="form-group to-inp">
								<input type="text" class="form-control" id="to" placeholder="Search">
								<input type="text" class="form-control" id="numbers" placeholder="To">
								<label>Import CSV:</label>
								<input type="file" id="import-csv">
								<div class="contacts-suggestions"><table></table></div>
							</div>
							<div class="form-group">
								<textarea id="msg" placeholder="Type Your Message Here" style="width: 100%" rows="10"></textarea>
							</div>
							<img src="images/load.gif" class="loading-gif">
							<input type="button" class="btn btn-success btn-block" value="Send" id="send-sms-btn">
						</div>
	                </div>
			</header>
		</div>

		<div class="panel-group">
			<header class="panel panel-default col-md-12 rec-added-mem">
				<div class="panel-heading">Recently added Members</div>
	                <div class="panel-body">
						<div class="panel-body">
							<table></table>
							<script type="text/javascript">
								$.post("ajax-req-handler.php", { key: "get-recently-added-members" }, function(data){ $(".rec-added-mem table").html(data); });
							</script>
						</div>
	                </div>
			</header>
		</div>

	</div>

</div>

<script type="text/javascript">
	var contactsArr = [];
	$(document).ready(function(){
		$(".loading-gif").hide();
		pramukhIME.disable();
		$(".to-inp").hide();

		$("#send-sms-btn").click(function(){
			$(".loading-gif").show();
			if($("input:radio[name='sendto']:checked").val() == "all"){
				$.post("ajax-req-handler.php", { key: "send-msg-to-all", msg: $("#msg").val() }, function(data){ $.alert(data); });
			}
			else if($("input:radio[name='sendto']:checked").val() == "mem"){
				$.post("ajax-req-handler.php", { key: "send-msg-to-members", msg: $("#msg").val() }, function(data){ $.alert(data); });
			}
			else{
				$.post("ajax-req-handler.php", { key: "send-msg-to-individuals", to: $(".to-inp #numbers").val(), msg: $("#msg").val() }, function(data){ $.alert(data); });
			}
		});

		/*$(".send-msg textarea").focus(function(){
			if($("input:radio[name='lang']:checked").val() == "hin"){
				pramukhIME.addKeyboard(PramukhIndic,"hindi"); 
		   		pramukhIME.enable();
			}
			else{
				pramukhIME.disable();
			}
		});*/
		$("#to").keyup(function(){
			if($(this).val() != ""){
				var val = $(this).val();
				$.post("ajax-req-handler.php", { key: "get-contacts-suggestions-to-send-sms", val: val }, function(data){ $(".contacts-suggestions table").html(data); });
			}
			else{
				$(".contacts-suggestions").hide();
			}
			
		});
		$(".sendto").click(function(){
			if($("input:radio[name='sendto']:checked").val() == "ind"){
				$(".to-inp").show();
			}
			else{
				$(".to-inp").hide();
			}
		});
		$("#import-csv").change(function(){
			$(this).parse({
				config: {
					delimiter: "",	// auto-detect
					newline: "",	// auto-detect
					quoteChar: '"',
					header: false,
					complete: function(results, file) {
						for(var i=0; i<results.data.length;i++){
							contactsArr.push(results.data[i][4]);
						}
						$("#numbers").val(contactsArr.toString());
					}
				}
			});
		});
		$(".send-msg textarea").focusout(function(){
			pramukhIME.disable();
			
		});
		$(".members-card").click(function(){
			$.post("ajax-req-handler.php", {
				key: "View-all-members"
			}, function(resp){
				$.confirm({
					title: "All Members", 
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-12 col-md-offset-0',
					buttons: {
						close: function () {text: 'Close'}
					},
					content: resp,
					contentLoaded: function(data, status, xhr){
						// data is already set in content
						this.setContentAppend('<br>Status: ' + status);
					}
				});
			});
		});

		$(".contacts-suggestions").hide();
	});
</script>
<?php include "footer.php"; ?> 
