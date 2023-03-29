<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  if($_SESSION['user']->userType != 2){
    header("Location: index.php");
    exit;
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parking Parker">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#0134d4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags-->
    <!-- Title-->
    <title>Parking Parker</title>
    <?php
        include 'defaults/resource.php';
    ?>
  </head>
  <body>
    <!-- Preloader-->
    <div class="preloader d-flex align-items-center justify-content-center" id="preloader">
      <div class="spinner-grow text-primary" role="status">
        <div class="sr-only">Loading...</div>
      </div>
    </div>
    <!-- Internet Connection Status-->
    <div class="internet-connection-status" id="internetStatus"></div>
    <!-- Header Area-->
    <?php
      include 'defaults/top-nav.php';
    ?>
    <!-- Dark mode switching-->
    <div class="dark-mode-switching">
      <div class="d-flex w-100 h-100 align-items-center justify-content-center">
        <div class="dark-mode-text text-center"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-moon" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M14.53 10.53a7 7 0 0 1-9.058-9.058A7.003 7.003 0 0 0 8 15a7.002 7.002 0 0 0 6.53-4.47z"/>
</svg>
          <p class="mb-0">Switching to dark mode</p>
        </div>
        <div class="light-mode-text text-center"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-brightness-high" viewBox="0 0 16 16">
<path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
</svg>
          <p class="mb-0">Switching to light mode</p>
        </div>
      </div>
    </div>
    <!-- Sidenav Black Overlay-->
    <div class="sidenav-black-overlay"></div>
    <!-- Side Nav Wrapper-->

    <?php
      include 'defaults/side-nav.php';
    ?>

    <div class="page-content-wrapper">
      <!-- Hero Slides-->
      <div class="owl-carousel-one owl-carousel d-none">
        <!-- Single Hero Slide-->
        <div class="single-hero-slide bg-overlay" style="background-image: url('img/bg-img/banner2.jpg')">
          <div class="slide-content h-100 d-flex align-items-center text-center">
          </div>
        </div>
        <!-- Single Hero Slide-->
        <div class="single-hero-slide bg-overlay" style="background-image: url('img/bg-img/banner1.png')">
        </div>
        <!-- Single Hero Slide-->
        <div class="single-hero-slide bg-overlay" style="background-image: url('img/bg-img/1.jpg')">
        </div>
        <!-- Single Hero Slide-->
        <div class="single-hero-slide bg-overlay" style="background-image: url('img/bg-img/1.jpg')">
        </div>
      </div>
      
      <div class="py-4">
        <div class="container direction-rtl">
          
        </div>
      </div>
      
      <div class="container">
        <div class="row">
          <div id="currentActivitiesList">
            <div class="col-12">
              <div class="card bg-info mb-3 shadow-sm bg-gradient direction-rtl">
                <div class="card-body">
                  <h2 class="text-white">Current Activity</h2>
                  <p class="text-white mb-4">..... </p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12">
            <div class="card bg-danger mb-3 shadow-sm bg-gradient direction-rtl">
              <div class="card-body">
                <h2 class="text-white">Frequent activities</h2>
                <p class="text-white mb-4">..... </p>
              </div>
            </div>
          </div>
          
          <div class="col-12">
            <div class="card bg-primary mb-3 shadow-sm bg-gradient direction-rtl">
              <div class="card-body">
                <h2 class="text-white">Recent activities</h2>
                <p class="text-white mb-4">..... </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="pb-3">
      </div>
    </div>
    

    <?php 
      $file = basename(__FILE__, '.php');
      include 'defaults/footer.php'; 
    ?>
    <script src="js/index.js"></script>
    <script>
      PARAMS.init({
        getCurrentActivitiesURL : "<?=request_url("getCurrentActivities","get")?>",
        getCurrentActivityURL : "reserve.php",
        routesURL : "route.php",
        baseUrl : "<?=base_url()?>"
      });
      // PARAMS.getParkingLocations();
    </script>
  </body>
</html>