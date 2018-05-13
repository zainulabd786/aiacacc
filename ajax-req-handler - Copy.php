<?php
    //include "database_connection.php";
    class database extends SQLite3{
    	function __construct(){
			$db_file = "data/AIACACC.sqlite3";
    		$this->open($db_file);
    	}
    }
    $db = new database();
    //function to get the number of rows returned by sql query
    function numr( $result ){
    	$nrows = 0;
		$result->reset();
		while ($result->fetchArray())
		    $nrows++;
		$result->reset();
		return $nrows;
    }

    function find_person( $name, $add, $city, $state, $mobile ){
    	class fPDatabase extends SQLite3{
	    	function __construct(){
				$database_file = "data/AIACACC.sqlite3";
	    		$this->open($database_file);
	    	}
	    }
	    $db_conn = new fPDatabase();
    	$sql = "SELECT * FROM personal_info WHERE name='$name' AND address='$add' AND city='$city' AND state='$state' AND mobile='$mobile' "; 
		$result = $db_conn->query($sql);
		if(numr($result) > 0){ return true; }
		else{ return false; }
		$db_conn->close();
    }

    function cbal(){
    	class fPDatabase extends SQLite3{
	    	function __construct(){
				$database_file = "data/AIACACC.sqlite3";
	    		$this->open($database_file);
	    	}
	    }
	    $db_conn = new fPDatabase();
    	$sql = "SELECT balance FROM cash ORDER BY date DESC LIMIT 1";
		$result = $db_conn->query($sql);
		if(numr($result) > 0){
			while($row=$result->fetchArray(SQLITE3_ASSOC)){
				$cbal = $row['balance'];
			}
		}
		else{
			$cbal = 0;
		}
		return $cbal;
    }

    function bbal(){
    	class fPDatabase extends SQLite3{
	    	function __construct(){
				$database_file = "data/AIACACC.sqlite3";
	    		$this->open($database_file);
	    	}
	    }
	    $db_conn = new fPDatabase();
    	$sql = "SELECT balance FROM bank ORDER BY date DESC LIMIT 1";
		$result = $db_conn->query($sql);
		if(numr($result) > 0){
			while($row=$result->fetchArray(SQLITE3_ASSOC)){
				$bbal = $row['balance'];
			}
		}
		else{
			$bbal = 0;
		}
		return $bbal;
    }

    date_default_timezone_set("Asia/Calcutta");
    $key = $_REQUEST['key'];
    switch($key){
	   
	  	case "generate-receipt-number":
			$sql = "SELECT id FROM donations ORDER BY id DESC LIMIT 1";
			$result = $db->query($sql);
			if(numr( $result ) > 0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$id = $row['id']+1;
				}
			}
			else{
				$id = 1;
			}
			echo $id;
			$db->close();
	   	break;
	   
		case 'generate-voucher-number':
			$sql = "SELECT id FROM vouchers ORDER BY id DESC LIMIT 1";
			$result = $db->query($sql);
			if(numr( $result ) > 0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$id = $row['id']+1;
				}
			}
			else{
				$id = 1;
			}
			echo $id;
			$db->close();
		break;

	   	case "find-existing-entries":
	   		$val = $_REQUEST['val'];
	   		$sql = "SELECT * FROM personal_info WHERE name LIKE '%$val%'";
	   		$result = $db->query($sql); 
	   		if(numr( $result ) > 0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$id = $row['id'];
					$name = $row['name'];
					$add = $row['address']; 
					$city = $row['city']; 
					$state = $row['state']; 
					$mobile = $row['mobile']; ?>
					<tr class="single-name-row" id="<?php echo $id; ?>">
						<td class="name-res"><?php echo $name; ?></td>
						<td class="mobile-res"><?php echo $mobile; ?></td>
						<td style="display: none;" class="add-res"><?php echo $add; ?></td>
						<td style="display: none;" class="city-res"><?php echo $city; ?></td>
						<td style="display: none;" class="state-res"><?php echo $state; ?></td>
					</tr> <?php
				} ?>
				<script type="text/javascript">
					$(".single-name-row").click(function(){
						var id = $(this).attr('id');
						var name = $(this).find(".name-res").text();
						var mobile = $(this).find(".mobile-res").text();
						var add = $(this).find(".add-res").text();
						var city = $(this).find(".city-res").text();
						var state = $(this).find(".state-res").text();
						$("#name").val(name).trigger('keyup');
						$("#mob").val(mobile).trigger('keyup');
						$("#add").val(add).trigger('keyup');
						$("#city").val(city).trigger('keyup');
						$("#state").val(state).trigger('keyup');
						$("#person-id").val(id);
						$(".name-resp-cont").hide();
					});
					$("body").click(function(){
						$(".name-resp-cont").hide();
					});
				</script> <?php
			}
	   	break;

       case "Insert-records-to-db":
			$bill_date = date("Y-m-d", strtotime($_REQUEST['billDate']));
			$bill_time = date("H:i:s");
			$bill_date_time = $bill_date." ".$bill_time;
			$pid = $_REQUEST['personId'];
			$name = $_REQUEST['name'];
			$add = $_REQUEST['add'];
			$city = $_REQUEST['city'];
			$state = $_REQUEST['state'];
			$mobile = $_REQUEST['mob'];
			$rank = $_REQUEST['rank'];
			$upto = $_REQUEST['upto'];
			$mem_fee = $_REQUEST['memFee'];
			$amount = $_REQUEST['amount'];
			$total_amt = $amount +$mem_fee;
			$pno = $_REQUEST['pno'];
			$pdate = $_REQUEST['pdate'];
			$mop = $_REQUEST['mop'];
			$img_name = $_FILES["file"]["name"];
			$target_dir = "uploads/members/";
			$target_file = $target_dir . basename($img_name);
			($mem_fee != 0) ? $member = 1 : $member = 0; //decide the value for member column in table 'personal_info'
			$person_id = "";
			$snAbr = explode(" ",$name);

			foreach($snAbr as $val){
				$person_id .= substr($val,0,1);
			}
			$person_id .= date("dmysi");
			$tid = "";
			$snAbr = explode(" ",$name);
			foreach($snAbr as $val){
				$tid .= substr($val,0,1);
			}
			$tid .= "T".date("dmysi"); 
			

			if($pdate == "0000-00-00" || $pdate == "1970-01-01" || $pdate == "" || empty($pdate)) $pdate = 0; 
			
			

			if(find_person( $name, $add, $city, $state, $mobile ) == false){
				$sql_person = "INSERT INTO personal_info (id, name, address, city, state, mobile, member, image) VALUES('$person_id','$name','$add','$city','$state','$mobile','$member','$target_file')";
				$sql_mem_info = "INSERT INTO membership_info (person_id, join_date, rank, expiration, mem_fee, transaction_id) VALUES('$person_id','$bill_date','$rank','$upto','$mem_fee','$tid') ";
			}
			else{
				if($mem_fee != 0){
					$sql_person = "UPDATE personal_info SET member = '1' WHERE id= '$pid' ";
					$sql_mem_info = "UPDATE membership_info SET join_date = '$bill_date', rank = '$rank', expiration = '$upto', mem_fee = '$mem_fee', transaction_id = '$tid' WHERE person_id = '$pid' ";
				}
				$person_id = $pid;
			}
			$sql_donation = "INSERT INTO donations(date, person_id, amount, pno, mop, pdate, transaction_id, rank, expiration, mem_fee) VALUES('$bill_date','$person_id','$amount','$pno','$mop','$pdate','$tid','$rank','$upto','$mem_fee') ";
			if($mop == "Cash"){
				$sql_cbal = "SELECT balance FROM cash ORDER BY date DESC LIMIT 1";
				$result_cbal = $db->query($sql_cbal);
				if(numr($result_cbal) > 0){
					while($row_cbal=$result_cbal->fetchArray(SQLITE3_ASSOC)){
						$cbal = $row_cbal['balance']+$amount+$mem_fee;
					}
				}
				else{
					$cbal = $amount+$mem_fee;
				}
				$remarks = "Donation From ".$name."(".$person_id.") by ".$mop;
				$sql_trans = "INSERT INTO cash (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$person_id','$bill_date_time','$remarks','in','$total_amt','$cbal') ";
			}
			else{
				$sql_bbal = "SELECT balance FROM bank ORDER BY date DESC LIMIT 1";
				$result_bbal = $db->query($sql_bbal);
				if(numr($result_bbal) > 0){
					while($row_bbal=$result_bbal->fetchArray(SQLITE3_ASSOC)){
						$bbal = $row_bbal['balance']+$amount+$mem_fee;
					}
				}
				else{
					$bbal = $amount + $mem_fee;
				}
				$remarks = "Donation From ".$name."(".$person_id.") by ".$mop."(".$pno.")";
				$sql_trans = "INSERT INTO bank (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$person_id','$bill_date_time','$remarks','in','$total_amt','$bbal') ";
			}
			try{
		   		$db->query('BEGIN;');
		   		if($db->query($sql_person) == false || $db->query($sql_donation) == false || $db->query($sql_mem_info) == false || $db->query($sql_trans) == false || move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/members/' . $_FILES['file']['name']) == false){
		   			throw new Exception($db->lastErrorMsg());
		   		}
		   		else{ ?>
		   			<div class='alert alert-success'>Record Successfully Entered</div> <?php
		   		}
		   		
		   		$db->query('COMMIT;');
		   	}
		   	catch(Exception $e){
		   		$db->query('ROLLBACK;');
		   		echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   	}
	   break;
	   
	   case "insert-voucher-details":
	   		$v_date = $_REQUEST['date'];
	   		$bill_date = date("Y-m-d", strtotime($v_date));
			$bill_time = date("H:i:s");
			$bill_date_time = $bill_date." ".$bill_time;
	   		$pid = $_REQUEST['personId'];
	   		$name = $_REQUEST['name'];
	   		$mobile = $_REQUEST['mob'];
	   		$add = $_REQUEST['add'];
	   		$city = $_REQUEST['city'];
	   		$state = $_REQUEST['state'];
	   		$chnum = $_REQUEST['chnum'];
	   		$pdesc = $_REQUEST['pdesc'];
	   		$amount = $_REQUEST['amount'];
	   		$mop = $_REQUEST['mop'];
	   		$person_id = "";
			$snAbr = explode(" ",$name);
			foreach($snAbr as $val){
				$person_id .= substr($val,0,1);
			}
			$tid = "";
			$snAbr = explode(" ",$name);
			foreach($snAbr as $val){
				$tid .= substr($val,0,1);
			}
			$tid .= "T".date("dmysi");
			$person_id .= date("dmysi");
	   		if(find_person( $name, $add, $city, $state, $mobile ) == false){
	   			$sql_voucher = "INSERT INTO vouchers (person_id, date, amount, description, mop, transaction_id, chnum) VALUES('$person_id','$v_date','$amount','$pdesc','$mop','$tid','$chnum')";
	   			$sql_personal = "INSERT INTO personal_info (id, name, address, city, state, mobile, member) VALUES('$person_id','$name','$add','$city','$state','$mobile','0')";
	   			$sql_mem_info = "INSERT INTO membership_info (person_id, join_date, transaction_id) VALUES('$person_id','$v_date','$tid')";
	   			if( $mop == "Cash" ){
	   				$sql_cbal = "SELECT balance FROM cash ORDER BY date DESC LIMIT 1";
					$result_cbal = $db->query($sql_cbal);
					if(numr($result_cbal) > 0){
						while($row_cbal=$result_cbal->fetchArray(SQLITE3_ASSOC)){
							$cbal = $row_cbal['balance']-$amount;
						}
					}
					else{
						$cbal = 0-$amount;
					}
					$remarks = "Payment to ".$name."(".$person_id.") by ".$mop;
					$sql_trans = "INSERT INTO cash (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$person_id','$bill_date_time','$remarks','out','$amount','$cbal') ";
		   		}
		   		else{
		   			$sql_bbal = "SELECT balance FROM bank ORDER BY date DESC LIMIT 1";
					$result_bbal = $db->query($sql_bbal);
					if(numr($result_bbal) > 0){
						while($row_bbal=$result_bbal->fetchArray(SQLITE3_ASSOC)){
							$bbal = $row_bbal['balance']-$amount;
						}
					}
					else{
						$bbal = 0-$amount;
					}
					$remarks = "Payment to ".$name."(".$person_id.") by ".$mop."(".$chnum.")";
					$sql_trans = "INSERT INTO bank (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$person_id','$bill_date_time','$remarks','out','$amount','$bbal') ";
		   		}
		   		try{
		   			$db->query('BEGIN;');

		   			if($db->query($sql_voucher) == false || $db->query($sql_personal) == false || $db->query($sql_mem_info) == false || $db->query($sql_trans) == false){
		   				throw new Exception($db->lastErrorMsg());
		   			}
		   			else{
		   				echo "<div class='alert alert-success'>Record Successfully Entered</div>";
		   			}
		   			
		   			$db->query('COMMIT;');
		   		}
		   		catch(Exception $e){
		   			$db->query('ROLLBACK;');
		   			echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   		}

	   		}
	   		else{
	   			if( $mop == "Cash" ){
	   				$sql_cbal = "SELECT balance FROM cash ORDER BY date DESC LIMIT 1";
					$result_cbal = $db->query($sql_cbal);
					if(numr($result_cbal) > 0){
						while($row_cbal=$result_cbal->fetchArray(SQLITE3_ASSOC)){
							$cbal = $row_cbal['balance']-$amount;
						}
					}
					else{
						$cbal = 0-$amount;
					}
					$remarks = "Payment to ".$name."(".$pid.") by ".$mop."(".$chnum.")";
					$sql_trans = "INSERT INTO cash (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$pid','$bill_date_time','$remarks','out','$amount','$cbal') ";
		   		}
		   		else{
		   			$sql_bbal = "SELECT balance FROM bank ORDER BY date DESC LIMIT 1";
					$result_bbal = $db->query($sql_bbal);
					if(numr($result_bbal) > 0){
						while($row_bbal=$result_bbal->fetchArray(SQLITE3_ASSOC)){
							$bbal = $row_bbal['balance']-$amount;
						}
					}
					else{
						$bbal = 0-$amount;
					}
					$remarks = "Payment to ".$name."(".$pid.") by ".$mop."(".$chnum.")";
					$sql_trans = "INSERT INTO bank (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$pid','$bill_date_time','$remarks','out','$amount','$bbal') ";
		   		}
		   		$sql_vouchers = "INSERT INTO vouchers (person_id, date, amount, description, mop, transaction_id, chnum) VALUES('$pid','$v_date','$amount','$pdesc','$mop','$tid','$chnum')";
		   		try{
		   			$db->query('BEGIN;');

		   			if($db->query($sql_vouchers) == false || $db->query($sql_trans) == false){
		   				throw new Exception($db->lastErrorMsg());
		   			}
		   			else{
		   				echo "<div class='alert alert-success'>Record Successfully Entered</div>";
		   			}
		   			
		   			$db->query('COMMIT;');
		   		}
		   		catch(Exception $e){
		   			$db->query('ROLLBACK;');
		   			echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   		}
	   		}
	   break;

	   case "Load-alla-bills":
			$sql = "SELECT *, donations.id AS rec_id FROM donations JOIN personal_info ON (personal_info.id = donations.person_id) JOIN membership_info ON (membership_info.person_id = donations.person_id) WHERE donations.amount != 0";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Reciept No.</td>
				<td>Date</td>
				<td>Name</td>
				<td>Address</td>
				<td>State</td>
				<td>Mobile</td>
				<td>Rank</td>
				<td>Up To</td>
				<td>Amount</td>
				<td>PNo.</td>
				<td>Date</td>
			</tr> <?php
			if(numr($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$id = $row['rec_id'];
					$name = $row['name'];
					$date = date("d/m/Y", strtotime($row['date']));
					$add = $row['address'];
					$state = $row['state'];
					$mob = $row['mob'];
					$rank = $row['rank'];
					$upto = date("d/m/Y", strtotime($row['upto']));
					$mem_fee = $row['mem_fee'];
					$amount = $row['amount'];
					$pno = $row['pno'];
					if($row['pdate'] != 0) $pdate = date("d/m/Y", strtotime($row['pdate'])); else $pdate = 0; ?>
					<tr class="single-row" id="<?php echo $id; ?>">
						<td><?php echo $id; ?></td>
						<td><?php echo $date ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $add; ?></td>
						<td><?php echo $state; ?></td>
						<td><?php echo $mob; ?></td>
						<td><?php echo $rank; ?></td>
						<td><?php echo $upto; ?></td>
						<td>&#8377;<?php echo number_format($amount+$mem_fee); ?>/-</td>
						<td><?php echo $pno; ?></td>
						<td><?php echo $pdate; ?></td>
					</tr> <?php
				} ?>
				<script>
					$(".single-row").click(function(){
						$.post("ajax-req-handler.php", {
							key: "fetch-single-bill-details",
							id: $(this).attr('id')
						}, function(resp){
							$.confirm({
								title: "Receipt Details", 
								type: 'green',
								typeAnimated: true,
								columnClass: 'col-md-10 col-md-offset-1',
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
				</script> <?php
			}
	   break;
	   
	   case "Load-all-vouchers":
			$sql = "SELECT *, vouchers.id AS rec_id FROM vouchers JOIN personal_info ON (personal_info.id = vouchers.person_id)";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Voucher No.</td>
				<td>Date</td>
				<td>Name</td>
				<td>Mobile</td>
				<td>Amount</td>
				<td>PNo.</td>
			</tr> <?php
			if(numr($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$id = $row['rec_id'];
					$name = $row['name'];
					$date = date("d/m/Y", strtotime($row['date']));
					$mob = $row['mobile'];
					$amount = $row['amount'];
					$pno = $row['chnum'];
					$pdate = date("d/m/Y", strtotime($row['pdate'])); ?>
					<tr class="single-row" id="<?php echo $id; ?>">
						<td><?php echo $id; ?></td>
						<td><?php echo $date ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $mob; ?></td>
						<td><i class="fa fa-inr"></i><?php echo number_format($amount); ?>/-</td>
						<td><?php echo $pno; ?></td>
					</tr> <?php
				} ?>
				<script>
					$(".single-row").click(function(){
						$.post("ajax-req-handler.php", {
							key: "fetch-single-voucher-details",
							id: $(this).attr('id')
						}, function(resp){
							$.confirm({
								title: "Voucher Details", 
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
				</script> <?php
				$db = null;
			}
	   break;
//fetch single voucher details and create the functionality for updation
	   case 'fetch-single-voucher-details':
	   		$id = $_REQUEST['id'];
			$sql = "SELECT *, vouchers.id AS rec_id, personal_info.id AS pid FROM vouchers JOIN personal_info ON (personal_info.id = vouchers.person_id) WHERE vouchers.id = '$id' ";
			$result = $db->query($sql); ?>
			<div class="success-msg"></div>
			<table class='voucher-tab'> 
				<div style="margin-bottom:10px" class="btn-group btn-group-justified">
					<div class="btn-group reprint-group">
						<button type="button" class="btn btn-primary reprint">Reprint</button>
					</div>
					<div class="btn-group attach-group">
						<button type="button" class="btn btn-success attach">Attach Signed Voucher</button>
					</div>
					<div class="btn-group edit-group">
						<button type="button" class="btn btn-info edit">Edit</button>
					</div>
					<div class="btn-group done-group">
						<button type="button" class="btn btn-success done">Done</button>
					</div>
					<div class="btn-group delete-group">
						<button type="button" class="btn btn-danger delete">Delete</button>
					</div>
				</div>	<?php
			if(count($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){ 
					$id = $row['rec_id'];
					$pid = $row['pid'];
					$date = date("d.m/Y", strtotime($row['date']));
					$mop = $row['mop'];
					$name = $row['name'];
					$mob = $row['mobile'];
					$address = $row['address'];
					$city = $row['city'];
					$state = $row['state'];
					$chnum = $row['chnum'];
					$desc = $row['description'];
					$amount = $row['amount'];
					$img = $row['signed_img']; ?>
					<tr>
						<td>Date:</td>
						<td><input type="text" value="<?php echo $date; ?>" disabled></td>
						<td>Mode Of Payment:</td>
						<td><input type="text" value="<?php echo $mop; ?>" disabled></td>
						<td>Voucher Id:</td>
						<td><input type="text" value="<?php echo $id; ?>" disabled></td>
					</tr>
					<tr>
						<td>Client Name:</td>
						<td><input type="text" id="vu-name" value="<?php echo $name; ?>" readonly></td>
						<td>Mobile:</td>
						<td><input type="text" id="vu-mob" value="<?php echo $mob; ?>" readonly></td>
						<td>Address:</td>
						<td><input type="text" id="vu-add" value="<?php echo $address; ?>" readonly></td>
					</tr>
					<tr>
						<td>City:</td>
						<td><input type="text" id="vu-city" value="<?php echo $city; ?>" readonly></td>
						<td>State:</td>
						<td><input type="text" id="vu-state" value="<?php echo $state; ?>" readonly></td>
						<td>Cheque Number</td>
						<td><input type="text" id="vu-cheque" value="<?php echo $chnum; ?>" <?php (!empty($chnum)) ? "readonly" : "disabled"; ?> ></td>
					</tr>
					<tr>
						<td>Payment Description:</td>
						<td colspan="3"><textarea type="text" id="vu-desc" cols="77" readonly><?php echo $desc; ?></textarea></td>
						<td>Amount:</td>
						<td><input type="text" id="vu-amt" value="<?php echo number_format($amount); ?>" disabled></td>
					</tr> <?php
				}
			} ?>
			</table>
			<div class="signed-img"><?php if(!empty($img)) echo "<img src='".$img."'>"; ?></div>
			<script type="text/javascript">
				$(".done-group").hide();
				$(".reprint").click(function(){
					if( $('.signed-img').is(':empty') ){
						alert("Signed image of this Voucher is not available!");
					}
					else{
						$.print('.signed-img');
					}
				});
				$(".edit").click(function(){
					$(".voucher-tab input, .voucher-tab textarea").removeAttr('readonly');
					$(".done-group").show();
					$(".reprint-group").hide();
					$(".attach-group").hide();
					$(".edit-group").hide();
					$(".delete-group").hide();
				});
				$(".done").click(function(){
					$(".voucher-tab input, .voucher-tab textarea").attr("readonly", true);
					$(".done-group").hide();
					$(".reprint-group").show();
					$(".attach-group").show();
					$(".edit-group").show();
					$(".delete-group").show();
					$.post("ajax-req-handler.php", {
						key: "update-voucher-details",
						id: "<?php echo $id; ?>",
						pid: "<?php echo $pid; ?>",
						name: $("#vu-name").val(),
						mob: $("#vu-mob").val(),
						address: $("#vu-add").val(),
						city: $("#vu-city").val(),
						state: $("#vu-state").val(),
						chnum: $("#vu-cheque").val(),
						pdesc: $(".voucher-tab textarea").val()
					}, function(data){
						$(".success-msg").html(data);
					});
				});
				$(".attach").click(function(){
					$.post("ajax-req-handler.php", {
						key: "start-signed-voucher-upload-process",
						id: "<?php echo $id; ?>"
					}, function(resp){
						$.confirm({
							title: "Voucher Details", 
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

			</script> <?php
	   break;

	   case 'update-voucher-details':
	   		$id = $_REQUEST['id'];
	   		$pid = $_REQUEST['pid'];
	   		$name = $_REQUEST['name'];
	   		$mobile = $_REQUEST['mob'];
	   		$address = $_REQUEST['address'];
	   		$city = $_REQUEST['city'];
	   		$state = $_REQUEST['state'];
	   		$chnum = $_REQUEST['chnum'];
	   		$pdesc = $_REQUEST['pdesc'];
	   		$sql_personal = "UPDATE personal_info SET name='$name', address='$address', city='$city', state='$state', mobile='$mobile' WHERE id='$pid' ";
	   		$sql_vouchers = "UPDATE vouchers SET chnum='$chnum', description='$pdesc' WHERE id='$id' ";
	   		try{
	   			$db->query("BEGIN;");

	   			if($db->query($sql_personal)==false || $db->query($sql_vouchers)==false ){
	   				throw new Exception($db->lastErrorMsg());
	   			}
	   			else{
	   				echo "<div class='alert alert-success'>Record Successfully Updated</div>";
	   			}

	   			$db->query("COMMIT;");
	   		}
	   		catch(Exception $e){
	   			$db->query("ROLLBACK;");
	   			echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
	   		}
	   break;
//fetch single voucher details and create the functionality for updation ends here//

//upload scanned signed voucher
	   case 'start-signed-voucher-upload-process':
	   		$id = $_REQUEST['id']; ?>
	   		<form>
	   			<input type="file" class="form-control" id="voucher-img-inp">
	   		</form>
	   		<button class="btn btn-primary upload-voucher-btn" type="button">Upload</button>
	   		<script type="text/javascript">
	   			$(".upload-voucher-btn").click(function(){
	   				var key = "submit-data-for-voucher-siged-image";
	   				var id = "<?php echo $id; ?>";
				   	var file_data = $('#voucher-img-inp').prop('files')[0];   
				    var form_data = new FormData(); 
	   				form_data.append('file', file_data); 
	   				form_data.append('key', key); 
	   				form_data.append('id', id); 
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
								title: 'Action Completed!',
								content: data,
								buttons: {
									OK: function () {
										location.reload();	
									}
								}
							});
				        }
				    });
				});
	   		</script> <?php
	   break;

	   case 'submit-data-for-voucher-siged-image':
	   		$id = $_REQUEST['id'];
	   		$img_to_del_sql = "SELECT signed_img FROM vouchers WHERE id = '$id' ";
	   		$result = $db->query($img_to_del_sql);
			if(count($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
	   				$img = $row['signed_img'];
				}
			} 
			$sql_del_img = "UPDATE vouchers SET signed_img = '' WHERE id='$id' AND signed_img = '$img' ";
	   		$img_name = $_FILES["file"]["name"];
	   		$target_dir = "uploads/vouchers/";
	   		$target_file = $target_dir . basename($img_name);
	   		$sql = "UPDATE vouchers SET signed_img = '$target_file' WHERE id='$id' ";
	   		try{
		   		$db->query('BEGIN;');

		   		if($db->query($sql_del_img) == false || $db->query($sql) == false || move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $_FILES['file']['name']) == false){
		   			throw new Exception($db->lastErrorMsg());
		   		}
		   		else{
		   			if(!empty($img)) unlink($img);
		   			echo "<div class='alert alert-success'>Record Successfully Entered</div>";
		   		}
		   			
		   		$db->query('COMMIT;');
		   	}
		   	catch(Exception $e){
		   		$db->query('ROLLBACK;');
		   		unlink($target_file);
		   		echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   	}
	   break;
//upload scanned signed voucher ends Here//

//fetch single Bill details and add functionaity for updation
	   case "fetch-single-bill-details":
			$id = $_REQUEST['id'];
			$sql = "SELECT *, donations.id AS rec_id FROM donations JOIN personal_info ON (personal_info.id = donations.person_id) JOIN membership_info ON (membership_info.person_id = donations.person_id) WHERE donations.amount != 0 AND donations.id='$id'";
			$result = $db->query($sql);
			if(count($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$pid = $row['person_id'];
					$tid = $row['transaction_id'];
					$id = $row['rec_id'];
					$date = date("d/m/Y", strtotime($row['date']));
					$name = $row['name'];
					$add = $row['address'];
					$city = $row['city'];
					$state = $row['state'];
					$mob = $row['mobile'];
					$rank = $row['rank'];
					$upto = date("d/m/Y", strtotime($row['expiration']));
					$mem_fee = $row['mem_fee'];
					$amount = $row['amount'];
					$pno = $row['pno'];
					$mop = $row['mop'];
					if($row['pdate'] != 0) $pdate = date("d/m/Y", strtotime($row['pdate'])); else $pdate = 0;  ?>
					<div class="success-msg"></div>
					<div style="margin-bottom:10px" class="btn-group btn-group-justified">
						<div class="btn-group reprint-group">
							<button type="button" class="btn btn-primary reprint">Reprint</button>
						</div>
						<div class="btn-group edit-group">
							<button type="button" class="btn btn-info edit">Edit</button>
						</div>
						<div class="btn-group done-group">
							<button type="button" class="btn btn-success done">Done</button>
						</div>
						<div class="btn-group delete-group">
							<button type="button" class="btn btn-danger delete">Delete</button>
						</div>
					</div>
					<div class="prev prev-copy">

						<div style="float:left;width:85%;text-align:center">
							<p style="float:left;width:20%;">PAN No.: AAFTA1692P</p>
							<p style="float:right;width:80%;text-align:center">GOVERNMENT REGD. CELL NCT DELHI (INDIA)&nbsp;&nbsp;<strong> A REGD. NGO UNDER INDIAN T.ACT.-1882 </strong></p>
							<h2 style="margin-bottom: 0;"><strong><b class="main-heading">ALL INDIA ANTI CORRUPTION ANTI CRIME CELL</b></strong><sup>&#174;</sup></h2>
							<p style="text-align:center;font-size: 12px;"><strong>NATIONAL ADMINISTRATIVE OFFICE: 3, CHHABRA COMPLEX, NEAR THANA QUTUBSHER, <br/>
							AMBALA ROAD, SAHARANPUR (U.P), (NATIONAL PRESIDENT: G.S. BABBAR, 08923878250, 09358327726)<br/>
							Website: aiacacc.com, Email: aiacacc@gmail.com
							</strong></p>
						</div>
							
						<div class="rec" style="float:right;width:15%;">
							<p style="margin:5px 0 0 0;">RECEIPT No.</p>
							<p><strong class="rec-no">
								<?php echo $id; ?>
							</strong></p>
							<div class="date-label">&#9734;&#9734;&#9734;DATE&#9734;&#9734;&#9734;</div>
							<div style="text-align:center;border-bottom:2px dotted;font-size:18px;">
								<strong id="date">
									<?php echo $date; ?>
								</strong>
							</div>
						</div>
						
						<div style="margin-top:20px;" class="blanks b1">
							<div>Recieved With Thanks From </div> &nbsp; <input type="text" readonly value="<?php echo $name; ?>" class="name uname"> 
						</div>
					
						<div class="blanks b2">
							Address &nbsp;&nbsp; <input type="text" maxlength="65" readonly value="<?php echo substr($add,0,65); ?>" class="add uadd"> 
						</div>
							
						<div class="blanks b3">
							<input type="text" readonly value="<?php echo substr($add, 65) ?>" class="add2 uadd2"> <div class="d3">City</div><input type="text" readonly value="<?php echo $city; ?>"class="s3 city ucity"> <div>State</div> <input type="text" readonly value="(<?php echo $state; ?>)" class="state ustate"> <div class="d2">Mob</div><input type="text" readonly value="<?php echo $mob; ?>" class="s2 mob umob">
						</div>
							
						<div class="blanks b4">
							<div>Rank</div> &nbsp; <input type="text" readonly value="<?php echo $rank ?>" class="rank urank">  <div class="d2">Up To</div> &nbsp; <input type="text" readonly value="<?php echo $upto; ?>" class="s2 uupto upto"> 
						</div>
							
						<div class="blanks b5">
							<div>Membership Fee </div> <strong class="mem-fee "><?php echo "<i class='fa fa-inr'></i>".number_format($mem_fee)."/-"; ?></strong> <strong readonly class="s2 mem-fee-words umem-fee-words"></strong><div class="d2">& Voluntarily Donated </div>
						</div>
							
						<div class="blanks b6">
							<div>Rupees</div> <strong class="amount uamount s2"><?php echo "<i class='fa fa-inr'></i>".number_format(abs($amount))."/-"; ?></strong> <strong type="text" readonly  class="amt-words uamt-words"></strong> <div class="d2">Total </div>
						</div>
							
						<div class="blanks b7">
							 &nbsp;<div>Amount</div> <strong class="total"><?php echo "<i class='fa fa-inr'></i>".number_format($amount+$mem_fee)."/-"; ?></strong> <div class="d2">&nbsp;on Account of "ALL INDIA ANTI CORRUPTION ANTI CRIME CELL"</div>
						</div>
							
						<div class="blanks b8">
							<div> By </div> <div class="mop"><?php echo $mop; ?></div> &nbsp; <input type="text" readonly value="<?php echo $pno; ?>" class="pno upno"> <div class="d2">Dated</div> <input type="text" readonly value="<?php echo $pdate; ?>" class="date udate s2">
						</div>
							
						<table style="margin: 20px 0 0 0" class="bill-bottom ">
							<tr>
								<td  class="amt">
									<div class="rupee-sym" ><i class="fa fa-inr"></i></div>
									<div style="min-width:100px" class="rupee-amt"><?php echo $mem_fee+$amount."/-"; ?></div>
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
						<script>
							var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
							var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];
							function inWords (num) {
							    if ((num = num.toString()).length > 9) return 'overflow';
							    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
							    if (!n) return; var str = '';
							    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
							    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
							    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
							    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
							    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
							    return str;
							}
							function cleanArray(actual) {
							  	var newArray = new Array();
							  	for (var i = 0; i < actual.length; i++) {
							    	if (actual[i]) {
							     	 newArray.push(actual[i]);
							    	}
							  	}
							  	return newArray;
							}
							function capitalLetter(str) {
							    str = cleanArray(str.split(" "));
							    for (var i = 0, x = str.length; i < x; i++) {
							        str[i] = str[i][0].toUpperCase() + str[i].substr(1);
							    }
							    return str.join(" ");
							}

							function convertDate(inputFormat) {
								function pad(s) { return (s < 10) ? '0' + s : s; }
							 	var d = new Date(inputFormat);
							  	return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
							}
							$(".umem-fee-words").text(capitalLetter(inWords($(".mem-fee").text().replace(/[^a-z0-9.\s]/gi, '').replace(/[_\s]/g, ''))+" Rupees"));
							$(".uamt-words").text(capitalLetter(inWords($(".uamount").text().replace(/[^a-z0-9.\s]/gi, '').replace(/[_\s]/g, ''))+" Rupees"));
							
							$(".done-group").hide();
							$(".utotal").keyup(function(){ $(".rupee-amt").text($(this).val()); });
							$(".reprint").click(function(){
							 	//$.print(".prev-copy"); 
							 	$(".prev-copy").print({
								globalStyles: true,
								mediaPrint: false,
								stylesheet: null,
								noPrintSelector: ".no-print",
								iframe: true,
								append: $(".prev-copy"),
								prepend: null,
								manuallyCopyFormValues: true,
								deferred: $.Deferred(),
								timeout: 750,
								title: null,
								doctype: '<!doctype html>'
							});
							});
							$(".edit").click(function(){
								$(".edit-group").hide();
								$(".reprint-group").hide();
								$(".delete-group").hide();
								$(".done-group").show();
								$(".prev input").removeAttr("readonly");
							});
							console.log($(".udate").val());
							console.log($(".uupto").val());
							$(".done").click(function(){
								$(".edit-group").show();
								$(".reprint-group").show();
								$(".delete-group").show();
								$(".done-group").hide();
								$(".prev input").attr("readonly", true);
								$.post("ajax-req-handler.php", {
									key: "update-bill",
									id: '<?php echo $id; ?>',
									pid: '<?php echo $pid; ?>',
									tid: '<?php echo $tid; ?>',
									name: $(".uname").val(),
									add: $(".uadd").val()+$(".uadd2").val(),
									city: $(".ucity").val(),
									state: $(".ustate").val().replace(/[^a-z0-9.\s]/gi, '').replace(/[_\s]/g, ''),
									mob: $(".umob").val(),
									rank: $(".urank").val(),
									exp: $(".uupto").val(),
									/*memFee: $(".umem-fee").val(),
									amount: $(".uamount").val(),*/
									pno: $(".upno").val(),
									pdate: $(".udate").val()
								}, function(data){
									$(".success-msg").html(data);
								});
							});
							$('.delete').click(function(){
								$.confirm({
									title: 'Confirm!',
									content: 'Are you sure you want to Delete This Bill?',
									buttons: {
										confirm: function () {
											$.post("ajax-req-handler.php", {key:"delete-bill", id:'<?php echo $id; ?>'}, function(data){
												$.confirm({
													title: 'Action Completed!',
													content: data,
													buttons: {
														OK: function () {
															location.reload();	
														}
													}
												});
											});
										},
										cancel: function () {
											
										}
									}
								});
								
							});
						</script>
					</div> <?php
				}
			}				
	   break;

	   case "update-bill":
	   		$pid = $_REQUEST['pid'];
			$id = $_REQUEST['id'];
			$tid = $_REQUEST['tid'];
			$name = $_REQUEST['name'];
			$add = $_REQUEST['add'];
			$city = $_REQUEST['city'];
			$state = $_REQUEST['state'];
			$mob = $_REQUEST['mob'];
			$rank = $_REQUEST['rank'];
			//$mem_fee = $_REQUEST['memFee'];
			//$amt = $_REQUEST['amount'];
			$pno = $_REQUEST['pno'];
			$pdate = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['pdate'])));
			$upto =  date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['exp'])));
			echo $pdate."<br/>";
			echo $upto;
			$sql_personal = "UPDATE personal_info SET name='$name', address='$add', city='$city', state='$state', mobile='$mob' WHERE id='$pid' ";
			$sql_membership = "UPDATE membership_info SET rank='$rank', expiration='$upto' WHERE person_id='$pid' ";
			$sql_donation = "UPDATE donations SET pno='$pno', pdate='$pdate' WHERE transaction_id='$tid' ";
			try{
				$db->query('BEGIN;');
				if($db->query($sql_personal) == false || $db->query($sql_membership) == false || $db->query($sql_donation) == false)
					throw new Exception($db->lastErrorMsg());
				else
					echo "<div class='alert alert-success'>Record Successfully Updated</div>";
			$db->query('COMMIT;');
			}
			catch(Exception $e){
				$db->query('ROLLBACK');
				echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";	
			}
	   break;
//fetch single Bill details and add functionaity for updation ends here//

	   case "delete-bill":
			$id = $_REQUEST['id'];
			$sql = "DELETE FROM bill_details WHERE id='$id'";
			if($db->exec($sql)){
				echo "<div class='alert alert-success'>Bill Successfully Deleted</div>";
			}
			else{
				echo "<div class='alert alert-danger'>ERROR!</div>";
			}
	   break;
	   
	   case "search-bill":
			$value = $_REQUEST['val'];
			$sql = "SELECT *, c.id AS rec_id FROM donations c JOIN personal_info a ON (a.id = c.person_id) JOIN membership_info b ON (b.person_id = c.person_id) WHERE c.amount != 0 AND a.member = 1 AND (a.id LIKE '%$value%' OR a.name LIKE '%$value%' OR a.address LIKE '%$value%' OR a.state LIKE '%$value%' OR a.city LIKE '%$value%' OR b.rank LIKE '%$value%' OR a.mobile LIKE '%$value%' OR b.id LIKE '%$value%' OR c.pno LIKE '%$value%') ORDER BY b.id DESC"; ?>
			<tr class="tab-head">
				<td>Reciept No.</td>
				<td>Date</td>
				<td>Name</td>
				<td>Address</td>
				<td>State</td>
				<td>Mobile</td>
				<td>Rank</td>
				<td>Up To</td>
				<td>Total</td>
				<td>PNo.</td>
				<td>PDate</td>
			</tr> <?php
			echo $sql;
			$result = $db->query($sql);
			if(numr($result) > 0){
			    while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
					$id = $row['id'];
					$date = date("d/m/Y", strtotime($row['date']));
					$name = $row['name'];
					$add = $row['address'];
					$state = $row['state'];
					$mob = $row['mobile'];
					$rank = $row['rank'];
					$upto = date("d/m/Y", strtotime($row['expiration']));
					$mem_fee = $row['mem_fee'];
					$amount = $row['amount'];
					$pno = $row['pno'];
					$pdate = date("d/m/Y", strtotime($row['pdate'])); ?>
					<tr class="single-row" id="<?php echo $id; ?>">
						<td><?php echo $id; ?></td>
						<td><?php echo $date; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $add; ?></td>
						<td><?php echo $state; ?></td>
						<td><?php echo $mob; ?></td>
						<td><?php echo $rank; ?></td>
						<td><?php echo $upto; ?></td>
						<td style="min-width:90px;">&#8377;<?php echo number_format($amount); ?>/-</td>
						<td><?php echo $pno; ?></td>
						<td><?php echo $pdate; ?></td>
					</tr> <?php
				} 
			}
			else{
				echo "<tr><td colspan=11 class='alert alert-warning'>Oops! Nothing to show.</td></tr>";
			}	?>
				<script>
					$(".single-row").click(function(){
						$.post("ajax-req-handler.php", {
							key: "fetch-single-bill-details",
							id: $(this).attr('id')
						}, function(resp){
							$.confirm({
								title: "Product Details", 
								type: 'green',
								typeAnimated: true,
								columnClass: 'col-md-10 col-md-offset-1',
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
				</script> <?php
	   break;

	   case "search-voucher":
			$value = $_REQUEST['val'];
			$sql = "SELECT *, c.id AS rec_id FROM Vouchers c JOIN personal_info a ON (a.id = c.person_id) WHERE c.amount != 0 AND (a.id LIKE '%$value%' OR a.name LIKE '%$value%' OR a.address LIKE '%$value%' OR a.state LIKE '%$value%' OR a.city LIKE '%$value%' OR a.mobile LIKE '%$value%' OR c.chnum LIKE '%$value%') ORDER BY c.id DESC"; ?>
			<tr class="tab-head">
				<td>Voucher No.</td>
				<td>Date</td>
				<td>Name</td>
				<td>Mobile</td>
				<td>Amount</td>
				<td>Cheque Number</td>
			</tr> <?php
			$result = $db->query($sql);
			if(numr($result) > 0){
			    while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
					$id = $row['rec_id'];
					$date = date("d/m/Y", strtotime($row['date']));
					$name = $row['name'];
					$mob = $row['mobile'];
					$amount = $row['amount'];
					$pno = $row['chnum'];
					$pdate = date("d/m/Y", strtotime($row['pdate'])); ?>
					<tr class="single-row" id="<?php echo $id; ?>">
						<td><?php echo $id; ?></td>
						<td><?php echo $date; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $mob; ?></td>
						<td style="min-width:90px;">&#8377;<?php echo number_format($amount); ?>/-</td>
						<td><?php echo $pno; ?></td>
					</tr> <?php
				} 
			}
			else{
				echo "<tr><td colspan=11 class='alert alert-warning'>Oops! Nothing to show.</td></tr>";
			}	?>
				<script>
					$(".single-row").click(function(){
						$.post("ajax-req-handler.php", {
							key: "fetch-single-voucher-details",
							id: $(this).attr('id')
						}, function(resp){
							$.confirm({
								title: "Product Details", 
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
				</script> <?php
	   break;
	   
	   case "find-number-of-members":
	   		$sql = "SELECT COUNT(id) AS count FROM personal_info WHERE member = 1";
	   		$result = $db->query($sql);
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				echo $row['count'];
	   			}
	   		}
	   		else{
	   			echo 0;
	   		}
	   break;

	   case "Fetch-Cash-Balance":
	   		$sql = "SELECT balance FROM cash ORDER BY date DESC LIMIT 1";	   
	   		$result = $db->query($sql);
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				echo '<i class="fa fa-inr"></i>'.number_format($row['balance']).'/-';
	   			}
	   		}
	   		else{
	   			echo '<i class="fa fa-inr"></i>0/-';
	   		}
	   break;

	   case "Fetch-Bank-Balance":
	   		$sql = "SELECT balance FROM bank ORDER BY date DESC LIMIT 1";	   
	   		$result = $db->query($sql);
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				echo '<i class="fa fa-inr"></i>'.number_format($row['balance']).'/-';
	   			}
	   		}
	   		else{
	   			echo '<i class="fa fa-inr"></i>0/-';
	   		}
	   break;

	   case 'get-expiring-members':
	   		$sql = "SELECT a.rank, a.expiration, b.name, b.id AS pid FROM membership_info a, personal_info b WHERE (julianday(expiration) - julianday('now')) < 30 AND b.id=a.person_id AND b.member = '1' AND a.expiration > date('now') ;";
	   		$result = $db->query($sql); ?>
	   		<tr class="tab-head">
	   			<td>Name</td>
	   			<td>Rank</td>
	   			<td>Expiration Date</td>
	   		</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row['pid'];
	   				$name = $row['name'];
	   				$rank = $row['rank'];
	   				$exp = date("d/m/Y", strtotime($row['expiration'])); ?>
	   				<tr class="single-person-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $rank; ?></td>
	   					<td><?php echo $exp; if($row['expiration'] < date("Y-m-d")) echo '<button class="btn btn-danger btn-xs" style="margin: 0 5px 0 5px;" type="button">Expired</button>'; ?></td>
	   				</tr> <?php
	   				if($row['expiration'] < date("Y-m-d")){
	   					$sql_exp_mem = "UPDATE personal_info SET member=0 WHERE id='$id' AND member=1; ";
	   					if($db->query($sql_exp_mem) != true){ ?> <div class="alert alert-danger">Some Error occured while removing the expired member from members lis</div> <?php }
	   				}
	   			} ?>
	   			<script type="text/javascript">
	   				$(".single-person-row").click(function(e){
	   					e.preventDefault();
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Person Details", 
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
	   					e.stopImmediatePropagation();
	   				});
	   			</script> <?php
	   		}
	   		else{ ?>
	   			<tr>
	   				<td colspan="3" class="alert alert-warning">Nothing To Show!</td>
	   			</tr> <?php
	   		}
	   break;

		case 'get-recently-added-members':
	   		$sql = "SELECT a.rank, a.join_date, b.name, b.id FROM membership_info a, personal_info b WHERE (julianday('now') - julianday(join_date)) < 30 AND b.id=a.person_id AND b.member = 1;";
	   		$result = $db->query($sql); ?>
	   		<tr class="tab-head">
	   			<td>Name</td>
	   			<td>Rank</td>
	   			<td>Join Date</td>
	   		</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row['id'];
	   				$name = $row['name'];
	   				$rank = $row['rank'];
	   				$jdate = date("d/m/Y", strtotime($row['join_date'])); ?>
	   				<tr class="single-person-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $rank; ?></td>
	   					<td><?php echo $jdate; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<script type="text/javascript">
	   				$(".single-person-row").click(function(e){
	   					e.preventDefault();
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Person Details", 
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
	   					e.stopImmediatePropagation();
	   				});
	   			</script> <?php
	   		}
	   		else{ ?>
	   			<tr>
	   				<td colspan="3" class="alert alert-warning">Nothing To Show!</td>
	   			</tr> <?php
	   		}
	  	break;

	  	case 'get-expired-members':
	   		$sql = "SELECT *, personal_info.id AS pid FROM personal_info JOIN membership_info ON (membership_info.person_id = personal_info.id) WHERE personal_info.member=0 AND membership_info.expiration < date('now') AND membership_info.expiration != ''";
	   		$result = $db->query($sql); ?>
	   		<tr class="tab-head">
	   			<td>Name</td>
	   			<td>Rank</td>
	   			<td>Expired On</td>
	   		</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row['pid'];
	   				$name = $row['name'];
	   				$rank = $row['rank'];
	   				$exp = date("d/m/Y", strtotime($row['expiration'])); ?>
	   				<tr class="single-person-row-expd" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $rank; ?></td>
	   					<td><?php echo $exp; if($row['expiration'] < date("Y-m-d")) echo '<button class="btn btn-danger btn-xs" style="margin: 0 5px 0 5px;" type="button">Expired</button>'; ?></td>
	   				</tr> <?php
	   				if($row['expiration'] < date("Y-m-d")){
	   					$sql_exp_mem = "UPDATE personal_info SET member=0 WHERE id='$id' AND member=1; ";
	   					if($db->query($sql_exp_mem) != true){ ?> <div class="alert alert-danger">Some Error occured while removing the expired member from members lis</div> <?php }
	   				}
	   			} ?>
	   			<script type="text/javascript">
	   				$(".single-person-row-expd").click(function(e){
	   					e.preventDefault();
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Person Details", 
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
	   					e.stopImmediatePropagation();
	   				});
	   			</script> <?php
	   		}
	   		else{ ?>
	   			<tr>
	   				<td colspan="3" class="alert alert-warning">Nothing To Show!</td>
	   			</tr> <?php
	   		}
	   break;

		case 'find-total-donation':
	   		$sql = "SELECT SUM(amount) AS donation FROM donations";
	   		$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$donation = $row['donation'];
	   				echo "<i class='fa fa-inr'></i>".number_format($donation)."/-";
	   			}
	   		}
		break;

		case "find-today's-income":
			$sql = "SELECT SUM(amount) total FROM (
					    SELECT type,amount,date
					    FROM cash
					    UNION ALL
					    SELECT type,amount,date
					    FROM bank 
					) 
					WHERE type = 'in' AND SUBSTR(date,0,11) = date('now') GROUP BY SUBSTR(date,0,10)";
	   		$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$amount = $row['total'];
	   				echo "<i class='fa fa-inr'></i>".number_format($amount)."/-";
	   			}
	   		}
	   		else{
	   			echo "<i class='fa fa-inr'></i>0/-";
	   		}
		break;

		case "find-today's-expense":
			$sql = "SELECT SUM(amount) total FROM (
					    SELECT type,amount,date
					    FROM cash
					    UNION ALL
					    SELECT type,amount,date
					    FROM bank 
					) 
					WHERE type = 'out' AND SUBSTR(date,0,11) = date('now') GROUP BY SUBSTR(date,0,10)";
	   		$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$amount = $row['total'];
	   				echo "<i class='fa fa-inr'></i>".number_format($amount)."/-";
	   			}
	   		}
	   		else{
	   			echo "<i class='fa fa-inr'></i>0/-";
	   		}
		break;

		case "load-all-transactions":
			$sql = "SELECT *, 'cash' AS mop FROM cash UNION ALL SELECT *, 'bank' AS mop FROM bank ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "load-cash-transactions":
			$sql = "SELECT * FROM cash ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "load-bank-transactions":
			$sql = "SELECT * FROM bank ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "load-all-transactions-fromDate-to-now":
			$from = $_REQUEST['fromDate'];
			$sql = "SELECT *, 'cash' AS mop FROM cash WHERE SUBSTR(date,0,11) BETWEEN '$from' AND date('now') UNION ALL SELECT *, 'bank' AS mop FROM bank  WHERE SUBSTR(date,0,11) BETWEEN '$from' AND date('now') ORDER BY date DESC";
			echo $sql;
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-cash-transactions-fromDate-to-now':
			$from = $_REQUEST['fromDate'];
			$sql = "SELECT * FROM cash WHERE SUBSTR(date,0,11) BETWEEN '$from' AND date('now') ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-bank-transactions-fromDate-to-now':
			$from = $_REQUEST['fromDate'];
			$sql = "SELECT * FROM bank WHERE SUBSTR(date,0,11) BETWEEN '$from' AND date('now') ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-all-transactions-from-begining-to-toDate':
			$to = $_REQUEST['toDate'];
			$sql = "SELECT *, 'cash' AS mop FROM cash WHERE SUBSTR(date,0,11) <= '$to' UNION ALL SELECT *, 'bank' AS mop FROM bank WHERE SUBSTR(date,0,11) <= '$to' ORDER BY date DESC";
			echo $sql;
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-cash-transactions-from-begining-to-toDate':
			$to = $_REQUEST['toDate'];
			$sql = "SELECT * FROM cash WHERE SUBSTR(date,0,11) <= '$to' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-bank-transactions-from-beigning-to-toDate':
			$to = $_REQUEST['toDate'];
			$sql = "SELECT * FROM bank WHERE SUBSTR(date,0,11) <= '$to' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-all-transactions-from-fromDate-to-toDate':
			$from = $_REQUEST['fromDate'];
			$to = $_REQUEST['toDate'];
			$sql = "SELECT *, 'cash' AS mop FROM cash WHERE SUBSTR(date,0,11) BETWEEN '$from' AND '$to' UNION ALL SELECT *, 'bank' AS mop FROM bank WHERE SUBSTR(date,0,11) BETWEEN '$from' AND '$to' ORDER BY date DESC";
			echo $sql;
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-cash-transactions-from-fromDate-to-toDate':
			$from = $_REQUEST['fromDate'];
			$to = $_REQUEST['toDate'];
			$sql = "SELECT * FROM cash WHERE SUBSTR(date,0,11) BETWEEN '$from' AND '$to' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'load-bank-transactions-from-fromDate-to-toDate':
			$from = $_REQUEST['fromDate'];
			$to = $_REQUEST['toDate'];
			$sql = "SELECT * FROM bank WHERE SUBSTR(date,0,11) BETWEEN '$from' AND '$to' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'view-detailed-person-details':
			$id = $_REQUEST['id'];
			$sql = "SELECT * FROM personal_info JOIN membership_info ON (membership_info.person_id = personal_info.id) WHERE personal_info.id='$id'";
			$result = $db->query($sql); 
			if(count($result)>0){
				while($row=$result->fetchArray(SQLITE3_ASSOC)){
					$pid = $row['person_id'];
					$join_date = date("d/m/Y", strtotime($row['join_date']));
					$name = $row['name'];
					$add = $row['address'];
					$city = $row['city'];
					$state = $row['state'];
					$mob = $row['mobile'];
					$rank = $row['rank'];
					$upto = date("d/m/Y", strtotime($row['expiration']));
					$mem_fee = $row['mem_fee'];
					$image = $row['image'];
					$membership_status = $row['member']; ?>
					<div class="success-msg"></div>
					<div class="btn-group edit-group">
						<button type="button" class="btn btn-info edit">Edit</button>
					</div>
					<?php if($row['expiration'] < date("Y-m-d")){ ?>
					<div class="btn-group renew-group">
						<button type="button" class="btn btn-success renew">Renew Membership</button>
					</div>
					<?php } ?>
					<div class="btn-group done-group">
						<button type="button" class="btn btn-success done">Done</button>
					</div>

					<div class="btn-group delete-group">
						<button type="button" class="btn btn-danger delete">Delete</button>
					</div>
					<div class="row person-details">
						<div class="col-sm-8">
							<h3>Personal Info</h3>
							<table>
								<tr>
									<td>Name:</td>
									<td><input type="text" id="pd-name" value="<?php echo $name; ?>" readonly></td>
									<td>Mobile:</td>
									<td><input type="text" id="pd-mob" value="<?php echo $mob; ?>" readonly></td>
									<td rowspan="2">Address:</td>
									<td rowspan="2"><textarea type="text" id="pd-add" readonly><?php echo $add; ?></textarea></td>
								</tr>
								<tr>
									<td>City:</td>
									<td><input type="text" id="pd-city" value="<?php echo $city ?>" readonly></td>
									<td>State:</td>
									<td><input type="text" id="pd-state" value="<?php echo $state ?>" readonly></td>
								</tr>
							</table> <?php
							if($membership_status == 1){ ?>
								<h3>Membership Info</h3>
								<table>
									<tr>
										<td>Join Date:</td>
										<td><input type="text" id="pd-jd" value="<?php echo $join_date; ?>" disabled></td>
										<td>Rank</td>
										<td><input type="text" id="pd-rank" value="<?php echo $rank; ?>" readonly></td>
									</tr>
									<tr>
										<td>Exiration:</td>
										<td><input type="text" id="pd-exp" value="<?php echo $upto; ?>" disabled></td>
										<td>Membership Fee</td>
										<td><input type="text" id="pd-mem-fee" value="<?php echo $mem_fee; ?>" disabled></td>
									</tr>
								</table> <?php
							}
							else{ ?>
								<input type="hidden" id="pd-rank" value="<?php echo $rank; ?>"> <?php
								if(!empty($upto)){
									echo "<div class='alert alert-danger'><strong>".$name."</strong> has been expired on <strong>".$upto."</strong> and joined AIACACC on <strong>".$join_date."</strong> as <strong>".$rank."</strong> !</div>"; ?>
									<div class="panel-group renew-panel">
										<header class="panel panel-default">
											<div class="panel-heading">Renew Membership of <?php echo $name; ?> </div>
								                <div class="panel-body">
													<div class="panel-body">
														<div class="form-group">
															<label for="ren-exp">Expiration Date</label>
															<input type="date" class="form-control" id="ren-exp">
														</div>
														<div class="form-group">
															<label for="ren-mem-fee">Membership Fee(<i class="fa fa-inr"></i>)</label>
															<input type="text" class="form-control" id="ren-mem-fee">
														</div>
														<div class="form-group">
															<label for="ren-rank">Rank</label>
															<input type="text" class="form-control" id="ren-rank">
														</div>
														<div class="form-group">
															<label for="ren-mop">Mode Of Payment</label>
															<select class="form-control" id="ren-mop">
																<option value="">Select Mode of Payment</option>
																<option value="cash">Cash</option>
																<option value="cheque">Cheque</option>
																<option value="dd">DD</option>
																<option value="neft">NEFT</option>
																<option value="rtgs">RTGS</option>
															</select>
														</div>
														<div class="form-group ren-pno-group">
															<label for="ren-pno">Cheque/DD/RTGS/NEFT Number</label>
															<input type="text" class="form-control" id="ren-pno">
														</div>
														<input type="button" class="btn btn-basic btn-block" value="Submit" id="ren-sub-btn">
													</div>
								                </div>
										</header>
									</div> <?php
								}
								else{
									echo "<div class='alert alert-info'>".$name. " is not a member yet!</div>";
								}
								
							} ?>
						</div>
						<div class="col-sm-4">
							<div> <?php
								if(!empty($image)){ ?>
									<img src="<?php echo $image; ?>" height='200' width='400'><?php 
								}
								else{ ?>
									<img src="images/No_image.png" height='200' width='400'><?php 
								} ?>
							</div>
							<button class="btn btn-defaut btn-block change-img" type="button">Change/Upload</button>
							<form enctype="multipart/form-data" method="post"  class="upload-section">
								<input style="width: 80%;" type="file" class="img-inp">
								<button class="upld-btn btn-defaut" type="button">Upload</button>
							</form>
						</div>
						<div class="panel-group" id="accordion">
							<div class="panel panel-primary">
								<h4 class="panel-title">
									<button type="button" class="btn btn-primary btn-block view-all-trans" id="collapse-button" data-toggle="collapse" href="#all-transactions-wid"><i class="fa fa-user" aria-hidden="true"></i> View All Transactions with <?php echo $name; ?></button>
								</h4>
								<div id="all-transactions-wid" class="panel-collapse collapse">
									<div id="panel-body" class="panel-body">
										<table></table>
										<script type="text/javascript">
											$(".view-all-trans").click(function(){
												$.post("ajax-req-handler.php", {
													key: "view-all-transactions-with-a-particular-person",
													id: '<?php echo $pid; ?>'
												}, function(data){
													$("#all-transactions-wid .panel-body table").html(data);
												});
											});
										</script>
									</div>
								</div>
							</div>
			    		</div>
					</div> 
					<script type="text/javascript">
						$(".done-group").hide();
						$(".renew-panel").hide();
						$(".upload-section").hide();
						$(".ren-pno-group").hide();
						$(".edit").click(function(){
							$(".done-group").show();
							$(".delete-group").hide();
							$(".edit-group").hide();
							$(".renew-group").hide();
							$(".person-details input, .person-details textarea").removeAttr("readonly");
						});
						$(".change-img").click(function(){
							$(".upload-section").toggle();
						});
						$(".upld-btn").click(function(){
							var key = "change-or-upload-person-image";
							var id = "<?php echo $pid ?>";
							var file_data = $('.img-inp').prop('files')[0];   
						    var form_data = new FormData(); 
			   				form_data.append('file', file_data); 
			   				form_data.append('key', key); 
			   				form_data.append('id', id); 
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
										title: 'Action Completed!',
										content: data,
										buttons: {
											OK: function () {
												location.reload();	
											}
										}
									});
						        }
						    });
						});
						$(".done").click(function(){
							$(".done-group").hide();
							$(".delete-group").show();
							$(".edit-group").show();
							$(".renew-group").show();
							$(".person-details input, .person-details textarea").attr("readonly", true);
							$.post("ajax-req-handler.php", {
								key: "update-person-details",
								name: $("#pd-name").val(),
								mob: $("#pd-mob").val(),
								add: $("#pd-add").val(),
								city: $("#pd-city").val(),
								state: $("#pd-state").val(),
								rank: $("#pd-rank").val(),
								pid: '<?php echo $pid; ?>'
							}, function(data){
								$(".success-msg").html(data);
							});
						});
						$("#ren-sub-btn").one("click",function(){
							var renExp = $("#ren-exp").val();
							var renMemFee = $("#ren-mem-fee").val();
							var renMop = $("#ren-mop").val();
							var renRank = $("#ren-rank").val();
							var renPno = $("#ren-pno").val();
							var id = "<?php echo $pid; ?>";
							var renName = "<?php echo $name; ?>";
							if(renExp != "" || renMemFee != "" || renMop != "" || renRank != ""){
								$.post("ajax-req-handler.php", {
									key: "Renew-member",
									renExp: renExp,
									renMemFee: renMemFee,
									renMop: renMop,
									renRank: renRank,
									renPno: renPno,
									id: id,
									renName: renName
								}, function(data){
									$.confirm({
										title: 'Action Completed!',
										content: data,
										buttons: {
											OK: function () {
												location.reload();	
											}
										}
									});
								});
							}
							else{
								alert("Please fill all the input Fields");
							}
						});
						$("#ren-mop").change(function(){
							if($(this).val() != "cash"){
								$(".ren-pno-group").show();
							}
						});
						$(".renew").click(function(){
							$(".renew-panel").toggle();
							$(".person-details input").removeAttr("readonly");
						});
					</script><?php
				}
			}
		break;

		case 'Renew-member':
			$ren_exp = $_REQUEST['renExp'];
			$ren_mem_fee = $_REQUEST['renMemFee'];
			$ren_mop = $_REQUEST['renMop'];
			$ren_rank = $_REQUEST['renRank'];
			$ren_pno = $_REQUEST['renPno'];
			$id = $_REQUEST['id'];
			$name = $_REQUEST['renName'];
			$tid = "";
			$snAbr = explode(" ",$name);
			foreach($snAbr as $val){
				$tid .= substr($val,0,1);
			}
			$tid .= "T".date("dmysi"); 
			$cdt = date("Y-m-d H:i:s");
			if(!empty($ren_exp) && !empty($ren_mem_fee) && !empty($ren_mop) && !empty($ren_rank)){
				$sql_personal_info = "UPDATE personal_info SET member = 1 WHERE id = '$id' AND member = 0 ";
				$sql_membership_info = "UPDATE membership_info SET rank = '$ren_rank', expiration = '$ren_exp', mem_fee = '$ren_mem_fee', transaction_id = '$tid' WHERE person_id = '$id'; "; 
				if($ren_mop == "cash"){
					$cbal = cbal()+$ren_mem_fee;
					$remarks = "Membership of ".$name."(".$id.") Renewed by Cash";
					$sql_trans = "INSERT INTO cash(id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$id','$cdt','$remarks','in','$ren_mem_fee','$cbal') ";
				}
				else{
					$bbal = bbal()+$ren_mem_fee;
					$remarks = "Membership of ".$name."(".$id.") Renewed by ".$ren_mop."(".$ren_pno.")";
					$sql_trans = "INSERT INTO bank(id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$id','$cdt','$remarks','in','$ren_mem_fee','$bbal') ";
				}
				try{
			   		$db->query('BEGIN;');
			   		if( $db->query($sql_personal_info) == false || $db->query($sql_membership_info) == false || $db->query($sql_trans) == false ){
			   			throw new Exception($db->lastErrorMsg());
			   		}
			   		else{ ?>
			   			<div class='alert alert-success'>Membership Of <?php echo $name; ?> Successfully Renewed</div> <?php
			   		}
			   		
			   		$db->query('COMMIT;');
			   	}
			   	catch(Exception $e){
			   		$db->query('ROLLBACK;');
			   		echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
			   	}
			}
		break;

		case 'view-all-transactions-with-a-particular-person':
			$id = $_REQUEST['id'];
			$sql = "SELECT *, 'cash' AS mop FROM cash WHERE person_id='$id' UNION ALL SELECT *, 'bank' AS mop FROM bank WHERE person_id='$id' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case 'update-person-details':
			$id = $_REQUEST['pid'];
			$name = $_REQUEST['name'];
			$mob = $_REQUEST['mob'];
			$add = $_REQUEST['add'];
			$city = $_REQUEST['city'];
			$state = $_REQUEST['state'];
			$rank = $_REQUEST['rank'];
			$sql_person = "UPDATE personal_info SET name='$name', mobile='$mob', address='$add', city='$city', state='$state' WHERE id = '$id' ";
			$sql_membership = "UPDATE membership_info SET rank='$rank' WHERE person_id = '$id'  ";
			try{
		   		$db->query('BEGIN;');
		   		if( $db->query($sql_person) == false || $db->query($sql_membership) == false ){
		   			throw new Exception($db->lastErrorMsg());
		   		}
		   		else{ ?>
		   			<div class='alert alert-success'>Record Successfully Updated</div> <?php
		   		}
		   		
		   		$db->query('COMMIT;');
		   	}
		   	catch(Exception $e){
		   		$db->query('ROLLBACK;');
		   		echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   	}
		break;

		case 'find-member-suggestions':
			$value = $_REQUEST['val'];
			$sql = "SELECT *, a.id AS pid FROM personal_info a JOIN membership_info b ON (b.person_id = a.id) WHERE (a.id LIKE '%$value%' OR a.name LIKE '%$value%' OR a.mobile LIKE '%$value%' OR a.address LIKE '%$value%' OR a.city LIKE '%$value%' OR a.state LIKE '%$value%' OR b.rank LIKE '%$value%') AND (a.member != 0)";
			$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row["pid"];
	   				$name = $row["name"];
	   				$rank = $row["rank"];
	   				$mobile = $row["mobile"]; ?>
	   				<tr class="single-search-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $mobile; ?></td>
	   					<td><?php echo $rank; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<script type="text/javascript">
					$(".input-suggestions").show();
					$("body").click(function(){
						$(".input-suggestions").hide();
					});
	   				$(".single-search-row").click(function(){
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Member Details", 
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
	   			</script> <?php
	   		}
		break;

		case 'find-reciept-suggestions':
			$value = $_REQUEST['val'];
			$sql = "SELECT *, a.id AS rec_id FROM donations a JOIN personal_info b ON (b.id = a.person_id) JOIN membership_info c ON (c.person_id = a.person_id) WHERE a.id LIKE '%$value%' OR a.pno LIKE '%$value%' OR b.name LIKE '%$value%' OR b.address LIKE '%$value%' OR b.city LIKE '%$value%' OR b.state LIKE '%$value%' OR b.mobile LIKE '%$value%' OR c.rank  LIKE '%$value%' ";
			$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row["rec_id"];
	   				$name = $row["name"];
	   				$rank = $row["rank"];
	   				$mobile = $row["mobile"]; ?>
	   				<tr class="single-search-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $mobile; ?></td>
	   					<td><?php echo $rank; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<script type="text/javascript">
					$(".input-suggestions").show();
					$("body").click(function(){
						$(".input-suggestions").hide();
					});
	   				$(".single-search-row").click(function(){
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "fetch-single-bill-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Reciept Details", 
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
	   			</script> <?php
	   		}
		break;

		case 'find-voucher-suggestions':
			$value = $_REQUEST['val'];
			$sql = "SELECT *, a.id AS rec_id, b.id AS pid FROM vouchers a JOIN personal_info b ON (b.id = a.person_id) WHERE a.id LIKE '%$value%' OR a.description LIKE '%$value%' OR a.chnum LIKE '%$value%' OR b.name LIKE '%$value%' OR b.address LIKE '%$value%' OR b.city LIKE '%$value%' OR b.state LIKE '%$value%' OR b.mobile LIKE '%$value%' ";
			$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row["rec_id"];
	   				$name = $row["name"];
	   				$mobile = $row["mobile"]; ?>
	   				<tr class="single-search-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $mobile; ?></td>
	   					<td><?php echo $id; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<script type="text/javascript">
					$(".input-suggestions").show();
					$("body").click(function(){
						$(".input-suggestions").hide();
					});
	   				$(".single-search-row").click(function(){
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "fetch-single-voucher-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Voucher Details", 
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
	   			</script> <?php
	   		}
		break;

		case 'find-person-suggestions':
			$value = $_REQUEST['val'];
			$sql = "SELECT *, a.id AS pid FROM personal_info a JOIN membership_info b ON (b.person_id = a.id) WHERE a.id LIKE '%$value%' OR a.name LIKE '%$value%' OR a.mobile LIKE '%$value%' OR a.address LIKE '%$value%' OR a.city LIKE '%$value%' OR a.state LIKE '%$value%' OR b.rank LIKE '%$value%' ";
			$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row["pid"];
	   				$name = $row["name"];
	   				$mobile = $row["mobile"]; ?>
	   				<tr class="single-search-row" id="<?php echo $id; ?>">
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $mobile; ?></td>
	   					<td><?php echo $id; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<script type="text/javascript">
					$(".input-suggestions").show();
					$("body").click(function(){
						$(".input-suggestions").hide();
					});
	   				$(".single-search-row").click(function(){
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Person Details", 
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
	   			</script> <?php
	   		}
		break;

		case "View-all-members":
			$sql = "SELECT *, a.id AS pid FROM personal_info a JOIN membership_info b ON (b.person_id = a.id) WHERE a.member != 0";	?>
			<table>
				<tr class="tab-head">
					<td>Image</td>
					<td>Join Date</td>
					<td>Name</td>
					<td>Mobile</td>
					<td>Address</td>
					<td>City</td>
					<td>State</td>
					<td>rank</td>
					<td>Expiration</td>
				</tr><?php
			$result = $db->query($sql); 
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$id = $row['pid'];
	   				$join_date = date("d/m/Y", strtotime($row['join_date']));
	   				$name = $row['name'];
	   				$mobile = $row['mobile'];
	   				$address = $row['address'];
	   				$city = $row['city'];
	   				$state = $row['state'];
	   				$rank = $row['rank'];
	   				$image = $row['image'];
	   				$expiration = date("d/m/Y", strtotime($row['expiration'])); ?>
	   				<tr class="single-row" id="<?php echo $id; ?>">
	   					<td><img src="<?php if(!empty($image)) echo $image; else echo 'images/No_image.png'; ?>" height="40" width="40"></td>
	   					<td><?php echo $join_date; ?></td>
	   					<td><?php echo $name; ?></td>
	   					<td><?php echo $mobile; ?></td>
	   					<td><?php echo $address; ?></td>
	   					<td><?php echo $city; ?></td>
	   					<td><?php echo $state; ?></td>
	   					<td><?php echo $rank; ?></td>
	   					<td><?php echo $expiration; ?></td>
	   				</tr><?php
	   			}
	   		} ?>
	   		</table>
	   		<script type="text/javascript">
					$(".input-suggestions").show();
					$("body").click(function(){
						$(".input-suggestions").hide();
					});
	   				$(".single-row").click(function(){
	   					var id = $(this).attr('id');
	   					$.post("ajax-req-handler.php", {
	   						key: "view-detailed-person-details", 
	   						id: id 
	   					}, function(resp){
	   						$.confirm({
								title: "Member Details", 
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
	   			</script> <?php
		break;

		case "change-or-upload-person-image":
			$id = $_REQUEST['id'];
			$img_name = $_FILES["file"]["name"];
			$target_dir = "uploads/members/";
			$target_file = $target_dir . basename($img_name);
			$sql_prev_img = "SELECT image FROM personal_info WHERE id = '$id' ";
			$result_prev_img = $db->query($sql_prev_img); 
	   		if(numr($result_prev_img)>0){
	   			while ($row_prev_img=$result_prev_img->fetchArray(SQLITE3_ASSOC)) {
	   				$prev_img = $row_prev_img['image'];
	   			}
	   		}
	   		$sql_insert_img = "UPDATE personal_info SET image='$target_file' WHERE id='$id' ";
	   		try{
		   		$db->query('BEGIN;');
		   		if($db->query($sql_insert_img) == false || move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $img_name) == false){
		   			throw new Exception($db->lastErrorMsg());
		   		}
		   		else{ 
		   			if(!empty($prev_img)) unlink($prev_img); ?>
		   			<div class='alert alert-success'>Image Successfully Updated</div> <?php
		   		}
		   		
		   		$db->query('COMMIT;');
		   	}
		   	catch(Exception $e){
		   		$db->query('ROLLBACK;');
		   		echo "<div class='alert alert-danger'>ERROR! ".$e->getMessage()."</div>";
		   	}
		break;

		case 'search-all-transactions':
			$value = $_REQUEST['val'];
			$sql = "SELECT *, 'cash' AS mop FROM cash WHERE remarks LIKE '%$value%' UNION ALL SELECT *, 'bank' AS mop FROM bank  WHERE remarks LIKE '%$value%' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Mode</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash Balance</td>
				<td>Bank Balance</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); 
	   				$mop == "cash" ? ($cbal = $balance AND $bbal = "-") : ($bbal = $balance AND $cbal = "-"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php echo $mop; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($cbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($cbal).'/-'; else echo $cbal; ?></td>
	   					<td><?php if($bbal != "-") echo '<i class="fa fa-inr"></i>'.number_format($bbal).'/-'; else echo $bbal; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "search-cash-transactions":
			$value = $_REQUEST['val'];
			$sql = "SELECT * FROM cash WHERE remarks LIKE '%$value%' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "search-bank-transactions":
			$value = $_REQUEST['val'];
			$sql = "SELECT * FROM bank WHERE remarks LIKE '%$value%' ORDER BY date DESC";
			$result = $db->query($sql); ?>
			<tr class="tab-head">
				<td>Date & Time</td>
				<td>Narration</td>
				<td>Debit</td>
				<td>Credit</td>
				<td>Cash</td>
			</tr> <?php
	   		if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) { 
	   				$date = date("d/m/Y h:i:s", strtotime($row['date']));
	   				$remarks = $row['remarks'];
	   				$type = $row['type'];
	   				$amount = $row['amount'];
	   				$balance = $row['balance'];
	   				$mop = $row['mop'];
	   				$type == "in" ? ($credit = $amount AND $debit = "-" AND $row_color = "style='color:#4CAF50'") : ($debit = $amount AND $credit = "-" AND $row_color = "style='color:#C9302C'"); ?>
	   				<tr <?php echo $row_color; ?>>
	   					<td><?php echo $date; ?></td>
	   					<td><?php echo $remarks; ?></td>
	   					<td><?php if($debit != "-") echo '<i class="fa fa-inr"></i>'.number_format($debit).'/-'; else echo $debit; ?></td>
	   					<td><?php if($credit != "-") echo '<i class="fa fa-inr"></i>'.number_format($credit).'/-'; else echo $credit; ?></td>
	   					<td><?php if($balance != "-") echo '<i class="fa fa-inr"></i>'.number_format($balance).'/-'; else echo $balance; ?></td>
	   				</tr> <?php
	   			}
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-warning' colspan=7>Oops! Nothing Found</td></tr>";
	   		}
		break;

		case "send-msg-to-all":
			$sql = "SELECT mobile FROM personal_info";
			$result = $db->query($sql);
			if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$mobile = $row['mobile'];
	   			} ?>
	   			<div class="alert-success">Message Successfully sent to <?php echo numr($result); ?> people.</div> <?php
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-danger' colspan=7>No records found</td></tr>";
	   		}
		break;

		case 'send-msg-to-members':
			$sql = "SELECT mobile FROM personal_info WHERE member = 1";
			$result = $db->query($sql);
			if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$mobile = $row['mobile'];
	   			} ?>
	   			<div class="alert-success">Message Successfully sent to <?php echo numr($result); ?> people.</div> <?php
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-danger' colspan=7>No records found</td></tr>";
	   		}
		break;

		case 'send-msg-to-individuals':
			$to = explode(",", $_REQUEST['to']);
			for($i=0;$i<count($to);$i++){
				echo $to[$i];
			}
		break;

		case 'get-contacts-suggestions-to-send-sms':
			$value = $_REQUEST['val'];
			$sql = "SELECT mobile, name FROM personal_info WHERE mobile LIKE '%$value%'";
			$result = $db->query($sql);
			if(numr($result)>0){
	   			while ($row=$result->fetchArray(SQLITE3_ASSOC)) {
	   				$mobile = $row['mobile'];
	   				$name = $row['name']; ?>
	   				<tr class="contact-row">
	   					<td><?php echo $name; ?></td>
	   					<td class="num"><?php echo $mobile; ?></td>
	   				</tr> <?php
	   			} ?>
	   			<div class="alert-success">Message Successfully sent to <?php echo numr($result); ?> people.</div>
	   			<script type="text/javascript">
	   				$(".contacts-suggestions").show();
	   				$("body").click(function(){
	   					$(".contacts-suggestions").hide();
	   				});
	   				$(".contact-row").click(function(){
	   					var mob = $(this).find(".num").text();
	   					var contacts = $(".to-inp #numbers").val().split(",");
						function checkNum(num) {
						    return num == mob;
						};
						if(contacts.find(checkNum) == mob){
							$(".send-msg .alerts").html("<div class='alert alert-warning'>This number is already Selected</div>");
							setTimeout(function() {
							    $('.send-msg .alerts').empty();
							}, 5000);
							$("#to").val("").focus();
						}
						else{
	   						contactsArr.push(mob);
							$(".to-inp #numbers").val(contactsArr.toString());
	   						$("#to").val("").focus();
						}
	   					
	   				});
	   			</script> <?php
	   		}
	   		else{
	   			echo "<tr><td class='alert alert-danger' colspan=7>No records found</td></tr>";
	   		}
		break;
    }
    $db->close();
?>
