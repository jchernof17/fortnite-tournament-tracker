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
	$status=validate(1, "required", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
			//get adminname
			$ID = (int) $_SESSION["userID"];
			$gnq=mysqli_query($dbc,"SELECT `firstname` FROM `login` WHERE `userID`='$ID'") or trigger_error("Query error: ".mysqli_error($dbc));
			$row=mysqli_fetch_array($gnq);
			$name=$row['firstname'];
			alert("Welcome back, ".$name."!","primary");
			echo '<div class="pb-3"></div>';

			//GetAdminPoints
			$getAdminM="SELECT COUNT(`createdBy`) as `m` FROM `matches` WHERE `createdBy`='$ID'"; //Matches x2
			$getAdminE="SELECT COUNT(`createdBy`) as `e` FROM `events` WHERE `createdBy` = '$ID'";
			$getAdminT="SELECT COUNT(`createdBy`) as `t` FROM `teams` WHERE `createdBy`='$ID'";
			$getAdminP="SELECT COUNT(`createdBy`)as `p` FROM `players` WHERE `createdBy`='$ID'";
			$gam=mysqli_query($dbc,$getAdminM) or trigger_error("Query error: ".mysqli_error($dbc));
			$gae=mysqli_query($dbc,$getAdminE) or trigger_error("Query error: ".mysqli_error($dbc));
			$gat=mysqli_query($dbc,$getAdminT) or trigger_error("Query error: ".mysqli_error($dbc));
			$gap=mysqli_query($dbc,$getAdminP) or trigger_error("Query error: ".mysqli_error($dbc));
			$trow=mysqli_fetch_array($gam);
			$matchcount=$trow['m'];
			$trow=mysqli_fetch_array($gae);
			$eventcount=$trow['e'];
			$trow=mysqli_fetch_array($gat);
			$teamcount=$trow['t'];
			$trow=mysqli_fetch_array($gap);
			$playercount=$trow['p'];
			$body='';
			$body.= '<div class="card-title text-center font-weight-bold">Your Admin Stats</div>';
			$body.= '<div class="card-body text-dark">';
			$body.= '<p class="card-text ">Matches Created: '.$matchcount.'</p>';
			$body.= '<p class="card-text">Events Created: '.$eventcount.'</p>';
			$body.= '<p class="card-text">Teams Created: '.$teamcount.'</p>';
			$body.= '<p class="card-text">Players Created: '.$playercount.'</p>';
			$body.= '</div>';
			$body.= '<div class="card-title text-center font-weight-bold">Your Game Scoring Stats</div>';

			$getAdminPA="SELECT `archiveID`,`createdBy`,`accuracy` FROM `perfarchive` GROUP BY `gameID` ORDER BY `dateCreated` asc";
			$gapa=mysqli_query($dbc,$getAdminPA) or trigger_error("Query error: ".mysqli_error($dbc));
			$gamescounter=0;
			while($trow=mysqli_fetch_array($gapa)){
				if($ID==$trow['createdBy']){
					$gamescounter=$gamescounter+1;
				}
			}//end while loop

			$gettotalscored="SELECT COUNT(*) as `numperfs`,`archiveID`,`createdBy`,`accuracy` FROM `perfarchive` WHERE `createdBy`='$ID'";
			$gts=mysqli_query($dbc,$gettotalscored) or trigger_error("Query error: ".mysqli_error($dbc));
			$grow=mysqli_fetch_array($gts);
			$np= $grow['numperfs']/4;
			$juju=$np+$gamescounter+$playercount+$teamcount+$eventcount+$matchcount;
			

			$body.= '<div class="card-body text-dark py-1">';
			$body.= '<p class="card-text font-weight-bold">Games Scored: '.$gamescounter.'</p>';
			$body.= '<p class="card-text font-weight-bold">Total Commits: '.$np.'</p>';
			$body.= '</div>';
			$body.= '<div class="card-title text-center font-weight-bold">Your Juju</div>';
			$body.= '<p class="card-text font-weight-bold text-center">'.$juju.'</p>';

			
			//GetScorerPoints
			//Calculate total
			//Display

			makecard("Your Profile",$body,"dark","light");
	/*?>
		
Add a /div here after the ?> if you want to iclude the sidebar of entries
		*/?>
<!-- 	</div> -->

	<div id="upmain" class="col-lg-8 col-sm-12">
		<!-- <h1>Your profile</h1> -->
		<?php
		


		?>
	</div>
	<?php
	}
}
	else{

		}
?>
	<div class="container">
	<div class="col my-5"></div>
	<div class="col my-5"></div>
	</div>

</html>