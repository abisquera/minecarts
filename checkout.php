<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  $invalid = true;

  $d=false;
  if(isset($_REQUEST['d']))
    $d = $_REQUEST["d"];

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
    <div class="page-content-wrapper pt-3">
      <div class="container" id="checkoutContainer">
        
      </div>
    </div>
    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
  </body>
  <script src="https://www.paypal.com/sdk/js?client-id=Afy5P7hhsWvZJAFeuBzKKyPZdXgP6VWXTdSRkcApGH8oJKrP89uiQ0pP5cUA8cUgAaV6hp3MEC6JXVXN&enable-funding=venmo&currency=PHP" data-sdk-integration-source="button-factory"></script>
  <script src="js/checkout.js"></script>
  <script>
    PARAMS.init({
      loadActivityInfoURL : "<?=request_url("loadActivityInfoCheckout")?>?d=<?=$d?>",
      ppcreateOrderURL : "<?=request_url("ppcreateOrder","post")?>",
      pponApproveURL : "<?=request_url("pponApprove","post")?>",
      baseURL : "<?=base_url()?>",
      fixedRate : "<?=$_SESSION["fixedRate"]?>",
    });
  </script>
</html>