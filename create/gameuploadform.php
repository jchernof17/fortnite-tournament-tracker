<div id="uploadform">

<?php
//$t1p1=$t1p2=$t2p1=$t2p2=$t1p1drop=$t2p2drop=$t1p2drop=$t2p1drop=$t1p1kills=$t2p2kills=$t1p2kills=$t2p1kills=$t1p1place=$t2p2place=$t1p2place=$t2p1place='';
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

function enterperformance($pfID,$kills,$deaths,$drop,$place,$gID,$ID,$status,$dbc){
	$endupload=True;
	//echo "Inside function: ".$drop;
	$returnval=0;
	//If the status is not new, then check the existing performance vs. this one.
	if($status != "new"){
		$checkperf="SELECT `performanceID`,`kills`,`deaths`,`droplocation`,`place`,`isScored`,`teamID`,`gameID`,`playerID` FROM `performances` WHERE `performanceID`='$pfID'";
		$cpq=mysqli_query($dbc, $checkperf) or trigger_error("Query error: ".mysqli_error($dbc));
		$row=mysqli_fetch_array($cpq);

		if($row['kills']!=$kills OR $row['deaths']!=$deaths OR $row['droplocation']!=$drop OR $row['place']!=$place){
			//If there is a difference, upload this to archive with relevance isScored+1 and switch out the data in performance
			$returnval=1;

			$rti=$row['teamID'];
			$newaccuracy=2; //New edits always have an accuracy of 2.
			$rgi=$row['gameID'];

			$rpi=$row['playerID'];

			//insert into perfarchive
			$uptoarchive="INSERT INTO `perfarchive` (`archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy`) VALUES (NULL,'$pfID','$rti','$rgi','$rpi','$kills','$deaths','$drop','$place','$ID','$newaccuracy')";
			$endupload=False;
			$uta=mysqli_query($dbc, $uptoarchive) or trigger_error("Query error: ".mysqli_error($dbc));


			//update performance
			// $intoperf="UPDATE `performances` SET `kills`='$kills',`deaths`='$deaths',`droplocation`='$drop',`place`='$place',`isScored`=`isScored`+1,`editedBy`='$ID' WHERE `performanceID`='$pfID'";
			// $itp=mysqli_query($dbc, $intoperf) or trigger_error("Query error: ".mysqli_error($dbc));
		}

		else{ //this is if the edit was just the exact same

				//Update existing performance archive to bump up the accuracy by 2.
				//Add a new performance and set accuracy to be 3.

			$rti=$row['teamID'];
				$newaccuracy=3; //Edits that confirm the previous game have an accuracy of 3 and give all other matches an accuracy bump of 2.
				$rgi=$row['gameID'];

				$rpi=$row['playerID'];
				$uptoarchive="INSERT INTO `perfarchive` (`archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy`) VALUES (NULL,'$pfID','$rti','$rgi','$rpi','$kills','$deaths','$drop','$place','$ID','3')";
				$endupload=False;
				//INSERT A NEW PERFARCHIVE WITH ACCURACY 3
				$uta=mysqli_query($dbc, $uptoarchive) or trigger_error("Query error: ".mysqli_error($dbc));

				//Set the performance to be the most relevant game.
				$lookforrelevance="SELECT `archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy` FROM `perfarchive` WHERE `performanceID`='$pfID' ORDER BY `accuracy` desc";
				$lfr=mysqli_query($dbc, $lookforrelevance) or trigger_error("Query error: ".mysqli_error($dbc));
				$counter=1;
				while($newrow=mysqli_fetch_array($lfr)){
					$i=$newrow['archiveID'];
						//This is the most accurate result. Check if the values are the same.
						if($newrow['kills']==$kills AND $newrow['deaths']==$deaths AND $newrow['droplocation']==$drop AND $newrow['place']==$place){
							//If the top value is the same, then we need to update the top value's accuracy to be accuracy+2.
							
							if($counter==1){
							$addaccuracy=mysqli_query($dbc,"UPDATE `perfarchive` SET `accuracy`=`accuracy`+2 WHERE `archiveID`='$i'");
							}
							else{
								$addaccuracy=mysqli_query($dbc,"UPDATE `perfarchive` SET `accuracy`=`accuracy`+2 WHERE `archiveID`='$i'");
							}
					}
					$counter +=1;
				}//end while loop

				//$intoperf="UPDATE `performances` SET `isScored`=`isScored`+1 WHERE `performanceID`='$pfID'";
				$updatedref="SELECT `archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy` FROM `perfarchive` WHERE `performanceID`='$pfID' GROUP BY `accuracy` desc LIMIT 1";
				$lfr=mysqli_query($dbc, $updatedref) or trigger_error("Query error: ".mysqli_error($dbc));
				$urow=mysqli_fetch_array($lfr);
				$k=$urow['kills'];
				$d=$urow['deaths'];
				$dr=$urow['droplocation'];
				$p=$urow['place'];
				$cb=$ID;

				//$itp=mysqli_query($dbc, $intoperf) or trigger_error("Query error: ".mysqli_error($dbc));
		}//end else

		$updatedref="SELECT `archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy` FROM `perfarchive` WHERE `performanceID`='$pfID' ORDER BY `accuracy` desc LIMIT 1";
			$lfr=mysqli_query($dbc, $updatedref) or trigger_error("Query error: ".mysqli_error($dbc));
			$urow=mysqli_fetch_array($lfr);
			$k=$urow['kills'];
			$d=$urow['deaths'];
			$dr=$urow['droplocation'];
			$p=$urow['place'];
			$cb=$ID;
			


		$uq="UPDATE `performances` SET `kills`='$k',`deaths`='$d',`droplocation`='$dr',`place`='$p',`revisedBy`='$cb',`isScored`=`isScored`+1 WHERE `performanceID`='$pfID'";
		$ruq=mysqli_query($dbc,$uq) or trigger_error("Query error: ".mysqli_error($dbc));
		
	} //end of if NOT NEW
		
	//If the status is new, then upload this game to archive with relevance 1 and upload this to performances
		//$pfID,$kills,$deaths,$drop,$place,$gID,$ID
		$uq="UPDATE `performances` SET `kills`='$kills',`deaths`='$deaths',`droplocation`='$drop',`place`='$place',`revisedBy`='$ID',`isScored`=1 WHERE `performanceID`='$pfID'";
		$ruq=mysqli_query($dbc,$uq) or trigger_error("Query error: ".mysqli_error($dbc));

		$checkperf="SELECT `gameID`,`teamID`,`playerID` FROM `performances` WHERE `performanceID`='$pfID'";
		$cpq=mysqli_query($dbc, $checkperf) or trigger_error("Query error: ".mysqli_error($dbc));
		$row=mysqli_fetch_array($cpq);
		$rti=$row['teamID'];
			$newaccuracy=1; //New performances always have an accuracy of 2.
			$rgi=$row['gameID'];

			$rpi=$row['playerID'];

			//insert into perfarchive
			if($endupload){
			$uptoarchive="INSERT INTO `perfarchive` (`archiveID`,`performanceID`,`teamID`,`gameID`,`playerID`,`kills`,`deaths`,`droplocation`,`place`,`createdBy`,`accuracy`) VALUES (NULL,'$pfID','$rti','$rgi','$rpi','$kills','$deaths','$drop','$place','$ID','$newaccuracy')";
			$uta=mysqli_query($dbc, $uptoarchive) or trigger_error("Query error: ".mysqli_error($dbc));
		}

	//If there are no changes, then return True

//	return $returnval;
}//end of function

function displaywarnings($preventry, $yourentry, $name, $nameofstat,$statID){

	if($preventry!=$yourentry){
			echo '<div class="card text-white bg-white mb-3 px-0 mx-3 col-sm-5 col-lg-4">';
				echo '<div class="card-header bg-warning text-center font-weight-bold">Possible error detected</div>';
				echo '<div class="card-body text-dark">';
					echo '<h5 class="card-title">'.$name.' '.$nameofstat.'</h5>';
					echo '<p class="card-text">Your entry: '.$yourentry.'. Previous entry: '.$preventry.'.</p>';
					echo '<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">';
					  echo '<label class="btn btn-info active col-sm-6">';

					   if($nameofstat=="Drop Location"){
					  	echo '<input type="radio" name="'.$statID.'" value ="'.$yourentry.'" id="optionuno" autocomplete="off" checked>Change';
					  echo '</label>';
					  echo '<label class="btn btn-info col-sm-6">';

					    echo '<input type="radio" name="'.$statID.'" value='.$preventry.' id="optiondos" autocomplete="off">Keep';
					  }
					  else{
					   echo '<input type="radio" name="'.$statID.'" value = '.$yourentry.' id="optionuno" autocomplete="off" checked>Change ('.$yourentry.')';
					  echo '</label>';
					  echo '<label class="btn btn-info col-sm-6">';

					    echo '<input type="radio" name="'.$statID.'" value='.$preventry.' id="optiondos" autocomplete="off">Keep ('.$preventry.')';
					}
					  echo '</label>';
					echo '</div>';
		  		echo '</div>';
			echo '</div>';
		}

		else{
			echo '<input id="id" name='.$statID.' type="hidden" value='.$preventry.'>';

		}
}


$t1p1=$t1p2=$t2p1=$t2p2='';
$selectmatchupstr='';

if(isset($_POST['revision'])){ //this is after we've reconciled
	
	$p1k=$_POST['p1k'];
	$p1d=$_POST['p1d'];
	$p1dr=$_POST['p1dr'];
	$p1p=$_POST['p1p'];

	$p2k=$_POST['p2k'];
	$p2d=$_POST['p2d'];
	$p2dr=$_POST['p2dr'];
	$p2p=$_POST['p2p'];

	$p3k=$_POST['p3k'];
	$p3d=$_POST['p3d'];
	$p3dr=$_POST['p3dr'];
	$p3p=$_POST['p3p'];

	$p4k=$_POST['p4k'];
	$p4d=$_POST['p4d'];
	$p4dr=$_POST['p4dr'];
	$p4p=$_POST['p4p'];

	$pf1ID=$_POST['performance1ID'];
	$pf2ID=$_POST['performance2ID'];
	$pf3ID=$_POST['performance3ID'];
	$pf4ID=$_POST['performance4ID'];

		// echo $p1d;
		// echo $p2d;
		// echo $p3d;
		// echo $p4d;

	$gameID=$_POST['gameID'];
	$ID=$_SESSION['userID'];

	$t1kills=$p1k+$p2k;
	$t2kills=$p3k+$p4k;
	$totalkills=$t1kills+$t2kills;

	$shouldweupload=True;
	if($p1dr=="" OR $p2dr=="" OR $p3dr=="" OR $p4dr==""){
		$shouldweupload=False;
		alert("Error: You must select a drop location for all four players. If you don't know, select \"Don't know\".","failure");
	}

	else if($p1k>100 OR $p1k<0){
		$shouldweupload=False;
		alert("Error: Player 1 should have kills between 0 and 100.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p2k>100 OR $p2k<0){
		$shouldweupload=False;
		alert("Error: Player 2 should have kills between 0 and 100.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p3k>100 OR $p3k<0){
		$shouldweupload=False;
		alert("Error: Player 3 should have kills between 0 and 100.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p4k>100 OR $p4k<0){
		$shouldweupload=False;
		alert("Error: Player 4 should have kills between 0 and 100.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}

	else if($p1d<0 OR $p1d > 1 OR $p2d<0 OR $p2d>1 OR $p3d<0 OR $p3d>1 OR $p4d<0 OR $p4d>1){
		$shouldweupload=False;
		
		alert("Error: All players need a deaths number of 0 or 1.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}

	else if($p1p<1 OR $p1p>100 OR $p2p<1 OR $p2p>100 OR $p3p<1 OR $p3p>100 OR $p4p<1 OR $p4p>100){
		$shouldweupload=False;
		alert("Error: All players need a placement between 1 and 100.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}

	else if($p1p==1 AND $p1d!=0){
		$shouldweupload=False;
		alert("Error: Player 1 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p2p==1 AND $p2d!=0){
		$shouldweupload=False;
		alert("Error: Player 2 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p3p==1 AND $p3d!=0){
		$shouldweupload=False;
		alert("Error: Player 3 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}
	else if($p4p==1 AND $p4d!=0){
		$shouldweupload=False;
		alert("Error: Player 4 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
		echo "Redirecting you back in 5 seconds.";
		echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
	}

	if($shouldweupload){
	enterperformance($pf1ID,$p1k,$p1d,$p1dr,$p1p,$gameID,$ID,"notnew",$dbc);
	enterperformance($pf2ID,$p2k,$p2d,$p2dr,$p2p,$gameID,$ID,"notnew",$dbc);
	enterperformance($pf3ID,$p3k,$p3d,$p3dr,$p3p,$gameID,$ID,"notnew",$dbc);
	enterperformance($pf4ID,$p4k,$p4d,$p4dr,$p4p,$gameID,$ID,"notnew",$dbc);


	// $updatepf1 = "UPDATE `performances` SET `kills` = '$p1k', `deaths` = '$p1d', `place` = '$p1p', `droplocation` = '$p1dr',`isScored`=`isScored`+ 1, `revisedBy` = '$ID' WHERE `performances`.`performanceID` = '$pf1ID'";

	// $updategc1=  "UPDATE `gamecontrol` SET `flaggedDate`=now(),`flaggedBy`='$ID' WHERE `gamecontrol`.`performanceID`='$pf1ID'";

	// $updatepf2 = "UPDATE `performances` SET `kills` = '$p2k', `deaths` = '$p2d', `place` = '$p2p', `droplocation` = '$p2dr',`isScored`=`isScored`+ 1, `revisedBy` = '$ID' WHERE `performances`.`performanceID` = '$pf2ID'";

	// $updategc2=  "UPDATE `gamecontrol` SET `flaggedDate`=now(),`flaggedBy`='$ID' WHERE `gamecontrol`.`performanceID`='$pf2ID'";

	// $updatepf3 = "UPDATE `performances` SET `kills` = '$p3k', `deaths` = '$p3d', `place` = '$p3p', `droplocation` = '$p3dr',`isScored`=`isScored`+ 1, `revisedBy` = '$ID' WHERE `performances`.`performanceID` = '$pf3ID'";

	// $updategc3= "UPDATE `gamecontrol` SET `flaggedDate`=now(),`flaggedBy`='$ID' WHERE `gamecontrol`.`performanceID`='$pf3ID'";

	// $updatepf4 = "UPDATE `performances` SET `kills` = '$p4k', `deaths` = '$p4d', `place` = '$p4p', `droplocation` = '$p4dr',`isScored`=`isScored`+ 1, `revisedBy` = '$ID' WHERE `performances`.`performanceID` = '$pf4ID'";

	// $updategc4= "UPDATE `gamecontrol` SET `flaggedDate`=now(),`flaggedBy`='$ID' WHERE `gamecontrol`.`performanceID`='$pf4ID'";

	// $upf1=mysqli_query($dbc, $updatepf1) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upf2=mysqli_query($dbc, $updatepf2) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upf2=mysqli_query($dbc, $updatepf3) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upf2=mysqli_query($dbc, $updatepf4) or trigger_error("Query error: ".mysqli_error($dbc));

	// $upg1=mysqli_query($dbc, $updategc1) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upg2=mysqli_query($dbc, $updategc2) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upg3=mysqli_query($dbc, $updategc3) or trigger_error("Query error: ".mysqli_error($dbc));
	// $upg4=mysqli_query($dbc, $updategc4) or trigger_error("Query error: ".mysqli_error($dbc));

	//Now we need to edit the game
	$updatedg= "UPDATE `games` SET `revisedBy`='$ID',`isScored`=`isScored`+1,`team1gameScore`='$t1kills',`team2gameScore`='$t2kills',`collabgameScore`='$totalkills' WHERE `games`.`gameID`='$gameID'";
	$upg=mysqli_query($dbc, $updatedg) or trigger_error("Query error: ".mysqli_error($dbc));
 
	alert("Successfully edited a game.","success");
}

}


else if(isset($_POST['Edit'])){ //This part uploads a first-time game
	// echo '<div class="alert alert-success" role="alert">
 //  Successfully scored a game!
	// </div>';
	//	echo "hi";
		/*
	


		//Pull all the posts*/
		$ID = (int) $_SESSION["userID"];
		$gameID=$_POST['gameID'];
		$p1drop =$_POST['p1drop'];
		$p2drop= $_POST['p2drop'];
		$p3drop =$_POST['p3drop'];
		$p4drop= $_POST['p4drop'];
		// echo "Initial post: ".$p1drop;
		// echo "Initial post: ".$p2drop;
		// echo "Initial post: ".$p3drop;
		// echo "Initial post: ".$p4drop;
		// echo $p1drop;
		// echo $p2drop;
		// echo $p3drop;
		// echo $p4drop;

		$p1kills =$_POST['p1kills'];
		$p2kills= $_POST['p2kills'];
		$p3kills =$_POST['p3kills'];
		$p4kills= $_POST['p4kills'];

		$p1place =$_POST['p1place'];
		$p2place= $_POST['p2place'];
		$p3place =$_POST['p3place'];
		$p4place= $_POST['p4place'];
		
		$p1deaths =$_POST['p1deaths'];
		$p2deaths= $_POST['p2deaths'];
		$p3deaths =$_POST['p3deaths'];
		$p4deaths= $_POST['p4deaths'];
		$performance1ID=$_POST['performance1ID'];
		$performance2ID=$_POST['performance2ID'];
		$performance3ID=$_POST['performance3ID'];
		$performance4ID=$_POST['performance4ID'];


		$t1gscore=0;
		$t2gscore=0;
		$isteam1split=0;
		$isteam2split=0;
		$areteamssplit=0;
		$collabgamescore=0;
		$isScored=1;



		//Format data

		/*
			Need to add the following properties:

				gameID [auto increment]--------------added
				createdBy [auto]---------------------new section for editedBy
				eventID [auto pass through]----------added
				matchupID [auto pass through]--------added
				gamenum [auto pass through?]---------added
				t1p1drop-----------------------------added
				t1p2drop-----------------------------added
				t2p1drop-----------------------------added
				t2p2drop-----------------------------added

				areteamssplit [auto]*/
				//If (1=3 and 2=4) or (1=4 and 2=3), then true, else false
				//if(strcmp($p1drop,$p3drop)==0 and strcmp($p2drop,$p4drop))

				//isteam1split [auto]
				//If (1=2), 1, else 0
				if(strcmp($p1drop,$p2drop)==0){
					$isteam1split=0;
				}
				else{
					$isteam1split=1;
				}


				//isteam2split
				//If (3=4), 1, else 0
				if(strcmp($p3drop,$p4drop)==0){
					$isteam2split=0;
				}
				else{
					$isteam2split=1;
				}

				/*t1p1kills----------------------------added
				t1p2kills----------------------------added
				t2p1kills----------------------------added
				t2p2kills----------------------------added

				t1p1place----------------------------added
				t1p2place----------------------------added
				t2p1place----------------------------added
				t2p2place----------------------------added
				*/

				//t1gscore [auto]
				//1kills+2kills
				$t1gscore=$p1kills+$p2kills;
				//t2gscore [auto]
				//3kills+4kills
				$t2gscore=$p3kills+$p4kills;

				//collabgamescore [auto]	
				//t1gscore+t2gscore		
				$collabgamescore=$t1gscore+$t2gscore;


		//Query for upload


			$shouldweupload=True;
				if($p1drop=="" OR $p2drop=="" OR $p3drop=="" OR $p4drop==""){
					$shouldweupload=False;
					alert("Error: You must select a drop location for all four players. If you don't know, select \"Don't know\".","failure");
				}

				else if($p1kills>100 OR $p1kills<0){
					$shouldweupload=False;
					alert("Error: Player 1 should have kills between 0 and 100.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p2kills>100 OR $p2kills<0){
					$shouldweupload=False;
					alert("Error: Player 2 should have kills between 0 and 100.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p3kills>100 OR $p3kills<0){
					$shouldweupload=False;
					alert("Error: Player 3 should have kills between 0 and 100.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p4kills>100 OR $p4kills<0){
					$shouldweupload=False;
					alert("Error: Player 4 should have kills between 0 and 100.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}

				else if($p1deaths<0 OR $p1deaths > 1 OR $p2deaths<0 OR $p2deaths>1 OR $p3deaths<0 OR $p3deaths>1 OR $p4deaths<0 OR $p4deaths>1){
					$shouldweupload=False;
					alert("Error: All players need a deaths number of 0 or 1.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}

				else if($p1place<1 OR $p1place>100 OR $p2place<1 OR $p2place>100 OR $p3place<1 OR $p3place>100 OR $p4place<1 OR $p4place>100){
					$shouldweupload=False;
					alert("Error: All players need a placement between 1 and 100.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}

				else if($p1place==1 AND $p1deaths!=0){
					$shouldweupload=False;
					alert("Error: Player 1 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p2place==1 AND $p2deaths!=0){
					$shouldweupload=False;
					alert("Error: Player 2 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p3place==1 AND $p3deaths!=0){
					$shouldweupload=False;
					alert("Error: Player 3 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}
				else if($p4place==1 AND $p4deaths!=0){
					$shouldweupload=False;
					alert("Error: Player 4 was marked as being in 1st place but also as dying. Both can't be true at the same time.","failure");
					echo "Redirecting you back in 5 seconds.";
					echo"<meta http-equiv='REFRESH' content='5;url=game.php'>";
				}

				$gameedit = "UPDATE `games` SET `team1gameScore` = '$t1gscore', `team2gameScore`='$t2gscore', `collabgameScore`='$collabgamescore', `editedBy` = '$ID', `isScored` = `isScored`+ 1 WHERE `games`.`gameID` = '$gameID' AND `games`.`isScored` < 1 AND `shouldweHide`=0"; //if isScored is 0, then upload it. Otherwise, let's compare all the data to existing data.

				if($shouldweupload){
				$geresult=mysqli_query($dbc, $gameedit) or trigger_error("Query error: ".mysqli_error($dbc)); //upload game 2
				//echo "uploaded to GAMES table - ".$gameID." - isScored = ".$isScored;



				//Add a new game if game 1 and game 2 were tied.

				//Find out if this is game 2.
				//Query game 1 and game 2 score.
				//Insert new match if g1t1+g2t1=g1t2+g2t2
				$getgameinfo="SELECT `games`.`gamenum`,`games`.`matchupID`,`games`.`team1gameScore`,`games`.`team2gameScore` FROM `games` WHERE `gameID`='$gameID'";
				$ggi=mysqli_query($dbc, $getgameinfo) or trigger_error("Query error: ".mysqli_error($dbc));
				$ggidata=mysqli_fetch_array($ggi);
				$ggimid=$ggidata['matchupID'];

				if($ggidata['gamenum']==2){
					$getgame1info="SELECT * from `games` WHERE `gamenum`=1 AND `matchupID`='$ggimid'";
					$getg=mysqli_query($dbc, $getgame1info) or trigger_error("Query error: ".mysqli_error($dbc));
					$getgrows=mysqli_fetch_array($getg);

					$geID=$getgrows['eventID'];
					$gmID=$getgrows['matchupID'];
					$ggn=3;
					$gp1=$getgrows['player1ID'];
					$gp2=$getgrows['player2ID'];
					$gp3=$getgrows['player3ID'];
					$gp4=$getgrows['player4ID'];


					if($ggidata['team1gameScore']+$getgrows['team1gameScore']==$ggidata['team2gameScore']+$getgrows['team2gameScore']){
					$getteams="SELECT `team1ID`,`team2ID` from `matches` WHERE `matchID`='$gmID'";
					$gett=mysqli_query($dbc, $gett) or trigger_error("Query error: ".mysqli_error($dbc));
					$gettrows=mysqli_fetch_array($gett);
					$gt1=$gettrows['team1ID'];
					$gt2=$gettrows['team2ID'];
					//$gt1=$getgrows['']
					//$gt2=

					$newgamequery="INSERT INTO `games`(`gameID`,`createdBy`,`eventID`,`matchupID`,`gamenum`,`player1ID`,`player2ID`,`player3ID`,`player4ID`) VALUES (NULL, '$ID','$geID','$gmID','$ggn',$gp1,$gp2,$gp3,$gp4)";

								$gcr=mysqli_query($dbc,$newgamequery) or trigger_error("Upload error: ".mysqli_error($dbc));//upload each game into the system with the relevant matchup id.
					//echo"Everything is uploading fine";
					$latestgID=mysqli_insert_id($dbc);

					////NEED TO GET TEAM IDS FOR THESE FOUR QUERIES. BEST WAY IS TO DO A JOIN ON A PERFORMANCE FROM THE SAME MATCH. THEN WE NEED TO RENAME ALL THE VARIABLES

					//For each game, we also have to create 4 performances
					//$k=1;
					$newperformancequery1="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`droplocation`,`createdBy`,`isScored`,`editedBy`) VALUES(NULL,'$latestgID','$gp1','$gt1','','','','','$ID',0,'')";
						
					$newperformancequery2="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`droplocation`,`createdBy`,`isScored`,`editedBy`) VALUES(NULL,'$latestgID','$gp2','$gt1','','','','','$ID',0,'')";
						
					$newperformancequery3="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`droplocation`,`createdBy`,`isScored`,`editedBy`) VALUES(NULL,'$latestgID','$gp3','$gt2','','','','','$ID',0,'')";
						
					$newperformancequery4="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`droplocation`,`createdBy`,`isScored`,`editedBy`) VALUES(NULL,'$latestgID','$gp4','$gt2','','','','','$ID',0,'')";


						


					//create all four performances
					$npq1=mysqli_query($dbc,$newperformancequery1) or trigger_error("Upload error: ".mysqli_error($dbc));
					$p1=mysqli_insert_id($dbc);
					$npq2=mysqli_query($dbc,$newperformancequery2) or trigger_error("Upload error: ".mysqli_error($dbc));
					$p2=mysqli_insert_id($dbc);
					$npq3=mysqli_query($dbc,$newperformancequery3) or trigger_error("Upload error: ".mysqli_error($dbc));
					$p3=mysqli_insert_id($dbc);
					$npq4=mysqli_query($dbc,$newperformancequery4) or trigger_error("Upload error: ".mysqli_error($dbc));
					$p4=mysqli_insert_id($dbc);

				}

			}//end of check if there is a tie

				//$newgamequery="INSERT INTO `games`(`gameID`,`createdBy`,`eventID`,`matchupID`,`gamenum`,`player1ID`,`player2ID`,`player3ID`,`player4ID`) VALUES (NULL, '$ID','$eID','$lastmatchupID','$i',$g_player1ID,$g_player2ID,$g_player3ID,$g_player4ID)";
			

				/*
					1) put in all the data from all four performances
					





				*/

				enterperformance($performance1ID,$p1kills,$p1deaths,$p1drop,$p1place,$gameID,$ID,"new",$dbc);
				enterperformance($performance2ID,$p2kills,$p2deaths,$p2drop,$p2place,$gameID,$ID,"new",$dbc);

				enterperformance($performance3ID,$p3kills,$p3deaths,$p3drop,$p3place,$gameID,$ID,"new",$dbc);
				enterperformance($performance4ID,$p4kills,$p4deaths,$p4drop,$p4place,$gameID,$ID,"new",$dbc);
				// echo "After function: ".$p1drop;
				// echo "After function: ".$p2drop;
				// echo "After function: ".$p3drop;
				// echo "After function: ".$p4drop;
		
				// $p1edit="UPDATE `performances` SET `kills` = '$p1kills', `deaths` = '$p1deaths', `place` = '$p1place', `droplocation` = '$p1drop', `isScored` = `isScored`+ 1, `editedBy` = '$ID' WHERE `performances`.`performanceID` ='$performance1ID'";
				// $p2edit="UPDATE `performances` SET `kills` = '$p2kills', `deaths` = '$p2deaths', `place` = '$p2place', `droplocation` = '$p2drop', `isScored` = `isScored`+ 1, `editedBy` = '$ID' WHERE `performances`.`performanceID` ='$performance2ID'";
				// $p3edit="UPDATE `performances` SET `kills` = '$p3kills', `deaths` = '$p3deaths', `place` = '$p3place', `droplocation` = '$p3drop', `isScored` = `isScored`+ 1, `editedBy` = '$ID' WHERE `performances`.`performanceID` ='$performance3ID'";
				// $p4edit="UPDATE `performances` SET `kills` = '$p4kills', `deaths` = '$p4deaths', `place` = '$p4place', `droplocation` = '$p4drop', `isScored` = `isScored`+ 1, `editedBy` = '$ID' WHERE `performances`.`performanceID` ='$performance4ID'";



				// $p1result=mysqli_query($dbc, $p1edit) or trigger_error("Query error: ".mysqli_error($dbc));
				// // echo $performance1ID;
				// // echo $performance2ID;
				// // echo $performance3ID;
				// // echo $performance4ID;
				// $p2result=mysqli_query($dbc, $p2edit) or trigger_error("Query error: ".mysqli_error($dbc));
				// $p3result=mysqli_query($dbc, $p3edit) or trigger_error("Query error: ".mysqli_error($dbc));
				// $p4result=mysqli_query($dbc, $p4edit) or trigger_error("Query error: ".mysqli_error($dbc));
				//echo "uploaded to performance table";
				alert("Successfully scored this match.","success");




				//Also need to add gameControl entries

					$gc1="INSERT INTO `gamecontrol`(`performanceID`,`gameControlID`,`createdBy`) VALUES('$performance1ID',NULL,'$ID')";
					$gc2="INSERT INTO `gamecontrol`(`performanceID`,`gameControlID`,`createdBy`) VALUES('$performance2ID',NULL,'$ID')";
					$gc3="INSERT INTO `gamecontrol`(`performanceID`,`gameControlID`,`createdBy`) VALUES('$performance3ID',NULL,'$ID')";
					$gc4="INSERT INTO `gamecontrol`(`performanceID`,`gameControlID`,`createdBy`) VALUES('$performance4ID',NULL,'$ID')";
					$gc1q=mysqli_query($dbc, $gc1) or trigger_error("Query error: ".mysqli_error($dbc));
					$gc2q=mysqli_query($dbc, $gc2) or trigger_error("Query error: ".mysqli_error($dbc));
					$gc3q=mysqli_query($dbc, $gc3) or trigger_error("Query error: ".mysqli_error($dbc));
					$gc4q=mysqli_query($dbc, $gc4) or trigger_error("Query error: ".mysqli_error($dbc));
				}
}//end of POST['f_uploadgame']



else if(isset($_POST['Revise'])){ //This is 

	//Pull the POST info
		$counter=0;
		$ID = (int) $_SESSION["userID"];
		$gameID=$_POST['gameID'];
		$p1drop =$_POST['p1drop'];
		$p2drop= $_POST['p2drop'];
		$p3drop =$_POST['p3drop'];
		$p4drop= $_POST['p4drop'];
		// echo $p1drop;
		// echo $p2drop;
		// echo $p3drop;
		// echo $p4drop;

		$p1kills =$_POST['p1kills'];
		$p2kills= $_POST['p2kills'];
		$p3kills =$_POST['p3kills'];
		$p4kills= $_POST['p4kills'];

		$p1place =$_POST['p1place'];
		$p2place= $_POST['p2place'];
		$p3place =$_POST['p3place'];
		$p4place= $_POST['p4place'];
		
		$p1deaths =$_POST['p1deaths'];
		$p2deaths= $_POST['p2deaths'];
		$p3deaths =$_POST['p3deaths'];
		$p4deaths= $_POST['p4deaths'];
		$gameID=$_POST['gameID'];
		$performance1ID=$_POST['performance1ID'];
		$performance2ID=$_POST['performance2ID'];
		$performance3ID=$_POST['performance3ID'];
		$performance4ID=$_POST['performance4ID'];
	//Pull the existing game info
		$pullgame="SELECT * from `games` WHERE `gameID`='$gameID'";
		$pgq=mysqli_query($dbc, $pullgame) or trigger_error("Query error: ".mysqli_error($dbc));
		$gamestats=mysqli_fetch_array($pgq);

		//Now we need to do a join on each performance ID
		$pullp1="SELECT `performances`.`performanceID`,`performances`.`kills`,`performances`.`deaths`,`performances`.`place`,`performances`.`droplocation`,`performances`.`editedBy`,`players`.`name` FROM `performances` JOIN `players` ON `performances`.`playerID`=`players`.`playerID` WHERE `performanceID`='$performance1ID'";

		$pullp2="SELECT `performances`.`performanceID`,`performances`.`kills`,`performances`.`deaths`,`performances`.`place`,`performances`.`droplocation`,`performances`.`editedBy`,`players`.`name` FROM `performances` JOIN `players` ON `performances`.`playerID`=`players`.`playerID` WHERE `performanceID`='$performance2ID'";

		$pullp3="SELECT `performances`.`performanceID`,`performances`.`kills`,`performances`.`deaths`,`performances`.`place`,`performances`.`droplocation`,`performances`.`editedBy`,`players`.`name` FROM `performances` JOIN `players` ON `performances`.`playerID`=`players`.`playerID` WHERE `performanceID`='$performance3ID'";

		$pullp4="SELECT `performances`.`performanceID`,`performances`.`kills`,`performances`.`deaths`,`performances`.`place`,`performances`.`droplocation`,`performances`.`editedBy`,`players`.`name` FROM `performances` JOIN `players` ON `performances`.`playerID`=`players`.`playerID` WHERE `performanceID`='$performance4ID'";

		$pp1=mysqli_query($dbc, $pullp1) or trigger_error("Query error: ".mysqli_error($dbc));
		$pp2=mysqli_query($dbc, $pullp2) or trigger_error("Query error: ".mysqli_error($dbc));
		$pp3=mysqli_query($dbc, $pullp3) or trigger_error("Query error: ".mysqli_error($dbc));
		$pp4=mysqli_query($dbc, $pullp4) or trigger_error("Query error: ".mysqli_error($dbc));

		$pr1=mysqli_fetch_array($pp1);
		$pr2=mysqli_fetch_array($pp2);
		$pr3=mysqli_fetch_array($pp3);
		$pr4=mysqli_fetch_array($pp4);

		$p1_kills=$pr1['kills'];
		$p1_deaths=$pr1['deaths'];
		$p1_place=$pr1['place'];
		$p1_drop=$pr1['droplocation'];
		$p1_name=$pr1['name'];
		$p2_kills=$pr2['kills'];
		$p2_deaths=$pr2['deaths'];
		$p2_place=$pr2['place'];
		$p2_drop=$pr2['droplocation'];
		$p2_name=$pr2['name'];
		$p3_kills=$pr3['kills'];
		$p3_deaths=$pr3['deaths'];
		$p3_place=$pr3['place'];
		$p3_drop=$pr3['droplocation'];
		$p3_name=$pr3['name'];
		$p4_kills=$pr4['kills'];
		$p4_deaths=$pr4['deaths'];
		$p4_place=$pr4['place'];
		$p4_drop=$pr4['droplocation'];
		$p4_name=$pr4['name'];

	echo "<div class='container'>";
		echo '<form action="game.php" method="post">';
		echo "<div class='row'>";
		displaywarnings($p1_kills, $p1kills, $p1_name, "Kills","p1k");
		displaywarnings($p1_deaths, $p1deaths, $p1_name, "Deaths","p1d");
		displaywarnings($p1_drop, $p1drop, $p1_name, "Drop Location","p1dr");
		displaywarnings($p1_place, $p1place, $p1_name, "Place","p1p");

		displaywarnings($p2_kills, $p2kills, $p2_name, "Kills","p2k");
		displaywarnings($p2_deaths, $p2deaths, $p2_name, "Deaths","p2d");
		displaywarnings($p2_drop, $p2drop, $p2_name, "Drop Location","p2dr");
		displaywarnings($p2_place, $p2place, $p2_name, "Place","p2p");

		displaywarnings($p3_kills, $p3kills, $p3_name, "Kills","p3k");
		displaywarnings($p3_deaths, $p3deaths, $p3_name, "Deaths","p3d");
		displaywarnings($p3_drop, $p3drop, $p3_name, "Drop Location","p3dr");
		displaywarnings($p3_place, $p3place, $p3_name, "Place","p3p");

		displaywarnings($p4_kills, $p4kills, $p4_name, "Kills","p4k");
		displaywarnings($p4_deaths, $p4deaths, $p4_name, "Deaths","p4d");
		displaywarnings($p4_drop, $p4drop, $p4_name, "Drop Location","p4dr");
		displaywarnings($p4_place, $p4place, $p4_name, "Place","p4p");
		echo "</div>";
		echo '<input class="btn btn-primary btn-block" type="submit" name="revision" value="Update Game">';
		echo "<input id='' name='performance1ID' type='hidden' value='".$performance1ID."'>";
		echo "<input id='' name='performance2ID' type='hidden' value='".$performance2ID."'>";
		echo "<input id='' name='performance3ID' type='hidden' value='".$performance3ID."'>";
		echo "<input id='' name='performance4ID' type='hidden' value='".$performance4ID."'>";
		//echo "<input id='' name='submittype' type='hidden' value=".$_POST['action'].">";
		//echo $pf1ID;
		//echo "THIS IS PF1ID";

		echo '<input id="gameID" name="gameID" type="hidden" value="'.$gameID.'">';
		echo '</form>';

		
	echo "</div>";
		//Compare everything



	//Compare each one. For each difference, display the difference and ask if they want to override.



	//If all of it is right, then update it as isScored=2, display a success message, and leave.

}


			//The games table has the following properties:
			/*



			Layout of entry form
			--Ideally, people can in their preferences choose the layout of their entry form here. Low priority

			--Ideally, people can choose if they will be scoring for both teams or just for one. 
			>>>>Who should be able to choose this?
			>>>>Should be able to score one half, both halves or the other half in the scorer section. Medium priority.


			So, we need to build the match display form first........
			No. Right now. Games link to matches in a select drop down.

			Step 1: Create a game. Step 2: Enter data for that game.


			So in Step 1:

			Matches query, players query, teams query, and events query are necessary to see:
			1) matchup with player names
			2) which event it's from

			query matchups, query teams from matchups, query players from teams, and then query events from matchups

			form: select with the players and matchups infno.
			*/

else if(isset($_POST['action'])){ //Now we need to create a form for editing the game
		//unset($_POST['action']);
		//Security Check: Need to add here a check to make sure game hasn't already been entered.
		//echo $_POST['action'];

		$ID = (int) $_SESSION["userID"];
		$gamename =$_POST['gameID'];

		//Now we need to get information from game ID.

		//-Info from games (regular query)
		$getgame="SELECT * FROM `games` WHERE `gameID`=$gamename";
		$gg=mysqli_query($dbc, $getgame) or trigger_error("Query error: ".mysqli_error($dbc));

		$gameInfo=mysqli_fetch_array($gg);
		//echo $gameInfo['gamenum'];
		$pf1ID= $gameInfo['perf1ID'];
		// echo "PF1ID = ".$pf1ID;
		$pf2ID= $gameInfo['perf2ID'];
		$pf3ID= $gameInfo['perf3ID'];
		$pf4ID= $gameInfo['perf4ID'];
		//-Info from events (join)
		$getevent="SELECT * from `events` LEFT JOIN `games` ON `events`.`eventID`=`games`.`eventID` WHERE `games`.`gameID`=$gamename AND `games`.`shouldweHide`=0";
		$ge=mysqli_query($dbc, $getevent) or trigger_error("Query error: ".mysqli_error($dbc));

		$eventInfo=mysqli_fetch_array($ge);
		//echo $eventInfo['event'];


		//-Info from matchups (join)
		$getmatch="SELECT * from `matches` LEFT JOIN `games` ON `matches`.`matchID`=`games`.`matchupID` WHERE `games`.`gameID`=$gamename";
		$gm=mysqli_query($dbc, $getmatch) or trigger_error("Query error: ".mysqli_error($dbc));

		$matchInfo=mysqli_fetch_array($gm);
		//echo $matchInfo['rounddesc'];
		
		//this form has all the game entry pieces
		
		
		if($status!="loggedout"){
		if($status=="sufficient"){
		//Here is where we need to perform an UPDATE on the existing game that was created in the match creation page.

		//Recall:

			/*
			########################################################
			[Player1/Player2 vs. Player3/Player4] | Friday Fortnite WR1 Game 1

			[Team 1 info]
			player1 drop
			player2 drop

			player1 kills
			player2 kills

			player1 place
			player2 place

			[Team 2 info]

			player3 drop
			player4 drop

			player3 kills
			player4 kills

			player3 place
			player4 place


			########################################################



			*/
			
			$namelist=getthoseplayers($gamename, $dbc);
			//need to make this form a post

			//Display EDIT scoreboard

			if($_POST['action']=='Revise'){
				$bgcolor="bg-info";
			}
			else{
				$bgcolor="bg-primary";
			}
			?>
			<div class="container">
				
			<form action="game.php" method="post">

				<div class="form-group">
				<?php
				//echo "[".$namelist[0]."/".$namelist[1]." vs. ".$namelist[2]."/".$namelist[3]."] | ".$eventInfo['event']." ".$matchInfo['rounddesc']." Game ".$gameInfo['gamenum'];

				 
				echo '<div class="card mb-3 col-sm-12 px-0 mx-3 mt-2">';
					echo '<div class="card-header font-weight-bold text-dark bg-light text-center">'.$namelist[0]."/".$namelist[1]." vs. ".$namelist[2]."/".$namelist[3]." | ".$eventInfo['event']." | ".$matchInfo['rounddesc']." | Game ".$gameInfo['gamenum'];
					echo '</div>';
				echo '</div>';

				//Team1 drops

				
				include('locations.php');
				echo '<div class="row">';
					//echo '<div class="card-deck">';


						echo '<div class="card mb-3 col-sm-12 col-md-6 px-0">';
							echo '<div class="card-header font-weight-bold text-white '. $bgcolor.' text-center">'.$namelist[0].'<span class="text-center">';
							echo '</div>';
							echo '<div class="card-body">';
								echo '<div class="form-group col-md-12">';
									echo "<div class='form-row mb-3'>";
										echo "<div class='col-xs-12 col-sm-4 col-md-12 mb-3'>";
											echo "Drop Location: <select class='form-control' name='p1drop' required>".$locations;
										echo "</div>";
										echo "<div class='col'>";
											echo 'Kills: <input type="number" class="form-control" name="p1kills" placeholder="Kills"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Place: <input type="number" class="form-control" name="p1place" placeholder="Place"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Deaths: <input type="number" class="form-control" name="p1deaths" placeholder="Deaths"autocomplete="off" required>';
										echo "</div>";
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
				
			
						echo '<div class="card mb-3 col-sm-12 col-md-6 px-0">';
							echo '<div class="card-header font-weight-bold text-white '. $bgcolor.' text-center">'.$namelist[1].'<span class="text-center">';
							echo '</div>';
							echo '<div class="card-body">';
								echo '<div class="form-group col-md-12">';
									echo "<div class='form-row mb-3'>";
										echo "<div class='col-xs-12 col-sm-4 col-md-12 mb-3'>";
											echo "Drop Location: <select class='form-control' name='p2drop' required>".$locations;
										echo "</div>";
										echo "<div class='col'>";
											echo 'Kills: <input type="number" class="form-control" name="p2kills" placeholder="Kills"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Place: <input type="number" class="form-control" name="p2place" placeholder="Place"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Deaths: <input type="number" class="form-control" name="p2deaths" placeholder="Deaths"autocomplete="off" required>';
										echo "</div>";
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
			
				echo '</div>';
				echo '<div class="row">';
				
						echo '<div class="card mb-3 col-sm-12 col-md-6 px-0 mx-0">';
							echo '<div class="card-header font-weight-bold text-white '. $bgcolor.' text-center">'.$namelist[2].'<span class="text-center">';
							echo '</div>';
							echo '<div class="card-body">';
								echo '<div class="form-group col-md-12">';
									echo "<div class='form-row mb-3'>";
										echo "<div class='col-xs-12 col-sm-4 col-md-12 mb-3'>";
											echo "Drop Location: <select class='form-control' name='p3drop' required>".$locations;
										echo "</div>";
										echo "<div class='col'>";
											echo 'Kills: <input type="number" class="form-control" name="p3kills" placeholder="Kills"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Place: <input type="number" class="form-control" name="p3place" placeholder="Place"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Deaths: <input type="number" class="form-control" name="p3deaths" placeholder="Deaths"autocomplete="off" required>';
										echo "</div>";
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';



						echo '<div class="card mb-3 col-sm-12 col-md-6 px-0 mx-0">';
							echo '<div class="card-header font-weight-bold text-white '. $bgcolor.' text-center">'.$namelist[3].'<span class="text-center">';
							echo '</div>';
							echo '<div class="card-body">';
								echo '<div class="form-group col-md-12">';
									echo "<div class='form-row mb-3'>";
										echo "<div class='col-xs-12 col-sm-4 col-md-12 mb-3'>";
											echo "Drop Location: <select class='form-control' name='p4drop' required>".$locations;
										echo "</div>";
										echo "<div class='col'>";
											echo 'Kills: <input type="number" class="form-control" name="p4kills" placeholder="Kills"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Place: <input type="number" class="form-control" name="p4place" placeholder="Place"autocomplete="off" required>';
										echo "</div>";
										echo "<div class='col'>";
											echo 'Deaths: <input type="number" class="form-control" name="p4deaths" placeholder="Deaths"autocomplete="off" required>';
										echo "</div>";
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';

					//echo '</div>'; //end of card deck
				echo '</div>'; //end of row




				echo "<input id='' name='performance1ID' type='hidden' value=$pf1ID>";
				echo "<input id='' name='performance2ID' type='hidden' value=$pf2ID>";
				echo "<input id='' name='performance3ID' type='hidden' value=$pf3ID>";
				echo "<input id='' name='performance4ID' type='hidden' value=$pf4ID>";
				//echo "<input id='' name='submittype' type='hidden' value=".$_POST['action'].">";
				//echo $pf1ID;
				//echo "THIS IS PF1ID";

				echo '<input id="gameID" name="gameID" type="hidden" value='.$gamename.'>';
				

 				echo '<input type="submit" class="btn btn-primary" name="'.$_POST['action'].'" value="Submit">';
				?>

			</form>
		</div></div>
			<?php
		//$urresult=mysqli_query ($dbc, $newgamequery) or trigger_error("Upload error: ".mysqli_error($dbc));
		}//end form
	}


		else{
			echo"Game update error: Your ID was null. Log out and log back in.";
			//echo $ID." ".$tname." ".$team1ID.$team2ID;
		}
		//echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS

}//this is if someone has clicked edit

else if(!isset($_POST["action"])){//This is the original display page	
	//$status=validate(1, "required", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){

		//Here is the games query. This queries games with property isScored set to no.
		$gamesquery="SELECT `gameID`,`matchupID`, `games`.`eventID`, `player1ID`, `player2ID`,`player3ID`, `player4ID`, `gamenum`, `matchupID`,`isScored`,`events`.`event` FROM `games` JOIN `events` ON `games`.`eventID`=`events`.`eventID` WHERE `isScored` < 4 AND `games`.`shouldweHide`=0 ORDER BY `isScored` asc";

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
				echo "<tr><td class='align-middle'>".$ename."</td> <td class='align-middle'>".$names[0]."/".$names[1]."</td>  <td class='align-middle'>".$names[2]."/".$names[3]."</td> <td class='align-middle'>".$rd."</td> <td class='align-middle'>Game ".$mqsrow['gamenum']."</td> <td class='align-middle'><form action='game.php' method='post'>";
				if($scoredstatus==0){//if it hasn't been edited before
			echo '<input id="id" name="gameID" type="hidden" value='.$gID.'><button type="submit" name="action" value="Edit" class="btn btn-primary btn-block">Score</button>';
			}
			else{//if it has been edited and only needs to be revised
			echo '<input id="id" name="gameID" type="hidden" value='.$gID.'><button type="submit" name="action" value="Revise" class="btn btn-info btn-block">Revise</button>';

			}

				echo "</form></td></tr>";
			//echo "<div class='col-sm-6 col-xs-12 col-md-4 col-xl-4 py-4'><div class='card'><div class='card-body'><h5 class='card-title text-center mb-3 pb-3'>".$names[0]."/".$names[1]." vs. ".$names[2]."/".$names[3]."</h5><p class='card-text text-center'>".$rd." Game ".$mqsrow['gamenum']."</p><p class='card-text font-italic text-center'>".$ename."</p>";
			
			
			
			
			$selectgamestr.="</option>";
			
			//echo '</form>';
			echo '</div></div></div>';
			
			
		}
		echo '</tbody><table>';
		echo "</div>"; //ends row
		
/*

			$formpart2='<form action="events.php" method="get">';
			$formpart3='<input id="id" name="id" type="hidden" value='.$eID.'><input type="submit" name="action" value="View" class="btn btn-primary"></form>';
			
			$formpart4="</div></div></div>";


*/
}
}
}//this is the end of the edit selector





	

	
?>
</div>



<?php /*

$listofmatchups = "(";
$meventID = "(";
while($matchrow = mysqli_fetch_array($mqs)){
	$meventID.=$matchrow['eventID'].","; //this adds a number and a comma to the event ID
	$matchupID=$matchrow['matchID'];
	$team1ID=$matchrow['team1ID'];
	$team2ID=$matchrow['team2ID'];
	//need to query teams in here
				$teamsquery ="SELECT `teamID`,`member1ID`,`member2ID` FROM `teams` WHERE `teamID`IN ($team1ID,$team2ID)";
				$ts=mysqli_query ($dbc, $teamsquery) or trigger_error("Query error: ".mysqli_error($dbc));
				$teamoptionslist='';
				while($teamrow=mysqli_fetch_array($ts)){
					$player1ID=$teamrow['member1ID'];
					$player2ID=$teamrow['member2ID'];
					$playerteamID=$teamrow['teamID'];

					$playersquery="SELECT `playerID`,`name` FROM `players` WHERE `playerID`IN(";
					$playersquery.=$player1ID.",".$player2ID.")";
					
					$pq=mysqli_query($dbc, $playersquery) or trigger_error("Query error: ".mysqli_error($dbc));
	
						while($miniplayerrow=mysqli_fetch_array($pq)){//This will always only have 2 results

							//If the query ID equals player 1's ID, set player1name equal to the query name
							if($miniplayerrow['playerID']==$player1ID){
								if($playerteamID==$team1ID){
								$t1p1=$miniplayerrow['name'];
							
								}
								else if($playerteamID==$team2ID){
								$t2p1=$miniplayerrow['name'];
								}

							}
							else if($miniplayerrow['playerID']==$player2ID){
								if($playerteamID==$team1ID){
									$t1p2=$miniplayerrow['name'];
								}
								else{
									$t2p2=$miniplayerrow['name'];
								}
							}
							//If they are not equal, then set player2name to the query name
								//$player2name=$miniplayerrow['name'];
	
							}//end miniplayerrow query loop

							//Create matchup text for select
							

						}//end team query loop
						$selectmatchupstr.="<option value=".$matchupID.">".$t1p1."/".$t1p2." vs. ".$t2p1."/".$t2p2."</option>";
}
$meventID.=")";

//Loop through matchups to query events for event name. later



//Here is the Events Query. This gets all events: Their name and ID.
$eventssquery ="SELECT `eventID`,`event` FROM `events`";
$rs=mysqli_query ($dbc, $eventssquery) or trigger_error("Query error: ".mysqli_error($dbc));
$eventoptionslist='';
while($row = mysqli_fetch_array($rs)){
			$n=$row['event'];
			$eID = $row['eventID'];
	$eventoptionslist .='<option value="'.$eID.'">'.$n.'</option>';

}

//Here is the teams query. This gets 


//Then, from here, we need to get the names of each of the member IDs from the players table. Right now, I'm going to do it an easy way, which will include one query for each team. This is labor-intensive.
/*
$ts=mysqli_query ($dbc, $teamsquery) or trigger_error("Query error: ".mysqli_error($dbc));
$teamoptionslist='';
while($teamrow=mysqli_fetch_array($ts)){
	$player1=$teamrow['member1ID'];
	$player2=$teamrow['member2ID'];

	$playersquery="SELECT `playerID`,`name` FROM `players` WHERE `playerID`IN(";
	$playersquery.=$player1.",".$player2.")";
	$player1name='';
	$player2name='';
	$pq=mysqli_query($dbc, $playersquery) or trigger_error("Query error: ".mysqli_error($dbc));
		while($miniplayerrow=mysqli_fetch_array($pq)){//This will always only have 2 results

			//If the query ID equals player 1's ID, set player1name equal to the query name
			if($miniplayerrow['playerID']==$player1){
				$player1name=$miniplayerrow['name'];
			}
			else{
			//If they are not equal, then set player2name to the query name
				$player2name=$miniplayerrow['name'];
			}
		}



	$tID=$teamrow['teamID'];
	echo $tID;
	echo "hi";
	$teamoptionslist .='<option value="'.$tID.'">'.$player1name."/".$player2name.'</option>';
}
//end queries*/

//'<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
?>