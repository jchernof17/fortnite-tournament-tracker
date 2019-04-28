<div id="uploadform">

<?php

//matchID,createdBy,eventID,team1ID,team2ID,rounddesc[dropdown],bestof[dropdown]
/*First we need to pull all teams. Then we need to pull all events. T

1)Drop down of every single team
2)Eventually, you can type in player1 or player2's name and the teams they are on will show up.

Then we need to pull all events. This can be the name of the event.
Drop down of event ID and name

Then we need to create two more dropdowns. round # and best of how manyu

Form
-event name
-team 1 name
-team 2 name
-round #
-best of how many


*/
//Then we need to create a "select" form line item
//Each item needs to have type


//Here is the Events Query. 
$eventssquery ="SELECT `eventID`,`event` FROM `events`";
$rs=mysqli_query ($dbc, $eventssquery) or trigger_error("Query error: ".mysqli_error($dbc));
$eventoptionslist='';
while($row = mysqli_fetch_array($rs)){
			$n=$row['event'];
			$eID = $row['eventID'];
	$eventoptionslist .='<option value="'.$eID.'">'.$n.'</option>';

}

//Here is the teams query.
$teamsquery ="SELECT `teamID`,`member1ID`,`member2ID` FROM `teams`";

//Then, from here, we need to get the names of each of the member IDs from the players table. Right now, I'm going to do it an easy way, which will include one query for each team. This is labor-intensive.

$ts=mysqli_query ($dbc, $teamsquery) or trigger_error("Query error: ".mysqli_error($dbc));
$teamoptionslist='<option value="20172017">-Select Team-</option>';
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
	// echo $tID;
	// echo "hi";
	$teamoptionslist .='<option value="'.$tID.'">'.$player1name."/".$player2name.'</option>';
}
//end queries

//'<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
?>	


<?php
	if(isset($_POST["f_uploadmatch"])){
		$ID = (int) $_SESSION["userID"];
		$eID =$_POST['f_eventname'];
		$mteam1= (int)$_POST['f_team1'];
		$mteam2= $_POST['f_team2'];
		$mroundnum=$_POST['f_roundnum'];
		$mbestof= $_POST['f_bestof'];
		$g_player1ID='';
		$g_player2ID='';
		$g_player3ID='';
		$g_player4ID='';
		
		if($status=="sufficient"){
		// $URquery="INSERT INTO `events` (`createdBy`, `date`, `event`, `streamlink`, `bracketlink`, `eventlogo`, `eventdesc`) VALUES ('$ID', '$dtl', '$evname', '$strlink', '$brlink', '$evlogo', '$evdesc')";

		//invalid data
			// echo"we got here";

			//Now we need to look up $mteam1 and $mteam2 to get the player names again.

			//mteam1 lookup. Look up the team for the players. Look up the players for their names.
			$team1lookup = "SELECT `member1ID`,`member2ID` FROM `teams` WHERE teamID=$mteam1";
			$t1l=mysqli_query($dbc,$team1lookup) or trigger_error("Query error: ".mysqli_error($dbc));
			while($t1lrow=mysqli_fetch_array($t1l)){
			$g_player1ID=$t1lrow['member1ID'];
			$g_player2ID=$t1lrow['member2ID'];
		}

			//mteam2 lookup
			$team2lookup = "SELECT `member1ID`,`member2ID` FROM `teams` WHERE `teamID`='$mteam2';";
			$t2l=mysqli_query($dbc,$team2lookup)or trigger_error("Query error: ".mysqli_error($dbc));
			while($t2lrow=mysqli_fetch_array($t2l)){
			$g_player3ID=$t2lrow['member1ID'];
			$g_player4ID=$t2lrow['member2ID'];
		}
			//Now we match those IDs to actual aliases in the players table

			$match2players = "SELECT `playerID`,`name` FROM `players` WHERE `playerID` IN ('$g_player1ID','$g_player2ID','$g_player3ID','$g_player4ID') ORDER BY FIELD(`playerID`,'$g_player1ID','$g_player2ID','$g_player3ID','$g_player4ID');";
			$m2p=mysqli_query($dbc,$match2players)or trigger_error("Query error: ".mysqli_error($dbc));


			//Now we need each of the four results to match to a player.
			$j=0;
			$p1name=$p2name=$p3name=$p4name='';
			while($rows=mysqli_fetch_array($m2p)){
				if($j==0){
					$p1name=$rows['name'];

				}
				else if(j==1){
					$p2name=$rows['name'];
				}
				else if(j==2){
					$p3name=$rows['name'];
				}
				else if(j==3){
					$p4name=$rows['name'];
				}


			}

		//This creates a new match
		$newmatchquery="INSERT INTO `matches` (`matchID`, `createdBy`, `eventID`, `rounddesc`,`bestof`, `team1ID`, `team2ID`) VALUES (NULL, '$ID', '$eID', '$mroundnum','$mbestof', '$mteam1','$mteam2')";


		$checker=True;
		$evensmallerchecker=False;
		//echo $mteam1." ".$mteam2." ".$eID." ".$mroundnum." | ";
		//Check a bunch of things

		//Check that the teams aren't the same
		if($mteam1==$mteam2){
			$checker=False;
			alert("Error: Can't add a match with the same team twice.","failure");
		}

		//Check that all players are distinct
		else if($g_player1ID==$g_player3ID OR $g_player1ID==$g_player4ID OR $g_player2ID==$g_player3ID OR $g_player2ID==$g_player4ID){
			$checker=False;
			alert("Error: Can't add a match where a player is on both teams.","failure");
			echo "<div class='pb-3'></div>";
		}
		//Check that something with event, t1,t2,r# aren't all the same (duplicate match)
		else{
			$checkmatches="SELECT `eventID`,`team1ID`,`team2ID`,`rounddesc` FROM `matches`";
			$cmq=mysqli_query ($dbc, $checkmatches) or trigger_error("Upload error: ".mysqli_error($dbc));
			while($row = mysqli_fetch_array($cmq)){
			//	echo $row['team1ID']." ".$row['team2ID']." ".$row['eventID']." ".$row['rounddesc']." | ";
				if((
					($mteam1==$row['team1ID'] AND $mteam2==$row['team2ID'])
						OR 
					($mteam1==$row['team2ID'] AND $mteam2==$row['team1ID'])) 
					AND 
					$row['eventID']==$eID 
					AND 
					$row['rounddesc']==$mroundnum){
					//This means that we have a duplicate match
					$checker=False;
					$evensmallerchecker=True;
					
				}
			}
			if($evensmallerchecker){
				alert("Error: Duplicate match detected.","failure");
				echo "<div class='pb-3'></div>";
			}
		}

		if($checker){
			alert("Successfully created match.","success");
		$urresult=mysqli_query ($dbc, $newmatchquery) or trigger_error("Upload error: ".mysqli_error($dbc));
			$lastmatchupID=mysqli_insert_id($dbc);
		//Now we need to create n new games, for n=number of expected games, that are tied to the match.
		$i=1;

		//while loop that adds i games and labels them
		while($i<=$mbestof){

			//this one is adding team1 into player3 and player4 instead of team2
		$newgamequery="INSERT INTO `games`(`gameID`,`createdBy`,`eventID`,`matchupID`,`gamenum`,`player1ID`,`player2ID`,`player3ID`,`player4ID`) VALUES (NULL, '$ID','$eID','$lastmatchupID','$i',$g_player1ID,$g_player2ID,$g_player3ID,$g_player4ID)";

			$gcr=mysqli_query($dbc,$newgamequery) or trigger_error("Upload error: ".mysqli_error($dbc));//upload each game into the system with the relevant matchup id.
			//echo"Everything is uploading fine";
			$latestgID=mysqli_insert_id($dbc);

			//For each game, we also have to create 4 performances
			//$k=1;
			$newperformancequery1="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`createdBy`,`isScored`) VALUES(NULL,'$latestgID','$g_player1ID','$mteam1',0,0,0,'$ID',0)";
				
			$newperformancequery2="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`createdBy`,`isScored`) VALUES(NULL,'$latestgID','$g_player2ID','$mteam1',0,0,0,'$ID',0)";
				
			$newperformancequery3="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`createdBy`,`isScored`) VALUES(NULL,'$latestgID','$g_player3ID','$mteam2',0,0,0,'$ID',0)";
				
			$newperformancequery4="INSERT INTO `performances`(`performanceID`,`gameID`,`playerID`,`teamID`,`kills`,`deaths`,`place`,`createdBy`,`isScored`) VALUES(NULL,'$latestgID','$g_player4ID','$mteam2',0,0,0,'$ID',0)";
				


			//create all four performances
			$npq1=mysqli_query($dbc,$newperformancequery1) or trigger_error("Upload error: ".mysqli_error($dbc));
			$p1=mysqli_insert_id($dbc);
			$npq2=mysqli_query($dbc,$newperformancequery2) or trigger_error("Upload error: ".mysqli_error($dbc));
			$p2=mysqli_insert_id($dbc);
			$npq3=mysqli_query($dbc,$newperformancequery3) or trigger_error("Upload error: ".mysqli_error($dbc));
			$p3=mysqli_insert_id($dbc);
			$npq4=mysqli_query($dbc,$newperformancequery4) or trigger_error("Upload error: ".mysqli_error($dbc));
			$p4=mysqli_insert_id($dbc);
			//echo "performances created";

			//need to link the performances back to the game
			$updateGame = "UPDATE `games` SET `perf1ID` = '$p1', `perf2ID` = '$p2', `perf3ID` = '$p3', `perf4ID` = '$p4' WHERE `games`.`gameID` = '$latestgID'";
			$ug=mysqli_query($dbc,$updateGame) or trigger_error("Upload error: ".mysqli_error($dbc));	//edits the performances back in


			$i++;
		}//end while loop
		}	
		}//end of valid ID match upload


		else{
			echo"Match creation error: Your ID was null. Log out and log back in.";
			// echo $ID." ".$tname." ".$team1ID.$team2ID;
		}
		//echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS

	}//end of post match upload
?>
<form action="match.php" method="post" class="needs-validation" novalidate> 
		
			<div class="form-row" id="u1">

				<?php
				//team ID, createdBy, team name, player1, player2, logoimg




				//YYYY-MM-DDTHH:MM:SS
			?>
			<div class="form-group col-sm-12 col-lg-6" id="u1">
			Event Name
			<select name="f_eventname" class="form-control-sm">
				<?php
				echo $eventoptionslist;
				?>
			</select>
			</div>
		</div>
			<div class="form-row" id="u2">
			<div class="col-md-5 col-sm-12 pb-3" id="u1">Team 1
			<select name="f_team1" class="form-control-sm" required> 
				<?php
				echo $teamoptionslist;
				?>
			</select>
		</div>
			<div class="col-md-5 col-sm-12 pb-3" id="u1">Team 2
			<select name="f_team2" class="form-control-sm" required> 
				<?php
				echo $teamoptionslist;
				?>
			</select></div>	
		</div>
		<div class="form-row" id="u1">
			<div class="col-md-6 col-sm-12 pb-3" id="u4">Round #:
			<select name="f_roundnum" class="form-control-sm" required>
				<option value='WR1' selected="selected">WR1</option>
				<option value='WR2'>WR2</option>
				<option value='WR3'>WR3</option>
				<option value='WR4'>WR4</option>
				<option value='WR5'>WR5</option>
				<option value='WR6'>WR6</option>
				<option value='LR1'>LR1</option>
				<option value='LR2'>LR2</option>
				<option value='LR3'>LR3</option>
				<option value='LR4'>LR4</option>
				<option value='LR5'>LR5</option>
				<option value='LR6'>LR6</option>
				<option value='LR7'>LR7</option>
				<option value='LR8'>LR8</option>
				<option value='Finals'>Finals</option>
				<option value='Finals (continuation)'>Finals (continuation)</option>
			</select>
			</div>
			</div>
			<div class="form-row" id="u1">
			<div class="form-group" id="u5">How many games in a typical match? (2?)
			<select name="f_bestof" class="form-control-sm" required>
				<option value='1'>1</option>
				<option value='2' selected="selected">2</option>
				<option value='3'>3</option>
		</select>
		</div>
	</div>
	<div class="form-row" id="u1">
			<div class="form-group" id="u6">
				
			<button type="submit" name="f_uploadmatch" class="form-control btn btn-primary text-white" value="Upload Match">Create Match</button>
		</div>
	</div>
</form>
</div>