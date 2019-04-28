<?php
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


		public function __construct($pID, $dbc){
			$this->pID=$pID;
			//Get all the kills with the performances query and add to numkills

					$howmanykills="SELECT `playerID`,`kills`,`performanceID`,(select count(*) from `performances` where `playerID`='$pID' AND `isScored`>0) as `counter` FROM `performances` WHERE `playerID` = '$pID' AND `isScored` > 0";
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

		echo '<table id="leaderboards" class="table table-hover col-sm-8"><thead><tr><th>place</th><th>Player</th><th>Kills</th><th>Matches</th><th>Kills/Match</th></tr></thead><tbody>';
		$perfscols="SELECT DISTINCT `playerID` FROM `performances` WHERE `isScored` > 0";
		$pkq=mysqli_query($dbc, $perfscols) or trigger_error("Leaderboard loading error: ".mysqli_error($dbc));
		while($row=mysqli_fetch_array($pkq)){
			$line= new Player($row['playerID'],$dbc);

			echo $line->createprow();
		}
		?>
		</tbody></table>
		<?php
		//match each teamID to player1/player2



		
		?>