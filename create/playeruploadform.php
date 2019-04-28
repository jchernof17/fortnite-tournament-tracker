

<?php
	if(isset($_POST["f_uploadplayer"])){
		$ID = (int) $_SESSION["userID"];

		$pname =mysqli_real_escape_string($dbc, $_POST['f_alias']);
		//echo $pname." IS THE PNAME";
		$plink= mysqli_real_escape_string($dbc, $_POST['f_plink']);;

		$twlink= mysqli_real_escape_string($dbc, $_POST['f_twitter']);
		//$pimage= $_POST['f_image'];
		$prealname = mysqli_real_escape_string($dbc, $_POST['f_realname']);
		$pbio = mysqli_real_escape_string($dbc, $_POST['f_bio']);
		
		if($status=="sufficient"){
		// $URquery="INSERT INTO `events` (`createdBy`, `date`, `event`, `streamlink`, `bracketlink`, `eventlogo`, `eventdesc`) VALUES ('$ID', '$dtl', '$evname', '$strlink', '$brlink', '$evlogo', '$evdesc')";

		//invalid data
		$newplayerquery="INSERT INTO `players` (`playerID`, `createdBy`, `name`, `realname`, `twitter`, `stream`, `bio`) VALUES (NULL, '$ID', '$pname', '$prealname', '$twlink', '$plink', '$pbio')";

		//$newUEquery="INSERT INTO `events` (`eventID`, `createdBy`, `event`, `bracketlink`, `streamlink`, `eventdesc`) VALUES (NULL, '$ID', '$event', '$brlink', '$strlink', '$evdesc')";

		//the below version is the most bare-bones.

		//$newUEquery="INSERT INTO `events` (`eventID`, `createdBy`, `event`, `eventdesc`) VALUES (NULL, '$ID', '$event', '$evdesc')";
		//$four=4;
		//$newUEquery="INSERT INTO `events` (`createdBy`) VALUES ('$four')";

		

		//We need to check if this player exists already
		$checkname=False;
		$checkplayersquery="SELECT `name` FROM `players` WHERE `name`='$pname'";
		$cpq=mysqli_query ($dbc, $checkplayersquery) or trigger_error("Upload error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($cpq)){
			if($row['name']==$pname){
				alert("Error: Player with name '".$pname."' already exists.","failure");
				$checkname=True;
			}
		}
		if($pname==''){
			alert("Error: Can't upload player with no name.","failure");
			$checkname=True;
		}
		if(!$checkname){

		$urresult=mysqli_query ($dbc, $newplayerquery) or trigger_error("Upload error: ".mysqli_error($dbc));
		alert("Successfully added new player ".$pname.".","success");
		}
	}
		else{
			echo"Player creation error: Your ID was null. Log out and log back in.";
			echo $ID." ".$pname." ".$pname;
		}
	//	echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS

	}//end isset post
?>
<div class="col pb-2"></div>
	<div id="uploadform">
<form action="player.php" method="post" class="needs-validation" novalidate> 
		<div class="form-row">
			<div class="form-group col-sm-6 col-lg-6" id="u1">

				<?php
				//YYYY-MM-DDTHH:MM:SS
			?>
			<!-- <label for="alias">Player Name (alias)</label> -->
			<input type="text" name="f_alias" class="form-control" placeholder="Player Name (alias)" id="vc1" required>
				<div class="valid-feedback">
	      		</div>
	      		<div class="invalid-feedback">
	      			Player name can't be empty
	      		</div>
			</div>
			<div class="form-group col-sm-6 col-lg-6" id="u2">
			<input type="text" name="f_realname" class="form-control" placeholder="Real Name (optional)">
			<div class="valid-feedback">
      		</div>
      		<div class="invalid-feedback">
      			Enter a name
      		</div>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-xs-12 col-sm-6 col-md-6" id="u3">
			<input type="text" name="f_twitter" class="form-control" placeholder="Twitter link (optional)">
		</div>
			<div class="form-group col-xs-12 col-sm-6 col-md-6" id="u3">
			<input type="text" name="f_plink"  class="form-control" placeholder="Stream link (optional)">
			</div>
		</div>
		<div class="form-row">
			<div class="col"></div>
			<div class="form-group col-sm-12" id="u4">
			<input type="textarea" name="f_bio" class="form-control" placeholder="Player bio (optional)">
			</div>
			<div class="col"></div>
			<!-- <div class="up" id="u5">
			<input type="textarea" name="feeling" plceholder="How did you feel?">
			</div> -->
		</div>
		
		<div class="form-group col-xs-12 col-sm-6 col-md-8 px-0">
		   <!--  <label for="exampleFormControlFile1"></label> -->
		    <input type="text" class="form-control" id="exampleFormControlFile1 name="f_image" placeholder="Player Image (imgur png)">
		  </div>
		 

			<button type="submit" name="f_uploadplayer" class="form-control btn btn-primary text-white" value="Create Player">Create Player</button>
		
	
</form>

</div>