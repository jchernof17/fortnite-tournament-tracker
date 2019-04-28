<div id="eventuploadform">
<form action="event.php" method="post"> 
		
			<div class="up" id="u1">

				<?php
				//YYYY-MM-DDTHH:MM:SS
				$td = substr(date('c'),0,17)."00";
			echo"<input type='datetime-local' name='f_date1' value='$td'>";
			?>
			</div>
			<div class="up" id="u2">
			<input type="text" name="f_event" placeholder="Event Name">
			</div>
			<div class="up" id="u3">
			<input type="text" name="f_bracketlink" placeholder="Bracket Link">
			<input type="text" name="f_streamlink" placeholder="Stream">
			<input type="text" name="f_eventlogo" placeholder="Event Logo jpg">
			</div>
			<div class="up" id="u4">
			<input type="textarea" name="f_eventdesc" placeholder="Event Description">
			</div>
			<!-- <div class="up" id="u5">
			<input type="textarea" name="feeling" placeholder="How did you feel?">
			</div> -->
			<div class="up" id="u6">
				
			<input type="submit" name="f_uploadevent" value="Create Event">
		</div>
</form>

<?php
	if(isset($_POST["f_uploadevent"])){
		$ID = (int) $_SESSION["userID"];
		$dtl =mysqli_real_escape_string ($dbc, $_POST['f_date1']);
		$evname =$_POST['f_event'];
		$strlink= $_POST['f_streamlink'];
		$brlink= $_POST['f_bracketlink'];
		$evlogo= $_POST['f_eventlogo'];
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


		$evdesc=$_POST['f_eventdesc'];
		//$feel=$_POST['feeling'];
		if(!($ID==NULL)){
		$URquery="INSERT INTO `fortaleza`.`events` (`createdBy`, `eventID`, `date`, `event`, `streamlink`, `bracketlink`, `eventlogo`, `eventdesc`, `feeling`) VALUES ('$ID', NULL, '$dtl', '$evname', '$strlink', '$brlink', '$evlogo', '$evdesc')";
		$urresult=mysqli_query ($dbc, $URquery) or trigger_error("Upload error: ".mysqli_error($dbc));
		}
		else{
			echo"Event creation error: You had some invalid data.";
		}
		echo"<meta http-equiv='REFRESH' content='0;url=success.php'>";
		//YYYY-MM-DDTHH:MM:SS

	}
?>
</div>