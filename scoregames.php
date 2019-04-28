<?php

session_start();

?>

<html lang="en">
	<head>
	<!-- <link rel="stylesheet" type="text/css" href="../stylesheet.css"> -->
	<?php
	include ($_SERVER['DOCUMENT_ROOT']."/sheets.html");
	?>
		<title>Fortnite Stats</title>

		
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
	$status=validate(1, "required", $dbc);

	if($status=="sufficient"){
			echo"<meta http-equiv='REFRESH' content='0;url=create/game.php'>";
	}

	else{ //not logged in

		echo"<meta http-equiv='REFRESH' content='0;url=register.php'>";
	}

	// }
	include $_SERVER['DOCUMENT_ROOT']."/footer.html";
?>


</html>