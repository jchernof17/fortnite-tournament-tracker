<?php session_start(); ?>
<html lang="en">
	<head>
	<!-- <link rel="stylesheet" type="text/css" href="../stylesheet.css"> -->
	<?php
	include ($_SERVER['DOCUMENT_ROOT']."/sheets.html");
	//	include("")

		?>
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
		<title>Fortnite Stats</title>
	</head>

<?php
	
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";

	$status=validate(2, "required", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
	?>
	<div id="upmain" class="maincontent col-sm-12 col-md-8 col-lg-7">
		<h1>Upload Events</h1>
		<?php
		include $_SERVER['DOCUMENT_ROOT']."/create/eventuploadform.php";
		?>
	</div>
	<?php
	}//end of proper credentials
	}//end of logged in
	else{//if status is logged out

	
		
	}

?>


</html>