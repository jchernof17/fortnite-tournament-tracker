<?php

session_start();
include $_SERVER['DOCUMENT_ROOT']."/sheets.html";
?>
<HTML>
	
	<head>
		
		<title>
		Register - Fortnite Stats - Picablo
		</title>

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
	<body>



<?php 
	
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1,"notrequired",$dbc);

	if($status=="loggedout"){
	//echo"<h1>Register</h1>";
	//Register stuff
	if(isset($_POST["Continue"])){

		$rpassw1=mysqli_real_escape_string ($dbc, $_POST['p1']) or trigger_error("No password entered");
		$rpassw2=mysqli_real_escape_string ($dbc, $_POST['p2']) or trigger_error("No password entered");
		$ruser=mysqli_real_escape_string ($dbc, $_POST['user']) or trigger_error("No username entered.");
		$remail=mysqli_real_escape_string ($dbc, $_POST['email']) or trigger_error("No email entered.");
		$rfname = mysqli_real_escape_string ($dbc, $_POST['firstname']) or trigger_error("No first name entered.");
		$rlname = mysqli_real_escape_string ($dbc, $_POST['lastname']) or trigger_error("No last name entered.");	
		$rname=$rfname." ".$rlname;	
		$shouldweregister=True;
		//$rquery = "INSERT INTO `login` (`username`, `password`) VALUES ('$ruser', SHA1('$rpassw'));";
		$rtestq = "SELECT * FROM login WHERE (username='$ruser' OR email='$remail')";
		$rtestr = mysqli_query($dbc,$rtestq) or trigger_error("Query error: ".mysqli_error($dbc));


		if($rpassw1!=$rpassw2){
			alert("Error: Passwords did not match. Try again.","failure");
			$shouldweregister=False;
		}

		else if(strlen($rfname)<3 OR strlen($rlname)<3){
			alert("Error: First and Last name must be at least 3 characters. Try again.","failure");
			$shouldweregister=False;
		}

		else if(strlen($rpassw1)<7){
			alert("Error: Password must be at least 7 characters. Try again.","failure");
			$shouldweregister=False;
		}

		else if(@mysqli_num_rows($rtestr)!=0){
			alert("Error: That username or email is already taken. Try again.","failure");
			$shouldweregister=False;
		}









		
		if($shouldweregister){
		//	echo "Uploading";
//rr=mysqli_query($dbc, $rquery) or trigger_error("Query error: ".mysqli_error($dbc));
			$cc=md5(microtime());
			$rexecuteq="INSERT INTO `login` (`username`, `password`, `email`, `adminname`,`securityLevel`,`firstname`,`lastname`,`confirmationcode`) VALUES ('$ruser', SHA1('$rpassw1'), '$remail', '$rname',1,'$rfname','$rlname','$cc')";
			//$fyourself="INSERT INTO `jchernof17`.`friends` (`user-id1`, `user-id2`) VALUES ('$ruser', '$ruser');";
			$rexecuter = mysqli_query($dbc,$rexecuteq) or trigger_error("Register error: ".mysqli_error($dbc));

			//Now we need to send the email.

			


			$to = $remail;
			$subject = "Confirming your account - Fortnite Stats";
			$headers = "From: admin@picablostats.com". "\r\n";
			$headers .= "Reply-To: admin@picablostats.com" . "\r\n";
			$headers .= "CC: jordan@picablostats.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message = '<html><body>';
			$message .= '<p>Dear '.$rfname.',<p>'."\r\n";
			$message .= '<p>Thank you for signing up to score Fortnite matches.</p>'."\r\n";
			$message .= '<p>Please click the link below for you to confirm your account.</p>'."\r\n";
			$message .= '<button><a href="https://www.picablostats.com/authidentity.php?c='.$cc.'">Confirm</a></button>'."\r\n";
			$message .= '<p>Email jordan@picablostats.com if you have any questions.'."\r\n";
			$message .= '</body></html>';

			mail($to,$subject,$message,$headers);
			//echo "mailed";

			

			//$executefyourseulf=mysqli_query($dbc,$fyourself) or trigger_error("Friending yourself error...");


			$_SESSION['username']=$ruser;
			//$_SESSION['firstname']=$rname;
			$_SESSION['name']=$rname;

			$useru = mysqli_real_escape_string($dbc, $_SESSION['username']);
			

			$queryu = "SELECT * FROM  `login` WHERE  `username` =  '$useru'";
			$ru=mysqli_query($dbc, $queryu) or trigger_error("Query error: ".mysqli_error($dbc));
	
			if(@mysqli_num_rows($ru)==0){
			//error
			}
			while($row = mysqli_fetch_array($ru)){
			$userID = $row['userID'];
			$_SESSION['userID']=$userID;

			}
			alert("Welcome, ".$_SESSION['name']."! Successfully registered your account. Please check your email to confirm your account.","success");
			//echo"<meta http-equiv='REFRESH' content='5;url=create/game.php'>";

			
			//echo"<meta http-equiv='REFRESH' content='0;url=index.php'>";
		}
		else{
			echo"<meta http-equiv='REFRESH' content='4;url=register.php'>";
		}

		

		

	}

}


	if(isset($_SESSION['userID'])){

		//echo ("<h1>".$loggedInMessage.$_SESSION['username']."</h1>");

		//Add logout button
		?>

		<form action="logoutform.php" method="post">
			<button class = "btn btn-primary" type = "submit" value = "Log out" name = "logout">Log Out</button>
		</form>


		<?php

		if(isset($_POST["logout"])){
			unset($_SESSION["username"]);
			echo "<meta http-equiv='REFRESH' content='0;url=login.php'>";
		}


	}


	else{
	?>
	<div class="container py-3">
    <div class="row">
        <div class="mx-auto col-sm-8 col-md-6">
                    <!-- form user info -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0 text-center">Create Your Scorer Profile</h4>
                        </div>
                        <div class="card-body">
                            <form action="register.php" method="post" class="form needs-validation" role="form" autocomplete="off" novalidate>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">First name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="text" name="firstname" placeholder="Jane" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Last name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="text" name="lastname" placeholder="Doe" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="email" name="email" placeholder="jane@doe.com" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Username</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="text" name="user" placeholder="llamalover12" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Password</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="password" name="p1" placeholder ="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Confirm Password</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="password" name="p2" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                        <input type="reset" class="btn btn-secondary" value="Cancel">
                                        <input type="submit" class="btn btn-primary" name="Continue" value="Continue">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /form user info -->
        </div>
    </div>
</div>
 	<?php
 	}



 	?>

 	<div class="container">
	<div class="col my-5"></div>
	<div class="col my-5"></div>
	</div>
	</body>

