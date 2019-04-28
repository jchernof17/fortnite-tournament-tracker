<div id="uploadform">

<?php
//First we need to pull all the player ID and alias pairs
//Then we need to create a "select" form line item
//Each item needs to have type
$rsquery ="SELECT `playerID`,`name` FROM `players`";
$rs=mysqli_query ($dbc, $rsquery) or trigger_error("Upload error: ".mysqli_error($dbc));
$optionslist='<option value="20172017">-Select Player-</option>';
while($row = mysqli_fetch_array($rs)){
			$n=$row['name'];
			$pID = $row['playerID'];
	$optionslist .='<option value="'.$pID.'">'.$n.'</option>';

}
//'<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
?>	


<?php
	if(isset($_POST["f_uploadteam"])){
		$ID = (int) $_SESSION["userID"];
		$tname =$_POST['f_alias'];
		$tplayer1= $_POST['f_player1'];
		$tplayer2= $_POST['f_player2'];
		//echo $tplayer1.$tplayer2;
		$timage= $_POST['f_teamlogo'];

		unset($_POST['f_uploadteam']);
		unset($_POST['f_alias']);
		unset($_POST['f_player1']);
		unset($_POST['f_player2']);
		unset($_POST['f_teamlogo']);

		
		if($status=="sufficient"){
		// $URquery="INSERT INTO `events` (`createdBy`, `date`, `event`, `streamlink`, `bracketlink`, `eventlogo`, `eventdesc`) VALUES ('$ID', '$dtl', '$evname', '$strlink', '$brlink', '$evlogo', '$evdesc')";

		//invalid data
		$newteamquery="INSERT INTO `teams` (`teamID`, `createdBy`, `teamname`, `logo`, `member1ID`, `member2ID`) VALUES (NULL, '$ID', '$tname', '$timage', '$tplayer1','$tplayer2')";

		//echo "The tplayer1 is ".$tplayer1;

		if($tplayer1==20172017 OR $tplayer2==20172017){
			alert("Error: You didn't enter two distinct players. Try again.","failure");
			$insert=0;
		}

		//If they're the same memberID, print that error.
		else if($tplayer1==$tplayer2){
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
				echo "Hey now. You can't make a team with the same player. Try again.";
				echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>';
			echo '</div>';
			$insert=0;
		}

		else{

		//else, check all teams for member1ID IN tplayer1,tplayer2 AND member2ID IN tplayer1, tplayer2
			$checkallteams="SELECT `teamID`,`member1ID`,`member2ID` FROM `teams` WHERE `member1ID`IN('$tplayer1','$tplayer2') AND `member2ID` IN('$tplayer1','$tplayer2')";
			$catresult=mysqli_query ($dbc, $checkallteams) or trigger_error("Upload error: ".mysqli_error($dbc));
			$insert=1;
			while($row = mysqli_fetch_array($catresult)){
				
		//Get the team ID of the rows that conflict and print them
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
				echo "Sorry chief. A team with those two players already exists. (Team ID ".$row['teamID'].")";
				echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>';
				echo '</div>';
				$insert=0;
			}//end while

			if($insert==1){
			$urresult=mysqli_query ($dbc, $newteamquery) or trigger_error("Upload error: ".mysqli_error($dbc));
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
				echo "Successfully added new team.";
				echo '</div>';
			}//end if

		}//end else

		}//end ID CHECKER

		else{
			echo"Team creation error: Couldn't verify your credentials. Log out and log back in.";
			echo $ID." ".$tname." ".$member1ID.$member2ID;
		}
		//echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS
		

	}
?>
<form action="team.php" method="post" class="needs-validation" novalidate> 
		
			<div class="form-row" id="u1">
				<div class="form-group col-sm-6 col-lg-6" id="u1">

				<?php
				//YYYY-MM-DDTHH:MM:SS
			?>
			<!-- <label for="alias">Player Name (alias)</label> -->
			<input type="text" name="f_alias" class="form-control" placeholder="Team Name (optional)" id="vc1">
				<div class="valid-feedback">
	      		</div>
	      		<div class="invalid-feedback">
	      		</div>
			</div>
			</div>
			<div class="form-row" id="u1">
			<div class="col-md-6 col-sm-12 pb-3" id="u2">Player 1
			<select name="f_player1" class="form-control-sm" required> 
				<?php
				echo $optionslist;
				?>
			</select></div>
			<div class="col-md-6 col-sm-12 pb-3" id="u2">
			Player 2
			<select name="f_player2" class="form-control-sm" required> 
				<?php
				echo $optionslist;
				?>
			</select></div>	</div>
			<div class="form-row" id="u1">
			<div class="form-group col-sm-6 col-lg-6" id="u1">
			<input type="text" name="f_teamlogo" class="form-control" placeholder="Team Logo (imgur png)">
			</div>
			<!-- <div class="up" id="u5">
			<input type="textarea" name="feeling" plceholder="How did you feel?">
			</div> -->
		</div>
			<div class="form-row" id="u6">
				<div class="form-group" id="u1">
				
			<button type="submit" name="f_uploadteam" class="form-control btn btn-primary text-white" value="Create Team">Create Team</button>
		</div>
</form>
</div>