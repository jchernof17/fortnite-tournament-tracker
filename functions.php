<?php
function alert($message, $type){
	if($type=='success'){
		echo '<div class="alert alert-success" role="alert">';
  		echo $message;
		echo '</div>';
	}

	else if($type=='failure'){
		echo '<div class="alert alert-danger" role="alert">';
  		echo $message;
		echo '</div>';
	}
}

function validate($requiredlevel,$type){
	if(isset($_SESSION['userID'])){
		$uid=$_SESSION['userID'];
		$getsl=mysqli_query($dbc,"SELECT `securityLevel` from `login` WHERE `userID`='$uid'");
		$row=mysqli_fetch_array($getsl);
		if(row['securityLevel']>=$requiredLevel){
			return true;
		}
		else if($type=="required"){
			alert("You do not have sufficient permissions to do that.","failure");
			return false;
		}
		else{
			return false;
		}
	}
	else if($type=="required"){//If there is no login.
		alert("You are not logged in. Log in and try again.","failure");
		return false;
	}
	else{
		return false;
	}
	
}
?>