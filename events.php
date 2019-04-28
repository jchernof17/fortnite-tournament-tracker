<?php

session_start();

?>
<html lang="en">
	<head>
	<?php
		include $_SERVER['DOCUMENT_ROOT']."/sheets.html";

		?>

	<script type="text/javascript">
	$(document).ready( function () {
		
   var t=$("#leaderboard").DataTable({
   	"responsive": true,

   	"order":[[4,'desc']],
   	"info":     false,
   	"paging":	false,
   	"searching": false,
   	"scrollX": true,
   	"scrollY": true,
   	"fixedHeader": {
            header: true,
            footer: true
        }

   	});
  var s=$("#teamleaderboard").DataTable({
  	"responsive": true,
   	"order":[[2,'desc']],
   	"info":     false,
   	"paging":	false,
   	"searching": false,
   	"scrollX": true,
   	"scrollY": true

   	});

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

	dt.columns.adjust();    

    s.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
	});//end of script

	</script>
	<!-- <style>
	table.dataTable,
		table.dataTable th,
		table.dataTable td {
		  -webkit-box-sizing: content-box;
		  -moz-box-sizing: content-box;
		  box-sizing: content-box;
}
</style> -->

		<title>Events</title>
	</head>
<body>
<?php


	//session_start();
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";
	$status=validate(1, "notrequired", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
		}
	}

	//If GET is not set, then show a view of events to choose from.
	if(!isset($_GET['id'])){//Here's what we do if we need to choose an event
	echo '<div class="container">';
		echo "<div class='row'>";

	//Pull all the events with matches
	$pullevents="SELECT DISTINCT `events`.`event` as 'name',`events`.`eventID` as 'eID',`events`.`eventdesc` as 'evdesc',`matches`.`eventID` from `events` INNER JOIN `matches` ON `events`.`eventID`=`matches`.`eventID`";
	$peq=mysqli_query($dbc,$pullevents) or trigger_error("Query error: ".mysqli_error($dbc));
			while($row=mysqli_fetch_array($peq)){
			//Loop through each event
				$eID=$row['eID'];
				$ename=$row['name'];
				$evdesc=$row['evdesc'];
			//Create a form for each one with a button. Pass through hidden field with ID for each form. the button has id=action and value=view
				//event name. event id. evdesc
			$formpart1="<div class='col-sm-6 col-md-4 py-5'><div class='card'><div class='card-body'><h5 class='card-title text-center mb-3 pb-3'>".$ename."</h5><p class='card-text'>".$evdesc."</p>";
			
			$formpart2='<form action="events.php" method="get">';
			$formpart3='<input id="id" name="id" type="hidden" value='.$eID.'><input type="submit" name="action" value="View" class="btn btn-primary btn-block"></form>';
			
			$formpart4="</div></div></div>";
			echo $formpart1.$formpart2.$formpart3.$formpart4;


			}//end while loop
		echo "</div>";
		echo "</div>";

	


	}//end of no set ID

	else{//Here's what we do if we need to display the stats from the event.
	$eID=$_GET['id'];
	$getename="SELECT `event` FROM `events` WHERE `eventID`='$eID'";
	$geq=mysqli_query($dbc,$getename);
	$row=mysqli_fetch_array($geq);
	$eventname=$row['event'];

	//Player Leaderboard


//we need to query performances for team ID. Then, we need to pull the # kills and the count of matches. Then, for the first match we need to join playerID on each performance to players for 'name'. Then, we can display name/name: kills/match.
	
	//Creates the class of a team.
	//Each team has an ID, 2 players, a number of kills and a number of matches










	//For the player leaderboard, we need to query performances for each playerID. Then, we need to add up the total number of kills for each player. We also need to left join each player with their name.

	//Player object that has values pID, name, kills, # matches, kills/match
	

	class Player {
		public $pID;
		public $numkills;
		public $nummatches;
		public $pname;
		public $killspermatch; 


		// public function __construct($pID, $dbc){
		// 	$this->pID=$pID;
		// 	//Get all the kills with the performances query and add to numkills

		// 			$howmanykills="SELECT `playerID`,`kills`,`performanceID`,(select count(*) from `performances` where `playerID`='$pID' AND `isScored`>0) as `counter` FROM `performances` WHERE `playerID` = '$pID' AND `isScored` > 0";
		// 			$hmk=mysqli_query($dbc, $howmanykills) or trigger_error("Solo kills leaderboard loading error: ".mysqli_error($dbc));
		// 			$simpletempcounter=0;
		// 		while($rows=mysqli_fetch_array($hmk)){
		// 			$simpletempcounter+=(int) $rows['kills'];
		// 			$counter=(int)$rows['counter']; //This sets $counter equal to nummatches and $simpletempcounter equal to total number of kills
		// 		}
		// 		//Use the counter to get numMatches. This counts one match for each player, so we need counter/2.
				
		// 		//add simpletempcounter to tkills.
		// 		$this->numkills=(int)$simpletempcounter;
		// 		$this->nummatches=(int)$counter;

				
			

		// 		$getpname="SELECT DISTINCT `players`.`name` as `nombre` FROM `players` LEFT JOIN `performances` ON `players`.`playerID`=`performances`.`playerID` WHERE `performances`.`playerID`='$pID'";
		// 		$p1q=mysqli_query($dbc, $getpname) or trigger_error("Solo kills leaderboard loading error: ".mysqli_error($dbc));
		// 		while($rows1=mysqli_fetch_array($p1q)){
		// 			$this->pname=$rows1['nombre'];
		// 		}



		// }//end of constructor
		public function __construct($pID, $dbc,$eID){
			$this->pID=$pID;

			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `playerID`,`kills`,`performanceID`,(select count(*) from `performances` where `playerID`='$pID' AND `isScored`>0) as `counter` FROM `performances` LEFT JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `playerID` = '$pID' AND `performances`.`isScored` > 0 AND `games`.`eventID`='$eID'";
					$hmk=mysqli_query($dbc, $howmanykills) or trigger_error("Solo kills leaderboard loading error: ".mysqli_error($dbc));
					$simpletempcounter=0;
				while($rows=mysqli_fetch_array($hmk)){
					$simpletempcounter+=(int) $rows['kills'];
					$counter=(int)$rows['counter']; //This sets $counter equal to nummatches and $simpletempcounter equal to total number of kills
				}
				//Use the counter to get numMatches. This counts one match for each player, so we need counter/2.
				
				//add simpletempcounter to tkills.
				$this->numkills=(int)$simpletempcounter;
				$this->nummatches=(int)$counter;

				
			

				$getpname="SELECT DISTINCT `players`.`name` as `nombre` FROM `players` LEFT JOIN `performances` ON `players`.`playerID`=`performances`.`playerID` WHERE `performances`.`playerID`='$pID'";
				$p1q=mysqli_query($dbc, $getpname) or trigger_error("Solo kills leaderboard loading error: ".mysqli_error($dbc));
				while($rows1=mysqli_fetch_array($p1q)){
					$this->pname=$rows1['nombre'];
				}



		}//end of constructor
		public function createprow(){
			if($this->nummatches>0){
			$killspermatch=round($this->numkills/$this->nummatches,2);
			
			$ret="<tr><td></td><td>".$this->pname."</td><td>".$this->numkills."</td><td>".$this->nummatches."</td><td>".$killspermatch."</td></tr>";
			return $ret;
		}
		return '';
		}
	}//end of class

		//create the top of the leaderboard

		//We need to select PERFORMANCES left join to GAMEID where game.eventID=eID
	echo '<div class="container">';
		echo '<div class="row">';

			echo '<div class="card-header col-md-12 bg-primary rounded mt-2"><h3 class="mb-0 text-center text-white">'.$eventname.'</h3></div>';
		echo '</div>';
	echo '</div>';
	echo '<div class="container">';?>
		<div class='row'>
		<div class='col-xs-12 col-sm-8 py-5'>

		<?php
		

		echo '<div class="card border--"> <div class="card-header bg-info">
                            <h3 class="mb-0 text-center text-white">Individual Stats</h3>
                        </div>
  <div class="card-body col-sm-12 col-md-12 responsive no-wrap pt-0">
    <div class="app-table-responsive"><table id="leaderboard" width="100%" class="table display responsive no-wrap table-striped table-hover col-sm-12 col-lg-8 mb-0"><thead><tr><th></th><th>Player</th><th>Kills</th><th>Matches</th><th>Kills/Match</th></tr></thead><tbody>';
		$perfscols="SELECT `performances`.`gameID`, `playerID`, `performances`.`isScored`,	`games`.`eventID` as `eID` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `performances`.`isScored`>0 AND `games`.`eventID`='$eID' GROUP BY `playerID`";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
			$line= new Player($row['playerID'],$dbc,$row['eID']);

			echo $line->createprow();
		}
		?>
		</tbody></table> </div>
  </div>
</div>
		</div>
		



		
		
<?php
//we need to query performances for team ID. Then, we need to pull the # kills and the count of matches. Then, for the first match we need to join playerID on each performance to players for 'name'. Then, we can display name/name: kills/match.
	
	//Creates the class of a team.
	//Each team has an ID, 2 players, a number of kills and a number of matches

	class Team {
		public $tID;
		public $p1;
		public $p2;
		public $numkills;
		public $nummatches;


		public function __construct($tID, $dbc, $eID){
			$this->tID=$tID;
			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `teamID`,`kills`,`performanceID`,(select count(*) from `performances` where `teamID`='$tID' AND `isScored`>0) as `counter` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `teamID` = '$tID' AND `performances`.`isScored` > 0 AND `games`.`eventID`='$eID'";
					$hmk=mysqli_query($dbc, $howmanykills) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
					$simpletempcounter=0;
				$counter=0;
				//echo "Team ID: ". $tID."<br>";

				while($rows=mysqli_fetch_array($hmk)){
				//	echo "inside while";
					//echo $rows['counter'];
					$simpletempcounter+=(int) $rows['kills'];
					$counter=(int)$rows['counter']/2;
					//echo $counter." matches<br>";
				}
				//Use the counter to get numMatches. This counts one match for each player, so we need counter/2.
				
				//add simpletempcounter to tkills.
				$this->numkills=(int)$simpletempcounter;
				$this->nummatches=(int)$counter;

				
			

				$playerIDquery1="SELECT `players`.`name` as `nombre` FROM `players` LEFT JOIN `teams` ON `players`.`playerID`=`teams`.`member1ID` WHERE `teams`.`teamID`='$tID'";
				$p1q=mysqli_query($dbc, $playerIDquery1) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
				while($rows1=mysqli_fetch_array($p1q)){
					$this->p1=$rows1['nombre'];
				}
				$playerIDquery2="SELECT `players`.`name` as `nombre` FROM `players` LEFT JOIN `teams` ON `players`.`playerID`=`teams`.`member2ID` WHERE `teams`.`teamID`='$tID'";
				$p2q=mysqli_query($dbc, $playerIDquery2) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
				while($rows2=mysqli_fetch_array($p2q)){
					$this->p2=$rows2['nombre'];
				}




		}//end of constructor

		public function createlrow(){
			if($this->nummatches>0){
			$killspermatch=round($this->numkills/$this->nummatches,2);
			
			$ret="<tr><td></td><td>".$this->p1."/".$this->p2."</td><td>".$killspermatch."</td></tr>";
			return $ret;
		}
		return '';
		}
	}//end of class

		//create the top of the leaderboard';
	echo '<div class="card border"> <div class="card-header bg-info">
                            <h3 class="mb-0 text-center text-white">Team Stats</h3>
                        </div>';
		echo '<div class=col-xs-12 col-sm-3 py-5>';
		echo '<table id="teamleaderboard" class="table display table-striped table-hover col-sm-4"><thead><tr><th></th><th>Team</th><th>Kills/Match</th></tr></thead><tbody>';
		$perfscols="SELECT `teamID`,`performances`.`gameID`,`games`.`eventID` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID`  WHERE `performances`.`isScored` > 0 AND `games`.`eventID`='$eID' GROUP BY `teamID`";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
		
			$line= new Team($row['teamID'],$dbc,$row['eventID']);
			//echo $row['teamID'];
			echo $line->createlrow();
		}

		?>
		</tbody></table></div><div></div>
		<?php
		//match each teamID to player1/player2



		
		
	}//end of else

//include $_SERVER['DOCUMENT_ROOT']."/footer.html";

	?>

</body></html>
