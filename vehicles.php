

<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }


  $user = $_SESSION['user'];


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
      <div class="container pt-3">
        <a class="btn btn-primary mb-3" id="addVehicleBtn">Add Vehicle</a>
          <!-- User Information-->
              <div id="vehicleContent" class="row">

              </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="modAddVehicle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addVehicleLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form onsubmit="event.preventDefault();"  id="addVehicle" >
            <div class="modal-header">
              <h6 class="modal-title" id="addVehicleLabel">Add Vehicle</h6>
              <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:400px;">
              <div class="form-group">
                  <label class="form-label" for="vehicleId">Plate Number</label>
                  <input type="text" class="form-control" name="plateNum" placeholder="AAA-0000" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="vehicleBrand">Brand</label>
                  <input type="text" class="form-control" name="vehicleBrand" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="vehicleModel">Model</label>
                  <input type="text"  class="form-control"name="vehicleModel" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="vehicleModel">Type</label>
                  <select name="vehicleType" id=""  class="form-control" required>
                    <option value="" disabled selected>-- Select Type --</option>
                    <option value="Bike">Bike</option>
                    <option value="SUV">SUV</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="Crossover">Crossover</option>
                    <option value="Convertible">Convertible</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Sports Car">Sports Car</option>
                    <option value="Coupe">Coupe</option>
                    <option value="Minivan">Minivan</option>
                    <option value="Station Wagon">Station Wagon</option>
                    <option value="Truck">Truck</option>
                  </select>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-sm btn-success " type="submit"><i class="fa fa-plus"></i> Add Vehicle</button>
            </div>
          </form>
        </div>
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
    loadVehicles()
  })  
  let mod = $("#modAddVehicle")
  $("#addVehicleBtn").on("click",function(){
    mod.modal("show")
  })

  function loadVehicles()
  {
    let content = $("#vehicleContent")
    $.get("<?=request_url("loadVehicles")?>",
        function (data, textStatus, jqXHR) {
          if(data.length){
            let r = ""
            $.each(data, function (a, b) { 
               r += `
                <div class='col-md-4'>
                  <div class="card user-info-card mb-3">
                      <div class="card-body d-flex align-items-center">
                        <div class="user-info">
                            <div class="d-flex align-items-center">
                            <h4 class="mb-1" id="">${b.plateNum}</h4>
                            </div>
                            <p class="mb-0">${b.vehicleBrand} ${b.vehicleModel}</p>
                            
                        </div>
                      </div>
                  </div>
                </div>
               `
            });
            content.html(r)
          }
        }
    );
  }

  mod.find("form").on("submit",function(){
    let data = $(this).serializeArray()

    $.post("<?=request_url("addVehicle","post")?>", data,
      function (res, textStatus, jqXHR) {
        swal(res.h,res.m,res.s)
        
        if(res.s =="success"){
          mod.modal("hide")
          $(':input',"#addVehicle")
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .prop('checked', false)
            .prop('selected', false);
            loadVehicles()
        }
          
          
      }
    );

  })
</script>