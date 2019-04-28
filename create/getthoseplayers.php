<?php 
$join1="SELECT `fortaleza`.`players`.name FROM `fortaleza`.`players` LEFT JOIN `fortaleza`.`games` ON `players`.playerID=`games`.player1ID WHERE `games`.gameID='$gID'";
	$j1r=mysqli_query($dbc, $join1) or trigger_error("Query error: ".mysqli_error($dbc));
	$j1rrow=mysqli_fetch_array($j1r);
	
	$join2="SELECT `fortaleza`.`players`.name FROM `fortaleza`.`players` LEFT JOIN `fortaleza`.`games` ON `players`.playerID=`games`.player2ID WHERE `games`.gameID='$gID'";
	$j2r=mysqli_query($dbc, $join2) or trigger_error("Query error: ".mysqli_error($dbc));
	$j2rrow=mysqli_fetch_array($j2r);

	$join3="SELECT `fortaleza`.`players`.name FROM `fortaleza`.`players` LEFT JOIN `fortaleza`.`games` ON `players`.playerID=`games`.player3ID WHERE `games`.gameID='$gID'";
	$j3r=mysqli_query($dbc, $join3) or trigger_error("Query error: ".mysqli_error($dbc));
	$j3rrow=mysqli_fetch_array($j3r);

	$join4="SELECT `fortaleza`.`players`.name FROM `fortaleza`.`players` LEFT JOIN `fortaleza`.`games` ON `players`.playerID=`games`.player4ID WHERE `games`.gameID='$gID'";
	$j4r=mysqli_query($dbc, $join4) or trigger_error("Query error: ".mysqli_error($dbc));
	$j4rrow=mysqli_fetch_array($j4r);


	$namedp1=$j1rrow['name'];
	$namedp2=$j2rrow['name'];
	$namedp3=$j3rrow['name'];
	$namedp4=$j4rrow['name'];
	?>