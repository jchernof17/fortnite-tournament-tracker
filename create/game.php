<?php

session_start();

?>
<html lang="en">
	<head>
	<!-- <link rel="stylesheet" type="text/css" href="../stylesheet.css"> -->
		<?php
		include $_SERVER['DOCUMENT_ROOT']."/sheets.html";
		?>
		<title>Fortnite Stats - Picablo</title>
		<!-- <script>
			$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});
$(document).keydown(function(e){
    if(e.which === 123){
       return false;
    }
});
		</script> -->
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
<script type="text/javascript">
	$(document).ready( function () {
    $("#gamestoscore").DataTable( {
        "paging":   true,
        "ordering": true,
        "searching": true,
        "info":     true
    } );

    $("#leaderboards").DataTable({"order":[[3,'desc']]})
	});//end of script

	</script>
	</head>

<?php
	//session_start();
include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1, "required", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
	//include $_SERVER['DOCUMENT_ROOT']."/fort/nav_loggedin.php";
	/*?>

Add a /div here after the ?> if you want to iclude the sidebar of entries
		*/?>
<!-- 	</div> -->

	<div id="upmain" class="col-xs-12 pl-5 pt-2">
		<!-- <h1>Upload Matches</h1> -->
		<?php
		include("gameuploadform.php");

		
		?>
	</div>
	<div class="container">
	<div class="col my-5"></div>
	<div class="col my-5"></div>
	</div>
	
	<?php
	}

}
	else{

		// include("/fort/navnologin.php");
		// echo"<meta http-equiv='REFRESH' content='0;url=fort/login.php'>";
	}
?>


</html>