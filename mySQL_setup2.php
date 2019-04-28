<?php

?><!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WCF427C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
/*
DBuser - your database username
DBpass - your database password
DBhost - should be localhost or whatever you're connecting to
DBname - the name of the database

*/
DEFINE ('DBuser', '');
DEFINE ('DBpass', '');
DEFINE ('DBhost', 'localhost');
DEFINE ('DBname', '');

$dbc=@mysqli_connect(DBhost,DBuser,DBpass,DBname);
//echo md5(microtime());
if(!$dbc){
	trigger_error('Error connecting to MySQL: '.mysqli_connect_error());
	
}

//list of functions


function alert($message, $type){
	if($type=='success'){
		echo '<div class="alert alert-success my-0" role="alert">';
  		echo $message;
  		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>';
		echo '</div>';
	}

	else if($type=='failure'){
		echo '<div class="alert alert-danger my-0" role="alert">';
  		echo $message;
  		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>';
		echo '</div>';
	}

	else if($type=='primary'){
		echo '<div class="alert alert-primary my-0" role="alert">';
  		echo $message;
  		echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>';
		echo '</div>';
	}
}

function makecard($headercontent,$bodycontent,$hcolor,$bcolor){

echo '<div class="card text-white bg-white mb-3 px-0 mx-3 col-sm-5 col-lg-4">';
	echo '<div class="card-header bg-'.$hcolor.' text-center font-weight-bold">'.$headercontent.'</div>';
	echo '<div class="card-body text-dark bg-'.$bcolor.'">';
		echo $bodycontent;	
	echo '</div>';
echo '</div>';
}

function validate($requiredlevel,$type ,$dbc){
	//echo "validating";
	//include ($_SERVER['DOCUMENT_ROOT']."/fort/sheets.html");
	//include ($_SERVER['DOCUMENT_ROOT']."/footer.html");
	if(isset($_SESSION['userID'])){
		$uid=$_SESSION['userID'];
		//echo $uid;
		$getsl=mysqli_query ($dbc, "SELECT `securityLevel`,`confirmed` from `login` WHERE `userID`='$uid'") or trigger_error("Query error: ".mysqli_error($dbc));
		$row=mysqli_fetch_array($getsl);
		//echo $row['securityLevel'];

	if($row['confirmed']==0 AND $type=="required"){
		//We don't have a validated login
		include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
		alert("Uh oh: You haven't validated your email yet.","failure");
		//include $_SERVER['DOCUMENT_ROOT']."/loginform.html";
		return "insufficient";

	}

	else if($row['confirmed']==0 AND $type!="required"){
		//echo "Logged in but not confirmed";
		include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
		return "sufficient";

	}

	else if($row['confirmed']==1){
		if($row['securityLevel']>=$requiredlevel){
			
			include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
			return "sufficient";
		}
		else{
			include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
			if($type=="required"){
			alert("Sorry partner. This page isn't for you. Contact the admin team admin@picablostats.com if you believe this is a mistake.","failure");
		}
			return "insufficient";
		}//end else
	}

	}
	else{//If there is no login.
		include $_SERVER['DOCUMENT_ROOT']."/navnologin.php";
		if($type=="required"){
		alert("You are not logged in. Log in and try again.","failure");
		include $_SERVER['DOCUMENT_ROOT']."/loginform.html";
	}
		return "loggedout";
	}//end else
	
}//end validate

function hpvalidate($requiredlevel,$type ,$dbc,$nav){
	if(isset($_SESSION['userID'])){
		$uid=$_SESSION['userID'];
		//echo $uid;
		$getsl=mysqli_query ($dbc, "SELECT `securityLevel` from `login` WHERE `userID`='$uid'") or trigger_error("Query error: ".mysqli_error($dbc));
		$row=mysqli_fetch_array($getsl);
		//echo $row['securityLevel'];
		if($row['securityLevel']>=$requiredlevel){
			if($nav){
			include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
			}
			return "sufficient";
		}
		else{
			if($nav){
			include $_SERVER['DOCUMENT_ROOT']."/nav_loggedin.php";
		}
			if($type=="required"){
			alert("You do not have sufficient permissions to do that.","failure");
		}
			return "insufficient";
		}
	}
	else{//If there is no login.
		if($nav){
		include $_SERVER['DOCUMENT_ROOT']."/navnologin.php";
	}
		if($type=="required"){
		alert("You are not logged in. Log in and try again.","failure");
	}
		return "loggedout";
	}
	
}

//mysqli_close($dbc);
?>