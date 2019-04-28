<?php

session_start();

?>

<html lang="en">
	<head>
	
	<?php
		include $_SERVER['DOCUMENT_ROOT']."/sheets.html";
		?>
		<title>Your Profile - Fortnite Stats - Picablo</title>

		<script type="text/javascript">
		$('.alert').alert()
		</script>
		<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
	</head>

<?php
	//session_start();
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1, "notrequired", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
			//get adminname
			$ID = (int) $_SESSION["userID"];
			
		?>
<!-- 	</div> -->

	<div id="upmain" class="col-lg-8 col-sm-12">
		<!-- <h1>Your profile</h1> -->
		<?php
		


		?>
	</div>
	<?php
	}
}


	else{//If you are logged out

		}

//Pull the info from the URL
	$confirmationcode = mysqli_real_escape_string ($dbc, $_GET['c']);
	$attemptsuccess=false;
	//Query to see if this exists
	$ifccexists="SELECT * from `login` WHERE `confirmationcode`='$confirmationcode'";
	$ice=mysqli_query($dbc, $ifccexists) or trigger_error("Authentication error: ".mysqli_error($dbc));


	if(@mysqli_num_rows($ice)==0){
			//This is an invalid confirmation error
			alert("Error: that confirmation code is invalid. Try again?","failure");
		}
		else{


	$result=mysqli_fetch_array($ice);
	$rc=$result['confirmed'];
	$rf=$result['firstname'];
	$re=$result['email'];

	if($rc==0){
		//Thi is a success
		$attemptsuccess=true;
		$confirmemail="UPDATE `login` SET `confirmed`=1 WHERE `confirmationcode`='$confirmationcode'";
		$ceq=mysqli_query($dbc, $confirmemail) or trigger_error("Confirm email error: ".mysqli_error($dbc));
		alert("Successfully confirmed log in.","success");
		echo"<meta http-equiv='REFRESH' content='0;url=login.php'>";

	}
	else if($rc==1){

		alert("This email has already been confirmed. Taking you to the homepage.","primary");
		echo"<meta http-equiv='REFRESH' content='0;url=index'>";

	}

}//end of the successful confirmation code time

	//If it exists and confirm is 0, set the confirm code to 1
		//Display a success message
		//Display a "continue to login" message
	//If it doesn't exist, display an error message. click to send another email.


?>
	<div class="container">
	<div class="col my-5"></div>
	<div class="col my-5"></div>
	</div>

</html>