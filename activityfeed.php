

<!-- <h2>Recent Friends' Activities</h2><br> -->
<div class="hi">
		<?php

		//include("loremipsum.html");

		// function makeActivity($r, $friend){
		// 	$activity=$r['activity-id'];
		// 	$distance=$r['distance']." mi";
		// 	$userID = $r['user-id'];
		// 	echo $userID;
			
		// 	$f = "SELECT `username` FROM `login` WHERE `user-id` = $userID";
		// 	$q = mysqli_query($dbc, $f) or trigger_error("Friend-user link error.");
			
		// 		while($row = mysqli_fetch_array($q)){
		// 			$j=$row['username'];
		// 		}
			








		// 	//month
		// 	$monthOfRun=substr($r['time-run'], 5, 2);
		// 	if(substr($monthOfRun,0,1)=='0'){
		// 		$monthOfRun=substr($monthOfRun,1,1);
		// 	}
		// 	if($monthOfRun=='0'){
		// 		$monthOfRun=='12';
		// 	}

		// 	$months=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		// 	$mR=$months[$monthOfRun-1];


		// 	//day
		// 	$day=substr($r['time-run'],8,2);

		// 	$route=$r['route'];

		// 	$time=$r['duration-hr']." hours, ".$r['duration-min']." minutes, ".$r['duration-sec']." seconds";
		// 	echo"<div class='Activity' id='$activity'><h1>".$j."</h1><br><h2> ran on ".$mR." ".$day."<br></h2><h3>".$route."</h3><br>Distance: ".$distance."<br>Time: ".$time."<br></div>";


		// }
		


		//Query for all friends
		$ID=$_SESSION["userID"];
		$name =$_SESSION['username'];
		$namequery="SELECT  * FROM `friends` WHERE `user-id1`='$name'";
		$nqr=mysqli_query($dbc, $namequery) or trigger_error("Friend loading error: ".mysqli_error($dbc));
		//k now you have the friends list
		$v="hihihi";
		if(@mysqli_num_rows($nqr)>0){
		$v="";
		while($frow = mysqli_fetch_array($nqr)){
			//for each friend
			$friend =$frow['user-id2'];
		//Find friends' ID's
			$queryu = "SELECT * FROM  `login` WHERE  `username` =  '$friend'";
			$ru=mysqli_query($dbc, $queryu) or trigger_error("Query error: ".mysqli_error($dbc));
	
			if(@mysqli_num_rows($ru)==0){
			//error
			}
			while($row = mysqli_fetch_array($ru)){
			$friendID = $row['user-id'];
			$v=$v.", ".$friendID;
			}
		}//done fetching friends
	}
?>
</div>
