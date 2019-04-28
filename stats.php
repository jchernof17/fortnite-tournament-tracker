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

  var l=$("#locleaderboard").DataTable({
  	"responsive": true,
   	"order":[[4,'desc']],
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

	

    s.on( 'order.dt search.dt', function () {
        s.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    l.on( 'order.dt search.dt', function () {
        l.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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

		<title>Overall Stats - Fortnite Stats - Picablo</title>
	</head>
<body>
<?php


	//session_start();
	include $_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php";

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
		public function __construct($pID, $dbc){
			$this->pID=$pID;

			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `playerID`,`kills`,`performanceID`,(select count(*) from `performances` where `playerID`='$pID' AND `isScored`>0) as `counter` FROM `performances` LEFT JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `playerID` = '$pID' AND `performances`.`isScored` > 0";
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
			
			$ret="<tr><td style='width: 10px'></td><td>".$this->pname."</td><td>".$this->numkills."</td><td>".$this->nummatches."</td><td>".$killspermatch."</td></tr>";
			return $ret;
		}
		return '';
		}
	}//end of Player class


	$status=validate(1, "notrequired", $dbc);
	if($status!="loggedout"){
		if($status=="sufficient"){
		}
	}
	class Team {
		public $tID;
		public $p1;
		public $p2;
		public $numkills;
		public $nummatches;


		public function __construct($tID, $dbc){
			$this->tID=$tID;
			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `teamID`,`kills`,`performanceID`,(select count(*) from `performances` where `teamID`='$tID' AND `isScored`>0) as `counter` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `teamID` = '$tID' AND `performances`.`isScored` > 0";
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
			
			$ret="<tr><td></td><td>".$this->p1."/".$this->p2."</td><td>".$killspermatch."</td><td>".$this->nummatches."</tr>";
			return $ret;
		}
		return '';
		}
	}//end of Team class

	//Here's what we do if we need to display the stats from the event.
	
	//Get each performance

	//Create individual table
		echo '<div class="container mb-5 pb-5">';?>


		<div class='row'>
		<!-- <div class='col-xs-12 col-sm-8 py-5'> -->

		<?php
		

		echo '<div class="col-xs-12 col-sm-8 py-5"><div class="card border"> <div class="card-header bg-info">
                            <h3 class="mb-0 text-center text-white">Individual Stats</h3>
                        </div>
   							<div class="app-table-responsive responsive no-wrap"><table id="leaderboard" width="100%" class="table display responsive no-wrap table-striped table-hover col-sm-12 col-lg-8 mb-0"><thead><tr><th></th><th>Player</th><th>Kills</th><th>Matches</th><th>Kills/Match</th></tr></thead><tbody>';
		$perfscols="SELECT `performances`.`gameID`, `playerID`, `performances`.`isScored` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID` WHERE `performances`.`isScored`>0 GROUP BY `playerID`";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
			$line= new Player($row['playerID'],$dbc);

			echo $line->createprow();
		}
		?>
		</tbody></table>
  </div>
</div>
</div>

		<?php


		echo '<div class="col"></div><div class="col py-5"><div class="card border my-5"> 
				<div class="card-header bg-info">
                    <h3 class="mb-0 text-center text-white">Team Stats</h3>
                </div>';
		// echo '<div class="col-xs-12 col-sm-3 py-5">';
		echo '<table id="teamleaderboard" class="table display table-striped table-hover col-sm-4"><thead><tr><th></th><th>Team</th><th>Kills/Match</th><th>Matches</th></tr></thead><tbody>';
		$perfscols="SELECT `teamID`,`performances`.`gameID` FROM `performances` JOIN `games` ON `performances`.`gameID`=`games`.`gameID`  WHERE `performances`.`isScored` > 0 GROUP BY `teamID`";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
		
			$line= new Team($row['teamID'],$dbc);
			//echo $row['teamID'];
			echo $line->createlrow();
		}

		?>
		</tbody></table></div><div></div></div>
		<?php
		echo '<div class="col"></div>';
		//Query all performances for drop location
		//Table should look like this 
		/*
		Location | Percent of time | AVG place | AVG kills | Death rate
		*/

		//So we need to query performances for all droplocations. Then while dl for each dl, we loop through each performance with droplocation = dl, and we add dl.kills to a master kill number, dl.deaths to master death number, dl.place to a master place number.
		$getnumperfs="SELECT COUNT(*) as `numperfs` FROM performances WHERE `droplocation`!='' AND `isScored`>0";
		$gnp=mysqli_query($dbc,$getnumperfs) or trigger_error("Location board loading error: ".mysqli_error($dbc));
		$overalltotalperformances=0;
		$npr=mysqli_fetch_array($gnp);
			$overalltotalperformances=$npr['numperfs'];
		echo '<div class="col-xs-12 col-sm-8 md-6 py-5"><div class="card border my-5"> 
				<div class="card-header bg-info">
                    <h3 class="mb-0 text-center text-white">Location Stats</h3>
                </div>';
		// echo '<div class="col-xs-12 col-sm-3 py-5">';
		echo '<table id="locleaderboard" class="table display table-striped table-hover col-sm-8"><thead><tr><th></th><th>Location</th><th>Drop Frequency</th><th>Average Place</th><th>Average Kills</th><th>Death Rate</th></tr></thead><tbody>';

		$getalldls="SELECT `droplocation` FROM `performances` WHERE `isScored`>0 AND `droplocation` != '' GROUP BY `droplocation`";
		$gad=mysqli_query($dbc,$getalldls) or trigger_error("Location board loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($gad)){
			$dl=$row['droplocation'];
			$numofperformances=0;
			$kills=0;
			$deaths=0;
			$place=0;

			//Look for all performances with this dl that are scored
			$finddrops="SELECT `kills`,`deaths`,`place` FROM `performances` WHERE `droplocation`='$dl' AND `isScored`>0";
			$fds=mysqli_query($dbc,$finddrops) or trigger_error("Location board loading error: ".mysqli_error($dbc));

			//Loop through them
			while($per=mysqli_fetch_array($fds)){
				//Add kills, deaths, numofperformances, place to counters and add 1 to overalltotalperformances
				$kills+=$per['kills'];
				$deaths+=$per['deaths'];
				$place+=$per['place'];
				$numofperformances+=1;
			}
			
			$kpm=round($kills/$numofperformances,2);
			$dpm=round($deaths/$numofperformances,3);$dpm*=100;
			$ppm=round($place/$numofperformances,2);
			$landavg=round($numofperformances/$overalltotalperformances,3); $landavg*=100;
			//Echo a table row
			//<tr><td></td><td>drop loc</td><td>percent of time</td><td>avg place</td><td>kills</td><td>death rate</td></tr>
			echo"<tr><td>1</td><td>".$dl."</td><td>".$landavg."%</td><td>".$ppm."</td><td>".$kpm."</td><td>".$dpm."%</td>";
		}//end of loop through each drop location


		?>
		</tbody></table></div><div></div></div></div>
	</div> <!-- end row --></div></div>
		<div class="container my-5 py-5">
			<div class="col my-5"></div>
			<div class="col my-5"></div>

		</div>	
		<?php

	//Create team table

	//Create drop locations table
		
		

	?>