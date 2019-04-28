<?php
session_start();
?><!DOCTYPE html>
<html lang="en">

  <head>
    <?php
    include ($_SERVER['DOCUMENT_ROOT']."/sheets.html");
    ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fortnite Stats - Picablo</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <!--  <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'> -->

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/creative.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121197605-1"></script>
<script type="text/javascript">
    $('.alert').alert()
    </script>
<script>

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-121197605-1');
</script>




  </head>

  <body class="pt-0">
    <?php
    include ($_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php");
    $status=validate(1, "optional", $dbc, False);

    if($status!="loggedout" AND $status=="sufficient"){//Display the homepage for logged in folks
      $an=$_SESSION['name'];
      alert("Welcome back, ".$an.".","primary");
      //Display a welcome back primary alert

      //Add a quick carousel | View Profile, Score Games, View Stats, and Join the Discord
      ?>
      <div id="myCarousel" class="carousel slide mb-0" data-ride="carousel" style="height:500px;">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active" style="height:500px;">
            <!-- <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide"> -->
            <img class="first-slide" src="img/headeralt.jpg" alt="First slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>Start Scoring Games</h1>
                <p>We have 12 games waiting to be scored.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/scoregames" role="button">Score Games</a></p>
              </div>
            </div>
          </div>

          <div class="carousel-item"style="height:500px;">
            <img class="second-slide" src="img/header.jpg" alt="Second slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>View Your Profile</h1>
                <p>See how much you've contributed and change your profile info (coming soon).</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/profile" role="button">View Profile</a></p>
              </div>
            </div>
          </div>

          <div class="carousel-item"style="height:500px;">
            <img class="third-slide" src="img/headeralt4.jpg" alt="Third slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>View Overall Stats</h1>
                <p>See a complete summary of all stats in the database.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/stats" role="button">View Stats</a></p>
              </div>
            </div>
          </div>

          <div class="carousel-item"style="height:500px;">
            <img class="fourth-slide" src="img/headeralt5.jpg" alt="Fourth slide" style="object-fit: cover; overflow: hidden;>
            <div class="container">
              <div class="carousel-caption">
                <h1>Join the Discord</h1>
                <p>Joining the discord is the quickest way to stay up-to-date and involved with the rest of the community.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="#discord" role="button">Join Discord</a></p>
              </div>
            </div>
          </div>


        <!-- </div> -->
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      <?php

      //Then get on to it

  }
  

    else{ //If we are logged out


      //How It Works
      ?>



      <div id="myCarousel" class="carousel slide mb-0" data-ride="carousel" style="height:500px;">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active" style="height:500px;">
            <img class="first-slide" src="img/headeralt4.jpg" alt="First slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>In-Depth Fortnite Tournament Stats</h1>
                <p>Our stats are crowd-sourced and rely on volunteers like you.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/stats" role="button">View Stats</a></p>
              </div>
            </div>
            <!-- <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide"> -->
          </div>

          <div class="carousel-item"style="height:500px;">
            <img class="second-slide" src="img/header.jpg" alt="Second slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>View Fortnite Friday 6/22 Stats</h1>
                <p>See stats from the June 22nd Fortnite Friday tournament.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/events?id=1" role="button">View Stats</a></p>
              </div>
            </div>
          </div>

          <div class="carousel-item"style="height:500px;">
           <img class="third-slide" src="img/headeralt.jpg" alt="Third slide" style="object-fit: cover; overflow: hidden;">
            <div class="container">
              <div class="carousel-caption">
                <h1>Start Scoring Games</h1>
                <p>Support Fortnite Stats by helping add stats.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="/scoregames" role="button">Score Games</a></p>
              </div>
            </div>
          </div>

          <div class="carousel-item"style="height:500px;">
            <img class="fourth-slide" src="img/headeralt5.jpg" alt="Fourth slide" style="object-fit: cover; overflow: hidden;>
            <div class="container">
              <div class="carousel-caption">
                <h1>Join the Discord</h1>
                <p>Joining the discord is the quickest way to stay up-to-date and involved with the rest of the community.</p>
                <p><a class="btn btn-lg btn-primary text-capitalize" href="#discord" role="button">Join Discord</a></p>
              </div>
            </div>
          </div>


        <!-- </div> -->
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>


       <section id="services" class="">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading">How It Works</h2>
            <hr class="my-4">
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-users text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Score While Watching</h3>
              <p class="text-muted font-italic mb-0">Score matches while you watch your favorite streamers</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-line-chart text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Advanced Stats</h3>
              <p class="text-muted font-italic mb-0">Individual, team, event and overall statistics for Fortnite tournaments</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-lock text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Validated Data</h3>
              <p class="text-muted font-italic mb-0">Secure match scoring system identifies and prevents user errors</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-trophy text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Earn Rewards</h3>
              <p class="text-muted font-italic mb-0">(perhaps) prizes for dedicated volunteers</p>
            </div>
          </div>
        </div>
      </div>
    </section>
      <?php
    }//End of logged out display

      //Display the below stuff regardless

  //    include("footer.html");
  ?>

  

   

<!-- 
    <section  class="pt-5" id="portfolio">
      <div class="container">
        <div class="row" >
          <div class="col-lg-12 text-center">
            <h2 class="section-heading">View Stats</h2>
            <hr class="my-4">
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          
          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-light ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-gamepad text-warning mb-3 sr-icons"></i>
     
              <h3 class="text-muted mb-3">Fortnite Friday 6/22/18</h3>
            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="/events.php?id=1">View Stats</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-plus-circle text-warning mb-3 sr-icons"></i>
            
              <h3 class="text-white mb-3">Score Games</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3 text-muted" href="/scoregames.php">Start Scoring</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-light ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-line-chart text-warning mb-3 sr-icons"></i>
            
              <h3 class="text-muted mb-3 mt-2">View Overall Stats</h3>

            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="#">View Stats</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-comment text-warning mb-3 sr-icons"></i>
          
              <h3 class="text-white mb-3 mt-2">Join the Discord</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3" href="#">Join</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-light ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-code text-warning mb-3 sr-icons"></i>
          
              <h3 class="text-muted mb-3">Become an admin</h3>
            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="mailto:jordan@picablostats.com">Get in touch</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-certificate text-warning mb-3 sr-icons"></i>
          
              <h3 class="text-white mb-3">More coming soon!</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3" href="#">:)</a>
          </div>
         
        </div>
      </div>
    </section> -->

    <section class="bg-dark text-white">
      <div class="container text-center">
        <h2 class="mb-4">Report a bug or make a feature request</h2>
        <a class="btn btn-light btn-xl sr-button" href="https://goo.gl/forms/6dMHfplgBEUxZkNy1">Give Feedback</a>
      </div>
    </section>

    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-heading">Inquiries</h2>
            <hr class="my-4">
            <p class="mb-5">Questions, business inquiries or looking to become an admin? Send me an email.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 ml-auto text-center">
            <i class="fa fa-twitter fa-3x mb-3 sr-contact"></i>
            <p><a href="https://twitter.com/harecutt">@harecutt</a></p>
          </div>
          <div class="col-lg-4 mr-auto text-center">
            <i class="fa fa-envelope-o fa-3x mb-3 sr-contact"></i>
            <p>
              <a href="mailto:jordan@picablostats.com">jordan@picablostats.com</a>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/creative.min.js"></script>

  </body>

</html>
