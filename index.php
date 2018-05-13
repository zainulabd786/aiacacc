<?php include "header-login.php"; ?> 
<center>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="credential-container">
	<div class="form-group">
	  	<label for="usr">Username:</label>
	  	<input type="text" name="uname" class="form-control" id="usr">
	</div>
	<div class="form-group">
	  	<label for="pwd">Password:</label>
	  	<input type="password" name="pass" class="form-control" id="pwd">
	</div>
	<button type="submit" class="btn btn-success btn-block">Login</button>
</form>
</center>

<?php //include "footer.php"; ?>
<?php 
if($_SERVER['REQUEST_METHOD']=="POST"){
	$auname = $_POST['uname'];
	$apsw = $_POST['pass'];
	$sql = "SELECT value FROM settings WHERE id='2' AND setting='password'";
	$result = $db_conn->query($sql);
	if(numr($result)>0){
		while($row=$result->fetchArray(SQLITE3_ASSOC)){
			$pass = $row['value'];
		}
	}
	if($auname=="admin" && $apsw==$pass){
		$_SESSION["auname"]=$auname;
		echo "<script type='text/javascript'>window.location.href = 'dashboard.php';</script>";
	}
	else{ ?>
		<script>
			$.confirm({
				title: 'Error',
				content: 'oops! Invalid usename or password.',
				type: 'red',
				typeAnimated: true,
				buttons: {
					TryAgain: function () {
					}
				}
			});
		</script>		<?php	
	}
}
?>