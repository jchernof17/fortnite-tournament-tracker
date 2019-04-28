<?php
session_start();
?>
<head>
<?php
include $_SERVER['DOCUMENT_ROOT']."/sheets.html";
?>
</head>
<?php
include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1,"notrequired",$dbc);

	if($status!="loggedout"){

		//echo ("<h1>".$loggedInMessage.$_SESSION['username']."</h1>");

		//Add logout button
		
	
		//	unset($_SESSION["username"]);
			unset($_SESSION["userID"]);
			unset($_SESSION['name']);
			echo '<div class="alert alert-success" role="alert"> You have successfully logged out. Taking you back to the home page. </div>';
			echo "<meta http-equiv='REFRESH' content='5;url=home.php'>";
		}


	
else{
	echo $_SESSION['userID'];
	echo '<div class="alert alert-danger" role="alert"> You\'re already logged out. Redirecting you to the home page. </div>';
	echo "<meta http-equiv='REFRESH' content='3;url=home.php'>";
	//echo "<meta http-equiv='REFRESH' content='0;url=login.php'>";
}
?>