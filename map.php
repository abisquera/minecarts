<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  if($_SESSION['user']->userType == 2){
    header("Location: scanner.php");
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
          
          <nav aria-label="breadcrumb" class="mb-2">
              <ol class="breadcrumb mb-0 py-2 px-3 rounded">
                <li class="breadcrumb-item"><a href="<?=base_url();?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Map</li>
              </ol>
            </nav>

          <div class="search-form-wrapper">
            <div class="input-group mb-2"><span class="input-group-text bg-dark" id="basic-addon1"><i class="fa fa-search text-light"></i></span>
              <input class="form-control form-control-clicked controls" type="text" id="search" value="Parking" placeholder="Search parking places here" id="pac-input" aria-label="" aria-describedby="basic-addon1">
            </div>
          </div>
          <div id="map" style="min-height:600px"></div>
        </div>
      </div>
      <div class="pb-3">
      </div>
    </div>

    <div class="modal fade" id="reserveParking" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reserveParkingLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form onsubmit="event.preventDefault();"  id="reserveParkingForm" >
            <div class="modal-header">
              <h6 class="modal-title" id="reserveParkingLabel">Test</h6>
              <input type="hidden" name="lotId" id="lotId">
              <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:400px;">
                <div class="form-group">
                  <div class="card p-0">
                    <div class="card-body direction-rtl">
                      <div class="row">
                        <div class="col-4">
                          <div class="single-counter-wrap text-center">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="text-info"><path fill="currentColor" d="M4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm1 2v14h14V5H5zm4 2h3.5a3.5 3.5 0 0 1 0 7H11v3H9V7zm2 2v3h1.5a1.5 1.5 0 0 0 0-3H11z"/></svg>
                            <h3 class="mb-1 text-info"><span id="lotSlot">0</span></h3>
                            <p class="mb-0">Total Lots</p>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="single-counter-wrap text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-warning" width="32" height="32" viewBox="0 0 2048 2048"><path fill="currentColor" d="M29 1075q-29 64-29 133v72q0 38 10 73t30 65t48 54t62 40q15 35 39 63t55 47t66 31t74 11q69 0 128-34t94-94h708q35 60 94 94t128 34q69 0 128-34t94-94h162q27 0 50-10t40-27t28-41t10-50v-256q0-79-30-149t-82-122t-122-83t-150-30h-37l-328-328q-27-27-62-41t-74-15H256v128h29L29 1075zm1507 461q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10zM896 512h267q26 0 45 19l237 237H896V512zM768 768H309l99-219q8-17 24-27t35-10h301v256zm-384 768q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10z"/></svg>
                            <h3 class="mb-1 text-warning"><span class="counter" id="lotSlotReserved">0</span></h3>
                            <p class="mb-0">Parked / Reserved</p>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="single-counter-wrap text-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="32" height="32" viewBox="0 0 2048 2048"><path fill="currentColor" d="M29 1459q-29 64-29 133v72q0 76 41 139t110 94q31 69 94 110t139 41q69 0 128-34t94-94h708q35 60 94 94t128 34q69 0 128-34t94-94h162q27 0 50-10t40-27t28-41t10-50v-256q0-80-30-150t-82-122t-122-82t-150-30h-37l-328-328q-27-27-62-41t-74-15H256v128h29L29 1459zm739-563v256H309l99-219q8-17 24-27t35-10h301zm395 0q26 0 45 19l237 237H896V896h267zm373 1024q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10zm0-384q-53 0-99 20t-82 55t-55 81t-20 100H896v-512h768q53 0 99 20t82 55t55 81t20 100v256h-128q0-53-20-99t-55-82t-81-55t-100-20zM384 1920q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10zm-256-328q0-41 17-80l106-232h517v512H640q0-53-20-99t-55-82t-81-55t-100-20q-42 0-81 13t-71 37t-56 57t-37 74q-11-27-11-53v-72z"/></svg>
                            <h3 class="mb-1 text-success"><span class=" counter" id="lotSlotAvailable">0</span></h3>
                            <p class="mb-0">Available</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="vehicleId">Select Vehicle</label>
                    <select name="vehicleId" class="form-control" id="vehicleId">
                      <option value="" disabled selected>1</option>
                    </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="datetimepicker1">Select Date Time</label>
                  <input type="datetime-local" name="reservationDateTime" class="form-control datetimepicker-input" id="datetimepicker1"/>
                </div>
                <div class="form-group">
                  <div class="single-plan-check active shadow-sm active-effect">
                    <div class="form-check mb-0">
                      <label class="form-check-label" for="schedType1">Fixed 24Hours - ₱<?=$_SESSION['fixedRate']?> 
                        <input class="form-check-input schedType" type="radio" id="schedType1" name="schedType" value="1" checked>
                      </label>
                    </div>
                    <i class="fa fa-check text-success"></i>
                  </div>
                  <div class="single-plan-check shadow-sm active-effect">
                    <div class="form-check mb-0">
                      <label class="form-check-label" for="schedType0">Hourly Rate
                        <input class="form-check-input schedType" type="radio" id="schedType0" name="schedType" value="0">
                      </label>
                    </div>
                    <i class="fa fa-clock-o text-primary"></i>
                  </div>
                </div>
                <div class="form-group d-none" id="selectDateTimeHR">
                  <label class="form-label" for="datetimepicker1">Rate</label>
                  <select name="lotPriceId" class="form-control" id="lotPriceId" disabled>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-sm btn-success " type="submit"><i class="fa fa-send"></i> Reserve</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
  </body>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeB2QeJncymKenop5aI4S6b0svjMvRY4g&callback=initMap&v=weekly"
      defer
    ></script>
  <script src="js/map.js"></script>
  <script>
    PARAMS.init({
      getParkingLocationsURL : "<?=request_url("getParkingLocations","get")?>",
      getParkingReservationInformationURL : "<?=request_url("getParkingReservationInformation","get")?>",
      getUserVehiclesURL : "<?=request_url("getUserVehicles","get")?>",
      reserveParkingURL : "<?=request_url("reserveParking","post")?>",
      baseUrl : "<?=base_url()?>",
      fixedRate : "<?=$_SESSION["fixedRate"]?>",
      loadLotRatesURL  : "<?=request_url("loadLotRates")?>",
    });
    // PARAMS.getParkingLocations();
  </script>
</html>