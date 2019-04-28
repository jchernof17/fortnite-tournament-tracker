<?php

session_start();

?>
<html lang="en">
	<head>
	<!-- <link rel="stylesheet" type="text/css" href="../stylesheet.css"> -->
	<?php
	include ($_SERVER['DOCUMENT_ROOT']."/sheets.html");
	?>
		<title>Create Player - Fortnite Stats - Picablo</title>

		
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
	include ($_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php");
	$status=validate(2, "required", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
	/*?>	

Add a /div here after the ?> if you want to iclude the sidebar of entries
		*/?>
<!-- 	</div> -->

		<div id="upmain" class="maincontent col-sm-12 col-lg-8">
		<h1>Upload Players</h1>
		<?php
		include ($_SERVER['DOCUMENT_ROOT']."/create/playeruploadform.php");


		?>
	</div>
	<?php
	}
	}
	else{ //not logged in


	}
include $_SERVER['DOCUMENT_ROOT']."/footer.html";
	// }
?>


</html>