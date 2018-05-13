<?php
	function compress_image($source_url, $destination_url, $quality) {

		$info = getimagesize($source_url);

	    if ($info['mime'] == 'image/jpeg')
	       	$image = imagecreatefromjpeg($source_url);

    	elseif ($info['mime'] == 'image/gif')
        	$image = imagecreatefromgif($source_url);

		elseif ($info['mime'] == 'image/png')
       		$image = imagecreatefrompng($source_url);

	   	imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}
	$bill_date = date("Y-m-d", strtotime($_REQUEST['billDate']));
	$bill_time = date("h:i:s");
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
	$pdate = $_REQUEST['date'];
	$mop = $_REQUEST['mop'];
	($mem_fee != 0) ? $member = 1 : $member = 0; //decide the value for member column in table 'personal_info'
	$person_id = "";
	$name = $_FILES["file"]["name"];
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($name);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$img = $_FILES["file"]["tmp_name"];
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
	$sql_donation = "INSERT INTO donations(date, person_id, amount, pno, mop, pdate, transaction_id) VALUES('$bill_date','$person_id','$amount','$pno','$mop','$pdate','$tid') ";
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
		$remarks = "Donation From ".$name;
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
		$remarks = "Donation From ".$name;
		$sql_trans = "INSERT INTO bank (id, person_id, date, remarks, type, amount, balance) VALUES('$tid','$person_id','$bill_date_time','$remarks','in','$total_amt','$bbal') ";
	}



	try{
	  	$db->query('BEGIN;');
		if($db->query($sql_person) == false || $db->query($sql_donation) == false || $db->query($sql_mem_info) == false || $db->query($sql_trans) == false || 0 < $_FILES['file']['error'] || move_uploaded_file($img, $target_file) == false) {
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
	$db->close();









  /*  if (  ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
       // move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
        //echo "successful";
        
		
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			echo "Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";	
		} 
		else {
			if(compress_image($img, $target_file_lq, 50)){
				if (move_uploaded_file($img, $target_file) ) {
					class database extends SQLite3{
				    	function __construct(){
							$db_file = "data/AIACACC.sqlite3";
				    		$this->open($db_file);
				    	}
				    }
				    $db = new database();
					$sq = "INSERT INTO personal_info(image) VALUES('$target_file');";
					if($db->query($sq)==TRUE){
						echo "OK ";
					}
					else{
						echo "error".$sq."<br>";
					}
					echo "<script>alert('Your Design has been uploaded successfully.')</script>";
				}
				else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
	}*/

?>