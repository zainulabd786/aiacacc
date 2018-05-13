<?php include "header.php"; ?>
<div class="panel-group">
	<header class="panel panel-primary">
		<ul class="nav nav-tabs">
		  	<li class="active"><a data-toggle="tab" href="#all">All Transactions</a></li>
		  	<li><a data-toggle="tab" id="ct" href="#cash-transactions">Cash Transactions</a></li>
		  	<li><a data-toggle="tab" id="bt" href="#bank-transactions">Bank Transactions</a></li>
		  	<div class="transactions-menu">
		  		<div class="print-ico">
		  			<i class="fa fa-print fa-2x"></i>
		  		</div>
		  		<div class="search-ico">
		  			<i class="fa fa-search fa-2x"></i>
		  		</div>
		  		<div class="date-filters form-inline">
		  			<div class="form-group">
						<label for="from">From</label>
						<input type="date" class="form-control" id="from">
					</div>
					<div class="form-group">
						<label for="to">To</label>
						<input type="date" class="form-control" id="to">
					</div>
		  		</div>
		  	</div>
		  	<div class="trans-search-bar">
		  		<div class="input-group">
				    <input type="text" class="form-control search-trans-inp" placeholder="Search">
				    <div class="input-group-btn">
				      	<button class="btn btn-default close-search" type="button">
				        	<i class="fa fa-close"></i>
				      	</button>
				    </div>
				</div>
		  	</div>
		</ul>

		<div class="tab-content transactions">
		  	<div id="all" class="tab-pane fade in active">
				<div class="statement-header">
					<h2 style="margin-bottom: 0;font-family: Impact, Charcoal, sans-serif;font-size: 34px">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL<sup>&#174;</sup></h2>
					<p style="text-align:center;font-size: 12px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
						AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250, 09358327726)<br/>
						Website: aiacacc.com, Email: aiacacc@gmail.com
					</strong></p>
					<p class="doc-head"><u>Transactions Record</u></p>
				</div>
		    	<h3>All Transactions</h3>
		    	<p class="st-date" ></p>
		    	<table>
		    		<script type="text/javascript">
		    			$.post("ajax-req-handler.php", { key: "load-all-transactions" }, function(data){ $(".transactions #all table").html(data); });
		    		</script>
		    	</table>
		 	</div>
		 	<div id="cash-transactions" class="tab-pane fade">
		 		<div class="statement-header">
					<h2 style="margin-bottom: 0;font-family: Impact, Charcoal, sans-serif;font-size: 34px">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL<sup>&#174;</sup></h2>
					<p style="text-align:center;font-size: 12px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
						AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250, 09358327726)<br/>
						Website: aiacacc.com, Email: aiacacc@gmail.com
					</strong></p>
					<p class="doc-head"><u>Transactions Record</u></p>
				</div>
		    	<h3>Cash Transactions</h3>
		    	<p class="st-date" ></p>
		   	 	<table>
		   	 		
		   	 	</table>
		  	</div>
		  	<div id="bank-transactions" class="tab-pane fade">
		  		<div class="statement-header">
					<h2 style="margin-bottom: 0;font-family: Impact, Charcoal, sans-serif;font-size: 34px">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL<sup>&#174;</sup></h2>
					<p style="text-align:center;font-size: 12px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
						AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250, 09358327726)<br/>
						Website: aiacacc.com, Email: aiacacc@gmail.com
					</strong></p>
					<p class="doc-head"><u>Transactions Record</u></p>
				</div>
		    	<h3>Bank Transactions</h3>
		    	<p class="st-date" ></p>
		    	<table>
		    		
		    	</table>
		  	</div>
		</div>
	</header>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$.post("ajax-req-handler.php", { key: "load-cash-transactions" }, function(data){ $(".transactions #cash-transactions table").html(data); });
	});
	$(document).ready(function(){
		$.post("ajax-req-handler.php", { key: "load-bank-transactions" }, function(data){ $(".transactions #bank-transactions table").html(data); });
	});
	$(".search-trans-inp").keyup(function(){
		var activeTab = $(".active a").attr('href');
		if($(this).val() == ""){
			if(activeTab == "#all"){
				$.post("ajax-req-handler.php", { key: "load-all-transactions" }, function(data){ $(".transactions #all table").html(data); });
			}
			else if(activeTab == "#cash-transactions"){
				$.post("ajax-req-handler.php", { key: "load-cash-transactions" }, function(data){ $(".transactions #cash-transactions table").html(data); });
			}
			else{
				$.post("ajax-req-handler.php", { key: "load-bank-transactions" }, function(data){ $(".transactions #bank-transactions table").html(data); });
			}
		}
		else{
			if(activeTab == "#all"){
				$.post("ajax-req-handler.php", { key: "search-all-transactions", val: $(this).val() }, function(data){ $(".transactions #all table").html(data); });
			}
			else if(activeTab == "#cash-transactions"){
				$.post("ajax-req-handler.php", { key: "search-cash-transactions", val: $(this).val() }, function(data){ $(".transactions #cash-transactions table").html(data); });
			}
			else{
				$.post("ajax-req-handler.php", { key: "search-bank-transactions", val: $(this).val() }, function(data){ $(".transactions #bank-transactions table").html(data); });
			}
		}
	});
	$(".search-ico").click(function(){
		$(".transactions-menu").hide();
		$(".trans-search-bar").show();
	});
	$(".close-search").click(function(){
		$(".transactions-menu").show();
		$(".trans-search-bar").hide();
	});
	$("#from").change(function(){
		if($(this).val() != ""){
			$(".transactions .active .st-date").html("<strong>From "+convertDate($(this).val())+"</strong>");
			var activeTab = $(".active a").attr('href');
			if(activeTab == "#all"){
				$.post("ajax-req-handler.php", { key: "load-all-transactions-fromDate-to-now", fromDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
			}
			else if(activeTab == "#cash-transactions"){
				$.post("ajax-req-handler.php", { key: "load-cash-transactions-fromDate-to-now", fromDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
			}
			else{
				$.post("ajax-req-handler.php", { key: "load-bank-transactions-fromDate-to-now", fromDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
			}

		}
		
	});
	$("#to").change(function(){
		if($("#from").val() == "" && $("#to").val() != ""){
			if($(this).val() != ""){
				$(".transactions .active .st-date").html("<strong>From "+convertDate($(this).val())+"</strong>");
				var activeTab = $(".active a").attr('href');
				if(activeTab == "#all"){
					$.post("ajax-req-handler.php", { key: "load-all-transactions-from-begining-to-toDate", toDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
				}
				else if(activeTab == "#cash-transactions"){
					$.post("ajax-req-handler.php", { key: "load-cash-transactions-from-begining-to-toDate", toDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
				}
				else{
					$.post("ajax-req-handler.php", { key: "load-bank-transactions-from-beigning-to-toDate", toDate: $(this).val() }, function(data){ $(".transactions .active table").html(data); });
				}
			}
		}
		else{
			if($("#from").val() != "" && $("#to").val() != ""){
				$(".transactions .active .st-date").html("<strong>From '"+convertDate($("#from").val())+"' To '"+convertDate($("#to").val())+"'</strong>");
				var activeTab = $(".active a").attr('href');
				if(activeTab == "#all"){
					$.post("ajax-req-handler.php", { key: "load-all-transactions-from-fromDate-to-toDate", toDate: $(this).val(), fromDate: $("#from").val() }, function(data){ $(".transactions .active table").html(data); });
				}
				else if(activeTab == "#cash-transactions"){
					$.post("ajax-req-handler.php", { key: "load-cash-transactions-from-fromDate-to-toDate", toDate: $(this).val(), fromDate: $("#from").val() }, function(data){ $(".transactions .active table").html(data); });
				}
				else{
					$.post("ajax-req-handler.php", { key: "load-bank-transactions-from-fromDate-to-toDate", toDate: $(this).val(), fromDate: $("#from").val() }, function(data){ $(".transactions .active table").html(data); });
				}
			}
		}
	});
	$(".print-ico").click(function(){
		$.print(".transactions .active");
	});
</script>
<?php include "footer.php"; ?>