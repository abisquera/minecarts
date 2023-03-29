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
          
        </div>
      </div>
      
      <div class="container">
      <nav aria-label="breadcrumb" class="mb-2">
          <ol class="breadcrumb mb-0 py-2 px-3 rounded">
            <li class="breadcrumb-item"><a href="<?=base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?=base_url();?>p-lots.php">Parking Lots</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
          </ol>
        </nav>
   

      <div class="search-form-wrapper">
        <div class="input-group mb-3"><span class="input-group-text bg-dark" id="basic-addon1"><i class="fa fa-search text-light"></i></span>
          <input class="form-control form-control-clicked controls" type="text" placeholder="Search parking places here" id="pac-input" aria-label="" aria-describedby="basic-addon1">
        </div>
      </div>
          <!-- Card 03 -->
          <div class="modal-body" id="map" style="min-height:600px;">

        </div>
      </div>
      <div class="pb-3">
      </div>
    </div>
    </div>
    <div class="modal fade" id="addParkingLot" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addParkingLotLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form onsubmit="event.preventDefault();"  id="addParkingForm" >
            <div class="modal-header">
              <h6 class="modal-title" id="addParkingLotLabel">Add Parking</h6>
              <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:400px;">
              <input type="hidden" name="lat"  value=0>
              <input type="hidden" name="lng" value=0>
              <input type="hidden" name="placeId" value=0>
                <div class="form-group">
                  <label class="form-label" for="exampleInputText">Parking Name</label>
                  <input class="form-control" name="name" id="name" type="text" placeholder="" >
                </div>
                <div class="form-group">
                  <label class="form-label" for="exampleInputText">Address</label>
                  <textarea class="form-control" name="address" id="address" type="text" placeholder="" ></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="exampleInputnumber"># of Slots</label>
                    <input class="form-control form-control-clicked" name="lotSlot" id="exampleInputnumber" type="number" value=1 placeholder="12">
                </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-sm btn-success " type="submit"><i class="fa fa-save"></i> Save</button>
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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeB2QeJncymKenop5aI4S6b0svjMvRY4g&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
  <script src="js/p-lots-add.js"></script>
  <script>

    $.get("<?=request_url("getParkingLocations","get")?>", { search : "" },
        function (data, textStatus, jqXHR) {
            $.each(data, function (key, val) { 
                mapMarker(val, map)
            });
        }
    );

    function mapMarker(data,map){
        let marker =  new google.maps.Marker({
            position: {lat : data.lat, lng : data.lng },
            label: "PP",
            map: map,
        });
        marker.addListener("click", () => {
            map.setZoom(16);
            map.setCenter(marker.getPosition());
        });
        
    }

    $("#addParkingForm").on("submit",function(){
      $.ajax({
        type: "POST",
        url: "<?=request_url('addParkingLot','post')?>",
        data: $(this).serializeArray()
      }).done(function(res){
        $(':input',$(this))
          .not(':button, :submit, :reset, :hidden')
          .val('')
          .prop('checked', false)
          .prop('selected', false);
        swal({
          title: res.h,
          text: res.m,
          icon: res.s,
          buttons: ["Continue", "Back to List"],
        })
        .then((willExit) => {
          if (willExit) {
            window.location.replace("<?=base_url()?>p-lots.php");
          } 
        });
        $("#addParkingLot").modal("hide")
      });
    })

     
  </script>
</html>