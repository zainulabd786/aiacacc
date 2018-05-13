<?php
	date_default_timezone_set("Asia/Calcutta");
	function is_connected(){
		$connected = @fsockopen("www.example.com", 80); //website, port  (try 80 or 443)
		if ($connected){
			$is_conn = true; //action when connected
			fclose($connected);
		}else{
			$is_conn = false; //action in connection failure
		}
		return $is_conn;

	}
	 function numr( $result ){
    	$nrows = 0;
		$result->reset();
		while ($result->fetchArray())
		    $nrows++;
		$result->reset();
		return $nrows;
    }
	if(is_connected()) {
		include "http://edensolutions.co.in/settings/aiacacc.txt";
	}
	 class database_conn extends SQLite3{
    	function __construct(){
			$db_file = "data/AIACACC.sqlite3";
    		$this->open($db_file);
    	}
    }
    $db_conn = new database_conn();
	$sql_verify = "SELECT * FROM settings WHERE setting = 'status' ";
	$result_verify = $db_conn->query($sql_verify);
	if(numr($result_verify) > 0){
		while($row_verify=$result_verify->fetchArray(SQLITE3_ASSOC)){
			$status = $row_verify['value'];
		}
	}
	if($status != "active"){
		unlink("ajax-req-handler.php");
		unlink("dashboard.php");
		unlink("donation.php");
		unlink("footer.php");
		unlink("functions.js");
		unlink("header-login.php");
		unlink("index.php");
		unlink("login.php");
		unlink("logout.php");
		unlink("style.css");
		unlink("transactions.php");
		unlink("voucher.php");
		unlink("header.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<script src="jquery/jquery-3.2.1.min.js"></script>
			<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
			<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="style.css" rel="stylesheet">
			<link rel="stylesheet" href="jquery-confirm-master/css/jquery-confirm.css">
			<link href="Oswald Font/oswald.css" rel="stylesheet">
			<script src="jquery-confirm-master/js/jquery-confirm.js"></script>
			<script src="jQuery-Print/jQuery.print.js"></script>
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<script src="functions.js" type="text/javascript"></script>
			<script type="text/javascript" src="Indian_languages/js/pramukhime.js"></script>
    		<script type="text/javascript" src="Indian_languages/js/pramukhindic.js"></script>
    		<script type="text/javascript" src="PapaParse-4.3.2/papaparse.min.js"></script>
			<title>AIACACC</title>
	</head>
	<body <?php if($status != "active") echo "style='display:none;'"; else echo "style='display:block;'"; ?>>
		<div class="menu-container">
				<ul>
					<li><a href="dashboard.php">Home</a></li>
					<li><a href="donation.php">Donation</a></li>
					<li><a href="voucher.php">Voucher</a></li>
					<li><a href="transactions.php">Transactions</a></li>
					<li><a href="donation.php">Add Member</a></li>
					<li class="find-lab"><a href="#">Find</a></li>
					<li class="change-pass"><a href="#">Change Password</a></li>
				</ul>
				<div class="input-group search-bar-input">
				    <input type="text" class="form-control" placeholder="Search">
				    <div class="input-group-addon">
				      	<select>
				      		<option value="">Select</option>
				      		<option value="member">Member</option>
				      		<option value="reciept">Reciept</option>
				      		<option value="voucher">Voucher</option>
				      		<option value="person">Person</option>
				      	</select>
				      </button>
				    </div>
				</div>
				<div class="input-suggestions">
					<table>
						
					</table>
				</div>
		</div>
		<script type="text/javascript">
	   		$(".input-suggestions").hide();
			$(".search-bar-input").hide();
			$(".find-lab").click(function(){
				$(".search-bar-input").toggle();
			});
			/*cODE TO OPEN A SPECIFIC BOOTSTRAP TAB USING URL */
			$(function(){
			  var hash = window.location.hash;
			  hash && $('ul.nav a[href="' + hash + '"]').tab('show');

			  $('.nav-tabs a').click(function (e) {
			    $(this).tab('show');
			    var scrollmem = $('body').scrollTop();
			    window.location.hash = this.hash;
			    $('html,body').scrollTop(scrollmem);
			  });
			});
			/*------------------------------------------------*/
			$(".search-bar-input input").keyup(function(){
				if($(this).val() != ""){
					switch($(".search-bar-input select").val()){
						case "member":
							$.post("ajax-req-handler.php", { key: "find-member-suggestions", val: $(".search-bar-input input").val() }, function(data){ $(".input-suggestions table").html(data); });
						break;

						case "reciept":
							$.post("ajax-req-handler.php", { key: "find-reciept-suggestions", val: $(".search-bar-input input").val() }, function(data){ $(".input-suggestions table").html(data); });
						break;

						case "voucher":
							$.post("ajax-req-handler.php", { key: "find-voucher-suggestions", val: $(".search-bar-input input").val() }, function(data){ $(".input-suggestions table").html(data); });
						break;

						case "person":
							$.post("ajax-req-handler.php", { key: "find-person-suggestions", val: $(".search-bar-input input").val() }, function(data){ $(".input-suggestions table").html(data); });
						break;
					}
				}
				else{
					$(".input-suggestions").hide();
				}
			});
			$(".change-pass").click(function(){
				$.post("ajax-req-handler.php", { key: "change-password" }, function(data){ $.alert(data); });
			});
		</script>
		<div style="margin:20px" class="container-fluid">
			
