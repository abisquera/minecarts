

<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  // if($_SESSION["user"]->userType != 1){
  //   header("Location: 404.php");
  //   exit;
  // }


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
          <!-- User Information-->
          <div class="card user-info-card mb-3">
            <div class="card-body d-flex align-items-center">
              <div class="user-profile me-3 d-none"><img src="img/bg-img/2.jpg" alt="">
                <form action="#">
                  <input class="form-control form-control-clicked" type="file">
                </form>
              </div>
              <div class="user-info">
                <div class="d-flex align-items-center">
                  <h5 class="mb-1" id="userName"><?=$user->firstName?> <?=$user->lastName?></h5>
                </div>
                <p class="mb-0"><?=($user->userType ? "Admin" : "User")?></p>
              </div>
            </div>
          </div>
          <!-- User Meta Data-->
          <div class="card user-data-card">
            <div class="card-body">
              <form id="profileForm"  onsubmit="event.preventDefault();">
                <div class="form-group mb-3">
                  <label class="form-label" for="Username">Contact Number</label>
                  <input class="form-control" id="Username" type="text" value="<?=$user->contactNumber?>" disabled placeholder="Username" readonly>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label" for="lastName">Last Name</label>
                  <input class="form-control" id="lastName" name="lastName" type="text" value="<?=$user->lastName?>" placeholder="Full Name" >
                </div>
                <div class="form-group mb-3">
                  <label class="form-label" for="firstName">First Name</label>
                  <input class="form-control" id="firstName" name="firstName" type="text" value="<?=$user->firstName?>" placeholder="Full Name" >
                </div>
                <div class="form-group mb-3">
                  <label class="form-label" for="middleName">Middle Name</label>
                  <input class="form-control" id="middleName" name="middleName" type="text" value="<?=$user->middleName?>" placeholder="Full Name">
                </div>
                <div class="form-group mb-3">
                  <label class="form-label" for="email">Email Address</label>
                  <input class="form-control" id="email" name="email" type="text" value="<?=$user->email?>" placeholder="Email Address" >
                </div>
                <button class="btn btn-success w-100" type="submit">Update Now</button>
              </form>
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
</html>
<script>
  $(function(){
  })  

  $("#profileForm").on("submit",function(){
    $.post("<?=request_url("updateProfile","post")?>", $(this).serializeArray(),
      function (data, textStatus, jqXHR) {
        swal(data.h,data.m,data.s)
        $("#userName").html(data.d)
      },
      
    );
  })
</script>