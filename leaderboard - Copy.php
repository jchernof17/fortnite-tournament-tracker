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


		public function __construct($tID, $dbc){
			$this->tID=$tID;
			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `teamID`,`kills`,`performanceID`,(select count(*) from `performances` where `teamID`='$tID' AND `isScored`>0) as `counter` FROM `performances` WHERE `teamID` = '$tID' AND `isScored` > 0";
					$hmk=mysqli_query($dbc, $howmanykills) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
					$simpletempcounter=0;
				while($rows=mysqli_fetch_array($hmk)){
					$simpletempcounter+=(int) $rows['kills'];
					$counter=(int)$rows['counter']/2;
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
			
			$ret="<tr><td>".$this->p1."/".$this->p2."</td><td>".$killspermatch."</td></tr>";
			return $ret;
		}
		return '';
		}
	}//end of class

		//create the top of the leaderboard

		echo '<table id="leaderboard" class="table table-striped table-hover"><thead><tr><th>Team</th><th>Kills/Match</th></tr></thead>';
		$perfscols="SELECT DISTINCT `teamID` FROM `performances` WHERE `isScored` > 0";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
			$line= new Team($row['teamID'],$dbc);

			echo $line->createlrow();
		}
		?>
		</table>
		<?php
		//match each teamID to player1/player2



		
		?>