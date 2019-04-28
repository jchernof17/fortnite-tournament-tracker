<html lang="en">
	<head>
	<link rel="stylesheet" type="text/css" href="../stylesheet.css">
		<title>Fort Stats - Success</title>
	</head>
<body>
<?php
	session_start();
	include("../mySQL_setup2.php");
	//if(isset($_SESSION['username'])){
	if(true){
	include("../AccessNavbar.html");

	?>
<div id="homepageside" class="sidebar">
	<?php
	echo"Successfully created an entry!";
	?>
</div>
<div id="homepagemain" class="maincontent">
	<?php













	?>
</div>
<?php
}
else{
	include("../noAccessNavbar.html");
	//echo"<meta http-equiv='REFRESH' content='0;url=index.php'>";
	}
?>
</html>