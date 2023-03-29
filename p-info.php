

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

  $placeId = "";
  if(isset($_REQUEST['id'])){
    $placeId = $_REQUEST['id'];
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
      <div class="container pt-3" id="p-info-content">
        <?php
          if(!$placeId)
            echo file_get_contents("404.php");
          else 
            echo file_get_contents("p-info-content.php")
        ?>
      </div>
    </div>

    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
    <!-- PWA-->
  </body>
</html>
<script>
  $(function(){
      loadParkingInfo()
      $("#linkParkingRates").attr("href","<?=base_url()?>p-lots-rates.php?id=<?=$placeId?>")
      $("#linkParkingAccounts").attr("href","<?=base_url()?>p-admin.php?id=<?=$placeId?>")
      $("#linkParkingLots").attr("href","<?=base_url()?>p-lots-spot.php?id=<?=$placeId?>")
      $("#linkParkingBack").attr("href","<?=base_url()?>p-lots.php")
  })  

  function loadParkingInfo()
  {
    const content = $('#p-info-content')
    $.get("<?=request_url("loadParkingInfo")?>?id=<?=$placeId?>",
      function (data, textStatus, jqXHR) {
        content.find("#name").html(data.name)
        content.find("#address").html(data.address)
        loadParkingInfoData(data.lotId)
      });
  }

  function loadParkingInfoData(lotId){
    $.get("<?=request_url("loadParkingInfoData")?>?id="+lotId,
      function (data, textStatus, jqXHR) {
        let row = "";
        $.each(data, function (a, b) { 
          row +=`
            <tr>
              <td>${(a+1)}
              <td>${b.plateNum}
              <td>${b.startEnd}
              <td>${b.reservationDateTime}
              <td>${b.price}
          `
        });
        $("#p-lots-data").html(row)
        $('#dataTables').DataTable({})
      });
  }
</script>