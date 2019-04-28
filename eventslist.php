<?php
		$eventscols="SELECT `event`,`eventdesc` FROM `events`";
		$ecq=mysqli_query($dbc, $eventscols) or trigger_error("Events loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($ecq)){
			//Create a div
			//Populate said div with name and desc
			//Close div
			$evname=$row['event'];
			$evdesc=$row['eventdesc'];
			echo "<div style='height: 150px'id='event' class=' mh-80 col-sm-3 bg-light ml-1 mr-1 mb-3 mt-3'><p class='font-weight-bold'>".$evname."</p><p class='font-weight-light text-truncate'>".$evdesc."</p></div>";

		}
		?>