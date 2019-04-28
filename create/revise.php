<?php

session_start();

?>
<html lang="en">
	<head>
	<!-- <link rel="stylesheet" type="text/css" href="../stylesheet.css"> -->
	<?php
	include ($_SERVER['DOCUMENT_ROOT']."/sheets.html");
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
		<title>Fortnite Stats</title>
	</head>
<body>
<?php
//	session_start();
	include ($_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php");
	$status=validate(2, "required", $dbc);

	function getthoseplayers($gID, $dbc){


				$join1="SELECT `players`.name FROM `players` LEFT JOIN `games` ON `players`.playerID=`games`.player1ID WHERE `games`.gameID='$gID'";
				$j1r=mysqli_query($dbc, $join1) or trigger_error("Query error: ".mysqli_error($dbc));
				$j1rrow=mysqli_fetch_array($j1r);
				
				$join2="SELECT `players`.name FROM `players` LEFT JOIN `games` ON `players`.playerID=`games`.player2ID WHERE `games`.gameID='$gID'";
				$j2r=mysqli_query($dbc, $join2) or trigger_error("Query error: ".mysqli_error($dbc));
				$j2rrow=mysqli_fetch_array($j2r);

				$join3="SELECT `players`.name FROM `players` LEFT JOIN `games` ON `players`.playerID=`games`.player3ID WHERE `games`.gameID='$gID'";
				$j3r=mysqli_query($dbc, $join3) or trigger_error("Query error: ".mysqli_error($dbc));
				$j3rrow=mysqli_fetch_array($j3r);

				$join4="SELECT `players`.name FROM `players` LEFT JOIN `games` ON `players`.playerID=`games`.player4ID WHERE `games`.gameID='$gID'";
				$j4r=mysqli_query($dbc, $join4) or trigger_error("Query error: ".mysqli_error($dbc));
				$j4rrow=mysqli_fetch_array($j4r);


				$namedp1=$j1rrow['name'];
				$namedp2=$j2rrow['name'];
				$namedp3=$j3rrow['name'];
				$namedp4=$j4rrow['name'];
				$names=array($namedp1,$namedp2,$namedp3,$namedp4);
				return $names;

	
			}

	if($status!="loggedout"){
		if($status=="sufficient"){
	/*?>

Add a /div here after the ?> if you want to iclude the sidebar of entries
*/

		if(isset($_POST['action']) AND $_POST['action']=='Delete'){
			$gID=$_POST['gameID'];

			//Check to see that it's unscored
			$hideagame="UPDATE `games` SET `shouldweHide`=1 WHERE `gameID`='$gID'";
			$hag=mysqli_query($dbc, $hideagame) or trigger_error("Query error: ".mysqli_error($dbc));
			alert("Successfully hidden a game","success");
			//We have now hidden it
		}




//Display Table
		?>
<!-- 	</div> -->
	


	<div id="upmain" class="col-lg-8 col-sm-12">
		<h1>Delete Matches</h1>
		<?php

			$gamesquery="SELECT `gameID`,`matchupID`, `games`.`eventID`, `player1ID`, `player2ID`,`player3ID`, `player4ID`, `gamenum`, `matchupID`,`isScored`,`events`.`event` FROM `games` JOIN `events` ON `games`.`eventID`=`events`.`eventID` WHERE `isScored` < 1 AND `shouldweHide`= 0 ORDER BY `isScored` asc";

		$mqs=mysqli_query($dbc, $gamesquery) or trigger_error("Query error: ".mysqli_error($dbc));

		

		//Loop through matchups to query teams for team names. I have moved the old data from here to the bottom of this file.
		$selectgamestr='';
		echo "<div class='row'>";
		?>
		<table id="gamestoscore" class="table display responsive"><thead><tr><th>Event</th><th>Team 1</th><th>Team 2</th><th>Round #</th><th>Game #</th><th>Action</th></tr></thead><tbody>
		<?php
		while($mqsrow=mysqli_fetch_array($mqs)){
			//Here we need to switch out Player IDs for names. We will do so using a Join table.
			$gID=$mqsrow['gameID'];
			$mID=$mqsrow['matchupID'];
			$p1i=$mqsrow['player1ID'];
			$p2i=$mqsrow['player2ID'];
			$p3i=$mqsrow['player3ID'];
			$p4i=$mqsrow['player4ID'];
			$scoredstatus=$mqsrow['isScored'];
			$ename=$mqsrow['event'];



			$names = getthoseplayers($gID, $dbc);

			$addition='';

			//$selectgamestr.="<option value='".$mqsrow['gameID']."'>".$names[0]."/".$names[1]." vs. ".$names[2]."/".$names[3]." - Game ".$mqsrow['gamenum'];

			//We also need to get the round number. So we query matchups by JOINNG games.gameID to matchupID and then looking for matchups.rounddesc.

			$rounddesclookup="SELECT `matches`.`rounddesc` as 'des' FROM `matches` WHERE `matchID`='$mID'";
			$rdl=mysqli_query($dbc, $rounddesclookup) or trigger_error("Query error: ".mysqli_error($dbc));
			$roundinfo=mysqli_fetch_array($rdl);
			$rd=$roundinfo['des'];
			
			//echo '<form action="game.php" method="post">';
				echo "<tr><td class='align-middle'>".$ename."</td> <td class='align-middle'>".$names[0]."/".$names[1]."</td>  <td class='align-middle'>".$names[2]."/".$names[3]."</td> <td class='align-middle'>".$rd."</td> <td class='align-middle'>Game ".$mqsrow['gamenum']."</td> <td class='align-middle'><form action='revise.php' method='post'>";
				
			echo '<input id="id" name="gameID" type="hidden" value='.$gID.'><button type="submit" name="action" value="Delete" class="btn btn-primary btn-block">Delete</button>';


				echo "</form></td></tr>";
			//echo "<div class='col-sm-6 col-xs-12 col-md-4 col-xl-4 py-4'><div class='card'><div class='card-body'><h5 class='card-title text-center mb-3 pb-3'>".$names[0]."/".$names[1]." vs. ".$names[2]."/".$names[3]."</h5><p class='card-text text-center'>".$rd." Game ".$mqsrow['gamenum']."</p><p class='card-text font-italic text-center'>".$ename."</p>";
			
			
			
			
			$selectgamestr.="</option>";
			
			//echo '</form>';
			echo '</div></div></div>';
			
			
		}
		echo '</tbody><table>';
		echo "</div>"; //ends row
		
		//list all games in a table

		//have a hide button


		//include ($_SERVER['DOCUMENT_ROOT']."/create/teamuploadform.php");


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