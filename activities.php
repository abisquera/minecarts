

<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
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
      <div class="container pt-3" id="activity-content">
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
      loadActivities()
  })  

  function loadActivities()
  {
    let content = $("#activity-content")
    $.get("<?=request_url("loadActivities")?>",
      function (data, textStatus, jqXHR) {
        if(!data.length){
          content.html("No Activity yet.")
          return
        }

        let card = ""

        $.each(data, function (a, b) {
          let paid = ""
          if(b.isPaid)
            paid = `<span class='badge bg-success rounded-pill ms-2'> Paid</span>`
          card+=`
           <div class="card user-info-card mb-3">
              <div class="card-body d-flex align-items-center">
                  <div class="user-info">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-1"><b>${b.placeName}</b></h5>
                    </div>
                    <p class="mb-0" id="address">${b.placeAddress}</p>
                    <p class="mb-0 font-weight-bold">${b.plateNum} - ${b.vehicleBrand} ${b.vehicleModel}</p>
                    <p class="mb-0">${b.reservationDT} ${paid}</p>
                  </div>
              </div>
          </div>
           ` 
        });

        content.html(card)
      }
    );
  }
</script>