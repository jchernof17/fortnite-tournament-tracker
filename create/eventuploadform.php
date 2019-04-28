<div id="eventuploadform">

	<?php
if(isset($_POST["f_uploadevent"])){
		$ID = (int) $_SESSION["userID"];
		$dtl =mysqli_real_escape_string ($dbc, $_POST['f_date1']);
		$evname =mysqli_real_escape_string($dbc, $_POST['f_event']);
		$strlink= mysqli_real_escape_string($dbc, $_POST['f_streamlink']);

		$brlink= mysqli_real_escape_string($dbc, $_POST['f_bracketlink']);
		$evlogo= mysqli_real_escape_string($dbc, $_POST['f_eventlogo']);
		// $correctedTimeMin=$timeMin;
		// $correctedTimeHr=$timeHr;
		// if($timeMin>=60){
		// 	$correctedTimeMin=$timeMin%60;
		// 	$correctedTimeHr=$timeHr+(($timeMin-$correctedTimeMin)/60);
		// }


		// if($timeSec>=60){
		// 	$correctedTimeSec=$timeSec%60;
		// 	$correctedTimeMin=$correctedTimeMin+(($timeSec-$correctedTimeSec)/60);
		// }
		// else{
		// 	$correctedTimeSec=$timeSec;
		// }


		$evdesc=mysqli_real_escape_string($dbc, $_POST['f_eventdesc']);
		// echo $ID; echo $dtl; echo $evname; echo $strlink; echo $brlink; echo $evlogo; echo $evdesc;
		//$feel=$_POST['feeling'];
		if(($status=="sufficient")){
		// $URquery="INSERT INTO `fortaleza`.`events` (`createdBy`, `date`, `event`, `streamlink`, `bracketlink`, `eventlogo`, `eventdesc`) VALUES ('$ID', '$dtl', '$evname', '$strlink', '$brlink', '$evlogo', '$evdesc')";

		//invalid data
		$newUEquery="INSERT INTO `events` (`eventID`, `createdBy`, `event`, `bracketlink`, `streamlink`, `eventdesc`, `eventlogo`) VALUES (NULL, '$ID', '$evname', '$brlink', '$strlink', '$evdesc', '$evlogo')";

		//If the name already exists
		$allevents=mysqli_query ($dbc, "SELECT `event`,`eventID` from `events`") or trigger_error("Upload error: ".mysqli_error($dbc));
		$checkevent=False;
		while($row=mysqli_fetch_array($allevents)){
			if($evname==$row['event']){
				//throw error
				alert("Can't upload event. An event with name '".$evname."' already exists.","failure");
				echo "<div class='col pb-2'></div>";
				$checkevent=True;
			}
		}
		if($evname==''){
			$checkevent=True;
		}
		if(!$checkevent){
		$urresult=mysqli_query ($dbc, $newUEquery) or trigger_error("Upload error: ".mysqli_error($dbc));
		}
		}
		else{
			echo"Event creation error: Your ID was null. Log out and log back in.";
			echo $ID." ".$evname." ".$evdesc;
		}
		//echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS

	}


	?>
<form action="event.php" method="post" class="needs-validation" novalidate> 
		<div class="form-row">
			<div class="form-group col-sm-6 col-lg-6" id="u1">

			<!-- <label for="alias">Player Name (alias)</label> -->

			<input type="text" name="f_event" class="form-control" placeholder="Event Name" id="vc1" required>
				<div class="valid-feedback">
	      		</div>
	      		<div class="invalid-feedback">
	      			Event name can't be empty
	      		</div>
			</div>
			<div class="form-group col-sm-6 col-lg-6" id="u2">
			<input type="text" class="form-control" name="f_bracketlink" placeholder="Bracket Link (optional)">
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
			<input type="text" name="f_streamlink" placeholder="Stream Link (optional)"  class="form-control">
			</div>
		</div>
		<div class="form-row">
			<div class="col"></div>
			<div class="form-group col-sm-12" id="u4">
			<input type="textarea" class="form-control"name="f_eventdesc" placeholder="Event Description">
			</div>
			<div class="col"></div>
			<!-- <div class="up" id="u5">
			<input type="textarea" name="feeling" plceholder="How did you feel?">
			</div> -->
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-8 px-0">
		   <!--  <label for="exampleFormControlFile1"></label> -->
		    <input type="text" class="form-control" id="exampleFormControlFile1" name="f_eventlogo" placeholder="Event Logo png (imgur png)">
		  </div>
		  <div class="form-group col-xs-12 col-sm-6 col-md-8 px-0">
		  	<?php
				//YYYY-MM-DDTHH:MM:SS
				$td = substr(date('c'),0,17)."00";
			echo"<input type='datetime-local' class='form-control' name='f_date1' value='".$td."'>";
			?>
		</div>
				
			<button type="submit" name="f_uploadevent" class="form-control btn btn-primary text-white" value="Create Event">Create Event</button>
		</div>
</form>

<?php
	
?>
</div>