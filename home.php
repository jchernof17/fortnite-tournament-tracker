<?php
	session_start();
  echo"<meta http-equiv='REFRESH' content='0;url=index.php'>";
	echo '<html lang="en"> </head>';
		include $_SERVER['DOCUMENT_ROOT']."/sheets.html";
		?>

	<script type="text/javascript">
	$(document).ready( function () {
    $("#leaderboard").DataTable( {
    	
        "paging":   false,
       // "ordering": false,
        "searching": false,
        "info":     false,
        "order":[[1, 'desc']],
    } );

    $("#leaderboards").DataTable({"order":[[3,'desc']]})
	
	});//end of script
	var jumboHeight = $('.jumbotron').outerHeight();
function parallax(){
    var scrolled = $(window).scrollTop();
    $('.bg-homepage').css('height', (jumboHeight-scrolled) + 'px');
}

$(window).scroll(function(e){
    parallax();
});
	</script>

	<style>
	.bg-homepage {
  background: url('https://i.imgur.com/99W0BSE.png') no-repeat center center;
  position: fixed;
  width: 100%;
  height: 350px; /*same height as jumbotron */
  top:0;
  left:0;
  z-index: -1;
	}

	.jumbotron {
  height: 350px;
  color: white;
  text-shadow: #444 0 1px 1px;
  background:transparent;
}

	</style>
		<title>Fortnite Stats</title>
	</head>
<body>
<?php

	
	include($_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php");
	$status=validate(6, "optional", $dbc);
	if($status!="loggedout" OR $status=="loggedout"){
		if($status=="sufficient" OR TRUE){
	


	?>
		<header class="masthead text-center text-white d-flex">
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-10 mx-auto">
            <h1 class="text-uppercase">
              <strong>Fortnite Stats</strong>
            </h1>
            <hr>
          </div>
          <div class="col-lg-8 mx-auto">
            <p class="text-faded mb-5">Powered by Picablo</p>
            <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Find Out More</a>
          </div>
        </div>
      </div>
    </header>

	

<?php
	}
}
?>


</html>