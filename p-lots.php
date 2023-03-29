

<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  if($_SESSION["user"]->userType != 1){
    header("Location: 404.php");
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
    <style>
      #map {
        height: 100%;
      }
      .custom-map-control-button {
        background-color: #fff;
        border: 0;
        border-radius: 2px;
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
        margin: 10px;
        padding: 0 0.5em;
        font: 400 18px Roboto, Arial, sans-serif;
        overflow: hidden;
        height: 40px;
        cursor: pointer;
      }
      .custom-map-control-button:hover {
        background: rgb(235, 235, 235);
      }

    </style>
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
      // AIzaSyBBxji7963bwlFqigejxtoAcX8g8mZKDrk
      // AIzaSyCeB2QeJncymKenop5aI4S6b0svjMvRY4g
    ?>

    <div class="page-content-wrapper">
      
      <div class="py-4">
        <div class="container direction-rtl">
          
        </div>
      </div>
      
      <div class="container">
      <nav aria-label="breadcrumb" class="mb-2">
          <ol class="breadcrumb mb-0 py-2 px-3 rounded">
            <li class="breadcrumb-item"><a href="<?=base_url();?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Parking Lots</li>
          </ol>
        </nav>

      <div class="search-form-wrapper">
        <div class="input-group mb-2"><span class="input-group-text bg-dark" id="basic-addon1"><i class="fa fa-search text-light"></i></span>
          <input class="form-control form-control-clicked controls" type="text" id="search" placeholder="Search parking places here" id="pac-input" aria-label="" aria-describedby="basic-addon1">
        </div>
      </div>
      
            <a class="btn btn-primary mb-3 " href="<?=base_url()?>p-lots-add.php">Add Parking Lot</a>
            <!-- <a class="btn btn-link mb-3 " href="#" data-bs-toggle="modal" data-bs-target="#fullscreenModal">Add Parking Lot</a> -->

          
          <!-- Card 03 -->
        <div id="parkingLots">

        </div>
      </div>
      <div class="pb-3">
      </div>
    </div>

    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
    <!-- PWA-->
  </body>
    <!-- <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeB2QeJncymKenop5aI4S6b0svjMvRY4g&callback=initMap&v=weekly"
      defer
    ></script> -->
  <script>
    $(function(){
      load_lots()
    })

    $('#search').keyup(delay(function (e) {
      load_lots()
    }, 500));

    function load_lots(){
      $.get("<?=request_url("getParkingLocations","get")?>",{ search : $("#search").val() },
        function (data, textStatus, jqXHR) {
          const parking = $("#parkingLots")
          let row = ""
          $.each(data, function (key, val) { 
            row += `
              <div class="card card mb-3">
                <div class="card-body d-flex align-items-center">
                  <div class="card-img-wrap">
                    <img src="img/core-img/pp-logo.png" alt="">
                  </div>
                  <div class="card-content">
                    <h5 class="mb-3">` + val.name +`</h5>
                    <p>`+ val.address +`</p>
                    <p># of Slots : <b>`+ val.lotSlot +`</b></p>
                    <a class="btn btn-info btn-round" href="<?=base_url()?>p-info.php?id=`+val.placeId+`">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pin-map" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8l3-4z"/>
                        <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
                      </svg> Open Information</a>
                  </div>
                </div>
              </div>
            `
          });
          parking.html(row)

        }
      );
    }
  </script>
</html>