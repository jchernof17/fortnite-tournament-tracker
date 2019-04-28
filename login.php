<?php

session_start();

?>
<HTML>
	
	<head>


		<?php
		include("sheets.html");
		?>

		<title>
		Training Tracks
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
	//session_start();
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1, "notrequired", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
		}
	}
	else{

	}
	//Login stuff
	if(isset($_POST["login"])){
		$passw=mysqli_real_escape_string ($dbc, $_POST['pass']);
		$user=mysqli_real_escape_string ($dbc, $_POST['user']);


		$query = "SELECT * FROM login WHERE (username='$user' AND password=SHA1('$passw'))";

		$r=mysqli_query ($dbc, $query) or trigger_error("Query error: ".mysqli_error($dbc));

		if(@mysqli_num_rows($r)>=1){

			$_SESSION['name']=$_POST['user'];
			//You're in.


			$useru = mysqli_real_escape_string($dbc, $_SESSION['name']);
			

			$queryu = "SELECT * FROM  `login` WHERE  `username` =  '$useru'";
			$ru=mysqli_query($dbc, $queryu) or trigger_error("Query error: ".mysqli_error($dbc));
	
			if(@mysqli_num_rows($ru)==0){
			//error
			}
			while($row = mysqli_fetch_array($ru)){
			$n=$row['firstname'];
			$userID = $row['userID'];
			$_SESSION['userID']=$userID;
			$_SESSION['name']=$n;

			}



			echo"<meta http-equiv='REFRESH' content='0;url=index'>";



		}

		else{//You have an incorrect password
			//echo "Incorrect password";
			echo"Incorrect Password";
		}

		mysqli_close($dbc);
	}

//	include("logoutform.php");


	if(!isset($_SESSION['name'])){
		include('loginform.html');
 	}

 	




	else{
	//You aren't sending the form

	}
	//include $_SERVER['DOCUMENT_ROOT']."/fort/footer.html";
 	?>

	</body>

