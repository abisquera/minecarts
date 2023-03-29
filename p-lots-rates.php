

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
        <div class="card user-info-card mb-3">
            <div class="card-body d-flex align-items-center">
                <div class="user-info">
                <div class="d-flex align-items-center">
                    <h5 class="mb-1" id="name"></h5>
                </div>
                <p class="mb-0" id="address"></p>
                </div>
            </div>
        </div>
        <a class="btn btn-primary mb-3" href="<?=base_url()?>p-info.php?id=<?=$placeId?>">Back</a>
        <div class="card user-data-card">
          <div class="card-body " >
              <button class="btn btn-sm btn-success mb-3" id="addRateBtn"><i class="fa fa-plus"></i> Add Rate</button>
              <table class="data-table w-100" id="dataTables">
                  <thead class='text-center'>
                  <tr >
                      <th width=50>#</th>
                      <th>Hours</th>
                      <th>Price</th>
                      <th width=50></th>
                  </tr>
                  </thead>
                  <tbody class='text-center' id="p-lots-rate-data">
                  </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>

    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
    <!-- PWA-->
  </body>
  
  <div class="modal fade" id="addLotRate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLotRateLabal" aria-hidden="true" style="display: none;">
      <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
          <form onsubmit="event.preventDefault();"  id="addLotRateForm" >
            <div class="modal-header">
              <h6 class="modal-title" id="addLotRateLabal">Add Parking Rate</h6>
              <input type="hidden" name="placeId" value="<?=$placeId?>">
              <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <div class="form-group">
                    <label class="form-label" for="vehicleId">Select Number of Hours</label>
                    <input class="form-control" name="hours" id="exampleInputnumber" type="number" min=1  value=1>
                </div>
                <div class="form-group">
                  <div class="input-group mb-3"><span class="input-group-text">Price</span>
                    <input class="form-control" name="price" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value=100><span class="input-group-text">₱</span>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-sm btn-success " type="submit"><i class="fa fa-save"></i> Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</html>
<script>
  let dTable
  $(function(){
      loadParkingInfo()
  })  
  let lotId = 0
  function loadParkingInfo()
  {
    const content = $('#p-info-content')
    $.get("<?=request_url("loadParkingInfo")?>?id=<?=$placeId?>",
      function (data, textStatus, jqXHR) {
        content.find("#name").html(data.name)
        content.find("#address").html(data.address)
        loadParkingRates(data.lotId)
        lotId = data.logId
      });
  }
  const mod = $("#addLotRate")
  $("#addRateBtn").on("click",function(){
      mod.modal("show")
  })

  $("#addLotRateForm").on("submit",function(){
    $.post("<?=request_url("addParkingRates",'post')?>", $(this).serializeArray(),
      function (data, textStatus, jqXHR) {
        swal(data.h,data.m,data.s)
        loadParkingRates(data.d)
      }
    );
  })

  function loadParkingRates(lotId)
  {
    $.get("<?=request_url("loadParkingRates")?>?id="+lotId, 
      function (data, textStatus, jqXHR) {
        let row = "";
        $.each(data, function (a, b) { 
          row +=`
            <tr>
              <td>${(a+1)}.
              <td>${b.hours}
              <td>${b.price}
              <td class='text-center' onclick='deleteParkingRate(${b.lotPriceId})'><a class="btn btn-sm"><i class='fa fa-trash text-danger'></i> </a>
          `
        });
        $("#p-lots-rate-data").html(row)
        mod.modal("hide")
      }
    );
  }

  function deleteParkingRate(lotPriceId) {
    swal({
      title: "Delete Rate",
      icon: "warning",
      text: "Are you sure you want to Delete this Parking Rate?", 
      buttons: ["No", "Yes"],
        dangerMode: true,
    })
    .then((willExit) => {
        $.ajax({
          type: "post",
          url: "<?=request_url("deleteParkingRate",'post')?>",
          data: { lotPriceId : lotPriceId },
        }).done(function(data){
          swal(data.h,data.m,data.s)
          loadParkingRates(lotId)
        });
    });
  }


</script>