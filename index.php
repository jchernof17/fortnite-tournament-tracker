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
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/creative.min.css" rel="stylesheet">

    <!-- <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=YoHOf25bOJXCNuETaBwxr44vfHupi8QGI124tOiljbJhMPZrjVoSrtc5Itoj"></script></span> -->
  </head>

  <body id="page-top">
    <?php
    include ($_SERVER['DOCUMENT_ROOT']."/mySQL_setup2.php");
    $status=hpvalidate(1, "optional", $dbc, False);

    if($status!="loggedout"){
      ?>
          <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Picablo</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#services">How it works</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#portfolio">View Stats</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger bg-info text-white rounded" href="/scoregames.php">Score Games</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/logoutform.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

      <?php
    }
    else{
      ?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Picablo (alpha)</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#services">How it works</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#portfolio">View Stats</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger bg-info rounded text-white" href="/scoregames.php">Become a Scorer</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  <?php 
}

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
            <p class="text-faded mb-5 text-lowercase">Powered by Picablo</p>
            <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Get Started</a>
          </div>
        </div>
      </div>
    </header>

    <section class="bg-primary" id="about">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-heading text-white">Individual and Team Stats from Fortnite Tournaments</h2>
            <hr class="light my-4">
            <p class="text-faded mb-4">Picablo Stats is a crowd-sourced stats-keeping project that tracks Fortnite tournaments.</p>
            <a class="btn btn-light btn-xl js-scroll-trigger" href="/scoregames.php">Score Games</a>
            <a class="btn btn-info btn-xl js-scroll-trigger" href="/stats.php">View Stats</a>
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="pb-0">
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
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-muted mb-3">Fortnite Friday 5/25/18</h3>
            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="/events.php?id=1">View Stats</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-plus-circle text-warning mb-3 sr-icons"></i>
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-white mb-3">Score Games</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3 text-muted" href="/scoregames.php">Start Scoring</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-light ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-line-chart text-warning mb-3 sr-icons"></i>
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-muted mb-3 mt-2">View Overall Stats</h3>

            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="#">View Stats</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-comment text-warning mb-3 sr-icons"></i>
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-white mb-3 mt-2">Join the Discord</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3" href="#">Join</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-light ">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-code text-warning mb-3 sr-icons"></i>
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-muted mb-3">Become an admin</h3>
            </div>
            <a class="btn btn-primary btn-xl js-scroll-trigger mb-3" href="mailto:jordan@picablostats.com">Get in touch</a>
          </div>

          <div class="col-lg-4 col-md-6 xs-12 py-2 px-4 text-center bg-primary">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-certificate text-warning mb-3 sr-icons"></i>
              <!-- <h3 class="mb-3">Advanced Stats</h3> -->
              <h3 class="text-white mb-3">More coming soon!</h3>
            </div>
            <a class="btn btn-light btn-xl js-scroll-trigger mb-3" href="#">:)</a>
          </div>
         
        </div>
      </div>
    </section>

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
